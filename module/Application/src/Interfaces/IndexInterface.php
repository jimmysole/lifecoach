<?php


namespace Application\Interfaces;


interface IndexInterface
{
	/**
	 * Lists all articles
	 * @return array
	 * @throws \Exception
	 */
    public function listArticles() : array;
}