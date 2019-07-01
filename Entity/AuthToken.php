<?php
namespace Pweb\Entity;

/**
 * @brief Represents an authentication token, used to maintain the user logged
 * in between sessions.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class AuthToken extends AbstractEntity
{

// Entity Properties {{{
	/**
	 * @internal
	 * @var int $_userId
	 * The id of the user associated to this token.
	 */
	protected $_userId;
	/**
	 * @internal
	 * @var string $_authToken
	 * The hashed token, used to validate the user.
	 */
	protected $_authToken;
	/**
	 * @internal
	 * @var int $_expireTime
	 * The expiration unix timestamp of the token.
	 */
	protected $_expireTime;
	/**
	 * @internal
	 * @var User $_user
	 * The user associated to this token.
	 */
	protected $_user;
// }}}

// Other Properties {{{
	/**
	 * @internal
	 * @var array $_getters
	 * An array of getter functions for each property/column.
	 */
	protected $_getters = [
		'userId' => 'getUserId',
		'authToken' => 'getAuthToken',
		'expireTime' => 'getExpireTime'
	];

	/**
	 * @var string TABLE_NAME
	 * The name of the database's table associated with the entity.
	 */
	public const TABLE_NAME = 'authTokens';
// }}}

// Getters {{{
	/**
	 * @brief Returns the user id.
	 *
	 * @retval int	The user id.
	 */
	public function getUserId()
	{
		return $this->_userId;
	}

	/**
	 * @brief Returns the hashed token.
	 *
	 * @retval string	The hashed token.
	 */
	public function getAuthToken()
	{
		return $this->_authToken;
	}

	/**
	 * @brief Returns the expiration unix timestamp.
	 *
	 * @retval int	The expiration timestamp.
	 */
	public function getExpireTime()
	{
		return $this->_expireTime;
	}

	/**
	 * @brief Returns the user associated with this token.
	 *
	 * @retval User		The user.
	 */
	public function getUser()
	{
		if (!$this->_user || $this->_userId !== $this->_user->getId())
			$this->_user = $this->_em->getFromDb('User', $this->_userId);
		return $this->_user;
	}
// }}}

// Setters {{{
	/**
	 * @brief Sets the user associated with this token.
	 *
	 * @param[in] User|int $user	The user.
	 * @retval bool			Returns TRUE on success; FALSE
	 * 				otherwise.
	 */
	public function setUser($user)
	{
		if (is_int($user)) {
			$this->_set('userId', $user);
		} else if (is_a($user, __NAMESPACE__ . '\\User')) {
			$this->_set('userId', $user->getId());
			$this->_user = $user;
		} else {
			return false;
		}
		return true;
	}
// }}}

