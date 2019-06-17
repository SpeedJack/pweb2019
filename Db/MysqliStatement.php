<?php
namespace Pweb\Db;

/**
 * @brief Represents a mysqli statement.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class MysqliStatement extends AbstractStatement
{
	/**
	 * @var array $metaFields
	 * Array of objects representing the fields in a result set.
	 */
	public $metaFields;
	/**
	 * @var mysqli_stmt $statement
	 * The mysqli statement, prepared for execution.
	 */
	public $statement;

	/**
	 * @brief Returns the number of rows affected by this statement.
	 *
	 * @retval int		The number of rows affected.
	 */
	public function rowsAffected()
	{
		return $this->statement ? $this->statement->affected_rows : null;
	}

	/**
	 * @brief Prepares the statement for execution.
	 *
	 * @throws Exception	If the prepare was unsuccessful.
	 */
	public function prepare()
	{
		if ($this->statement)
			return;

		$connection = $this->adapter->getConnection();
		$this->statement = $connection->prepare($this->query);
		if (!$this->statement)
			throw $this->_getException($connection->error,
				$connection->errno, $connection->sqlstate);
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
		if (!$this->statement)
			$this->prepare();

		$types = '';
		$bind = [];
		foreach ($this->params as &$param) {
			switch (gettype($param)) {
			case 'boolean':
			case 'integer':
			case 'NULL':
				$types .= 'i';
				break;
			case 'double':
				$types .= 'd';
				break;
			case 'array':
			case 'object':
			case 'resource':
			case 'resource (closed)':
			case 'unknown type':
				$types .= 'b';
				break;
			case 'string':
			default:
				$types .= 's';
			}
			$bind[] =& $param;
		}
		if (!empty($types)) {
			array_unshift($bind, $types);
			if (!call_user_func_array([$this->statement, 'bind_param'], $bind))
				throw $this->_getException(
					$this->statement->error,
					$this->statement->errno,
					$this->statement->sqlstate
				);
		}

		$success = $this->statement->execute();
		if (!$success)
			throw $this->_getException($this->statement->error,
				$this->statement->errno,
				$this->statement->sqlstate);

		$meta = $this->statement->result_metadata();
		if (!$meta)
			return $success;

		$this->metaFields = $meta->fetch_fields();
		if (!$this->statement->store_result())
			throw $this->_getException($this->statement->error,
				$this->statement->errno,
				$this->statement->sqlstate);

		$keys = [];
		$values = [];
		$refs = [];
		$i = 0;
		foreach ($this->metaFields as $field)
		{
			$keys[] =$field->name;
			$refs[] = null;
			$values[] =& $refs[$i];
			$i++;
		}

		$this->keys = $keys;
		$this->values = $values;

		if (!call_user_func_array([$this->statement, 'bind_result'],
			$this->values))
			throw $this->_getException($this->statement->error,
				$this->statement->errno,
				$this->statement->sqlstate);

		return $success;
	}

	/**
	 * @brief Returns the values of the result set returned by the execution
	 * of the statement.
	 *
	 * @throws Exception	If the fetch failed.
	 *
	 * @retval array|false	An array containing the values of the result
	 * 			set; FALSE if the statement is not prepared.
	 */
	public function fetchValues()
	{
		if (!$this->statement)
			return false;

		$success = $this->statement->fetch();
		if ($success === false)
			throw $this->_getException($this->statement->error,
				$this->statement->errno,
				$this->statement->sqlstate);
		if ($success === null)
			return array();

		$values = [];
		foreach ($this->values as $v)
			$values[] = $v;
		return $values;
	}
}
