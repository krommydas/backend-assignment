<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'CSVResponse.php';

class NotSupportedMediaTypeException extends Exception {}

class ResponseFactory
{
	function __construct(Request $request, Response $response) 
	 {
		 $this->Request = $request;
		 $this->Response = $response;
	 }	
	
	private $Request;
	private $Response;
	
	public function SerializeData($data) 
	{ 
		switch($this->Request->getHeaderLine('Content-Type'))
		{
			case '':
			case 'application/json': return new Zend\Diactoros\Response\JsonResponse($data);
			case 'text/csv': return new CSVResponse($data);
			default: throw new NotSupportedMediaTypeException();
		}
	}
	
	private function getJSONResponse($data)
	{
		$payload = $this->Response->getBody()->write($payload);
		return $this->Response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(200);
	}
	
	
}

?>