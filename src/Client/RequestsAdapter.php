<?php

namespace xpl\WebServices\Client;

class RequestsAdapter implements MultiRequestAdapterInterface {
	
	public function __construct() {
		\Requests::register_autoloader();
	}
	
	public function __invoke($url, array $options = array()) {
		
		$method = isset($options['method']) ? strtolower($options['method']) : 'get';
		
		$response = \Requests::$method((string)$url);
		
		return $response->body;
	}
	
	public function multi(array $requests, $complete_callback = null) {
		
		$array = array();
		$options = isset($complete_callback) ? array('complete' => $complete_callback) : array();
		
		foreach($requests as $key => $request) {
			$array[$key] = array(
				'url' => $request->getUrl(),
			);
		}
		
		$results = \Requests::request_multiple($array, $options);
		
		$responses = array();
		
		foreach($results as $key => $result) {
			
			if ($result instanceof \Requests_Response) {
				$responses[$key] = $requests[$key]->createResponse($result->body);
			} else {
				$responses[$key] = $result;
			}
		}
		
		return $responses;
	}
	
}

