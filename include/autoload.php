<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

require_once "error-functions.php";
require_once "string-functions.php";

spl_autoload_register(function ($class)
{
	$prefix = 'Pweb\\';
	$baseDir = $GLOBALS['APP_ROOT'] . '/';

	if (!starts_with($class, $prefix))
		return;

	$classFile = $baseDir . str_replace('\\', '/', trim_prefix($class, $prefix)) . '.php';
	if (!is_file($classFile))
		panic(404);

	require $classFile;

	if (!(class_exists($class, false) ||
		interface_exists($class, false) ||
		trait_exists($class, false)))
		panic(501);
});
