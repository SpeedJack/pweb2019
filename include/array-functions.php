<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

if (!function_exists("array_key_last")) {
	/**
	 * @brief Returns the last key of the array.
	 *
	 * @param[in] array $array	The array.
	 * @retval mixed		The last key of the array.
	 */
	function array_key_last(array $array)
	{
		if (empty($array))
			return null;
		return array_keys($array)[count($array)-1];
	}
}
