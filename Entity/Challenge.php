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
}
