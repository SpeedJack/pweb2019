<?php
namespace Pweb\Db;

/**
 * @brief Represents a PDO statement.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class PdoStatement extends AbstractStatement
{

// Protected Properties {{{
	/**
	 * @internal
	 * @var PDOStatement $_statement
	 * The PDO statement, prepared for execution.
	 */
	protected $_statement;
// }}}

// Public Methods {{{
	/**
	 * @brief Returns the number of rows affected by this statement.
	 *
	 * @retval int		The number of rows affected.
	 */
	public function rowsAffected()
	{
		return $this->_statement ? $this->_statement->rowCount() : null;
	}

	/**
	 * @brief Prepares the statement for execution.
	 *
	 * @throws Exception	If the prepare was unsuccessful.
	 */
	public function prepare()
	{
		if ($this->_statement)
			return;

		$connection = $this->_adapter->getConnection();
		try {
			$this->_statement = $connection->prepare($this->query);
		} catch (\PDOException $e) {
			throw $this->_getException($e->errorInfo[2],
				$e->errorInfo[1], $e->errorInfo[0]);
		}
	}

	/**
	 * @brief Executes the statement.
	 *
	 * @throws Exception	If an exception occured during the execution.
	 *
	 * @retval bool		Returns TRUE on success; FALSE on failure.
	 */
	public function execute()
	{
		if (!$this->_statement)
			$this->prepare();

		$index = 1;
		foreach ($this->_params as &$param) {
			switch (gettype($param)) {
			case 'boolean':
				$type = \PDO::PARAM_BOOL;
				break;
			case 'integer':
				$type = \PDO::PARAM_INT;
				break;
			case 'double':
			case 'string':
				$type = \PDO::PARAM_STR;
				break;
			case 'array':
			case 'object':
			case 'resource':
			case 'resource (closed)':
			case 'unknown type':
				$type = \PDO::PARAM_LOB;
				break;
			case 'NULL':
			default:
				$type = \PDO::PARAM_NULL;
			}
			try {
				$this->_statement->bindParam($index, $param, $type);
			} catch (\PDOException $e) {
				throw $this->_getException($e->errorInfo[2],
					$e->errorInfo[1], $e->errorInfo[0]);
			}
			$index++;
		}

		try {
			$this->_statement->execute();
		} catch (\PDOException $e) {
			throw $this->_getException($e->errorInfo[2],
				$e->errorInfo[1], $e->errorInfo[0]);
		}
		return true;
	}

	/**
	 * @brief Fetches the values from the executed query.
	 *
	 * @throws Exception		If the fetch failed.
	 * @throws LogicException	If the statement is not prepared.
	 *
	 * @retval array	The associative array containing the values with
	 * 			the column names as keys.
	 */
	public function fetch()
	{
		if (!$this->_statement)
			throw new LogicException('Trying to fetch values from an unprepared statement.');

		try {
			$values = $this->_statement->fetch(\PDO::FETCH_BOTH);
		} catch (\PDOException $e) {
			throw $this->_getException($e->errorInfo[2],
				$e->errorInfo[1], $e->errorInfo[0]);
		}
		if ($values === false || $values === null)
			return [];
		foreach ($values as $key => $value)
			if (is_numeric($value))
				$values[$key] = ctype_digit($value) ? (int)$value : (float)$value;
			else if ($value === 'NULL')
				$values[$key] = null;

		return $values;
	}
// }}}

}
