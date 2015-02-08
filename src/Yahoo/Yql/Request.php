<?php
/**
 * @package xpl\WebServices
 * @subpackage Yahoo\Yql
 */

namespace xpl\WebServices\Yahoo\Yql;

use xpl\WebServices\RequestInterface;

class Request implements RequestInterface
{

	private $baseUrl = 'http://query.yahooapis.com/v1/public/yql';
	protected $baseTableUrl = 'http://www.datatables.org/';
	protected $env = 'store://datatables.org/alltableswithkeys';
	protected $format = 'json';
	protected $diagnostics = false;
	protected $query;
	protected $url;
	protected $response;
	
	/**
	 * Constructor.
	 * 
	 * @param string|\xpl\WebServices\Yahoo\Yql\Request\Fluent $query [Optional]
	 */
	public function __construct($query = null) {
		isset($query) and $this->setQuery($query);
	}
	
	public function createResponse($data) {
		if ('xml' === $this->getFormat()) {
			return $this->response = new XmlResponse($data);
		} else {
			return $this->response = new JsonResponse($data);
		}
	}
	
	/**
	 * Returns the response object.
	 * 
	 * @return \xpl\WebServices\Yahoo\Yql\Response 
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * Set the "env" query param string.
	 * 
	 * @param string $env
	 * @return $this
	 */
	public function setEnv($env) {
		$this->env = $env;
		return $this;
	}
	
	/**
	 * Set whether to return diagnostics information.
	 * 
	 * @param boolean $val
	 * @return $this
	 */
	public function setDiagnostics($val) {
		$this->diagnostics = (bool)$val;
		return $this;
	}
	
	/**
	 * Sets the response format, one of "json" or "xml".
	 * 
	 * @param string $format "json" or "xml". Default is json.
	 * @return $this
	 */
	public function setFormat($format) {
			
		$format = strtolower($format);
		
		if ('json' === $format || 'xml' === $format) {
			$this->format = $format;
		}
		
		return $this;
	}
	
	/**
	 * Sets the query as string or instance of \xpl\WebServices\Yql\Request\Fluent
	 * 
	 * @param string|\xpl\WebServices\Yahoo\Yql\Request\Fluent $query 
	 * @return $this
	 */
	public function setQuery($query) {

		if (! is_string($query) && ! $query instanceof Request\Fluent) {
			throw new InvalidArgumentException("Invalid YQL query; must be FluentInterface or string, given: ".gettype($query));
		}
		
		$this->query = (string) $query;

		return $this;
	}
	
	/**
	 * Returns the YQL query as a string.
	 * 
	 * @return string
	 */
	public function getQuery() {
		return $this->query;
	}
	
	/**
	 * Returns the response format string, one of "json" or "xml".
	 * 
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}
	
	/**
	 * Returns options for the request, passed to client adapter.
	 * 
	 * @return array
	 */
	public function getOptions() {
		return array(
			'method' => 'GET',
			'format' => $this->getFormat()
		);
	}
	
	/**
	 * Returns the request URL string.
	 * 
	 * @return string
	 */
	public function getUrl() {
		isset($this->url) or $this->makeUrl();
		return $this->url;
	}
	
	/**
	 * Creates a URL string from the class properties.
	 */
	protected function makeUrl() {
		$this->url = $this->baseUrl
			.'?q='.urlencode($this->query)
			.'&format='.$this->format
			.((true == $this->diagnostics) ? '&diagnostics=true' : '')
			.(empty($this->env) ? '' : '&env='.urlencode($this->env));
	}

}
