<?php


namespace Application\Classes;

use Application\Interfaces\ForumAdminInterface;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

class ForumAdmin implements ForumAdminInterface
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


    public function removeBoard(int $board_id, array $reason = array()): bool
    {
        if (preg_match("/[0-9+]/", $board_id)) {
            if (count($reason, 1) > 0) {
                $reason_why = [];

                foreach ($reason as $k => $v) {
                    $reason_why[$k] = $v;
                }

                $select = $this->select->columns(['id', 'board_name'])
                    ->from('boards')
                    ->where(['id' => $board_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row = array_merge_recursive($row, array($key => $value));
                    }

                    // insert into the deleted boards table
                    // then remove from boards
                    $insert = $this->insert->into('deleted_boards')
                        ->columns(['board_id', 'board_name'])
                        ->values(['board_id' => $row['id'], 'board_name' => $row['board_name'], 'reason'  => $reason_why['reason']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        $delete = $this->delete->from('boards')
                            ->where(['id' => $row['id']]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
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
            } else {
                $select = $this->select->columns(['id', 'board_name'])
                    ->from('boards')
                    ->where(['id' => $board_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row = array_merge_recursive($row, array($key => $value));
                    }

                    // insert into the deleted boards table
                    // then remove from boards
                    $insert = $this->insert->into('deleted_boards')
                        ->columns(['board_id', 'board_name'])
                        ->values(['board_id' => $row['id'], 'board_name' => $row['board_name']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        $delete = $this->delete->from('boards')
                            ->where(['id' => $row['id']]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
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
        } else {
            return false;
        }
    }


    public function removePost(int $post_id, array $reason = array()): bool
    {
        if (preg_match("/[0-9+]/", $post_id)) {
            if (count($reason, 1) > 0) {
                $reason_why = [];

                foreach ($reason as $k => $v) {
                    $reason_why[$k] = $v;
                }

                // find the post id under the boards table
                // referenced by board_msg_id column in the table
                $select = $this->select->columns(['id', 'board_name', 'board_posts', 'board_msg_id'])
                    ->from('boards')
                    ->where(['board_msg_id' => $post_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row = array_merge_recursive($row, array($key => $value));
                    }

                    // insert into deleted_board_posts
                    // and remove from boards
                    $insert = $this->insert->into('deleted_board_posts')
                        ->columns(['board_id', 'board_name', 'board_post', 'board_post_id', 'reason'])
                        ->values(['board_id' => $row['id'], 'board_name' => $row['board_name'], 'board_post' => $row['board_post'], 'board_post_id' => $row['board_msg_id'], 'reason' => $reason_why['reason']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        $delete = $this->delete->from('boards')
                            ->where(['board_msg_id' => $row['board_msg_id']]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
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
            } else {
                // find the post id under the boards table
                // referenced by board_msg_id column in the table
                $select = $this->select->columns(['id', 'board_name', 'board_posts', 'board_msg_id'])
                    ->from('boards')
                    ->where(['board_msg_id' => $post_id]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($select),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    $row = [];

                    foreach ($query as $key => $value) {
                        $row = array_merge_recursive($row, array($key => $value));
                    }

                    // insert into deleted_board_posts
                    // and remove from boards
                    $insert = $this->insert->into('deleted_board_posts')
                        ->columns(['board_id', 'board_name', 'board_post', 'board_post_id', 'reason'])
                        ->values(['board_id' => $row['id'], 'board_name' => $row['board_name'], 'board_post' => $row['board_post'], 'board_post_id' => $row['board_msg_id']]);

                    $query = $this->gateway->getAdapter()->query(
                        $this->sql->buildSqlString($insert),
                        Adapter::QUERY_MODE_EXECUTE
                    );

                    if ($query->count() > 0) {
                        $delete = $this->delete->from('boards')
                            ->where(['board_msg_id' => $row['board_msg_id']]);

                        $query = $this->gateway->getAdapter()->query(
                            $this->sql->buildSqlString($delete),
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
        } else {
            return false;
        }
    }


    public function removeHotTopic(int $board_id, array $reason = array()): bool
    {
        // TODO: Implement removeHotTopic() method.
    }
}