// Entity Methods {{{
	/**
	 * @brief Retrives an AuthToken entity from the database with the
	 * specified id.
	 *
	 * @param[in] int $id	The id of the AuthToken to serch.
	 * @retval self|false	The AuthToken retrived, or FALSE if the
	 * 			AuthToken was not found.
	 */
	public static function getById($id)
	{
		$db = \Pweb\App::getInstance()->getDb();
		$em = EntityManager::getInstance();
		$data = $db->fetchRow('SELECT *, t.id AS tokenId FROM `' . self::TABLE_NAME . '` AS t INNER JOIN `' . User::TABLE_NAME . '` AS u ON t.userId = u.id WHERE t.id = ?;', $id);
		if (empty($data))
			return false;
		$userData = [
			'id' => $data['userId'],
			'username' => $data['username'],
			'email'  => $data['email'],
			'passwordHash' => $data['passwordHash'],
			'points' => $data['points']
		];
		$user = User::createFromData($userData);
		$em->addToSaved($user);
		$authData = [
			'id' => $data['tokenId'],
			'userId' => $data['userId'],
			'user' => $user,
			'authToken' => $data['authToken'],
			'expireTime' => $data['expireTime']
		];
		$auth = self::createFromData($authData);
		return $auth;
	}

	/**
	 * @brief Retrives all auth tokens from the database that are associated
	 * with the specified user id.
	 *
	 * @param[in] int $userid	The id of the user.
	 * @retval array		An array containing all AuthToken
	 * 				entities associated with the specified
	 * 				user id.
	 */
	public static function getByUserId($userid)
	{
		$db = \Pweb\App::getInstance()->getDb();
		$em = EntityManager::getInstance();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` WHERE userId = ?;',
			$userid);
		$authTokens = [];
		foreach ($data as $row)
			$authTokens[] = self::createFromData($row);
		return $authTokens;
	}

	/**
	 * @brief Removes every AuthToken associated with the user with the
	 * specified id from the database.
	 *
	 * @param[in] int $userid	The id of the user to remove.
	 */
	public static function deleteByUserId($userid)
	{
		$db = \Pweb\App::getInstance()->getDb();
		$em = EntityManager::getInstance();
		$db->query('DELETE FROM `' . self::TABLE_NAME . '` WHERE userId = ?;', $userid);
	}
// }}}

// Entity Life-cycle {{{
	/**
	 * @internal
	 * @brief Deletes this AuthToken if it's expired.
	 */
	protected function _preSave()
	{
		if ($this->isExpired())
			$this->delete();
	}
// }}}

// Public Methods {{{
	/**
	 * @brief Checks if the token is expired.
	 *
	 * @retval bool		TRUE if the token is expired; FALSE otherwise.
	 */
	public function isExpired()
	{
		return $this->_expireTime <= time();
	}

	/** @brief Resets the expiration time. */
	public function resetExpireTime()
	{
		$this->_set('expireTime', time() + $this->_app->config['auth_token_duration']);
	}

	/**
	 * @brief Checks if the provided token equals to this token.
	 *
	 * @param[in] string $token	The token to check.
	 * @retval bool			TRUE if the token is valid; FALSE
	 * 				otherwise.
	 */
	public function verifyToken($token)
	{
		return password_verify($token, $this->_authToken);
	}

	/**
	 * @brief Generates a new token and saves his hash in this entity.
	 *
	 * @retval string	The generated token.
	 */
	public function generateToken()
	{
		$raw_token = false;
		if (function_exists('random_bytes'))
			try {
				$raw_token = random_bytes($this->_app->config['auth_token_length']);
			} catch (\Exception $e) {
				/* insufficient entropy */
				$raw_token = false;
			}
		if ($raw_token === false && function_exists('openssl_random_pseudo_bytes'))
			$raw_token = openssl_random_pseudo_bytes($this->_app->config['auth_token_length']);
		if ($raw_token === false && function_exists('mcrypt_create_iv'))
			$raw_token = mcrypt_create_iv($this->_app->config['auth_token_length'], MCRYPT_DEV_URANDOM);
		if ($raw_token === false)
			$raw_token = $this->_generateInsecureToken();
		$token = bin2hex($raw_token);
		$this->_set('authToken', password_hash($token, PASSWORD_DEFAULT));
		$this->resetExpireTime();
		return $token;
	}

	/**
	 * @brief Authenticates a visitor by checking the provided validator
	 * with this token.
	 *
	 * @param[in] string $token	Token validator.
	 * @retval User|false		The authenticated user; or FALSE if
	 * 				authentication failed.
	 */
	public function authenticate($token)
	{
		if ($this->isExpired() || !$this->verifyToken($token))
			return false;
		$this->resetExpireTime();
		return $this->_user;
	}
// }}}

// Private Methods {{{
	/**
	 * @internal
	 * @brief Generates a token using mt_rand().
	 *
	 * This does not generate a cryptographically secure token. It is used
	 * only if the PHP version running this application does not support
	 * cryptographically secure random generators.
	 *
	 * @retval string	The generated token.
	 */
	private function _generateInsecureToken()
	{
		error_log(__('Generating insecure auth token. You should update PHP to a newer version or install random_compat/OpenSSL extension.'));
		$token = '';
		for ($i = 0; $i < $this->_app->config['auth_token_length']; ++$i)
			$token .= chr(mt_rand(0, 255));
		return $token;
	}
// }}}

}
