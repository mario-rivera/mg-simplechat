<?php
namespace App\Chat\Repository;

interface ChatRepositoryInterface
{
	public function add($message, $userid);
	public function getLog(): array;
}