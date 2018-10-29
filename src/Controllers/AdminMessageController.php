<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Container;
use App\config\Constants;
use App\Chat\Room;
use App\Chat\Repository\ChatRepository;
use App\Chat\User;

class AdminMessageController{

	private $container;
	protected $user;

	public function __construct(Container $c){
		$this->container = $c;
		$this->user = $this->container[User::class];
	}

	public function postMessage(Request $request, Response $response){

		try{
			$payload = $request->getParsedBody();
			$rooms = [];
			
			foreach(scandir(Constants::$CHAT_ROOM_DIR) as $name){

				if(is_file(Constants::$CHAT_ROOM_DIR. DIRECTORY_SEPARATOR . $name)){

					$repository = new ChatRepository();
					$repository->setFileDestination(Constants::$CHAT_ROOM_DIR . DIRECTORY_SEPARATOR . $name);

					$room = new Room();
					$room->setRepository($repository);
					$room->setId($name);

					$rooms[] = $room;
				}
			}

			foreach($rooms as $room){
				$room->addMessage($payload['message'], $this->user->getUsername());
			}
		}catch(\Exception $e){

			return $response->withJson($e->getMessage(), 500);
		}

		return $response->withJson(null, 201);
	}
}