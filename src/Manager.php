<?php

namespace xpl\WebServices;

class Manager
{
	/**
	 * @var \xpl\WebServices\Client\RequestAdapterInterface
	 */
	protected static $adapter;
	
	/**
	 * @var string
	 */
	protected static $adapter_class;
	
	/**
	 * Sets the request client adapter object.
	 * 
	 * @param \xpl\WebServices\Client\RequestAdapterInterface $adapter
	 */
	public static function setAdapter(Client\RequestAdapterInterface $adapter) {
		static::$adapter = $adapter;
	}
	
	/**
	 * Sets the request client adapter class.
	 * 
	 * @param string $class
	 */
	public static function setAdapterClass($class) {
		static::$adapter_class = $class;
	}
	
	/**
	 * Sets the adapter class to that first detected.
	 * 
	 * @return boolean True if a class was detected and set, otherwise false.
	 */
	public static function setDetectedAdapterClass() {
		
		if ($class = static::detectAdapterClass()) {
			static::setAdapterClass($class);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns whether an adapter or adapter class is set.
	 * 
	 * @return boolean
	 */
	public static function hasAdapter() {
		return isset(static::$adapter) || isset(static::$adapter_class);
	}
	
	/**
	 * Returns the request client adapter, if set.
	 * 
	 * @return \xpl\WebServices\Client\RequestAdapterInterface
	 */
	public static function getAdapter() {
		
		if (isset(static::$adapter)) {
			return static::$adapter;
		}
		
		if (isset(static::$adapter_class)) {
			
			$class = static::$adapter_class;
			
			static::setAdapter(new $class);
			
			return static::$adapter;
		}
		
		return null;
	}
	
	/**
	 * Returns a Client instance if an adapter is set.
	 * 
	 * @return \xpl\WebServices\Client
	 */
	public static function getClient() {
		
		if ($adapter = static::getAdapter()) {
		
			return new Client($adapter);
		}
		
		return null;
	}
	
	/**
	 * Returns the "best" request adapter class available.
	 * 
	 * @return string Class name, or null if none are available.
	 */
	public static function detectAdapterClass() {
		
		$map = array(
			'Requests'			=> 'xpl\WebServices\Client\RequestsAdapter',
			'GuzzleHttp\Client'	=> 'xpl\WebServices\Client\GuzzleAdapter',
			'Buzz\Browser'		=> 'xpl\WebServices\Client\BuzzAdapter',
		);
		
		foreach($map as $library => $adapter) {
			
			if (class_exists($library, true)) {
				return $adapter;
			}
		}
		
		if (ini_get('allow_url_fopen')) {
			return 'xpl\WebServices\Client\FopenAdapter';
		}
		
		return null;
	}
	
	
}
