<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

namespace Pweb;

$APP_ROOT = __DIR__;
set_include_path(get_include_path() . PATH_SEPARATOR .
	"$APP_ROOT/include");

require_once 'config.php';

if (isset($config['debug']) && $config['debug'] === true) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	if (isset($config['show_all_exceptions']) && $config['show_all_exceptions'] === true) {
		ini_set('xdebug.show_error_trace', 1);
		ini_set('xdebug.show_exception_trace', 1);
	}
	error_reporting(E_ALL);
}
if (isset($config['error_log'])) {
	ini_set('error_log', $config['error_log']);
	ini_set('log_errors', 1);
}

require_once 'i18n.php';
require_once 'autoload.php';

$app = App::getInstance($config);
$app->init();
try {
	$app->route();
} catch (InvalidRouteException $e) {
	$app-rerouteHome();
}
