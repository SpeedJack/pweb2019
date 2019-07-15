<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

if (extension_loaded('gettext')) {
	bindtextdomain('Pweb', $GLOBALS['APP_ROOT'] . '/Locale');
	bind_textdomain_codeset('Pweb', 'UTF-8');
	textdomain('Pweb');
} else {
	/**
	 * @brief Returns the message.
	 *
	 * Defined only if the gettext extension is not loaded.
	 *
	 * @param[in] string $message	The message.
	 * @retval string		The message $message.
	 */
	function gettext($message)
	{
		return $message;
	}
}

/**
 * @brief Returns the localized message, with optional parameters.
 *
 * @param[in] string $message	The message.
 * @param[in] mixed $params	The parameters to replace in the message. Use
 * 				'%s' in the message string to indicate where the
 * 				parameters should be placed.
 * @retval string		The localized message.
 */
function __($message, ...$params)
{
	return stripslashes(sprintf(gettext($message), ...$params));
}
