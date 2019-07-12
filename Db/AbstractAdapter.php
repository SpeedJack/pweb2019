<?php

namespace Pweb\Db;

/**
 * @brief Represents a database connector adapter.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractAdapter extends \Pweb\AbstractSingleton
{

// Protected Properties {{{
	/**
	 * @internal
	 * @var object $connection
	 * The database connection.
	 */
	protected $connection;
	/**
	 * @internal
	 * @var string $statementClass
	 * The name of the class representing the statement.
	 */
	protected $statementClass;
	/**
	 * @internal
	 * @var array $config
	 * The database configuration.
	 */
	protected $config;
// }}}

	/**
	 * @brief Creates a new database adapter.
	 *
	 * This class must be instantiated using getInstance().
	 *
	 * @param[in] array $config	Database configuration.
	 * @return			The database adapter.
	 */
	protected function __construct(array $config)
	{
		$this->statementClass = $this->getStatementClass();

		$this->config = $config;
		$this->connect();
	}

// Public Methods {{{
	/**
	 * @brief Check if connected to the database.
	 *
	 * @retval bool		TRUE, if connected; FALSE, otherwise.
	 */
	public function isConnected()
	{
		return $this->connection !== null;
	}

	/**
	 * @brief Returns the detabase connection.
	 *
	 * @retval object	The database connection.
	 */
	public function getConnection()
	{
		if (!$this->connection)
			$this->connect();
		return $this->connection;
	}

	/**
	 * @brief Queries the database and returns the number of affected rows.
	 *
	 * @param[in] string $query	The query.
	 * @param[in] mixed $params	The parameters to bind to the query.
	 * @retval int			The number of affected rows.
	 */
	public function query($query, ...$params)
	{
		return $this->_query($query, $params)->rowsAffected();
	}

	/**
	 * @brief Queries the database and returns the first row of the result.
	 *
	 * @param[in] string $query	The query.
	 * @param[in] mixed $params	The parameters to bind to the query.
	 * @retval array		An array containing all fields returned
	 * 				by the query.
	 */
	public function fetchRow($query, ...$params)
	{
		return $this->_query($query, $params)->fetch();
	}

	/**
	 * @brief Queries the database and returns the value of the specified
	 * column in the first row of the result.
	 *
	 * @param[in] string $query	The query.
	 * @param[in] mixed $params	The parameters to bind to the query.
	 * @param[in] int|string	$column	The column index or name.
	 * @retval mixed		The value of the requested column.
	 */
	public function fetchColumn($query, $params = [], $column = 0)
	{
		return $this->_query($query, $params)->fetchColumn($column);
	}

	/**
	 * @brief Queries the database and returns all rows of the result.
	 *
	 * @param[in] string $query	The query.
	 * @param[in] mixed $params	The parameters to bind to the query.
	 * @retval array		An array containing all fields returned
	 * 				by the query.
	 */
	public function fetchAll($query, ...$params)
	{
		return $this->_query($query, $params)->fetchAll();
	}

	/**
	 * @brief Queries the database and returns an array containing the value
	 * of the specified column for each row of the result.
	 *
	 * @param[in] string $query		The query.
	 * @param[in] mixed $params		The parameters to bind to the
	 * 					query.
	 * @param[in] int|string $column	The column index or name.
	 * @retval array			An array containing the value of
	 * 					the specified column for each
	 * 					row of the result.
	 */
	public function fetchAllColumn($query, $params = [], $column = 0)
	{
		return $this->_query($query, $params)->fetchAllColumn($column);
	}

	/**
	 * @brief Queries the database and returns all rows of the result using
	 * the specified column's values as keys for the array.
	 *
	 * @param[in] string $query	The query.
	 * @param[in] int|string $key	The column whose values should be used
	 * 				as keys for the returned array.
	 * @param[in] mixed $params	The parameters to bind to the query.
	 * @retval array		An array containing all fields returned
	 * 				by the query, indexed with the values of
	 * 				the column specified.
	 * @todo currently not used.
	 */
	public function fetchAllKeyed($query, $key, ...$params)
	{
		return $this->_query($query, $params)->fetchAllKeyed($key);
	}
// }}}

// Private Methods {{{
	/**
	 * @internal
	 * @brief Sends a query to the database.
	 *
	 * @param[in] string $query	The query to send.
	 * @param[in] array $params	The parameters to bind to the query.
	 * @retval AbstractStatement	The statement executed.
	 */
	private function _query($query, $params = [])
	{
		$this->connect();

		$class = $this->statementClass;

		$statement = new $class($this, $query, $params);
		$statement->execute();

		return $statement;
	}
// }}}

// Abstract Methods {{{
	/** @brief Connect to the database. */
	abstract protected function connect();

	/** @brief Close the connection to the database. */
	abstract public function closeConnection();
// }}}

}
