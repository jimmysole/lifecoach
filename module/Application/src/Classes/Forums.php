<?php

namespace Application\Classes;


use Application\Interfaces\ForumInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\TableGateway\TableGateway;


class Forums implements ForumInterface
{
    private TableGateway $gateway;

    private Sql $sql;

    private Insert $insert;

    private Select $select;

    private Delete $delete;

    private Update $update;

    private string $user;


    public function __construct(TableGateway $gateway, string $user)
    {
        $this->gateway = $gateway;

        $this->user = $user;

        $this->sql = new Sql($this->gateway->getAdapter());

        $this->insert = new Insert();

        $this->select = new Select();

        $this->delete = new Delete();

        $this->update = new Update();
    }


    public function displayBoards(): array
    {
        $select = $this->select->columns(['id', 'board_name', 'board_topic', 'board_moderators', 'board_posts'])
            ->from('boards')
            ->where('id is not null');

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $boards = [];

            foreach ($query as $key => $value) {
                $boards = array_merge_recursive($boards, array($key => $value));
            }

            return $boards;
        } else {
            // return empty array, no boards exist
            return [];
        }
    }


    public function displayHotTopics(): array
    {
        $select = $this->select->from(['b' => 'boards'])
            ->join(['t' => 'hot_topics'],
                'b.id = t.board_id');

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $rows = [];

            foreach ($query as $key => $value) {
                $rows = array_merge_recursive($rows, array($key => $value));
            }

            return $rows;
        } else {
            // return empty array, no hot topics exist
            return [];
        }
    }


    public function displayBoardModerators(): array
    {
        $select = $this->select->columns(['board_moderators'])
            ->from('boards')
            ->where('id is not null');

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $moderators = [];

            foreach ($query as $key => $value) {
                $moderators = array_merge_recursive($moderators, array($key => $value));
            }

            return $moderators;
        } else {
            // return empty array, no moderators exist
            return [];
        }
    }


    public function subscribeToBoard(string $board, array $options): bool
    {
        if (!empty($board) && count($options, 1) > 0) {
            $sub_options = [];

            foreach ($options as $k => $v) {
                $sub_options = array_merge_recursive($sub_options, array($k => $v));
            }

            // find the board
            $select = $this->select->columns(['id'])
                ->from('boards')
                ->where(['board_name' => $board]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $row = [];

                foreach ($query as $key => $value) {
                    $row = array_merge_recursive($row, array($key => $value));
                }

                // subscribe to board now
                $insert = $this->insert->into('board_subscriptions')
                    ->columns(['board_id', 'board_subscribers', 'board_notifications'])
                    ->values(['board_id' => $row['id'], 'board_subscribers' => $this->user, 'board_notifications' => $sub_options['notify'] == 1 ? 1 : 2]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function postMessage(string $board, string $topic, string $message, array $message_options = []): bool
    {
        if (!empty($board) && !empty($topic) && !empty($message)) {
            $select = $this->select->columns(['id', 'board_name'])
                ->from('boards')
                ->where(['board_name' => $board]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                // board exists
                // post the message now
                $values = [];

                foreach ($query as $key => $value) {
                    $values = array_merge_recursive($values, array($key => $value));
                }

                $insert = $this->insert->into('boards')
                    ->columns(['board_name', 'board_topic', 'board_posts', 'board_msg_id'])
                    ->values(['board_name' => $board, 'board_topic' => $topic, 'board_posts' => $message, 'board_msg_id' => rand(0, 10000)]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    // check if any options were passed
                    if (count($message_options, 1) > 0) {
                        $msg_opts = [];

                        foreach ($message_options as $msg_key => $msg_value) {
                            $msg_opts = array_merge_recursive($msg_opts, array($msg_key => $msg_value));
                        }

                        if ($msg_opts['subscribe_to_post']) {
                            $insert = $this->insert->into('board_subscriptions')
                                ->columns(['board_id', 'board_subscribers', 'board_notifications'])
                                ->values(['board_id' => $values['id'], 'board_subscribers' => $this->user, 'board_notifications' => 1]);

                            $query = $this->gateway->getAdapter()->query(
                                $this->sql->buildSqlString($insert),
                                Adapter::QUERY_MODE_EXECUTE
                            );

                            if ($query->count() > 0) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function editMessage(string $board, int $message_id, array $edits): bool
    {
        if (!empty($board) && preg_match("/[0-9+]/", $message_id) && count($edits, 1) > 0) {
            // find the board the message is tied to
            // and update
            $select = $this->select->columns(['id', 'board_msg_id', 'board_posts'])
                ->from('boards')
                ->where(['board_name' => $board, 'board_msg_id' => $message_id]);

            $query = $this->gateway->getAdapter()->query(
                $this->sql->buildSqlString($select),
                Adapter::QUERY_MODE_EXECUTE
            );

            if ($query->count() > 0) {
                $board_info = [];

                foreach ($query as $key => $value) {
                    $board_info = array_merge_recursive($board_info, array($key => $value));
                }

                // board and message found
                // update
                $update = $this->update->table('boards')
                    ->set(['board_posts' => $edits['edited_message']])
                    ->where(['id' => $board_info['id'], 'board_msg_id' => $board_info['board_msg_id']]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($update),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}