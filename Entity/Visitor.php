<?php
namespace Pweb\Entity;

require_once 'string-functions.php';

/**
 * @brief Represent a visitor of this website.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class Visitor extends AbstractEntity
{

// Public Properties {{{
	/**
	 * @var User $user
	 * The User entity when the vistor is logged in.
	 */
	public $user;
	/**
	 * @var AuthToken $authToken
	 * The AuthToken entity used by the visitor to stay logged in across
	 * sessions.
	 */
	public $authToken;
	/**
	 * @var string $page
	 * The name of the page requested by the visitor.
	 */
	public $page = 'ChallengesPage';
	/**
	 * @var string $action
	 * The name of the action requested by the visitor.
	 */
	public $action = 'actionIndex';
// }}}

// Protected Properties {{{
	/**
	 * @internal
	 * @var array $_postParams
	 * Array of strings containing the POST parameters.
	 */
	protected $_postParams = [];
	/**
	 * @internal
	 * @var array $_getParams
	 * Array of strings containing the GET parameters.
	 */
	protected $_getParams = [];
// }}}

	/**
	 * @brief Creates a Visitor.
	 *
	 * @param[in] int $id	The entity's id.
	 * @return		The Visitor instance.
	 */
	public function __construct($id)
	{
		parent::__construct($id);
		$this->_readParams();
		$this->_initSession();
		$this->_login();
	}

