<?php


namespace Application\Classes;


use Application\Interfaces\SocialInterface;


class Social implements SocialInterface
{
    public function __construct()
    {

    }


    public function chat(string $with, array $params): SocialInterface|bool
    {

        return $this;
    }
}
