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

// Public Properties {{{
	/**
	 * @var string $query
	 * The query that this statement represents.
	 */
	public $query;
// }}}

// Protected Properties {{{
	/**
	 * @var AbstractAdapter $_adapter
	 * The database connector adapter.
	 */
	protected $_adapter;
	/**
	 * @var array $_params
	 * The parameters bound to the query.
	 */
	protected $_params;
// }}}

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
		$this->_adapter = $adapter;
		$this->query = $query;
		$this->_params = is_array($params) ? $params : [$params];
	}

// Public Methods {{{
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
		$values = $this->fetch();
		if (!$values)
			return false;
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
// }}}

// Protected Methods {{{
	/**
	 * @internal
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
// }}}

// Abstract Methods {{{
	/**
	 * @brief Prepares the statement for execution and binds the parameters.
	 */
	abstract public function prepare();

	/**
	 * @brief Fetches the values from the executed query.
	 *
	 * @retval array	An array containing the values fetched.
	 */
	abstract public function fetch();

	/**
	 * @brief Executes the statement.
	 *
	 * @retval bool		TRUE if execution succeded; FALSE otherwise.
	 */
	abstract public function execute();

	/**
	 * @brief Returns the number of rows affected by the query.
	 *
	 * @retval int		The number of rows affected.
	 */
	abstract public function rowsAffected();
// }}}

}
