<?php
namespace Pweb;

require_once 'string-functions.php';
require_once 'error-functions.php';

/**
 * @brief The application class.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class App extends AbstractSingleton
{

// Public Properties {{{
	/**
	 * @var string APP_ROOT
	 * The application root directory.
	 */
	const APP_ROOT = __DIR__;
	/**
	 * @var string $serverName
	 * The name of the server host under which this application is running.
	 */
	public $serverName;
	/**
	 * @var int $serverPort
	 * The port on the server machine being used by the web server for
	 * communication.
	 */
	public $serverPort;
	/**
	 * @var bool $isHttps
	 * TRUE if using HTTPS, FALSE otherwise.
	 */
	public $isHttps;
	/**
	 * @var Pages::AbstractPage $pageClass
	 * The class of the loaded page.
	 */
	public $pageClass;
	/**
	 * @var string $actionName
	 * The name of the action being executed.
	 */
	public $actionName;
	/**
	 * @var array $config
	 * The application configuration.
	 */
	public $config = [];
// }}}

// Protected Properties {{{
	/**
	 * @internal
	 * @var	Entity::Visitor $_visitor
	 * The visitor instance.
	 */
	protected $_visitor;
	/**
	 * @internal
	 * @var Db::AbstractAdapter $_db
	 * The database adapter.
	 */
	protected $_db;
	/**
	 * @internal
	 * @var Entity::EntityManager $_em
	 * The EntityManager instance.
	 */
	protected $_em;
// }}}

	/**
	 * @brief Creates the application.
	 *
	 * This class must be instantiated using getInstance().
	 *
	 * @param[in] array $config	The user provided configuration.
	 * @return			The App instance.
	 */
	protected function __construct(array $config = [])
	{
		$this->_setConfig($config);
		$this->_setServerInfos();
		$this->_setLanguage();

		$this->_em = Entity\EntityManager::getInstance();

		@set_exception_handler(array($this, 'exception_handler'));
		@set_error_handler(array($this, 'error_handler'));
	}

// Public Methods {{{

	/** @brief Initializes the visitor instance. */
	public function init()
	{
		$this->_visitor = $this->_em->create('Visitor');
	}

	/**
	 * @brief Returns the database instance.
	 *
	 * This function creates a new database instance if it is not already
	 * created.
	 *
	 * @retval Db::AbstractAdapter	The database instance.
	 */
	public function getDb()
	{
		if (isset($this->_db))
			return $this->_db;

		if (!$this->config['db']['prefer_mysqli_over_pdo'] &&
			extension_loaded('pdo') && extension_loaded('pdo_mysql'))
			$this->_db = Db\PdoAdapter::getInstance($this->config['db']);
		else if (extension_loaded('mysqli'))
			$this->_db = Db\MysqliAdapter::getInstance($this->config['db']);
		else
			throw new \Exception(
				__("No database extension loaded: PDO or MySQLi is required."),
				500
			);

		return $this->_db;
	}

