<?php

namespace xpl\WebServices\Yahoo\Finance;

class Response extends \xpl\WebServices\Response 
{
	
	public function __construct($data, $headers = array()) {
		
		parent::__construct($data);
		
		if (is_array($headers)) {
			
			$array = csv2array($data, false);
			
			foreach($array as &$row) {
				$this->bindHeaders($row, $headers);
			}
		
		} else {
			
			is_bool($headers) or $headers = false;
			
			$array = csv2array($data, $headers);
		}
		
		if (count($array) === 1) {
			$array = array_shift($array);
		}
		
		$this->decoded_data = array_to_object($array, true);
	}
	
	public function getResults() {
		
		if (isset($this->results)) {
			return $this->results;
		}
		
		return $this->decoded_data;
	}
	
	protected function bindHeaders(array &$data, array $headers) {
		if (count($data) == count($headers)) {
			$data = array_combine($headers, $data);
		}
	}
	
}
