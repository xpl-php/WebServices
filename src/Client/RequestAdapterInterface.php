<?php

namespace xpl\WebServices\Client;

interface RequestAdapterInterface {
	
	public function __invoke($url, array $options = array());
	
}
