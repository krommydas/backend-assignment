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
require 'responseFactory.php';

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

$app->addErrorMiddleware(false, true, true);

$app->get('/backend-assignment/shipLocations', function (Request $request, Response $response) {
	
	$itemCriteria = new ShipLocationFilters( $request->getQueryParams());
	
	$items = $this->get('dataService')->getShipLocations($itemCriteria);

    return (new ResponseFactory($request, $response))->SerializeData($items);
});

$app->post('/backend-assignment/shipLocations', function (Request $request, Response $response) {
	
	$items = $request->getParsedBody();

    $this->get('dataService')->insertShipLocations($items);

    return new Zend\Diactoros\Response\EmptyResponse();
});


$app->run();
