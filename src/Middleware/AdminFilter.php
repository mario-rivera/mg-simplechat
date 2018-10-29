<?php
namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use App\Chat\User;
use App\Chat\Room;

class AdminFilter
{
	protected $container;
	protected $user;

	public function __construct(ContainerInterface $c){

		$this->container = $c;
		$this->user = $this->container[User::class];
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
	{

		$level = $this->user->getLevel();

		if($level != 'admin'){
			return $response->withJson(null, 403);
		}	

		$response = $next($request, $response);
		return $response;
	}
}