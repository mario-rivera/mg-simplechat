<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Container;
use Dwoo\Core as DwooCore;

class HomeController{

	private $container;

	public function __construct(Container $c){
		$this->container = $c;
	}

	public function getHome(Request $request, Response $response){

		return $response->withHeader('Location', "/room/test/login")->withStatus(302);
	}


	public function getLogin(Request $request, Response $response, $args)
	{
		$params = $request->getQueryParams();

		if(!empty($params['username'])){
			$_SESSION['username'] = $params['username'];
			return $response->withHeader('Location', "/room/{$args['id']}")->withStatus(302);
		}

		$dwoo = $this->container->{DwooCore::Class};

		$view = $dwoo->get('login.html');
		return $response->getBody()->write($view);
	}
}