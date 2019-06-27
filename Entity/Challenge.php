<?php
namespace Pweb\Entity;

class Challenge extends AbstractEntity
{
	protected $_name;
	protected $_displayOrder;
	protected $_categoryOrder;
	protected $_categoryName;
	protected $_points;
	protected $_body;
	protected $_attachmentName;
	protected $_attachmentHash;
	protected $_getters = [
		'name' => 'getName',
		'DisplayOrder' => 'getDisplayOrder',
		'categoryOrder' => 'getCategoryOrder',
		'categoryName' => 'getCategoryName',
		'points' => 'getPoints',
		'body' => 'getBody',
		'attachmentName' => 'getAttachmentName',
		'attachmentHash' => 'getAttachmentHash'
	];

	public const TABLE_NAME = 'challenges';
	public const USER_JOIN_TABLE = 'solvedChallenges';

	public function getName()
	{
		return $this->_name;
	}

	public function getDisplayOrder()
	{
		return $this->_displayOrder;
	}

	public function getCategoryOrder()
	{
		return $this->_categoryOrder;
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

	public function setCategory($categoryName, $categoryOrder = 0)
	{
		$this->_set('categoryName', $categoryName);
		$this->_set('categoryOrder', $categoryOrder);
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
		foreach ($data as $row) {
			$chall = self::createFromData($row);
			if (isset($row['userId']) && $row['userId'] === $userid)
				$user->addSolvedChallenge($chall);
			$challs[] = $chall;
		}

		return $challs;
	}

	public static function getAllSolvedBy($user)
	{
		$em = EntityManager::getInstance();
		if (is_int($user))
			$user = $em->getFromDb('User', $user);
		$userid = $user->getId();

		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . self::TABLE_NAME . '` AS c INNER JOIN `' . self::USER_JOIN_TABLE . '` AS s ON c.id = s.challengeId WHERE s.userId = ? ORDER BY c.categoryName, c.id;', $userid);

		$challs = [];
		foreach ($data as $row)
			$challs[] = self::createFromData($row);
		return $challs;
	}
}
