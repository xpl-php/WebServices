<?php

namespace xpl\WebServices\Yahoo\Pipes;

class CsvResponse extends \xpl\WebServices\Response {
	
	public function __construct($data) {
		
		parent::__construct($data);
		
		$this->decoded_data = csv2array($data, false);
	}
	
	public function getResults() {
		
		if (isset($this->results)) {
			return $this->results;
		}
		
		if (count($this->decoded_data) === 1) {
			$data = (array) $this->decoded_data;
			return $this->results = array_shift($data);
		}
		
		return $this->decoded_data;
	}
	
}
