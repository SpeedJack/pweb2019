<?php
namespace Pweb\Entity;

/**
 * @brief Represents a user.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class User extends AbstractEntity
{

// Entity Properties {{{
	/**
	 * @var string $_username
	 * The user's username.
	 */
	protected $_username;
	/**
	 * @var string $_email
	 * The user's email.
	 */
	protected $_email;
	/**
	 * @var string $_passwordHash
	 * The user's hashed password.
	 */
	protected $_passwordHash;
	/**
	 * @var bool $_isAdmin
	 * Specifies if the user has admin rights or not.
	 */
	protected $_isAdmin = false;
	/**
	 * @var int $_points
	 * The number of user's points collected solving challenges.
	 */
	protected $_points = 0;
	/**
	 * @var array $_solvedChalls
	 * Array of challenges solved by the user.
	 */
	protected $_solvedChalls;
// }}}

// Other Properties {{{
	/**
	 * @internal
	 * @var array $_getters
	 * An array of getter functions for each property/column.
	 */
	protected $_getters = [
		'username' => 'getUsername',
		'email' => 'getEmail',
		'passwordHash' => 'getPasswordHash',
		'isAdmin' => 'isAdmin',
		'points' => 'getPoints'
	];

	/**
	 * @var string TABLE_NAME
	 * The name of the database's table associated with the entity.
	 */
	const TABLE_NAME = 'users';
	/**
	 * @var string CHALLENGE_JOIN_TABLE
	 * The name of the database's table used to join this entity with
	 * challenge entities.
	 */
	const CHALLENGE_JOIN_TABLE = 'solvedChallenges';
	/**
	 * @var int INVALID
	 * Returned by isValid* and set* functions when the username/email is
	 * invalid.
	 */
	const INVALID = 0;
	/**
	 * @var int ALREADY_IN_USE
	 * Returned by isValid* and set* functions when the username/email is
	 * already in use by another user.
	 */
	const ALREADY_IN_USE = 1;
	/**
	 * @var int VALID
	 * Returned by isValid* and set* functions when the username/email is
	 * valid.
	 */
	const VALID = 2;

	/**
	 * @var int WRONG_FLAG
	 * Returned by solveChallenge() if the user provided flag is wrong.
	 */
	const WRONG_FLAG = 0;
	/**
	 * @var int WRONG_FLAG
	 * Returned by solveChallenge() if the challenge has been already
	 * solved by the user.
	 */
	const ALREADY_SOLVED = 1;
	/**
	 * @var int WRONG_FLAG
	 * Returned by solveChallenge() if the user provided flag is correct.
	 */
	const CORRECT_FLAG = 2;
// }}}

// Getters {{{
	/**
	 * @brief Returns the user's username.
	 *
	 * @retval string	The user's username.
	 */
	public function getUsername()
	{
		return $this->_username;
	}

	/**
	 * @brief Returns the user's email.
	 *
	 * @retval string	The user's email.
	 */
	public function getEmail()
	{
		return $this->_email;
	}

	/**
	 * @brief Returns the user's hashed password.
	 *
	 * @retval string	The user's hashed password.
	 */
	public function getPasswordHash()
	{
		return $this->_passwordHash;
	}

	/**
	 * @brief Checks if the user is admin.
	 *
	 * @retval bool		TRUE if the user is admin; FALSE otherwise.
	 */
	public function isAdmin()
	{
		return $this->_isAdmin || $this->isSuperAdmin();
	}

	public function isSuperAdmin()
	{
		return in_array($this->getId(),
			$this->_app->config['super_admin_ids'], true);
	}

	/**
	 * @brief Returns the number of user's points.
	 *
	 * @retval int		The number of points collected by the user.
	 */
	public function getPoints()
	{
		return $this->_points;
	}

	/**
	 * @brief Returns an array containing all solved challenges by the user.
	 *
	 * @retval array	The array of solved challenges.
	 */
	public function getSolvedChallenges()
	{
		if (!isset($this->_solvedChalls)) {
			$challs = $this->_em->getFromDbBy('Challenge',
				'getAllSolvedBy', $this);
			$this->addSolvedChallenge();
			if ($challs !== false) {
				$challs = is_array($challs) ? $challs : [ $challs ];
				foreach ($challs as $chall)
					$this->addSolvedChallenge($chall);
			}
		}
		return $this->_solvedChalls;
	}
// }}}

