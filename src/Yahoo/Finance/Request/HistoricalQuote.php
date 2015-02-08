<?php

namespace xpl\WebServices\Yahoo\Finance\Request;

use xpl\WebServices\Yahoo\Finance\AbstractRequest;
use xpl\WebServices\Yahoo\Finance\Response;

class HistoricalQuote extends AbstractRequest
{
	
	protected $symbol;
	protected $start_date;
	protected $end_date;
	protected $interval;
	protected $request_args = array();
	
	protected $intervals = array(
		'daily' => 'd',
		'weekly' => 'w',
		'monthly' => 'm',
		'dividends' => 'v',
	);
	
	public function __construct($symbol = null) {
		isset($symbol) and $this->setSymbol($symbol);
	}
	
	public function setSymbol($symbol) {

		if (is_array($symbol) || false !== strpos($symbol, ',')) {
			throw new \InvalidArgumentException("Historical quote does not support multiple symbols.");
		}

		$this->symbol = str_replace('.', '-', $symbol);
	}
	
	public function setStartDate($date) {
		
		if (! $formatted = $this->formatDate($date)) {
			throw new \InvalidArgumentException("Invalid start date given.");
		}
		
		$this->start_date = $formatted;
	}
	
	public function setEndDate($date) {
		
		if (! $formatted = $this->formatDate($date)) {
			throw new \InvalidArgumentException("Invalid start date given.");
		}
		
		$this->end_date = $formatted;
	}
	
	/**
	 * Frequency interval. One of "daily" (default), "weekly", "monthly", or "dividends".
	 */
	public function setInterval($interval) {
		
		if (isset($this->intervals[$interval])) {
			$interval = $this->intervals[$interval];
		
		} else if (! in_array($interval, $this->intervals, true)) {
			throw new \InvalidArgumentException("Invalid interval given: '$interval'.");
		}
		
		$this->interval = $interval;
	}
	
	public function getUrl() {
		
		if (! isset($this->start_date)) {
			throw new \RuntimeException("Must set a start date.");
		}
		
		if (! isset($this->interval)) {
			$this->interval = 'daily';
		}
		
		list($sM, $sD, $sY) = $this->start_date;
		
		$params = array(
			's' => $this->symbol,
			'g' => $this->interval,
			'a' => $sM,
			'b' => $sD,
			'c' => $sY,
		);
		
		if (isset($this->end_date)) {
		
			list($eM, $eD, $eY) = $this->end_date;
			
			$params['d'] = $eM;
			$params['e'] = $eD;
			$params['f'] = $eY;
		}
		
		return $this->buildUrl($params);
	}
	
	public function formatDate($value) {
			
		if ($time = strtotime($value)) {
			return array(
				// yahoo is weird and has months indexed from 0
				date('m', $time) - 1,
				date('d', $time),
				date('Y', $time)
			);
		}

		return false;
	}

	public function createResponse($data) {
		// Passing TRUE to have headers parsed from data
		return $this->response = new Response($data, true);
	}
	
	public function getOptions() {
		return array(
			'method' => 'GET',
			'format' => $this->getFormat(),
		);
	}
	
	public function getBaseUrl() {
		return 'http://ichart.yahoo.com/table.csv';
	}
	
	protected function appendToUrl() {
		return '&ignore=.csv';
	}
	
	public function getParameters() {
		return array(
			'symbol' => 's',
			'start_month' => 'a',
			'start_day' => 'b',
			'start_year' => 'c',
			'end_month' => 'd',
			'end_day' => 'e',
			'end_year' => 'f',
			'interval' => 'g',
		);
	}
	
}
