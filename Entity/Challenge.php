<?php
namespace Pweb\Entity;

/**
 * @brief Represents a challenge.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class Challenge extends AbstractEntity
{

// Entity Properties {{{
	/**
	 * @internal
	 * @var string $_name
	 * The challenge's name.
	 */
	protected $_name;
	/**
	 * @internal
	 * @var string $_categoryName
	 * The name of the challenge's category.
	 */
	protected $_categoryName;
	/**
	 * @internal
	 * @var string $_flag
	 * The challenge's flag.
	 */
	protected $_flag;
	/**
	 * @internal
	 * @var int $_points
	 * The number of points this challenge is worth.
	 */
	protected $_points;
	/**
	 * @internal
	 * @var string $_body
	 * The main text of the challenge.
	 */
	protected $_body;
	/**
	 * @internal
	 * @var string|null $_attachmentName
	 * The attached file's name.
	 */
	protected $_attachmentName;
	/**
	 * @internal
	 * @var string|null $_attachmentHash
	 * The attached file's hash.
	 */
	protected $_attachmentHash;
// }}}

// Other Properties {{{
	/**
	 * @internal
	 * @var array $_getters
	 * An array of getter functions for each property/column.
	 */
	protected $_getters = [
		'name' => 'getName',
		'categoryName' => 'getCategoryName',
		'flag' => 'getFlag',
		'points' => 'getPoints',
		'body' => 'getBody',
		'attachmentName' => 'getAttachmentName',
		'attachmentHash' => 'getAttachmentHash'
	];

	/**
	 * @var string TABLE_NAME
	 * The name of the database's table associated with the entity.
	 */
	const TABLE_NAME = 'challenges';
	/**
	 * @var string USER_JOIN_TABLE
	 * The name of the database's table used to join this entity with user
	 * entities.
	 */
	const USER_JOIN_TABLE = 'solvedChallenges';
// }}}

// Getters {{{
	/**
	 * @brief Returns the challenge's name.
	 *
	 * @retval string	The challenge's name.
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @brief Returns the challenge's flag.
	 *
	 * @retval string	The challenge's flag.
	 */
	public function getFlag()
	{
		return $this->_flag;
	}

	/**
	 * @brief Returns the challenge's points.
	 *
	 * @retval int		The challenge's points.
	 */
	public function getPoints()
	{
		return $this->_points;
	}

	/**
	 * @brief Returns the challenge's main text.
	 *
	 * @retval string	The challenge's main text.
	 */
	public function getBody()
	{
		return $this->_body;
	}

	/**
	 * @brief Returns the name of the challenge's category.
	 *
	 * @retval string	The category's name.
	 */
	public function getCategoryName()
	{
		return $this->_categoryName;
	}

	/**
	 * @brief Returns the attached file's name.
	 *
	 * @retval string	The attached file's name.
	 */
	public function getAttachmentName()
	{
		return $this->_attachmentName;
	}

	/**
	 * @brief Returns the attached file's hash.
	 *
	 * @retval string	The attached file's hash.
	 */
	public function getAttachmentHash()
	{
		return $this->_attachmentHash;
	}
// }}}

// Setters {{{
	/**
	 * @brief Sets the challenge's name.
	 *
	 * @param[in] string $name	The challenge's name.
	 */
	public function setName($name)
	{
		$this->_set('name', $name);
	}

	/**
	 * @brief Sets the name of the challenge's category.
	 *
	 * @param[in] string $categoryName	The category's name.
	 */
	public function setCategory($categoryName)
	{
		$this->_set('categoryName', $categoryName);
	}

	/**
	 * @brief Sets the challenge's flag.
	 *
	 * @param[in] string $flag	The flag.
	 */
	public function setFlag($flag)
	{
		$this->_set('flag', $flag);
	}

	/**
	 * @brief Sets the challenge's points.
	 *
	 * @param[in] int $points	The challenge's points.
	 */
	public function setPoints($points)
	{
		$this->_set('points', $points);
	}

	/**
	 * @brief Sets the challenge's main text.
	 *
	 * @param[in] string $body	The challenge's main text.
	 */
	public function setBody($body)
	{
		$this->_set('body', $body);
	}

	/**
	 * @brief Sets the attached file.
	 *
	 * @param[in] mixed $attachment		The attached file.
	 */
	public function setAttachment($attachment)
	{
	}
// }}}

// Entity Methods {{{
	/**
	 * @brief Checks if the provided flag is correct.
	 *
	 * @param[in] string $flag	The provided flag.
	 * @retval bool		TRUE if the provided flag matches the
	 * 			challenge's flag; FALSE otherwise.
	 */
	public function checkFlag($flag)
	{
		return $flag === $this->_flag;
	}

	/**
	 * @brief Retrives all challenges from the database, ordered by
	 * category's name and challenge's id. If a User instance is provided,
	 * this function sets its array of solved challenges.
	 *
	 * @param[in] User|int|null $user	The User or user's id.
	 * @retval array	The array of challenges retrived.
	 */
	public static function getAll($user = null)
	{
		if (!isset($user))
			return parent::getAll('ORDER BY categoryName, id');

		$em = EntityManager::getInstance();
		if (is_int($user))
			$user = $em->getFromDb('User', $user);
		$userid = $user->getId();

		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` AS c LEFT OUTER JOIN (SELECT * FROM `' . self::USER_JOIN_TABLE . '` WHERE userId = ?) AS s ON c.id = s.challengeId ORDER BY c.categoryName, c.id;', $userid);

		$challs = [];
		$user->addSolvedChallenge();
		foreach ($data as $row) {
			$chall = self::createFromData($row);
			if (isset($row['userId']) && $row['userId'] === $userid)
				$user->addSolvedChallenge($chall);
			$challs[] = $chall;
		}

		return $challs;
	}

	/**
	 * @brief Retrives all challenges from the database solved by the
	 * specified user, ordered by category's name and challenge's id.
	 *
	 * @param[in] User|int $user	The User or user's id.
	 * @retval array	The array of challenges retrived.
	 */
	public static function getAllSolvedBy($user)
	{
		$em = EntityManager::getInstance();
		if (!is_int($user))
			$user = $user->getId();

		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` AS c INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON c.id = s.challengeId WHERE s.userId = ? ORDER BY c.categoryName, c.id;', $user);

		$challs = [];
		foreach ($data as $row)
			$challs[] = self::createFromData($row);
		return $challs;
	}
// }}}

// Entity Life-cycle {{{
	/**
	 * @brief Updates users' points and deletes solved challenges relations.
	 */
	protected function _preDelete()
	{
		$challid = $this->getId();
		$this->_db->query('UPDATE `' . User::TABLE_NAME . '` AS u INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON u.id = s.userId INNER JOIN `' . self::TABLE_NAME . '` AS c ON s.challengeId = c.id SET u.points = u.points - c.points WHERE c.id = ?;',
			$challid);
		$this->_db->query('DELETE FROM `' . self::USER_JOIN_TABLE . '` WHERE challengeId=?;',
			$challid);
	}

	/** @brief Updates users' points, if changed. */
	protected function _postUpdate()
	{
		if (!isset($this->_changedValues['points']))
			return;
		$this->_db->query('UPDATE `' . User::TABLE_NAME . '` AS u INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON u.id = s.userId SET u.points = u.points + ? WHERE s.challengeId = ?;',
			$this->getPoints() - $this->_changedValues['points'], $this->getId());
	}
// }}}

}
