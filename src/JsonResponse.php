<?php

namespace xpl\WebServices;

abstract class JsonResponse extends Response 
{
	
	/**
	 * Constructor.
	 * 
	 * @param mixed $raw Raw response content returned from request execution.
	 */
	public function __construct($raw) {
		
		parent::__construct($raw);
		
		if (is_string($raw)) {
			$this->jsonDecode();
		}
	}
	
}
