<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

/**
 * @brief Prints an error message and sets the HTTP response code.
 *
 * @param[in] int $code			The HTTP response code.
 * @param[in] string $errorMsg		The error message.
 * @param[in] Throwable $exception	The exception that caused the error.
 */
function panic($code = 500, $errorMsg = '', Throwable $exception = null)
{
	include 'config.php';
	if (!$exception)
		$exception = new Exception();

	$fullErrorMsg = __("Panic!!!\nError Code: %d.\nError Message: %s\nBacktrace:\n%s",
		$code, $errorMsg, $exception->getTraceAsString());
	error_log($fullErrorMsg);

	if (isset($config['debug']) && $config['debug'] === true)
		print "<pre>$fullErrorMsg</pre>";

	http_response_code($code);

	$errorFile = $GLOBALS['APP_ROOT'] . "/error-pages/$code";
	if (file_exists("$errorFile.php"))
		include "$errorFile.php";
	else if (file_exists("$errorFile.html"))
		include "$errorFile.html";

	if (isset($config['debug']) && $config['debug'] === true) {
		/* rethrow the exception to allow xdebug to catch it. We do not
		want our custom exception handler to catch this exception. */
		restore_exception_handler();
		throw $exception;
	}

	die();
}
