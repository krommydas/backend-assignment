<?php

class DataService
{
	 function __construct($databaseSettings) { 
        $this->MongoClient = new MongoDB\Client("mongodb://". $databaseSettings->mongoDBServer);
		$this->DatabaseCollection = $this->MongoClient->BackendAssignment;
    }
	
	private $MongoClient;
	private $DatabaseCollection;
	
	public function getRequestsCount($query)
	{
		return $this->DatabaseCollection->requests->countDocuments($query);
	}
	
	public function insertRequest($item)
	{
		$this->DatabaseCollection->requests->insertOne($item);
	}
	
	public function getShipLocations($filters)
	{
	    $query = isset($filters) ? $filters->toMongoDBFilters() : array();
		
		return $this->DatabaseCollection->shipLocations->find($query)->toArray();
	}
	
	public function insertShipLocations($items)
	{ 
		$this->DatabaseCollection->shipLocations->insertMany($items);
	}
}

?>