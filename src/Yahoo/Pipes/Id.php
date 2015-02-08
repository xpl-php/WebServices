<?php

namespace xpl\WebServices\Yahoo\Pipes;

class Id {
	
	protected static $protect = array();
	protected static $ids = array(
	
		// Name, Description, Sector, Industry, FullTimeEmployees
		'basic' => 'ab0f9a7da7de776943bed5863e6c0637',
		
		// Date, Time, Last, Open, Change, Volume, AvgDailyVolume, DayLow, DayHigh, MarketCap
		// FiftyDayMovingAvg, TwoHundredDayMovingAvg, YearLow, YearHigh, PriceToSales, 
		// PriceToEarnings, PriceToBook, PEGRatio
		'quote' => '9e88fc312b261410c127954bdd705372',
		
		// Beta, SharesShort, AvgVol, SharesOutstanding, Float, 
		// PercentageHeldbyInsiders/Institutions, EnterpriseValue
		// ForwardAnnualDividendRate/Yield, TrailingAnnualDividendYield
		// p_5YearAverageDividendYield, DividendDate, Ex_DividendDate
		'shareData' => '5a980da1fd4dfe075df7ddee3efa6b84',
		
		// financial statements..
		'statements' => '104edbcc09c1705a8076f252452d4c36',
		
		// Revenue, GrossProfit, EBITDA, NetIncomeAvltoCommon, TotalCash
		// TotalDebt, TotalDebtEquity, BookValuePerShare, OperatingCashFlow
		// LeveredFreeCashFlow, QtrlyRevenueGrowth, QtrlyEarningsGrowth
		// ProfitMargin, OperatingMargin, ReturnonAssets, ReturnonEquity
		// FiscalYearEnds, MostRecentQuarter
		'financials' => '447615e3d4563d58b1835e3535cd7a65',
	);
	
	public static function get($key) {
		return isset(static::$ids[$key]) ? static::$ids[$key] : null;
	}
	
	public static function set($key, $id) {
		
		if (isset(static::$protect[$key])) {
			throw new \RuntimeException("Cannot override protected item '$key'.");
		}
		
		static::$ids[$key] = $id;
	}
	
	public static function has($key) {
		return isset(static::$ids[$key]);
	}
	
	public static function remove($key) {
		
		if (isset(static::$protect[$key])) {
			throw new \InvalidArgumentException("Cannot remove protected item '$key'.");
		}
		
		if (isset(static::$ids[$key])) {
			unset(static::$ids[$key]);
		
		} else if ($realkey = static::lookup($key)) {
			static::remove($realkey);
		}
	}
	
	public static function contains($id) {
		return in_array($id, static::$ids, true);
	}
	
	public static function lookup($id) {
		return array_search($id, static::$ids, true);
	}
	
	public static function protect($key) {
		
		if (! isset(static::$ids[$key])) {
			throw new \InvalidArgumentException("Unknown Pipe key '$key'.");
		}
		
		static::$protect[$key] = true;
	}
	
	public static function isProtected($var) {
			
		// check if given a protected key
		if (isset(static::$protect[$var])) {
			return true;
		}
		
		// check if given a protected id
		if ($key = static::contains($var)) {
			return isset(static::$protect[$key]);
		}
		
		return false;
	}
	
}
