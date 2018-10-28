<?php
namespace App\Chat;

use App\Chat\Repository\ChatRepositoryInterface;
use App\Chat\User;

class Room
{
	protected $id;
	protected $repository;
	protected $user;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setRepository(ChatRepositoryInterface $r)
	{
		$this->repository = $r;
	}

	public function addMessage($message, $username)
	{	
		$this->repository->add($message, $username);
		return $this;
	}

	public function getLog()
	{
		return array_map([$this, 'mapLog'], $this->repository->getLog());
	}

	private function mapLog($line)
	{
		$lineData = explode(':', $line, 2);
		return ['username' => $lineData[0], 'message' => str_replace(PHP_EOL, '', $lineData[1])];
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}
}