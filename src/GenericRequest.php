<?php

namespace xpl\WebServices;

class GenericRequest implements RequestInterface
{
	protected $url;
	protected $format = 'json';
	protected $options = array();
	protected $response;
	
	public function __construct($url = null) {
		if (isset($url)) {
			$this->url = $url;
		}
	}
	
	public function setFormat($format) {
		$this->format = $format;
	}
	
	public function getFormat() {
		return $this->format;
	}
	
	public function getUrl() {
		return isset($this->url) ? $this->url : null;
	}
	
	public function setMethod($method) {
		$this->setOption('method', $method);
	}
	
	public function setOptions(array $options, $overwrite = true) {
		if ($overwrite || empty($this->options)) {
			$this->options = $options;
		} else {
			$this->options = array_merge($this->options, $options);
		}
	}
	
	public function setOption($key, $value) {
		$this->options[$key] = $value;
	}
	
	public function getOption($key) {
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}
	
	public function getOptions() {
		return $this->options;
	}
	
	public function createResponse($data) {
		return $this->response = new GenericResponse($data);
	}
	
	public function getResponse() {
		return isset($this->response) ? $this->response : null;
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	
}
