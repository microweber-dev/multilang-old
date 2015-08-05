<?php namespace Multilanguage;

require_once 'sqlparser/PHPSQLParser.php';

use PHPSQLParser;

class SqlParser {

	public $parsed;

	public function parse($sql)
	{
		$parser = new PHPSQLParser($sql, true);
		$this->parsed = $parser->parsed;
		return $this;
	}
	
	public function getTable($prefix = false)
	{
		$table = isset($this->parsed['FROM'][0]['no_quotes'])
	    ? $this->parsed['FROM'][0]['no_quotes']
	    : $this->parsed['UPDATE'][0]['no_quotes'];

	    if(!$prefix) {
			$table = substr($table, strlen( app('db')->connection()->getTablePrefix() ));
		}

		return $table;
	}

}

app()->singleton('mw.sqlparser', function() {
  return new SqlParser;
});