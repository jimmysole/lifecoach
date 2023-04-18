<?php


namespace Application\Classes;


use Application\Interfaces\Forum;
use Application\Interfaces\SocialInterface;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Where;


class Social implements SocialInterface
{
    private TableGateway $gateway;

    private Sql $sql;

    private Insert $insert;

    private Select $select;

    private Delete $delete;

    private Update $update;

    private string $user;

    private array $chat_settings = [];


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


    public function viewOnlineUsers(): array|bool
    {
        $select = $this->select->columns(['username'])
            ->from('sessions')
            ->where(['active' => 1]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $rows = [];

            foreach ($query as $key => $value) {
                $rows[$key] = $value;
            }

            return $rows;
        } else {
            return false;
        }
    }


    public function sendChatRequest(string $with, array $params): SocialInterface|bool
    {
        if (empty($with)) {
            return false;
        }

        foreach ($params as $k => $v) {
            $this->chat_settings[$k] = $v;
        }

        // see if $with is an active user
        $select = $this->select->columns(['username', 'active'])->from('users')
            ->where(['username' => $with]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $row = [];

            foreach ($query as $key => $value) {
                $row[$key] = $value;
            }



            if ($row[0]['active'] == 1) {
                // user is active
                // send an invitation to chat
                $insert = $this->insert->into('pending_chat_requests')
                    ->columns(['recipient', 'sent_by', 'message', 'date_sent'])
                    ->values(['recipient' => $row[0]['username'], 'sent_by' => $this->user,
                        'message' => $this->chat_settings['message'], 'date_sent' => date('Y-m-d H:i:s')]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    // chat request was sent
                    return $this;
                } else {
                    return false;
                }
            } else {
                // user is not active
                // bail
                return false;
            }
        } else {
            return false;
        }
    }


    public function viewOutgoingChatRequests(): array|bool
    {
        $select = $this->select->columns(['id', 'recipient', 'sent_by', 'message', 'date_sent', 'chat_accepted'])
            ->from('pending_chat_requests')
            ->where(['sent_by' => $this->user]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $rows = [];

            foreach ($query as $key => $value) {
                $rows[$key] = $value;
            }

            return $rows;
        } else {
            return false;
        }
    }


    public function viewIncomingChatRequests(): array|bool
    {
        $select = $this->select->columns(['id', 'recipient', 'sent_by', 'message', 'date_sent', 'chat_accepted'])
            ->from('pending_chat_requests')
            ->where(['recipient' => $this->user]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $rows = [];

            foreach ($query as $key => $value) {
                $rows[$key] = $value;
            }

            return $rows;
        } else {
            return false;
        }
    }


    public function acceptChatRequest(array $params): bool
    {
        $select = $this->select->columns(['id', 'recipient', 'sent_by', 'message', 'date_sent', 'chat_accepted'])
            ->from('pending_chat_requests')
            ->where(['recipient' => $this->user, 'id' => $params['id']]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $chats = [];

            foreach ($query as $key => $value) {
                $chats[$key] = $value;
            }


            if ($params['chat_accepted'] == 1) {
                // set the chat to accepted
                // and move it to the chat table
                // and delete it from pending chats
                $insert = $this->insert->into('chats')->columns(['room_title', 'room_members', 'room_moderators', 'room_transcript'])
                    ->values(['room_title' => 'Chat Room ', 'room_members' => implode(", ", [$chats[0]['recipient'], $chats[0]['sent_by']]),
                        'room_moderators' => implode(", ", [$chats[0]['recipient'], $chats[0]['sent_by']]), 'room_transcript' => $chats[0]['message']]);

                $query = $this->gateway->getAdapter()->query(
                    $this->sql->buildSqlString($insert),
                    Adapter::QUERY_MODE_EXECUTE
                );

                if ($query->count() > 0) {
                    // delete from pending chat requests
                    $delete = $this->delete->from('pending_chat_requests')
                        ->where(['id' => $chats[0]['id']]);

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
            return false;
        }
    }


    public function denyChatRequest(array $params): bool
    {
        $select = $this->select->columns(['id', 'recipient', 'sent_by', 'message', 'date_sent', 'chat_accepted'])
            ->from('pending_chat_requests')
            ->where(['recipient' => $this->user, 'id' => $params['id']]);

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $chats = [];

            foreach ($query as $key => $value) {
                $chats[$key] = $value;
            }

            if ($params['chat_denied'] == 1) {
                // set the chat to declined
                $update = $this->update->table('pending_chat_requests')
                    ->set(['chat_accepted' => 2])
                    ->where(['id' => $chats[0]['id']]);

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


    public function currentChats(): array|bool
    {
        $select = $this->select->columns(['room_title', 'room_members', 'room_moderators', 'room_transcript'])
            ->from('chats')
            ->where(function (Where $where) {
                $where->like('room_members', $this->user . '%');
            });

        $query = $this->gateway->getAdapter()->query(
            $this->sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($query->count() > 0) {
            $rows = [];

            foreach ($query as $key => $value) {
                $rows[$key] = $value;
            }

            return $rows;
        } else {
            return false;
        }
    }


    public function viewProfiles(array $criteria = []): array|bool
    {
        if (count($criteria, 1) > 0) {
            if ($criteria['by_location']) {
                if (!empty($criteria['location'])) {
                    $select = $this->select->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                        ->from('profile')
                        ->where(function (Where $where) use ($criteria) {
                           $where->like('location', $criteria['location'] . '%');
                        });

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
                        return false;
                    }
                } else {
                    return false;
                }
            } else if ($criteria['by_username']) {
                if (!empty($criteria['username'])) {
                    $select = $this->select->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                        ->from('profile')
                        ->where(function (Where $where) use ($criteria) {
                            $where->like('username', $criteria['username'] . '%');
                        });

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
                        return false;
                    }
                } else {
                    return false;
                }
            } else if ($criteria['by_real_name']) {
                if (!empty($criteria['real_name'])) {
                    $select = $this->select->columns(['username', 'real_name', 'location', 'avatar', 'bio'])
                        ->from('profile')
                        ->where(function (Where $where) use ($criteria) {
                            $where->like('real_name', $criteria['real_name'] . '%');
                        });

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
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            // nothing was passed, abort
            return false;
        }
    }


    public function forums(Forums $forums): Forum
    {
        // TODO: Implement forums() method.
        return $forums;
    }


    public function createProfile(array $profile_details): bool
    {
        // TODO: Implement createProfile() method.
        return true;
    }


    public function editProfile(array $edits): bool
    {
        // TODO: Implement editProfile() method.
        return true;
    }


    public function deleteProfile(string $reason): bool
    {
        // TODO: Implement deleteProfile() method.
        return true;
    }
}