// Routing {{{
	/**
	 * @brief Route the user to the selected page and action.
	 *
	 * This method does not return.
	 *
	 * @throws InvalidRouteException	If the selected page or action
	 * 					can not be found.
	 *
	 * @param[in] array $getParams	Associative array of key-value pairs of
	 * 				GET parameters.
	 * @param[in] array $postParams	Associative array of key-value pairs of
	 * 				POST parameters.
	 * @param[in] bool $resetParams	If TRUE, previous GET and POST
	 * 				parameters are reset.
	 */
	public function route(array $getParams = [], array $postParams = [],
		$resetParams = false)
	{
		if ($resetParams)
			$this->_visitor->clearParams();
		$this->_visitor->setGetParams($getParams);
		$this->_visitor->setPostParams($postParams);

		$page = str_replace('_', '\\', $this->_visitor->page);
		$action = $this->_visitor->action;
		$page = "Pweb\\Pages\\$page";

		$this->actionName = $action;

		$pageClass = new $page();
		$this->pageClass = $pageClass;

		if (!method_exists($pageClass, $action))
			throw new InvalidRouteException($page, $action);
		$pageClass->$action();

		die();
	}

	/**
	 * @brief Reroute the user to a new page and action.
	 *
	 * This method does not return.
	 * 
	 * @throws InvalidRouteException	If the selected page or action
	 * 					can not be found.
	 *
	 * @param[in] string $page		The page where the user should
	 * 					be routed.
	 * @param[in] string|null $action	The action where the user should
	 * 					be routed. If null, it defaults
	 * 					to actionIndex.
	 * @param[in] array $getParams		Associative array of key-value
	 * 					pairs of GET parameters.
	 * @param[in] array $postParams		Associative array of key-value
	 * 					pairs of POST parameters.
	 * @param[in] bool $resetParams		If TRUE, previous GET and POST
	 * 					parameters are reset.
	 */
	public function reroute($page, $action = null,
		array $getParams = [], $postParams = [], $resetParams = true)
	{
		$this->_visitor->setRoute($page, $action);
		$this->route($getParams, $postParams, $resetParams);
	}

	/* NOTE: Some bug reports says that Chromium/IE/Edge may not properly
	 * set cookies if they are set with a 301/302 HTTP redirect. Need some
	 * testing, especially on older version, to check that cookies are
	 * properly set even on redirect. This is browser's bug: HTTP specs
	 * allows cookies on redirect. Some browser may won't fix this bug.
	 * Update: Firefox >=67.0 ok! properly sets cookies on redirect.
	 * Update: according to [1], all major browsers accepts cookies on
	 * redirects: IE >= 6; FF >= 17; Safari >= 6.0.2; Opera >= 12.11. Still
	 * need testing on Chromium.
	 * NOTE: according to [1], 301 redirects are aggressively cached. If
	 * that's a problem, pages that trigger 301 redirects should always
	 * has a new url (eg. by setting a dummy GET parameter to a random
	 * value). (TODO)
	 * [1] https://blog.dubbelboer.com/2012/11/25/302-cookie.html
	 */
	/**
	 * @brief Redirect the user to an external resource.
	 *
	 * @param[in] string $link	The link to the external resource.
	 * @param[in] bool $permanent	If TRUE, does a permanent (301)
	 * 				redirect. If FALSE, does a temporary
	 * 				(302) redirect.
	 */
	public function externalRedirect($link, $permanent = false)
	{
		header("Location: $link", true, $permanent ? 301 : 302);
		die();
	}

	/**
	 * @brief Redirect the user to the specified page and action.
	 *
	 * @param[in] string $page		The page where the user should
	 * 					be redirected. If NULL,
	 * 					redirects to index.php.
	 * @param[in] string|null $action	The action where the user should
	 * 					be redirected. If NULL, it
	 * 					defaults to actionIndex.
	 * @param[in] array $params		Associative array of key-value
	 * 					pairs of GET parameters.
	 * @param[in] bool $permanent		If TRUE, does a permanent (301)
	 * 					redirect. If FALSE, does a
	 * 					temporary (302) redirect.
	 */
	public function redirect($page, $action = null, array $params = [],
		$permanent = false)
	{
		$link = $this->buildAbsoluteLink($page, $action, $params);
		$this->externalRedirect($link, $permanent);
	}

	/**
	 * @brief Redirect the user to the home page.
	 *
	 * @param[in] array $params	Associative array of key-value pairs of
	 * 				GET parameters.
	 * @param[in] bool $permanent	If TRUE, does a permanent (301)
	 * 				redirect. If FALSE, does a temporary
	 * 				(302) redirect.
	 */
	public function redirectHome(array $params = [], $permanent = false)
	{
		$this->redirect(null, null, $params, $permanent);
	}

	/**
	 * @brief Reroute the user to the home page.
	 */
	public function rerouteHome()
	{
		$this->reroute(null);
	}
// }}}

