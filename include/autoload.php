<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

require_once "error-functions.php";

spl_autoload_register(function ($class)
{
	$prefix = 'Pweb\\';
	$baseDir = $GLOBALS['APP_ROOT'] . '/';

	$prefixLen = strlen($prefix);
	if (strncmp($prefix, $class, $prefixLen) !== 0)
		return;

	$classFullName = substr($class, $prefixLen);

	$classFile = $baseDir . str_replace('\\', '/', $classFullName) . '.php';
	if (!is_file($classFile))
		panic(404);

	require $classFile;

	if (!(class_exists($class, false) ||
		interface_exists($class, false) ||
		trait_exists($class, false)))
		panic(501);
});
