<?php

class DataService
{
	 function __construct($databaseSettings) { 
        $this->MongoClient = new MongoDB\Client("mongodb://". $databaseSettings->mongoDBServer);
		$this->DatabaseCollection = $this->MongoClient->BackendAssignment;
    }
	
	private $MongoClient;
	private $DatabaseCollection;
	
	public function getShipLocations()
	{
		return $this->DatabaseCollection->shipLocations->find();
	}
	
	public function insertShipLocations($items)
	{ 
		$this->DatabaseCollection->shipLocations->insertMany($items);
	}
}

?>