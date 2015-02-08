<?php

namespace xpl\WebServices\Client;

interface MultiRequestAdapterInterface extends RequestAdapterInterface {
	
	public function multi(array $requests);
	
}
