<?php
namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\config\Constants;
use App\Chat\Room;
use Exception;

class RoomRequest
{
	protected $container;
	protected $room;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
		$this->room = $this->container->{Room::class};
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		mkdir(Constants::$CHAT_ROOM_DIR);
		mkdir(Constants::$CHAT_USER_DIR);

		chmod(Constants::$CHAT_ROOM_DIR, 0777);
		chmod(Constants::$CHAT_USER_DIR, 0777);

		if(!is_writable(Constants::$CHAT_ROOM_DIR) || !is_writable(Constants::$CHAT_USER_DIR) ){

			throw new Exception("System is unavailable. Cannot initialize chat.");
		}

		$room_name = $this->room->getId();

		if(!is_file(Constants::$CHAT_ROOM_DIR . DIRECTORY_SEPARATOR . $room_name)){

			touch(Constants::$CHAT_ROOM_DIR . DIRECTORY_SEPARATOR . $room_name);
			chmod(Constants::$CHAT_ROOM_DIR . DIRECTORY_SEPARATOR . $room_name, 0777);
		}

		if(!is_file(Constants::$CHAT_USER_DIR . DIRECTORY_SEPARATOR . $room_name)){

			touch(Constants::$CHAT_USER_DIR . DIRECTORY_SEPARATOR . $room_name);
			chmod(Constants::$CHAT_USER_DIR . DIRECTORY_SEPARATOR . $room_name, 0777);
		}

		$response = $next($request, $response);
		return $response;
	}
}