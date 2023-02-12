<?php

use Src\App\Controller\HomeController;
use Src\App\Controller\UserController;
use Src\App\Util\Response;

require 'vendor/autoload.php';

$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
$uri = parse_url($_SERVER['REQUEST_URI']);

$allowedMethods = [
	'GET', 'POST'
];

if (!in_array($requestMethod, $allowedMethods)) {
	Response::sendError(405, 'Метод недоступен');
}

try {
	$result = null;

	// Simple router for debugging
	switch ($uri['path']) {

		case '':
		case '/':
			$ctrl = new HomeController();
			$result = $ctrl->index();
			break;

		case '/user':
			$ctrl = new UserController();
			$result = $ctrl->index();
			break;

		default:
			Response::sendError(404, 'Route not found');
			break;
	}

	Response::sendJson($result);

} catch (Exception $e) {
	Response::sendException($e);
}

exit();
