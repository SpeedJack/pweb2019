<?php
namespace Pweb\Db;

/**
 * @brief Represent a query statement for the database.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractStatement
{
	/**
	 * @var AbstractAdapter $adapter
	 * The database connector adapter.
	 */
	public $adapter;
	/**
	 * @var string $query
	 * The query that this statement represents.
	 */
	public $query;
	/**
	 * @var array $params
	 * The parameters bound to the query.
	 */
	public $params;
	/**
	 * @var array $keys
	 * Array of strings containing the column names of the query's result.
	 */
	public $keys = [];
	/**
	 * @var array $values
	 * The values returned by the query.
	 */
	public $values = [];

	/**
	 * @brief Creates a new statement.
	 *
	 * @param[in] AbstractAdapter $adapter	The database connector.
	 * @param[in] string $query		The query.
	 * @param[in] array $params		The parameters to bind to the
	 * 					query.
	 * @return				The created statement.
	 */
	public function __construct(AbstractAdapter $adapter, $query, $params = [])
	{
		$this->adapter = $adapter;
		$this->query = $query;
		$this->params = is_array($params) ? $params : [$params];
	}

	/**
	 * @brief Prepares the statement for execution and binds the parameters.
	 */
	abstract public function prepare();

	/**
	 * @brief Executes the statement.
	 *
	 * @retval bool		TRUE if execution succeded; FALSE otherwise.
	 */
	abstract public function execute();

	/**
	 * @brief Fetches the values from the executed query.
	 *
	 * @retval array	An array containing the values fetched.
	 */
	abstract public function fetchValues();

	/**
	 * @brief Returns the number of rows affected by the query.
	 *
	 * @retval int		The number of rows affected.
	 */
	abstract public function rowsAffected();

	/**
	 * @brief Fetches one row from the executed query and returns the
	 * result.
	 *
	 * @retval array	The associative array containing the values with
	 * 			the column names as keys.
	 */
	public function fetch()
	{
		$values = $this->fetchValues();
		if (empty($values))
			return array();
		return array_combine($this->keys, $values);
	}

	/**
	 * @brief Fetches the column specified from the result set.
	 *
	 * @param[in] int|string $column	The column index or name to
	 * 					fetch.
	 * @retval mixed|false			The value of the column
	 * 					specified. FALSE of no result.
	 */
	public function fetchColumn($column = 0)
	{
		$values = $this->fetchValues();
		if (!$values)
			return false;
		if (is_int($column))
			return isset($values[$column]) ? $values[$column] : null;
		$values = array_combine($this->keys, $values);
		return isset($values[$column]) ? $values[$column] : null;
	}

	/**
	 * @brief Fetches all rows from the executed query and returns the
	 * result.
	 *
	 * @retval array	The associative array containing the values with
	 * 			the column names as keys.
	 */
	public function fetchAll()
	{
		$output = [];
		while ($v = $this->fetch())
			$output[] = $v;
		return $output;
	}

	/**
	 * @brief Fetches a entire column from the result set.
	 *
	 * @param[in] int|string $column	The column index or name to
	 * 					fetch.
	 * @retval array|false			An array containing the values
	 * 					of the specified column for each
	 * 					row. FALSE if no row is
	 * 					returned.
	 */
	public function fetchAllColumn($column = 0)
	{
		$output = [];
		while (($v = $this->fetchColumn($column)) !== false)
			$output[] = $v;
		return $output;
	}

	/**
	 * @brief Returns the result of a query, using a column to index the
	 * resulting array.
	 *
	 * @param[in] int|string $key	The column index or name to use as key
	 * 				for the resulting array.
	 * @retval array|false		An associative array containing the
	 * 				result of the query with the values of
	 * 				the specified column as keys. FALSE if
	 * 				no row is returned.
	 */
	public function fetchAllKeyed($key)
	{
		$output = [];
		$i = 0;
		while ($v = $this->fetch()) {
			if (!isset($v[$key]))
				return false;
			$output[$v[$key]] = $v;
		}
		return $output;
	}

	/**
	 * @brief Returns the proper exception to trigger when an error occurs.
	 *
	 * @param[in] string $message		The exception message.
	 * @param[in] int $code			The exception code.
	 * @param[in] string|null $sqlStateCode	The SQL State Code.
	 * @retval Exception			The exception.
	 */
	protected function _getException($message, $code = 0, $sqlStateCode = null)
	{
		if (!$sqlStateCode || $sqlStateCode === '00000')
			switch ($code) {
			case 1062: $sqlStateCode = '23000'; break; // duplicate key
			}

		switch($sqlStateCode) {
		case '23000': $exClass = 'DuplicateKeyException'; break; // duplicate key
		default: $exClass = 'Exception'; break;
		}

		return new Exception($message, $code, $sqlStateCode, $this);

	}
}
