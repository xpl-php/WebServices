<?php

namespace xpl\WebServices\Yahoo\Pipes;

use xpl\WebServices\RequestInterface;

class Request implements RequestInterface
{
	
	private $baseUrl = 'http://pipes.yahoo.com/pipes/pipe.run';
	protected $id;
	protected $format = 'json';
	protected $params = array();
	protected $url;
	protected $response;
	protected $validFormats = array('json', 'php', 'csv');
	
	public function __construct($pipe_id = null) {
		isset($pipe_id) and $this->setId($pipe_id);
	}
	
	public function setId($pipe_id) {
		$this->id = $pipe_id;
		return $this;
	}
	
	public function setFormat($format) {
		
		$format = strtolower($format);
		
		if (in_array($format, $this->validFormats, true)) {
			$this->format = $format;
		}
		
		return $this;
	}
	
	public function setParams(array $params) {
		$this->params = $params;
		return $this;
	}
	
	public function getId() {
		return isset($this->id) ? $this->id : null;
	}
	
	public function getFormat() {
		return $this->format;
	}
	
	public function getOptions() {
		return array(
			'method' => 'GET',
			'format' => $this->getFormat(),
		);
	}
	
	public function getParams() {
		return $this->params;
	}
	
	public function getUrl() {
		
		if (! isset($this->id)) {
			throw new \RuntimeException("Cannot get URL: no Pipe ID set.");
		}
		
		$args = array(
			'_id' => $this->id,
			'_render' => $this->format,
		);
		
		if (! empty($this->params)) {
			$args += $this->params;
		}
		
		return $this->baseUrl.'?'.http_build_query($args, null, '&');
	}
	
	public function createResponse($data) {
		if ('json' === $this->getFormat()) {
			return $this->response = new JsonResponse($data);
		} else {
			return $this->response = new CsvResponse($data);
		}
	}
	
	public function getResponse() {
		return isset($this->response) ? $this->response : null;
	}
	
}
