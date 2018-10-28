<?php
namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\Chat\Room;
use App\Chat\Repository\ChatRepository;
use App\config\Constants;

class RoomInit
{
	protected $container;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{
		$uri_path = ltrim( parse_url($request->getUri(), PHP_URL_PATH), '/');
		$room_name = explode('/', $uri_path)[1];
		
		$repository = new ChatRepository();
		$repository->setFileDestination(Constants::$CHAT_ROOM_DIR . DIRECTORY_SEPARATOR . $room_name);

		$room = new Room();
		$room->setRepository($repository);
		$room->setId($room_name);

		$this->container[Room::class] = $room;

		$response = $next($request, $response);
		return $response;
	}
}