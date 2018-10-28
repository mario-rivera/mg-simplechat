<?php
namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\Chat\User;
use App\Chat\Room;

class Authenticated
{
	protected $container;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{

		$room = $this->container[Room::class];

		if(!isset($_SESSION['username'])){

			return $response->withHeader('Location', "/room/{$room->getId()}/login")->withStatus(302);
		}

		$user = new User();
		$user->setUsername($_SESSION['username']);
		$user->setLevel('normal');

		if($_SESSION['username'] == 'admin'){
			$user->setLevel('admin');
		}

		$room->setUser($user);

		$response = $next($request, $response);
		return $response;
	}
}