// Setters {{{
	/**
	 * @brief Sets the user's username.
	 *
	 * @param[in] string $username	The username.
	 * @retval int			Returns VALID if username is valid;
	 * 				INVALID if invalid; ALREADY_IN_USE if
	 * 				already in use by another user.
	 */
	public function setUsername($username)
	{
		$validity = self::isValidUsername($username, true, $this->getId());
		if ($validity !== self::VALID)
			return $validity;
		$this->_set('username', $username);
		return self::VALID;
	}

	/**
	 * @brief Sets the user's email.
	 *
	 * @param[in] string $email	The email.
	 * @retval int			Returns VALID if email is valid;
	 * 				INVALID if invalid; ALREADY_IN_USE if
	 * 				already in use by another user.
	 */
	public function setEmail($email)
	{
		$validity = self::isValidEmail($email, true, $this->getId());
		if ($validity !== self::VALID)
			return $validity;
		$this->_set('email', $email);
		return self::VALID;
	}

	/**
	 * @brief Sets the user's password.
	 *
	 * @param[in] string $password	The password.
	 * @retval bool			TRUE if the password is valid; FALSE if
	 * 				the password is too short.
	 */
	public function setPassword($password)
	{
		if (strlen($password) < $this->_app->config['min_password_length'])
			return false;
		$this->_set('passwordHash',
			password_hash($password, PASSWORD_DEFAULT));
		return true;
	}

	/** @brief Gives the user admin's rights. */
	public function promoteAdmin()
	{
		$this->_set('isAdmin', true);
	}

	/** @brief Removes the user admin's rights. */
	public function demoteAdmin()
	{
		$this->_set('isAdmin', false);
	}

	/**
	 * @brief Adds a challenge to the list of solved challenges.
	 *
	 * This function only adds the challenge to the _solvedChalls array,
	 * without writing it to the database.
	 *
	 * @param[in] Challenge|null $chall	The challenge to add. If NULL,
	 * 					initializes the _solvedChalls as
	 * 					empty array.
	 */
	public function addSolvedChallenge($chall = null)
	{
		if (!isset($chall)) {
			$this->_solvedChalls = [];
			return;
		}
		if (is_int($chall))
			$chall = $this->_em->getFromDb('Challenge', $chall);
		$this->_solvedChalls[$chall->getId()] = $chall;
	}
// }}}

// Entity Methods {{{
	/**
	 * @brief Retrives the user with the specified username from the
	 * database.
	 *
	 * @param[in] string $username	The username of the user to retrive.
	 * @retval self|false		The retrived user or FALSE if no user
	 * 				was found.
	 */
	public static function getByUsername($username)
	{
		if (self::isValidUsername($username) !== self::VALID)
			return false;
		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchRow('SELECT * FROM `' . self::TABLE_NAME . '` WHERE username=?;', $username);
		return self::createFromData($data);
	}

	/**
	 * @brief Retrives the user with the specified email from the database.
	 *
	 * @param[in] string $email	The email of the user to retrive.
	 * @retval self|false		The retrived user or FALSE if no user
	 * 				was found.
	 */
	public static function getByEmail($email)
	{
		if (self::isValidEmail($email) !== self::VALID)
			return false;
		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchRow('SELECT * FROM `' . self::TABLE_NAME . '` WHERE email=?;', $email);
		return self::createFromData($data);
	}

	/**
	 * @brief Retrives all users that solved a challenge.
	 *
	 * @param[in] Challenge|int $chall	The challenge or challenge's id.
	 * @retval array|false	Array containing all users that solved the
	 * 			challenge.
	 */
	public static function getBySolvedChallenge($chall)
	{
		$em = EntityManager::getInstance();
		if (!is_int($chall))
			$chall = $chall->getId();

		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT u.* FROM `' . self::TABLE_NAME . '` AS u INNER JOIN `' . self::CHALLENGE_JOIN_TABLE . '` AS s ON u.id = s.userId WHERE s.challengeId = ?;',
			$chall);

		return parent::createFromDataArray($data);
	}

	/**
	 * @brief Retrives all users which username contains the given pattern.
	 *
	 * @param[in] string $username	The username pattern.
	 * @retval array|false		Array containing all users which
	 * 				username contains the given pattern.
	 */
	public static function getAllByUsernameLike($username)
	{
		$em = EntityManager::getInstance();
		$db = \Pweb\App::getInstance()->getdb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` WHERE username LIKE ?;', '%' . $username . '%');
		return parent::createFromDataArray($data);
	}

	/**
	 * @brief Retrives all users which email contains the given pattern.
	 *
	 * @param[in] string $email	The email pattern.
	 * @retval array|false		Array containing all users which email
	 * 				contains the given pattern.
	 */
	public static function getAllByEmailLike($email)
	{
		$em = EntityManager::getInstance();
		$db = \Pweb\App::getInstance()->getdb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` WHERE email LIKE ?;', '%' . $email . '%');
		return parent::createFromDataArray($data);
	}

	/**
	 * @brief Solves a challenge and writes it to the database.
	 *
	 * @param[in] Challenge|int $chall	The challenge or challenge's id.
	 * @param[in] string $flag		The user provided flag that
	 * 					should solve the challenge.
	 * @retval int	If the flag is wrong, returns WRONG_FLAG; If the
	 * 		challenge is already solved by the user, returns
	 * 		ALREADY_SOLVED; If the flag is ok, returns CORRECT_FLAG.
	 */
	public function solveChallenge($chall, $flag)
	{
		if (is_int($chall))
			$chall = $this->_em->getFromDb('Challenge', $chall);

		if ($this->hasSolvedChallenge($chall))
			return self::ALREADY_SOLVED;

		if (!$chall->checkFlag($flag))
			return self::WRONG_FLAG;

		$this->addSolvedChallenge($chall);
		try {
			$this->_db->query('INSERT INTO `' . self::CHALLENGE_JOIN_TABLE . '`(challengeId, userId) VALUES(?, ?);',
				$chall->getId(), $this->getId());
		} catch (\Pweb\Db\DuplicateKeyException $e) {
			return self::ALREADY_SOLVED;
		}

		$this->_set('points', $this->getPoints() + $chall->getPoints());
		return self::CORRECT_FLAG;
	}

	/**
	 * @brief Reloads the array of solved challenges from the database.
	 *
	 * @retval array	The new array of solved challenges.
	 */
	public function refreshSolvedChallenges()
	{
		unset($this->_solvedChalls);
		return $this->getSolvedChallenges();
	}

	/**
	 * @brief Creates a new User from the data passed as array.
	 *
	 * @param[in] array $data	Associative array of key-value pairs
	 * 				where the key is the property/column's
	 * 				name.
	 * @retval self|false	The entity created or FALSE if no data is
	 * 			provided.
	 */
	public static function createFromData(array $data)
	{
		if (isset($data['isAdmin']))
			$data['isAdmin'] = ($data['isAdmin'] ? true : false);
		return parent::createFromData($data);
	}
