<?php
namespace Pweb\Entity;

class Challenge extends AbstractEntity
{
	protected $_name;
	protected $_categoryName;
	protected $_flag;
	protected $_points;
	protected $_body;
	protected $_attachmentName;
	protected $_attachmentHash;
	protected $_getters = [
		'name' => 'getName',
		'categoryName' => 'getCategoryName',
		'flag' => 'getFlag',
		'points' => 'getPoints',
		'body' => 'getBody',
		'attachmentName' => 'getAttachmentName',
		'attachmentHash' => 'getAttachmentHash'
	];

	const TABLE_NAME = 'challenges';
	const USER_JOIN_TABLE = 'solvedChallenges';

	public function getName()
	{
		return $this->_name;
	}

	public function getFlag()
	{
		return $this->_flag;
	}

	public function getPoints()
	{
		return $this->_points;
	}

	public function getBody()
	{
		return $this->_body;
	}

	public function getCategoryName()
	{
		return $this->_categoryName;
	}

	public function getAttachmentName()
	{
		return $this->_attachmentName;
	}

	public function getAttachmentHash()
	{
		return $this->_attachmentHash;
	}

	public function setName($name)
	{
		$this->_set('name', $name);
	}

	public function setCategory($categoryName)
	{
		$this->_set('categoryName', $categoryName);
	}

	public function setFlag($flag)
	{
		$this->_set('flag', $flag);
	}

	public function setPoints($points)
	{
		$this->_set('points', $points);
	}

	public function setBody($body)
	{
		$this->_set('body', $body);
	}

	public function setAttachment($attachment)
	{
	}

	public function checkFlag($flag)
	{
		return $flag === $this->_flag;
	}

	public static function getAll($user = null)
	{
		if (!isset($user))
			return parent::getAll();

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

	public static function getAllSolvedBy($userid)
	{
		$em = EntityManager::getInstance();
		if (!is_int($userid))
			$userid = $userid->getId();

		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` AS c INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON c.id = s.challengeId WHERE s.userId = ? ORDER BY c.categoryName, c.id;', $userid);

		$challs = [];
		foreach ($data as $row)
			$challs[] = self::createFromData($row);
		return $challs;
	}

	protected function _preDelete()
	{
		$challid = $this->getId();
		$this->_db->query('UPDATE `' . User::TABLE_NAME . '` AS u INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON u.id = s.userId INNER JOIN `' . self::TABLE_NAME . '` AS c ON s.challengeId = c.id SET u.points = u.points - c.points WHERE c.id = ?;',
			$challid);
		$this->_db->query('DELETE FROM `' . self::USER_JOIN_TABLE . '` WHERE challengeId=?;',
			$challid);
	}

	protected function _postUpdate()
	{
		if (!isset($this->_changedValues['points']))
			return;
		$this->_db->query('UPDATE `' . User::TABLE_NAME . '` AS u INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON u.id = s.userId SET u.points = u.points + ? WHERE s.challengeId = ?;',
			$this->getPoints() - $this->_changedValues['points'], $this->getId());
	}
}
