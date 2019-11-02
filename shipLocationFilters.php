<?php

class ShipLocationFilters
{
	public $mmsi;
	
	public $minLat;
	
	public $minLon;
	
	public $maxLon;
	
	public $maxLat;
	
	public function toMongoDBFilters() 
	{
		if(!$this->hasAnyValue())
			return '{}';
		
		var $mmsiFilterOperator = is_array($this->mmsi) ? '$in' : '$eq';
		var $mmsiFilter = $this->getFieldMongoDBQuery('mmsi', $this->mmsi, $mmsiFilterOperator);
		
		var $minLatFilter = $this->getFieldMongoDBQuery('lat', $this->minLat, '$gt');
		
		var $minLonFilter = $this->getFieldMongoDBQuery('lon', $this->minLon, '$gt');
   
	    var $maxLatFilter = $this->getFieldMongoDBQuery('lat', $this->maxLat, '$lte');
		
		var $maxLonFilter = $this->getFieldMongoDBQuery('lon', $this->maxLon, '$lte');
		
		var $filters = "$mmsiFilter,$minLatFilter,$minLonFilter,$maxLatFilter,$maxLonFilter";
		return '{$and: ['. $filters .'] }';
	}
	
	private function getFieldMongoDBQuery($fieldName, $fieldValue, $operator)
	{
		var $filter = '{}';
		if(isset($fieldValue))
			$filter = $this->enchanceMongoDBFieldQuery($fieldName, "$operator:$fieldValue");
		return $filter;
	}
	
	private function enchanceMongoDBFieldQuery($fieldName, $fieldQuery)
	{
		return "{ $fieldName: {/$fieldQuery} }, { $fieldName: {". '$exists: true } }';
	}
	
	public function hasAnyValue() 
	{
		return isset($this->mmsi) || isset($this->minLat) || isset($this->minLon) || isset($this->maxLon) || isset($this->maxLat);
	}

}

?>