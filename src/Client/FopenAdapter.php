<?php

namespace xpl\WebServices\Client;

class FopenAdapter implements RequestAdapterInterface 
{
	
	public function __invoke($url, array $options = array()) {
		
		return file_get_contents((string)$url);
	}
	
}
