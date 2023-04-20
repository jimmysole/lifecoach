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


    public function __construct(TableGateway $gateway)
    {
        $this->gateway = $gateway;

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
        return [];
    }
}