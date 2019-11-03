<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ThrottlingException extends Exception {}

class RequestTrackMiddleware
{
     function __construct($dataService) 
	 {
		 $this->DataService = $dataService;
	 }		 
  
    private $DataService;
  
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
       $requestItem = $this->getRequestItem($request);
	   
	   $this->DataService->insertRequest($requestItem);
	   
	   if($requestItem['Throttled'])
		  throw new ThrottlingException();

       return $handler->handle($request);
    }
	
	private function getRequestItem(Request $request)
	{
		$ipAddress = $request->getAttribute('ip_address');
		$uri = $request->getQueryParams();
		$timestamp = time();
		$throttleIP = $this->throttleIP($ipAddress);
		
		return [ 'IP' => $ipAddress, 'URI' => $uri, 'Time' => $timestamp, 'Throttled' => $throttleIP ];
	}
	
	private function throttleIP($ip) 
	{
		$oneHourBefore = date_create()->sub(new DateInterval('PT1H'))->getTimestamp();
		
		$query = [ 'IP' => $ip, 'Time' => [ '$gt' => $oneHourBefore ] ];
		$requestsWithinOneHour = $this->DataService->getRequestsCount($query);
		
		return $requestsWithinOneHour > 9;
	}
}

?>