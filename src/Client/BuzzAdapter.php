<?php

namespace xpl\WebServices\Client;

class BuzzAdapter implements RequestAdapterInterface 
{
		
	protected $browser;
	
	public function __construct() {
		$this->browser = new \Buzz\Browser();
	}
	
	public function __invoke($url, array $options = array()) {
		
		$method = isset($options['method']) ? strtolower($options['method']) : 'get';
		
		$response = $this->browser->$method((string)$url);
		
		return $response->getContent();
	}
	
}
