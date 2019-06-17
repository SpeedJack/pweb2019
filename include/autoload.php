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
	$index = strrpos($classFullName, '\\') ?: -1;
	$className = substr($classFullName, $index + 1);
	$classRegex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
	if (!preg_match($classRegex, $className))
		panic(400);

	$classFile = $baseDir . str_replace('\\', '/', $classFullName) . '.php';
	if (!is_file($classFile))
		panic(404);

	require $classFile;

	if (!(class_exists($class, false) ||
		interface_exists($class, false) ||
		trait_exists($class, false)))
		panic(501);
});
