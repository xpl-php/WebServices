<?php

namespace xpl\WebServices\Client;

class GuzzleAdapter implements RequestAdapterInterface 
{
	
	protected $client;
	
	public function __construct() {
		$this->client = new \GuzzleHttp\Client();
	}
	
	public function __invoke($url, array $options = array()) {
		
		$method = isset($options['method']) ? strtolower($options['method']) : 'get';
		
		$response = $this->client->$method((string)$url);
		
		return $response->getBody();
	}
	
}
