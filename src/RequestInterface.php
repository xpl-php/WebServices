<?php

namespace xpl\WebServices;

interface RequestInterface {
	
	public function setFormat($format);
	
	public function getFormat();
	
	public function getUrl();
	
	public function getOptions();
	
	public function createResponse($data);
	
	public function getResponse();
	
}