// }}}

// Entity Life-cycle {{{
	/**
	 * @brief Deletes every auth tokens and solved challenge associated with
	 * this user.
	 */
	protected function _preDelete()
	{
		$userid = $this->getId();
		AuthToken::deleteByUserId($userid);
		$this->_db->query('DELETE FROM `' . self::CHALLENGE_JOIN_TABLE . '` WHERE userId=?;',
			$userid);
	}
// }}}

// Public Methods {{{
	/**
	 * @brief Checks if the provided username is valid.
	 *
	 * @param[in] string $username	The username to check.
	 * @param[in] bool $checkInUse	Set to TRUE to check if the username is
	 * 				already in use by another user.
	 * @param[in] int|null $userid	When $checkInUse is TRUE, if the
	 * 				username is already in use by a user
	 * 				that has this id this function still
	 * 				returns VALID.
	 * @retval int			Returns VALID if username is valid;
	 * 				INVALID if invalid; ALREADY_IN_USE if
	 * 				already in use by another user.
	 */
	public static function isValidUsername($username, $checkInUse = false, $userid = null)
	{
		if (!is_string($username))
			return self::INVALID;
		$app = \Pweb\App::getInstance();
		$res = preg_match($app->config['username_regex'], $username);
		if ($res === false)
			throw new \Exception(__('Error parsing regular expression: \'%s\'.',
				$app->config['username_regex']));
		if (!$res)
			return self::INVALID;
		if (!$checkInUse)
			return self::VALID;
		$em = EntityManager::getInstance();
		$inUse = $em->getFromDbBy('User', 'getByUsername', $username);
		if ($inUse === false)
			return self::VALID;
		if (!isset($userid))
			return self::ALREADY_IN_USE;
		return $inUse->getId() === $userid ? self::VALID : self::ALREADY_IN_USE;
	}

	/**
	 * @brief Checks if the provided email is valid.
	 *
	 * @param[in] string $email	The email to check.
	 * @param[in] bool $checkInUse	Set to TRUE to check if the email is
	 * 				already in use by another user.
	 * @param[in] int|null $userid	When $checkInUse is TRUE, if the
	 * 				email is already in use by a user that
	 * 				has this id this function still returns
	 * 				VALID.
	 * @retval int			Returns VALID if email is valid;
	 * 				INVALID if invalid; ALREADY_IN_USE if
	 * 				already in use by another user.
	 */
	public static function isValidEmail($email, $checkInUse = false, $userid = null)
	{
		// FIXME: FILTER_VALIDATE_EMAIL sometimes rejects RFC5321 valid
		// email addresses. Better use a custom regex.
		if (!is_string($email)
			|| filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			return self::INVALID;
		if (!$checkInUse)
			return self::VALID;
		$em = EntityManager::getInstance();
		$inUse = $em->getFromDbBy('User', 'getByEmail', $email);
		if ($inUse === false)
			return self::VALID;
		if (!isset($userid))
			return self::ALREADY_IN_USE;
		return $inUse->getId() === $userid ? self::VALID : self::ALREADY_IN_USE;
	}

	/**
	 * @brief Verifies that the provided password equals with this user's
	 * password.
	 *
	 * @param[in] string $password	The password to check.
	 * @retval bool			TRUE if the password matches this user's
	 * 				password; FALSE otherwise.
	 */
	public function verifyPassword($password)
	{
		return password_verify($password, $this->_passwordHash);
	}

	/**
	 * @brief Checks if the user has solved the specified challenge.
	 *
	 * @param[in] int $chall	The challenge or challenge's id.
	 * @retval bool		TRUE if the user has solved the challenge;
	 * 			FALSE otherwise.
	 */
	public function hasSolvedChallenge($chall)
	{
		if (!is_int($chall))
			$chall = $chall->getId();
		if (!isset($this->_solvedChalls))
			$this->refreshSolvedChallenges();

		if (isset($this->_solvedChalls[$chall]))
			return true;
		return false;
	}
// }}}

}
