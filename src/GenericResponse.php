<?php

namespace xpl\WebServices;

class GenericResponse extends Response 
{
	
	public function __construct($data) {
		if (! is_string($data)) {
			$this->decoded_data = $data;
		} else {
			$this->raw_data = $data;
			$this->processRawData();
		}
	}
	
	public function getResults() {
		return isset($this->decoded_data) ? $this->decoded_data : null;
	}
	
	protected function processRawData() {
		
		if (empty($this->raw_data)) {
			return;
		}
		
		if (@json_decode($this->raw_data) && JSON_ERROR_NONE === json_last_error()) {
			
			$this->jsonDecode();
		
		} else if ('<?xml ' == substr($this->raw_data, 0, 6)) {
			
			$this->xmlDecode();
		}
	}
	
}
