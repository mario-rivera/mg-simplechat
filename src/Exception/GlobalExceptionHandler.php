<?php
namespace App\Exception;

use Dwoo\Core;
use Exception;

class GlobalExceptionHandler{

	public function __invoke($request, $response, $e){
		
		$core = new Core();
		$content =  $core->get( __DIR__ . '/../templates/error/general.tpl', ['message' => $e->getMessage()]);

		return $response->withStatus(500)
		->withHeader('Content-Type', 'text/html')
		->write($content);
	}
}