// Link Building {{{
	/**
	 * @brief Generates a relative link to a page.
	 *
	 * @param[in] string|null $page		The page name. If NULL, returns
	 * 					a link to index.php. The special
	 * 					value '__current' can be used to
	 * 					refer to the current page.
	 * @param[in] string|null $action	The action name. If NULL,
	 * 					defaults to actionIndex. The
	 * 					special value '__current' can be
	 * 					used to refer to the current
	 * 					action.
	 * @param[in] array $params		Associative array of key-value
	 * 					pairs of GET parameters.
	 * @retval string			The generated link.
	 */
	public function buildLink($page = null, $action = null, array $params = [])
	{
		if (empty($page))
			return 'index.php';
		$rawAction = '';
		$page = $page === '__current' ?
			trimSuffix($this->_visitor->page, 'Page') : $page;
		$rawPage = "?page=$page";
		if ($this->config['use_url_rewrite'])
			$rawPage = "/$page";
		if (!empty($action)) {
			$action = $action === '__current' ?
				trimPrefix($this->_visitor->action, 'action') : $action;
			if ($this->config['use_url_rewrite'])
				$rawAction = "/$action";
			else
				$rawAction = "&action=$action";
		}
		$rawParams = $this->_getRawParams($params, true);
		return "index.php$rawPage$rawAction$rawParams";
	}

	/**
	 * @brief Generates an absolute link to an external resource.
	 *
	 * @param[in] string $link	Base URL.
	 * @param[in] bool $https	Set to TRUE to generate an https link,
	 * 				FALSE for a http link.
	 * @param[in] array $params	Associative array of key-value pairs of
	 * 				GET parameters.
	 * @param[in] int $port		The port to append to the domain name.
	 * @retval string		The generated link.
	 */
	public function buildExternalLink($link, $https = true, array $params = [],
		$port = 80)
	{
		$link = trim($link);
		$link = trimPrefix($link, 'https://');
		$link = trimPrefix($link, 'http://');

		$portStr = ":$port";
		if ($port === 80)
			$portStr = '';
		$portPos = strpos($link, '/');
		if ($portPos === false) {
			$portPos = strlen($link);
			$portStr .= '/';
		}
		$link = substr_replace($link, $portStr, $portPos, 0);

		return 'http' . ($https ? 's' : '') . "://$link"
			. $this->_getRawParams($params);
	}

	/**
	 * @brief Generates an absolute link to an internal (this website)
	 * resource.
	 *
	 * @param[in] string|null $page		The page name. If NULL, returns
	 * 					a link to index.php.
	 * @param[in] string|null $action	The action name. If NULL, no
	 * 					action is specified (defaults to
	 * 					actionIndex).
	 * @param[in] array $params		Associative array of key-value
	 * 					pairs of GET parameters.
	 * @retval string			The generated link.
	 */
	public function buildAbsoluteLink($page = null, $action = null, array $params = [])
	{
		$link = $this->serverName;
		if ($this->config['use_url_rewrite']) {
			if (isset($page))
				$link .= "/$page" . (isset($action) ? "/$action" : '');
			else
				$link .= '/index.php';
		} else {
			$link .= '/index.php';
			if (!empty($page))
				$params['page'] = $page;
			if (!empty($action))
				$params['action'] = $action;
		}
		return $this->buildExternalLink($link, $this->isHttps, $params,
			$this->serverPort);
	}
// }}}

// Error Handlers {{{
	/**
	 * @brief Generic error handler.
	 *
	 * This handler throws an ErrorException, triggering the exception
	 * handler.
	 *
	 * @throws	ErrorException
	 *
	 * @param[in] int $severity	Severity of the error.
	 * @param[in] string $message	The error message.
	 * @param[in] string $file	File name that triggered the error.
	 * @param[in] int $line		Line where the error was thrown.
	 */
	public function error_handler($severity, $message, $file, $line)
	{
		if (!(error_reporting() & $severity))
			return;
		throw new \ErrorException($message, 0, $severity, $file, $line);
	}

	/**
	 * @brief Generic exception handler.
	 *
	 * @param[in] Throwable $e	The exception thrown.
	 */
	public function exception_handler(\Throwable $e)
	{
		panic(500, __("Unhandled Exception: %s\n",
			addslashes($e->getMessage())), $e);
	}
// }}}

// }}}

