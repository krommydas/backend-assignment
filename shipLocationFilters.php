<?php

class ShipLocationFilters
{
	 function __construct($query) 
	 {
		 if(!is_array($query) || !isset($query)) 
			 return;
		 
		 foreach($query as $property => $value)
		 {
			 switch($property)
			 {
				 case 'mmsi': $this->mmsi = $value; break;
				 case 'minLat': $this->minLat = $value; break; 
				 case 'minLon': $this->minLon = $value; break;
				 case 'maxLon': $this->maxLon = $value; break;
				 case 'maxLat': $this->maxLat = $value; break;
				 default: break;
			 }
				 
		 }
	 }
	
	public $mmsi;
	
	public $minLat;
	
	public $minLon;
	
	public $maxLon;
	
	public $maxLat;
	
	public function toMongoDBFilters() 
	{
		if(!$this->hasAnyValue())
			return array();
		
		$finalQuery = array();
		 
	    $mmsiFilter = $this->getFieldMongoDBQuery('mmsi', [ '$in' => $this->tryExtractArrayIntValues($this->mmsi) ]);
		if(!empty($mmsiFilter)) array_push($finalQuery, $mmsiFilter);
		
		$latFilter = $this->getFieldMongoDBQuery('lat', [ '$gt' => $this->tryExtractDoubleValue($this->minLat), '$lte' => $this->tryExtractDoubleValue($this->maxLat)] );
		if(!empty($latFilter)) array_push($finalQuery, $latFilter);
		
		$lonFilter = $this->getFieldMongoDBQuery('lon', [ '$gt' => $this->tryExtractDoubleValue($this->minLon), '$lte' => $this->tryExtractDoubleValue($this->maxLon)] );
		if(!empty($lonFilter)) array_push($finalQuery, $lonFilter);
		
		if(empty($finalQuery))
			return array();
		
		return [ '$and' => $finalQuery ];
	}
	
	private function getFieldMongoDBQuery($fieldName, $fieldValueCriteria)
	{
		$fieldCriteria = array();

		foreach($fieldValueCriteria as $operator => $value)
		{
			if(!isset($value)) continue;
			array_push($fieldCriteria, [ $fieldName => [ $operator => $value ] ]); 
		}
		
		if(empty($fieldCriteria)) 
			return $fieldCriteria;
		
		array_push($fieldCriteria, [ $fieldName => [ '$exists' => true ]]);
		
		return ['$and' => $fieldCriteria ];
	}
	
	function tryExtractArrayIntValues($values)
	{
		if(!isset($values)) return $values; 
		
		$arrayValues = array();
		foreach(explode(',', $values) as $rawValue)
		{ 
			$value = $this->tryExtractIntValue($rawValue);
			if(!isset($value)) continue;
			array_push($arrayValues, $value);
		}
		
		if(empty($arrayValues))
			return null;
		
		return array_unique($arrayValues);
	}
	
	function tryExtractIntValue($value)
	{
		if(!isset($value)) return $value; 
		if(!is_numeric($value)) return null;
		return intval($value);
	}
	function tryExtractDoubleValue($value)
	{
		if(!isset($value)) return $value;
		if(!is_numeric($value)) return null;
		return doubleval($value);
	}
	
	public function hasAnyValue() 
	{
		return isset($this->mmsi) || isset($this->minLat) || isset($this->minLon) || isset($this->maxLon) || isset($this->maxLat);
	}

}

?>