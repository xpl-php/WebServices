<?php

namespace xpl\WebServices\Yahoo {
	class functions {
		// dummy class
	}
}

namespace {
	
	/**
	 * Creates and returns a YQL request object.
	 * 
	 * @param string $query [Optional] YQL query.
	 * @return \xpl\WebServices\Yahoo\Yql\Request
	 */
	function yql_query($query = null) {
		return new \xpl\WebServices\Yahoo\Yql\Request($query);
	}
	
	/**
	 * Returns a URL for the given YQL query.
	 * 
	 * @param string $query YQL query string.
	 * @param boolean $json [Optional] Whether to fetch as JSON (otherwise, XML). Default true.
	 * @param string $env [Optional] YQL "env" query string.
	 * @return string URL for given YQL query.
	 */
	function yql_url($query, $json = true, $env = 'store://datatables.org/alltableswithkeys') {
		
		$query_string = http_build_query(array(
			'q' => $query,
			'format' => $json ? 'json' : 'xml',
			'env' => $env,
		), null, '&');
		
		return "http://query.yahooapis.com/v1/public/yql?{$query_string}";
	}
	
	/**
	 * Sets or gets a Yahoo Pipe ID by key.
	 * 
	 * @param string $key User-defined key name.
	 * @param string $id [Optional] Pipe ID to associate with the key.
	 * @return string Pipe ID if found, otherwise null.
	 */
	function yahoo_pipe_id($key, $id = null) {
		if (isset($id)) {
			\xpl\WebServices\Yahoo\Pipes\Id::set($key, $id);
		}
		return \xpl\WebServices\Yahoo\Pipes\Id::get($key);
	}
	
}
