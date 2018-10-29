<?php
namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\Chat\User;

class Authenticated
{
	protected $container;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{

		$uri_path = ltrim( parse_url($request->getUri(), PHP_URL_PATH), '/');
		$room_name = explode('/', $uri_path)[1];

		if(!isset($_SESSION['username'])){

			return $response->withHeader('Location', "/room/{$room_name}/login")->withStatus(302);
		}

		$user = new User();
		$user->setUsername($_SESSION['username']);
		$user->setLevel('normal');

		if($_SESSION['username'] == 'admin'){
			$user->setLevel('admin');
		}

		$this->container[User::class] = $user;

		$response = $next($request, $response);
		return $response;
	}
}