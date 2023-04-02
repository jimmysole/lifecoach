<?php


namespace Application\Interfaces;


interface SocialInterface
{
    public function chat(string $with, array $params) : SocialInterface|bool;


}
