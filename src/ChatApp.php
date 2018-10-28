<?php
namespace App;

use Slim\App as SlimFramework;
use Dwoo\Core;
use App\Exception\GlobalExceptionHandler;
use App\config\Constants;
use Exception;

class ChatApp extends SlimFramework{

	public function init(){

		$container = $this->getContainer();

		// catch all exceptions
		set_error_handler(function(int $errno, string $errstr ){});
		// start session
		session_start();

		$container['errorHandler'] = function($c){
			return new GlobalExceptionHandler;
		};

		$container['phpErrorHandler'] = function($c){
			return new GlobalExceptionHandler;
		};

		//templating service
		$container[Core::class] = function($container){

			$core = new Core();

			$core->setTemplateDir(__DIR__ . '/templates/');
			return $core;
		};

		// chat data dir
		if(!file_exists(Constants::$CHAT_DATA_DIR)){

			@mkdir(Constants::$CHAT_DATA_DIR);
		}

		if( !is_writable(Constants::$CHAT_DATA_DIR) ){

			throw new Exception("Make sure " . Constants::$CHAT_DATA_DIR . " is writable");
		}

		$this->loadRoutes();
		
		return $this;
	}

	private function loadRoutes(){

		$app = $this;
		require_once(__DIR__ . '/routes.php');
	}
}