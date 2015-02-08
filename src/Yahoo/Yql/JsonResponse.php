<?php

namespace xpl\WebServices\Yahoo\Yql;

class JsonResponse extends \xpl\WebServices\JsonResponse 
{
	
	/**
	 * Returns the response results, if able.
	 * 
	 * @return object
	 */
	public function getResults() {
		
		if (isset($this->results)) {
			return $this->results;
		}
			
		if (isset($this->decoded_data->query->results)) {
			
			// YQL returns a nested object within "results" with a key set 
			// by the table (so we cannot access it generically).
			// There is usually only 1 item, so we can just shift it off if so.
			
			$results = (array)$this->decoded_data->query->results;
			
			if (count($results) === 1) {
				return $this->results = array_shift($results);
			} else {
				return $this->results = $results;
			}
		}
	}
	
}
