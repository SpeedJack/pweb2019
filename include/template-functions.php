<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

/**
 * @brief Generates the 'class' attribute of an HTML element.
 *
 * @param[in] array $classes	Array of class names.
 * @retval string		The generate string.
 */
function getClassesString(array $classes)
{
	$classStr = '';
	if (!empty($classes)) {
		$classStr = 'class="';
		foreach ($classes as $class)
			$classStr .= "$class ";
		$classStr = rtrim($classStr);
		$classStr .= '"';
	}
	return $classStr;
}
