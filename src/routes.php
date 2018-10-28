<?php

$app->get('/', App\Controllers\HomeController::class . ':getHome');
$app->get('/room/{id}/login', App\Controllers\HomeController::class . ':getLogin');

$app->group('', function() use ($app){

	$app->get('/room/{id}', App\Controllers\RoomController::class . ':getChatroom')
	->add(App\Middleware\RoomRequest::class);

	$app->post('/room/{id}', App\Controllers\RoomController::class . ':postMessage');

	$app->get('/room/{id}/log', App\Controllers\RoomController::class . ':getLOg');

})
->add(App\Middleware\Authenticated::class)
->add(App\Middleware\RoomInit::class);