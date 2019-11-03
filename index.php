<?php

use DI\Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

require 'vendor/autoload.php';
require 'dataService.php';
require 'shipLocationFilters.php';
require 'requestTrackMiddleware.php';

$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('dataService', function () {
	$settingsFile =  file_get_contents("settings.json"); 
	$settings = json_decode($settingsFile);
    return new DataService($settings->databaseSettings);
});

$app->add(new RequestTrackMiddleware($container->get('dataService')));

$app->add(new RKA\Middleware\IpAddress(false));

$app->add(new BodyParamsMiddleware());

$app->get('/backend-assignment/shipLocations', function (Request $request, Response $response) {
	
	$itemCriteria = new ShipLocationFilters( $request->getQueryParams());
	
	$items = $this->get('dataService')->getShipLocations($itemCriteria);
	
    $payload = json_encode($items);

    $response->getBody()->write($payload);
    return $response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(200);
});

$app->post('/backend-assignment/shipLocations', function (Request $request, Response $response) {
	
	$items = $request->getParsedBody();

    $this->get('dataService')->insertShipLocations($items);

    return $response->withStatus(200);
});


$app->run();
