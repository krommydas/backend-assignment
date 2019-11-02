<?php

require 'shipLocationFilters.php';

class DataService
{
	 function __construct($databaseSettings) { 
        $this->MongoClient = new MongoDB\Client("mongodb://". $databaseSettings->mongoDBServer);
		$this->DatabaseCollection = $this->MongoClient->BackendAssignment;
    }
	
	private $MongoClient;
	private $DatabaseCollection;
	
	public function getShipLocations(ShipLocationFilters $filters)
	{
		var $query = isset($filters) ? $filters->toMongoDBFilters() : '{}';
		
		return $this->DatabaseCollection->shipLocations->find($query);
	}
	
	public function insertShipLocations($items)
	{ 
		$this->DatabaseCollection->shipLocations->insertMany($items);
	}
}

?>