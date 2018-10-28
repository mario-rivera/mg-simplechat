<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Dwoo\Core as DwooCore;
use App\Chat\Room;

class RoomController{

	protected $container;
	protected $room;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
		$this->room = $this->container->{Room::class};
	}

	public function getChatroom(Request $request, Response $response, $args){

		$dwoo = $this->container->{DwooCore::Class};
		$view = $dwoo->get('chatroom.html', [
			'room_id' => $this->room->getId(),
			'username' => $this->room->getUser()->getUsername()
		]);

		return $response->getBody()->write($view);
	}

	public function postMessage(Request $request, Response $response, $args){

		try{

			$payload = $request->getParsedBody();
			$this->room->addMessage($payload['message'], $payload['username']);
		}catch(\Exception $e){

			return $response->withJson(['errors' => [['message' => 'Failed to save message']]], 500);
		}

		return $response->withJson(null, 201);
	}

	public function getLog(Request $request, Response $response, $args)
	{
		$dwoo = $this->container->{DwooCore::Class};

		$view = $dwoo->get('log.html', [
			'logData' => $this->room->getLog()
		]);

		return $response->getBody()->write($view);
	}
}