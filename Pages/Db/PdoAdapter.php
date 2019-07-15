<?php
namespace Pweb\Db;

/**
 * @brief Represents a PDO connector adapter.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class PdoAdapter extends AbstractAdapter
{

// Public Methods {{{
	/** @brief Closes the connection to the database. */
	public function closeConnection()
	{
	}
// }}}

// Protected Methods {{{
	/**
	 * @brief Returns the fully qualified name of the associated statement
	 * class.
	 *
	 * @retval string	The fully qualified name of the statement class.
	 */
	protected function getStatementClass()
	{
		return __NAMESPACE__ . '\PdoStatement';
	}

	/**
	 * @brief Connects to the database.
	 *
	 * @throws PDOException		If the connection was unsuccessful.
	 */
	protected function connect()
	{
		if ($this->isConnected())
			return;

		$this->connection = new \PDO('mysql:host=' . $this->config['host'] .
			';port=' . $this->config['port'] . ';dbname=' .
			$this->config['dbname'] . ';charset=' .
			$this->config['charset'], $this->config['username'],
			$this->config['password'],
			[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
	}
// }}}

}
