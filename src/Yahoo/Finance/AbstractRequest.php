<?php

namespace xpl\WebServices\Yahoo\Finance;

abstract class AbstractRequest implements \xpl\WebServices\RequestInterface 
{
	
	protected $response;
	
	abstract public function getUrl();
	
	abstract public function getBaseUrl();
	
	abstract public function getParameters();
	
	abstract public function createResponse($data);
	
	public function getParam($key) {
		$params = $this->getParameters();
		return isset($params[$key]) ? $params[$key] : null;
	}
	
	public function getParamKey($param) {
		return array_search($param, $this->getParameters(), true);
	}
	
	public function buildUrl(array $params = array()) {
		
		$url = $this->getBaseUrl().'?';
		
		if (! empty($params)) {
			$url .= rawurldecode(http_build_query($params, null, '&'));
		}
		
		$url .= $this->appendToUrl();
		
		return rtrim($url, '&?');
	}
	
	public function translateParams(array $params) {
		$parameters = $this->getParameters();
		$translated = array();
		foreach($params as $key => $value) {
			if (! isset($parameters[$key])) {
				if (! $real_key = array_search($key, $parameters, true)) {
					continue;
				}
				$key = $real_key;
			}
			$translated[$key] = $value;
		}
		return $translated;
	}
	
	public function setFormat($format) {
		if ('csv' !== strtolower($format)) {
			throw new \InvalidArgumentException("Yahoo Finance requests are always CSV format.");
		}
	}
	
	public function getFormat() {
		return 'csv';
	}
	
	public function getResponse() {
		return $this->response;
	}
	
	protected function appendToUrl() {
		return '';
	}
	
}