// Public Methods {{{
	/**
	 * @brief Sets the AuthToken of the current Visitor.
	 *
	 * @param[in] AuthToken $authToken	The AuthToken.
	 * @param[in] string $validator		The token string, not hashed,
	 * 					that will be saved in the
	 * 					visitor's cookie.
	 */
	public function setAuthTokenCookie($authToken, $validator)
	{
		$this->setCookie('authToken', [
				'id' => $authToken->getId(),
				'validator' => $validator
			], $authToken->getExpireTime(), true);
		$this->authToken = $authToken;
		$this->user = $authToken->getUser();
	}

	/**
	 * @brief Sets the logged in User of this PHP session.
	 *
	 * @param[in] User $user	The User logged in.
	 */
	public function setSessionUser($user)
	{
		$this->user = $user;
		$this->_initSession();
		$_SESSION['userid'] = $user->getId();
	}

	/**
	 * @brief Logs out the user and clear his authentication token.
	 */
	public function logout()
	{
		if (isset($this->authToken))
			$this->authToken->delete();
		$this->authToken = null;
		$this->user = null;
		$this->_destroySession();
		$this->unsetCookie('authToken');
	}

	/**
	 * @brief Clears all GET and POST parameters.
	 */
	public function clearParams()
	{
		$this->_getParams = [];
		$this->_postParams = [];
	}

	/**
	 * @brief Adds values to the $_getParams array.
	 *
	 * @param[in] array $params	Associative array of key-value pairs of
	 * 				GET parameters to add.
	 */
	public function setGetParams(array $params = [])
	{
		foreach ($params as $key => $value)
			$this->_getParams[$key] = $this->_sanitizeParam($value);
	}

	/**
	 * @brief Adds values to the $_postParams array.
	 *
	 * @param[in] array $params	Associative array of key-value pairs of
	 * 				POST parameters to add.
	 */
	public function setPostParams(array $params = [])
	{
		foreach ($params as $key => $value)
			$this->_postParams[$key] = $this->_sanitizeParam($value);
	}

	/**
	 * @brief Sets the page to load.
	 *
	 * @param[in] string $page 	The name of the page to load; If empty,
	 * 				it defaults to the home page.
	 */
	public function setPage($page)
	{
		$page = $this->_sanitizeParam($page, true, true);
		$this->page = (!empty($page) ? $page : 'Challenges') . 'Page';
	}

	/**
	 * @brief Sets the action to load.
	 *
	 * @param[in] string $action	The name of the action to load; If
	 * 				empty, it defaults to actionIndex.
	 */
	public function setAction($action)
	{
		$action = $this->_sanitizeParam($action, true, true);
		$this->action = 'action' . (!empty($action) ? $action : 'Index');
	}

	/**
	 * @brief Sets the page and action to load.
	 *
	 * @param[in] string|null $page 	The name of the page to load; If
	 * 					NULL, it defaults to the home
	 * 					page.
	 * @param[in] string|null $action	The name of the action to load;
	 * 					If NULL, it defaults to
	 * 					actionIndex.
	 */
	public function setRoute($page = null, $action = null)
	{
		$this->setPage($page);
		$this->setAction($action);
	}

	/**
	 * @brief Checks if the Visitor is visiting the specified page.
	 *
	 * @param[in] string $page	The name of the page.
	 * @retval bool			TRUE if the Visitor is visiting the page
	 * 				$page; FALSE otherwise.
	 */
	public function isActivePage($page)
	{
		$page = $this->_sanitizeParam($page, true, true);
		if (!endsWith($page, 'Page'))
			$page = "${page}Page";
		return $this->page === $page;
	}

	/**
	 * @brief Checks if the Visitor is visiting the specified action.
	 *
	 * @param[in] string $action	The name of the action.
	 * @retval bool			TRUE if the Visitor is visiting the
	 * 				action $action; FALSE otherwise.
	 */
	public function isActiveAction($action)
	{
		$action = $this->_sanitizeParam($action, true, true);
		if (!startsWith($action, 'action'))
			$action = "action$action";
		return $this->action === $action;
	}

	/**
	 * @brief Checks if the Visitor is logged in.
	 *
	 * @retval bool		TRUE if the Visitor is logged in. FALSE
	 * 			otherwise.
	 */
	public function isLoggedIn()
	{
		return isset($this->user);
	}

	/**
	 * @brief Checks if the Visitor is logged in as admin.
	 *
	 * @retval bool		TRUE if the Visitor is logged in as admin.
	 * 			FALSE otherwise.
	 */
	public function isAdmin()
	{
		return $this->isLoggedIn() && $this->user->isAdmin();
	}

	public function isSuperAdmin()
	{
		return $this->isLoggedIn() && $this->user->isSuperAdmin();
	}

	/**
	 * @brief Returns a PHP session variable.
	 *
	 * @param[in] string $key	The name of the session variable.
	 * @retval mixed|null		The value of the session variable.
	 */
	public function session($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	/**
	 * @brief Returns the value of a GET or POST parameter.
	 *
	 * @param[in] string $key	The name of the parameter.
	 * @param[in] string $method	'GET' to search only in GET parameters.
	 * 				'POST' to search only in POST
	 * 				parameters. 'ANY' to search in GET and
	 * 				POST parameters.
	 * @retval string|null		The value of the parameter.
	 */
	public function param($key, $method = 'ANY')
	{
		$method = strtoupper($method);
		$key = strtolower($key);
		$getParam = isset($this->_getParams[$key])
			? $this->_getParams[$key] : null;
		$postParam = isset($this->_postParams[$key])
			? $this->_postParams[$key] : null;
		switch ($method) {
		case 'POST':
			return $postParam;
			break;
		case 'GET':
			return $getParam;
			break;
		case 'ANY':
		default:
			return isset($postParam) ? $postParam : $getParam;
		}
	}

	/**
	 * @brief Returns the value of a cookie.
	 *
	 * @param[in] string $key	The name of the cookie.
	 * @retval string|null		The value of the cookie.
	 */
	public function cookie($key)
	{
		return isset($_COOKIE[$key])
			? $_COOKIE[$key] : null;
	}

	/**
	 * @brief Sets a cookie.
	 *
	 * @param[in] string $key	The name of the cookie.
	 * @param[in] mixed $value	The value of the cookie.
	 * @param[in] int $expire	The expiration time of the cookie.
	 * @param[in] bool $httponly	Set to TRUE to allow this cookie to be
	 * 				accessed only over HTTP requests.
	 * @param[in] string $path	Set to a path to allow this cookie to
	 * 				be accessed only by a subpart of this
	 * 				website.
	 */
	public function setCookie($key, $value, $expire = 0, $httponly = false, $path = '/')
	{
		if (is_array($value))
			foreach ($value as $arraykey => $data)
				setcookie($key . '[' . $arraykey . ']', $data,
					$expire, $path, $this->_app->serverName,
					$this->_app->isHttps, $httponly);
		else
			setcookie($key, $value, $expire, $path,
				$this->_app->serverName, $this->_app->isHttps,
				$httponly);
		$_COOKIE[$key] = $value;
	}

	/**
	 * @brief Removes a cookie.
	 *
	 * @param[in] string $key	The name of the cookie.
	 */
	public function unsetCookie($key)
	{
		if (!isset($_COOKIE[$key]))
			return;
		if (is_array($_COOKIE[$key])) {
			foreach ($_COOKIE[$key] as &$cookie)
				$cookie = '';
			$this->setCookie($key, $_COOKIE[$key], time() - 60*60*24);
			return;
		}
		$this->setCookie($key, '', time() - 60*60*24);
		unset($_COOKIE[$key]);
	}
// }}}

// Private Methods {{{
	/**
	 * @internal
	 * @brief Initializes the PHP session.
	 */
	private function _initSession()
	{
		if (php_sapi_name() === 'cli')
			return;
		if (session_status() === PHP_SESSION_NONE)
			session_start();
		if (!isset($_SESSION['canary']) || $_SESSION['canary'] <
			time() - $this->_app->config['session_canary_lifetime']) {
			session_regenerate_id(true);
			$_SESSION['canary'] = time();
			$_COOKIE[session_name()] = session_id();
		}
	}

	/**
	 * @internal
	 * @brief Destroys the PHP session and its cookie.
	 */
	private function _destroySession()
	{
		if (session_status() !== PHP_SESSION_ACTIVE)
			return;
		$this->unsetCookie(session_name());
		session_destroy();
	}

	/**
	 * @internal
	 * @brief Mantains the user logged in using the AuthToken or the PHP
	 * session.
	 */
	private function _login()
	{
		if (php_sapi_name() === 'cli') {
			$this->authToken = null;
			$uid = intval(readline('[Login] Insert user id (leave empty for no login): '));
			if ($uid)
				$this->user = $this->_em->getFromDb('User', $uid);
			else
				$this->user = null;
			return;
		}
		$user = false;
		$authToken = false;
		$userid = $this->session('userid');
		$clientToken = $this->cookie('authToken');
		if (!empty($clientToken) && !empty($clientToken['id'])) {
			$authToken = $this->_em->getFromDb('AuthToken',
				$clientToken['id']);
			if ($authToken !== false && !empty($clientToken['validator']))
				$user = $authToken->authenticate($clientToken['validator']);
			if ($user === false
				|| (!empty($userid) && $user->getId() !== $userid)) {
				$authToken = false;
				$user = false;
				$this->_destroySession();
				$this->unsetCookie('authToken');
			} else {
				$_SESSION['userid'] = $user->getId();
			}
		} else if (!empty($userid)) {
			$user = $this->_em->getFromDb('User', $userid);
		}
		$this->user = $user ?: null;
		$this->authToken = $authToken ?: null;
		if (!$this->isLoggedIn())
			$this->logout();
	}

	/**
	 * @internal
	 * @brief Sanitizes GET and POST parameters.
	 *
	 * @param[in] string $value		The string to sanitize.
	 * @param[in] bool $removeSlashes	TRUE to remove any slash from
	 * 					the string (useful to avoid
	 * 					LFI vulnerabilities).
	 * @param[in] bool $ucwords		TRUE to make the first letter of
	 * 					each word upper case.
	 * @retval string			The sanitized string.
	 */
	private function _sanitizeParam($value, $removeSlashes = false, $ucwords = false)
	{
		if (empty($value))
			return '';
		$value = $removeSlashes ? str_replace('\\', '', $value) : $value;
		$value = trim($value);
		return $ucwords ? ucwords($value, " \t\r\n\f\v_-.") : $value;
	}

	/**
	 * @internal
	 * @brief Reads GET and POST parameters from the Visitor's request.
	 */
	private function _readParams()
	{
		if (php_sapi_name() === 'cli')
			return;

		foreach ($_GET as $key => $value) {
			$key = strtolower($key);
			switch ($key) {
			case 'page':
				$this->setPage($value);
				break;
			case 'action':
				$this->setAction($value);
				break;
			default:
				$this->setGetParams([$key => $value]);
			}
		}
		$this->setPostParams($_POST);
	}
// }}}

}
