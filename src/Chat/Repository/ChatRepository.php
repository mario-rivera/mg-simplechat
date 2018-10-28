<?php
namespace App\Chat\Repository;

class ChatRepository implements ChatRepositoryInterface
{
	protected $fileDestination;

	public function setFileDestination($file)
	{
		$this->fileDestination = $file;
	}

	public function add($message, $userid)
	{
		$data = $userid . ':' .  $message .  PHP_EOL;
		$bytes = file_put_contents($this->fileDestination, $data, FILE_APPEND);

		if($bytes === false){
			throw new \Exception('Failed to save message.');
		}

		return $bytes;
	}

	public function getLog(): array
	{
		$log = file($this->fileDestination);
		return ($log === false) ? [] : $log;
	}
}