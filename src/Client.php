<?php

namespace xpl\WebServices;

class Client {
	
	protected $adapter;
	
	public function __construct(Client\RequestAdapterInterface $adapter) {
		$this->adapter = $adapter;
	}
	
	public function __invoke(RequestInterface $request) {
		return $request->createResponse(call_user_func($this->adapter, $request->getUrl(), $request->getOptions()));
	}
	
	public function supportsMultiple() {
		return $this->adapter instanceof Client\MultiRequestAdapterInterface;
	}
	
	public function multi(array $requests) {
		
		if (! $this->supportsMultiple()) {
			throw new \RuntimeException("Adapter does not support multiple requests.");
		}
		
		return $this->adapter->multi($requests);
	}
	
	public function createRequest($url = null) {
		return new GenericRequest($url);
	}
	
	/**
	 * Returns the "best" request adapter class available.
	 * @return string Class name, or null if none are available.
	 */
	public static function detectAdapterClass() {
		return Manager::detectAdapterClass();
	}
	
}
