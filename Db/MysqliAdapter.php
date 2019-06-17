<?php

namespace Pweb\Db;

/**
 * @brief Represents a mysqli connector adapter.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class MysqliAdapter extends AbstractAdapter
{

// Public Methods {{{
	/** @brief Closes the connection to the database. */
	public function closeConnection()
	{
		if ($this->isConnected())
			$this->connection->close();
		$this->connecttion = null;
	}
// }}}

// Protected Methods {{{
	/**
	 * @internal
	 * @brief Returns the fully qualified name of the associated statement
	 * class.
	 *
	 * @retval string	The fully qualified name of the statement class.
	 */
	protected function getStatementClass()
	{
		return __NAMESPACE__ . '\MysqliStatement';
	}

	/**
	 * @brief Connects to the database.
	 *
	 * @throws RuntimeException	If the connection was unsuccessful.
	 */
	protected function connect()
	{
		if ($this->isConnected())
			return;

		$this->connection = new \mysqli($this->config['host'],
			$this->config['username'], $this->config['password'],
			$this->config['dbname'], $this->config['port']);

		if ($this->connection->connect_errno)
			throw new \RuntimeException(
				$this->connection->connect_error,
				$this->connection->connect_errno);

		$this->connection->set_charset($this->config['charset']);
	}
// }}}

}
