<?php


namespace Application\Classes;


use Application\Interfaces\SocialInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Delete;
use Laminas\Db\Sql\Insert;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Update;
use Laminas\Db\TableGateway\TableGateway;


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
        $this->sql = new Sql($this->gateway);
        $this->insert = new Insert();
        $this->select = new Select();
        $this->delete = new Delete();
        $this->update = new Update();
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

            if ($row['active'] == 1) {
                // user is active
                // send an invitation to chat
                $insert = $this->insert->into('pending_chat_requests')
                    ->columns(['recipient', 'sent_by', 'message', 'date_sent'])
                    ->values(['recipient' => $row['username'], 'sent_by' => $this->user,
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


    public function viewChatRequests(): array|bool
    {
        $select = $this->select->columns(['recipient', 'sent_by', 'message', 'date_sent', 'chat_accepted'])
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
}
