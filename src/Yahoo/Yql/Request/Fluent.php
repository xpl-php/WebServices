<?php
/**
 * @package xpl\WebServices
 * @subpackage Yahoo\Yql
 */

namespace xpl\WebServices\Yahoo\Yql\Request;

use xpl\WebServices\Yahoo\Yql\Request as YqlRequest;

class Fluent
{
	protected $request;
	protected $statements = array();
	protected $tables = array();

	/**
	 * Constructor.
	 * 
	 * @param \xpl\WebServices\Yahoo\Yql\Request
	 */
	public function __construct(YqlRequest $yqlRequest) {
		$this->request = $yqlRequest;
	}

	/**
	 * @return \xpl\WebServices\Yahoo\Yql\Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Adds a statement to YQL query.
	 * 
	 * @param string $type Statement type, lowercase.
	 * @param string $str Statement string.
	 * @return $this
	 */
	public function addStatement($type, $str) {

		if ('use' === $type || 'where' === $type) {
			$this->statements[$type][] = $str;
		} else {
			$this->statements[$type] = $str;
		}

		return $this;
	}

	/**
	 * Returns statement string.
	 */
	public function getStatement($type) {

		$str = '';

		switch(strtolower($type)) {

			case 'select' :
				return $this->statements['select'];

			case 'use' :
			case 'where' :
				
				if (empty($this->statements[$type])) {
					return '';
				}

				foreach ($this->statements[$type] as $stmt) {
					
					if ('where' === $type) {
						$strs[] = trim(str_ireplace('where', '', $stmt), ' ;');
					
					} else {
						$str .= $stmt;
					}
				}

				if ('where' === $type) {
					$str = 'WHERE '. implode(' AND ', $strs) .';';
				}

				return $str;

			case 'from' :
				
				if (! empty($this->statements['from'])) {
					return $this->statements['from'];
				}

				if (empty($this->tables)) {
					throw new \RuntimeException("No table set - must specify table using from() or useTable() methods.");
				}

				$tables = array_values($this->tables);
				
				// use first table
				return $tables[0];
				
			default:
				throw new \RuntimeException("Unknown statement type '$type'.");
		}
	}

	/**
	 * Sets Select statement of the YQL query
	 */
	public function select($select) {

		if (is_array($select)) {
			$select = implode(', ', $select);
		}

		$this->addStatement('select', "SELECT {$select} ");

		return $this;
	}

	/*
	 * Sets FROM statement of YQL query
	 * Not required if 'use_table' is set
	 */
	public function from($from) {

		$this->addStatement('from', "FROM {$from} ");

		return $this;
	}

	/**
	 * Sets WHERE statement of YQL query.
	 */
	public function where($where) {

		$this->addStatement('where', "WHERE {$where} ");

		return $this;
	}

	/**
	 *	Sets USE statement (optional)
	 */
	public function useTable($path, $table = null) {

		if (! filter_var($path, FILTER_VALIDATE_URL)) {
			$url = $this->request->baseTableUrl.ltrim($path, '/');
		} else {
			$url = $path;
		}

		if (empty($table)) {
			$table = trim(substr($path, strrpos($path, '/')), '/');
			$table = trim(str_replace('.xml', '', $table));
		}

		$this->addStatement('use', 'USE "'.$url.'" AS '.$table.'; ');

		$this->tables[$url] = $table;

		return $this;

	}

	/**
	 * Sets YQL environment (optional).
	 * 
	 * @param string $env Environment query param string.
	 */
	public function env($env) {
		
		$this->request->setEnv($env);
		
		return $this;
	}

	/**
	 * Returns YQL query string from statements.
	 * 
	 * @return string
	 */
	public function __toString() {
		return $this->getStatement('use')
			.$this->getStatement('select')
			.$this->getStatement('from')
			.$this->getStatement('where');
	}

}
