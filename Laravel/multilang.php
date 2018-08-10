<?php namespace Multilanguage;

use Closure;
use DB;
use Exception;
use PDO;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Database\DatabaseManager as BaseManager;
use Illuminate\Database\Connectors\ConnectionFactory as BaseConnectionFactory;
use Illuminate\Database\MySqlConnection as BaseMySqlConnection;
use Illuminate\Database\SqlServerConnection as BaseSqlServerConnection;
use Illuminate\Database\PostgresConnection as BasePostgresConnection;
use Illuminate\Database\SQLiteConnection as BaseSQLiteConnection;

class MySqlConnection extends BaseMySqlConnection {
	use MWQueryProcessing;
}
class SqlServerConnection extends BaseSqlServerConnection {
	use MWQueryProcessing;
}
class PostgresConnection extends BasePostgresConnection {
	use MWQueryProcessing;
}
class SQLiteConnection extends BaseSQLiteConnection {
	use MWQueryProcessing;
}
class DatabaseManager extends BaseManager {
}

trait MWQueryProcessing
{
	/*protected function run($query, $bindings, Closure $callback)
	{
		$this->reconnectIfMissingConnection();

		$start = microtime(true);
		if(starts_with($query, 'update')) var_dump($query);
		try
		{
			$result = $this->runQueryCallback($query, $bindings, $callback);
		}
		catch (QueryException $e)
		{
			$result = $this->tryAgainIfCausedByLostConnection(
				$e, $query, $bindings, $callback
			);
		}

		$time = $this->getElapsedTime($start);

		$this->logQuery($query, $bindings, $time);

		return $result;
	}*/

	public function select($query, $bindings = array(), $useReadPdo = true)
	{
		$result = null;
		
		try {
			event_trigger('mw.database.before_select', ['query' => $query, 'bindings' => $bindings, 'result' => &$result]);
			$result = parent::select($query, $bindings, $useReadPdo);
		}
		catch(Exception $e) { }

		event_trigger('mw.database.select', ['query' => $query, 'bindings' => $bindings, 'result' => &$result]);
		return $result;
	}

	public function update($query, $bindings = array())
	{
		$result = null;
		
		try {
			event_trigger('mw.database.before_update', ['query' => $query, 'bindings' => $bindings]);
			$result = parent::update($query, $bindings);
		}
		catch(Exception $e) { }
		
		event_trigger('mw.database.update', ['query' => $query, 'bindings' => $bindings]);
		return $result;
	}

	public function insert($query, $bindings = array())
	{
		$result = null;
		
		try {
			event_trigger('mw.database.before_insert', ['query' => $query, 'bindings' => $bindings]);
			$result = parent::insert($query, $bindings);
		}
		catch(Exception $e) { }
		
		event_trigger('mw.database.insert', ['query' => $query, 'bindings' => $bindings]);
		return $result;
	}
}

class ConnectionFactory extends BaseConnectionFactory
{
	protected function createConnection($driver, $connection, $database, $prefix = '', array $config = array())
	{
		if ($this->container->bound($key = "db.connection.{$driver}"))
		{
			return $this->container->make($key, array($connection, $database, $prefix, $config));
		}

		switch ($driver)
		{
			case 'mysql':
				return new MySqlConnection($connection, $database, $prefix, $config);

			case 'pgsql':
				return new PostgresConnection($connection, $database, $prefix, $config);

			case 'sqlite':
				return new SQLiteConnection($connection, $database, $prefix, $config);

			case 'sqlsrv':
				return new SqlServerConnection($connection, $database, $prefix, $config);
		}

		throw new InvalidArgumentException("Unsupported driver [$driver]");
	}
}

app()->singleton('db.factory', function($app, $deps) {
	return new ConnectionFactory($app);
});

app()->singleton('db', function($app, $deps) {
	return new DatabaseManager($app, new ConnectionFactory($app));
});

// This line took a long, long time...
DB::clearResolvedInstances();