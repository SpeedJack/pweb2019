<?php
namespace Pweb\Db;

/**
 * @brief Represents a database exception.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class Exception extends \Exception
{

// Public Properties {{{
	/**
	 * @var string $sqlStateCode
	 * The SQL State Code.
	 */
	public $sqlStateCode;
	/**
	 * @var AbstractStatement $statement
	 * The statement that triggered this exception.
	 */
	public $statement;
// }}}

	/**
	 * @brief Creates a new database exception.
	 *
	 * @param[in] string $message			The exception message.
	 * @param[in] int $code				Ignored.
	 * @param[in] string $sqlStateCode		The SQL State Code.
	 * @param[in] AbstractStatement $statement	The statement that
	 * 						triggered the exception.
	 * @param[in] Exception|null $previous		Previous exception if
	 * 						nested exceptions.
	 */
	public function __construct($message, $code, $sqlStateCode, $statement,
		Exception $previous = null)
	{
		$this->sqlStateCode = $sqlStateCode;
		$this->statement = $statement;
		$message .= __("\nSQL State Code: %s\nQuery: %s",
			$sqlStateCode, $statement->query);
		parent::__construct($message, 500, $previous);
	}
}
