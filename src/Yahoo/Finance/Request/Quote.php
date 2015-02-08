<?php

namespace xpl\WebServices\Yahoo\Finance\Request;

use xpl\WebServices\Yahoo\Finance\AbstractRequest;
use xpl\WebServices\Yahoo\Finance\Response;

class Quote extends AbstractRequest
{
	
	protected $symbol;
	protected $multi_query = false;
	protected $request_args = array();
	
	protected static $args = array(
		// GENERAL INFO
		'Symbol' => 's',
		'Name' => 'n',
		'Exchange' => 'x',
		'Currency' => 'c4',

		// --- SHARE DATA --- //
		'Float' => 'f6',
		'SharesOutstanding' => 'j2',
		'ShortRatio' => 's7',
		// Diluted EPS
		'EPS' => 'e',
		// Book value per share
		'BV' => 'b4',
		
		// --- RATIOS --- //
		// Price-to-earnings
		'PE' => 'r',
		// Price-to-earnings growth
		'PEG' => 'r5',
		// Price-to-sales
		'PS' => 'p5',
		// Price-to-book value
		'PBV' => 'p6',

		// --- DIVIDEND --- //
		'Dividend' => 'd',
		'DividendYield' => 'y',
		'ExDividendDate' => 'q',
		'DividendPayDate' => 'r1',

		// --- FINANCIAL --- //
		'EBITDA' => 'j4',
		'Revenue' => 's6',

		// --- QUOTE --- //
		'Date' => 'd1',
		'Time' => 't1',
		'Last' => 'l1',
		'Open' => 'o',
		'Change' => 'c1',
		'ChangePercent' => 'p2',
		'Volume' => 'v',
		'AvgDailyVolume' => 'a2',
		'Low' => 'g',
		'High' => 'h',
		'MarketCap' => 'j1',
		'Ask' => 'a',
		'Bid' => 'b',
		'PreviousClose' => 'p',
		
		// --- PRICES --- //
		// Low/High
		'YearLow' => 'j',
		'YearLowChange' => 'j5',
		'YearLowChangePercent' => 'j6',
		'YearHigh' => 'k',
		'YearHighChange' => 'k4',
		'YearHighChangePercent' => 'k5',
		
		// Moving averages
		'MA50' => 'm3',
		'MA200' => 'm4',
		'MA50Change' => 'm7',
		'MA200Change' => 'm5',
		'MA50ChangePercent' => 'm8',
		'MA200ChangePercent' => 'm6',
	);
	
	public function __construct($symbol = null) {
		isset($symbol) and $this->setSymbol($symbol);
	}
	
	public function setSymbol($symbol) {

		if (is_array($symbol)) {
			$symbol = implode(',', $symbol);
			$this->multi_query = true;
		
		} else if (false !== strpos($symbol, ',')) {
			$this->multi_query = true;
		}

		$this->symbol = str_replace('.', '-', $symbol);
	}
	
	public function getArg($key) {
		return isset(static::$args[$key]) ? static::$args[$key] : null;
	}
	
	public function lookupArg($value) {
		return array_search($value, static::$args, true);
	}
	
	public function addArg($key) {
		if ($flag = $this->getArg($key)) {
			$this->request_args[$key] = $flag;
		}
	}
	
	public function addArgs(array $args) {
		array_map(array($this, 'addArg'), $args);
	}
	
	public function hasArg($key) {
		return isset($this->request_args[$key]) || in_array($key, $this->request_args, true);
	}
	
	public function getRequestArgs() {
		return $this->request_args;
	}
	
	public function getDefaultArgs() {
		return array('d1', 't1', 'l1', 'o', 'c1', 'p2', 'v', 'a2', 'g', 'h', 'j1', 'a', 'b', 'p');
	}

	public function mapArgsToColumns(array $args) {
		$columns = array();
		foreach($args as $arg) {
			if ($col = $this->lookupArg($arg)) {
				$columns[$arg] = $col;
			}
		}
		return $columns;
	}
	
	public function isMulti() {
		return $this->multi_query;
	}
	
	public function createResponse($data) {
		
		$columns = $this->mapArgsToColumns($this->getRequestArgs());
		
		return $this->response = new Response($data, $columns);
	}
	
	public function getUrl() {
		
		if (! isset($this->symbol)) {
			throw new \RuntimeException("Cannot get URL without symbol.");
		}
		
		$args = $this->getRequestArgs();
		
		if (empty($args)) {
			$this->request_args = $args = $this->getDefaultArgs();
		}
		
		$params = array(
			's' => $this->symbol,
			'f' => implode('', $args),
		);
		
		return $this->buildUrl($params);
	}
	
	public function getOptions() {
		return array(
			'method' => 'GET',
			'format' => $this->getFormat(),
		);
	}
	
	public function getBaseUrl() {
		return 'http://download.finance.yahoo.com/d/quotes.csv';
	}
	
	protected function appendToUrl() {
		return '&e=.csv';
	}
	
	public function getParameters() {
		return array(
			'symbol' => 's',
			'properties' => 'f',
		);
	}
	
}
