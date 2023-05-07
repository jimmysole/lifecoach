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
        // TODO: Implement removeBoard() method.
    }


    public function removePost(int $post_id, array $reason = array()): bool
    {
        // TODO: Implement removePost() method.
    }


    public function removeHotTopic(int $board_id, array $reason = array()): bool
    {
        // TODO: Implement removeHotTopic() method.
    }
}