//Private Methods {{{
	/**
	 * @internal
	 * @brief Sets up the configuration, replacing empty fields with default
	 * 	values.
	 * @param[in] array $config	User provided configuration.
	 */
	private function _setConfig(array $config = [])
	{
		$this->config = array_replace_recursive([
			'app_name' => 'Pweb',
			'header_motd' => 'Message of the day',
			'db' => [
				'prefer_mysqli_over_pdo' => false,
				'host' => 'localhost',
				'username' => 'root',
				'password' => '',
				'dbname' => 'pweb',
				'port' => 3306,
				'charset' => 'utf8'
			],
			'use_url_rewrite' => false,
			'default_per_page' => 20,
			'username_regex' => '/^[a-zA-Z0-9._-]{5,32}$/',
			'min_password_length' => 8,
			'auth_token_length' => 20,
			'auth_token_duration' => 60*60*24*30,
			'session_canary_lifetime' => 60*5,
			'form_validation' => [
				'username_regex' => '^[a-zA-Z0-9._-]{5,32}$',
				'username_maxlength' => 32,
				'flag_regex' => '^(?:f|F)(?:l|L)(?:a|A)(?:g|G)\{[ -z|~]+\}$',
				'flag_maxlength' => 255
			],
			'locales' => [
				'/^en/i' => 'en_US.UTF-8',
				'/^it/i' => 'it_IT.UTF-8'
			],
			'default_locale' => 'en',
			'selector_languages' => [ 'en', 'it' ],
			'social_names' => [
				'facebook' => '',
				'instagram' => '',
				'twitter' => '',
				'youtube' => ''
			],
			'fallback_server_name' => 'localhost',
			'fallback_server_port' => 80,
			'use_fallback_server_infos' => false,
			'debug' => false,
			'show_all_exceptions' => false
		], $config);
	}

	/**
	 * @internal
	 * @brief Returns the canonical IETF BCP 47 locale string.
	 *
	 * @param[in] string $lang	The locale string.
	 * @retval string		The IETF BCP 47 language tag.
	 */
	private function _getCanonicalLocale($lang)
	{
		$lang = trim($lang);
		foreach ($this->config['locales'] as $pattern => $locale)
			if (preg_match($pattern, $lang))
				return $locale;
		return false;
	}

	/**
	 * @internal
	 * @brief Checks if $lang is a valid locale string.
	 *
	 * @param[in] string $lang	The string to check.
	 * @retval bool			TRUE if $lang is a valid locale, FALSE
	 * 				otherwise.
	 */
	private function _isValidLanguage($lang)
	{
		return $this->_getCanonicalLocale($lang) !== false;
	}

	/**
	 * @internal
	 * @brief Sets the current locale to the user selection.
	 *
	 * This function also sets the LANG environment variable and a cookie
	 * to remember the user's selection.
	 * Used by gettext for internationalization.
	 */
	private function _setLanguage()
	{
		if (!extension_loaded('gettext') || php_sapi_name() === 'cli')
			return;

		if (!empty($_GET['lang'])
			&& $this->_isValidLanguage($_GET['lang'])) {
			$lang = $_GET['lang'];
		} else if (!empty($_COOKIE['lang'])
			&& $this->_isValidLanguage($_COOKIE['lang'])) {
			$lang = $_COOKIE['lang'];
		} else {
			$acceptLang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])
				? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

			if (extension_loaded('intl')) {
				\Locale::setDefault($this->config['default_locale']);
				$lang = \Locale::acceptFromHttp($acceptLang);
			} else if (!empty($acceptLang)) {
				$langs = explode(',', $acceptLang);
				array_walk($langs, function (&$lang) {
					$lang = strtr(strtok($lang, ';'), '-', '_');
				});
				foreach ($langs as $elem)
					if ($this->_isValidLanguage($elem)) {
						$lang = $elem;
						break;
					}
			}
		}

		/* always use default locale for easier debugging */
		$lang = (!empty($lang) && !$this->config['debug'])
			? trim($lang) : $this->config['default_locale'];

		$lang = $this->_getCanonicalLocale($lang) ?: $lang;

		@putenv("LANG=$lang");
		if (@setlocale(LC_ALL, $lang) === false) {
			$lang = $this->_getCanonicalLocale($this->config['default_locale']);
			@putenv("LANG=$lang");
			@setlocale(LC_ALL, $lang);
		}

		if (!isset($_COOKIE['lang']) || $lang !== $_COOKIE['lang'])
			setcookie('lang', $lang, time()+60*60*24*365*10,
				'/', $this->serverName);
	}

	/**
	 * @internal
	 * @brief Reads and caches the server name and port.
	 */
	private function _setServerInfos()
	{
		$this->isHttps = !empty($_SERVER['HTTPS']);

		if ($this->config['use_fallback_server_infos']
			|| php_sapi_name() === 'cli') {
			$this->serverName = $this->config['fallback_server_name'];
			$this->serverPort = $this->config['fallback_server_port'];
			return;
		}

		if (!empty($_SERVER['SERVER_NAME']))
			$this->serverName = trim($_SERVER['SERVER_NAME']);
		else
			$this->serverName = $this->config['fallback_server_name'];
		if (!empty($_SERVER['SERVER_PORT']))
			$this->serverPort = $_SERVER['SERVER_PORT'];
		else
			$this->serverPort = $this->config['fallback_server_port'];
	}

	/**
	 * @internal
	 * @brief Creates a string of parameters that can be appended to a URL.
	 *
	 * @param[in] array $params		The parameter(s).
	 * @param[in] bool $append		Set to TRUE to generate a string
	 * 					that starts with & instead of ?.
	 * @retval string			The generated urlencoded string.
	 */
	private function _getRawParams(array $params, $append = false)
	{
		$rawParams = '';
		$i = 0;
		if (empty($params))
			return '';
		foreach ($params as $name => $value) {
			if ($i == 0 && !$append)
				$rawParams .= '?';
			else
				$rawParams .= '&';
			$rawParams .= urlencode($name) . '=' . urlencode($value);
			$i++;
		}
		return $rawParams;
	}
// }}}

}
