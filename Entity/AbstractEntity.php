<?php
namespace Pweb\Entity;


/**
 * @brief Represents an entity.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractEntity
{

// Protected Properties {{{
	/**
	 * @internal
	 * @var int $_entityId
	 * The entity's id. On entities saved to the database, this field is
	 * equal to the $_id field.
	 */
	protected $_entityId;
	/**
	 * @internal
	 * @var int $_id
	 * The id of the entity on the database.
	 */
	protected $_id;
	/**
	 * @internal
	 * @var array $_changedValues
	 * Associative array of changed columns of the entity.
	 */
	protected $_changedValues = [];
	/**
	 * @internal
	 * @var bool $_toDelete
	 * Indicates if the entity is marked from deletion from the database.
	 */
	protected $_toDelete = false;
	/**
	 * @internal
	 * @var bool $_toInsert
	 * Indicates if the entity must be inserted on the database.
	 */
	protected $_toInsert = false;
	/**
	 * @internal
	 * @var bool $_deleted
	 * Indicates if the entity has been deleted from the database.
	 */
	protected $_deleted = false;

	/**
	 * @internal
	 * @var Pweb::App $_app
	 * The application instance.
	 */
	protected $_app;
	/**
	 * @internal
	 * @var EntityManager $_em
	 * The Entity Manager instance.
	 */
	protected $_em;
	/**
	 * @internal
	 * @var Pweb::Db::AbstractAdapter $_db
	 * The database adapter.
	 */
	protected $_db;

	/**
	 * @internal
	 * @var array $_getters
	 * An array of getter functions for each property/column.
	 */
	protected $_getters = [];
// }}}

	/**
	 * @var string|null TABLE_NAME
	 * The name of the database's table associated with the entity.
	 */
	const TABLE_NAME = null;

	/**
	 * @brief Creates the entity.
	 *
	 * @param[in] int $entityId	The id of this entity.
	 * @return			The entity instance.
	 */
	public function __construct($entityId)
	{
		$this->setEntityId($entityId);
		$this->_app = \Pweb\App::getInstance();
		$this->_db = $this->_app->getDb();
		$this->_em = EntityManager::getInstance();
	}

// Getters {{{
	/**
	 * @brief Returns the fully qualified name of the entity.
	 *
	 * @retval string	The fully qualified name of the entity.
	 */
	public function getClassName()
	{
		return get_class($this);
	}

	/**
	 * @brief Returns the entity's id.
	 *
	 * @retval int		The id of this entity.
	 */
	public function getEntityId()
	{
		return $this->_entityId;
	}

	/**
	 * @brief Returns the id of this entity on the database.
	 *
	 * @retval int		The id of this entity on the database.
	 */
	public function getId()
	{
		return $this->_id;
	}
// }}}

// Setters {{{
	/**
	 * @internal
	 * @brief Sets a property which corresponds to a column in the database.
	 *
	 * @throws LogicException	If the property specified does not
	 * 				exists of if the entity has been
	 * 				deleted.
	 *
	 * @param[in] string $propertyName	The property/column name.
	 * @param[in] mixed $value		The property/column value.
	 */
	protected function _set($propertyName, $value)
	{
		$realName = "_$propertyName";
		if ($this->_deleted || $this->_toDelete)
			throw new \LogicException(
				__('Trying to set property %s in deleted entity %s.',
					$realName, $this->getClassName())
				);
		if (!property_exists($this, $realName))
			throw new \LogicException(
				__('Trying to set an non-existent property %s for entity %s.',
					$realName, $this->getClassName())
			);

		if (!isset($this->_changedValues[$propertyName]))
			$this->_changedValues[$propertyName] = $this->$realName;
		if ($this->_changedValues[$propertyName] === $value)
			unset($this->_changedValues[$propertyName]);

		$this->$realName = $value;
	}

	/**
	 * @brief Sets the entity's id.
	 *
	 * @param[in] int $entityId	The new id of this entity.
	 */
	public function setEntityId($entityId)
	{
		$this->_entityId = $entityId;
	}
// }}}

// Entity Methods {{{
	/**
	 * @brief Retrives an instance of this entity from the database with the
	 * specified id.
	 *
	 * @param[in] int $id	The id of the entity to search.
	 * @retval self|false	The entity retrived, or FALSE if the entity was
	 * 			not found.
	 */
	public static function getByid($id)
	{
		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchRow('SELECT * FROM `' . static::TABLE_NAME . '` WHERE id=?;', $id);
		return static::createFromData($data);
	}

	/**
	 * @brief Retrives all entities of the type of this instance from the
	 * database.
	 *
	 * @retval array	The array of entities retrived.
	 */
	public static function getAll()
	{
		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . static::TABLE_NAME . '`;');
		$entities = [];
		foreach ($data as $row)
			$entities[] = static::createFromData($row);
		return $entities;
	}

	public static function getAllPaged($orderBy, $page, $ascending = true, $perPage = null)
	{
		$perPage = isset($perPage) ? $perPage : $this->_app->config['default_per_page'];
		$db = \Pweb\App::getInstance()->getDb();
		$data = $db->fetchAll('SELECT * FROM `' . static::TABLE_NAME . "` ORDER BY $orderBy " . ($ascending ? 'ASC' : 'DESC') . " LIMIT $perPage OFFSET " . ($page - 1)*$perPage . ';');
		$entities = [];
		foreach ($data as $row)
			$entities[] = static::createFromData($row);
		return $entities;
	}

	public static function count()
	{
		$db = \Pweb\App::getInstance()->getDb();
		return $db->fetchColumn('SELECT COUNT(*) FROM `' . static::TABLE_NAME . '`;');
	}

	/**
	 * @brief Merges this entity with another entity of the same type.
	 *
	 * @param[in] self $entity	The entity to merge with this entity.
	 */
	public function merge(self $entity)
	{
		foreach ($this->_getters as $colName => $getter) {
			$propertyName = "_$colName";
			$this->$propertyName = isset($this->_changedValues[$colName])
				? $this->$propertyName : $entity->$getter();
		}
	}

	/**
	 * @brief Creates a new entity from the data passed as array.
	 *
	 * @param[in] array $data	Associative array of key-value pairs
	 * 				where the key is the property/column's
	 * 				name.
	 * @retval self|false		The entity created or FALSE if no data
	 * 				is provided.
	 */
	public static function createFromData(array $data)
	{
		if (empty($data))
			return false;
		$instance = new static(0);
		$instance->_fillData($data);
		return $instance;
	}

	/**
	 * @brief Marks this entity for deletion from the database.
	 */
	public function delete()
	{
		$this->_toDelete = true;
	}

	/**
	 * @brief Marks this entity for insertion on the database.
	 */
	public function insert()
	{
		$this->_toInsert = true;
	}
// }}}

// Entity Life-cycle {{{
	/**
	 * @internal
	 * @brief This function is executed before the entity is inserted on the
	 * database.
	 */
	protected function _preInsert() {}

	/**
	 * @internal
	 * @brief Inserts the entity on the database.
	 */
	protected function _insert()
	{
		$query = 'INSERT INTO `' . static::TABLE_NAME . '`(';
		$placeholders = '';
		$values = [];
		foreach ($this->_getters as $colName => $getter) {
			$query .= "$colName, ";
			$placeholders .= '?, ';
			$values[] = $this->$getter();
		}
		$placeholders = trimSuffix($placeholders, ', ');
		$query = trimSuffix($query, ', ') . ") VALUES($placeholders);";
		try {
			$this->_db->query($query, ...$values);
		} catch (\Pweb\Db\DuplicateKeyException $e) {
			if (!empty($this->_changedValues)) {
				$this->_preUpdate();
				$this->_update();
				$this->_postUpdate();
				$this->_changedValues = [];
			}
		}
	}

	/**
	 * @internal
	 * @brief This function is executed after the entity is inserted on the
	 * database.
	 */
	protected function _postInsert() {}

	/**
	 * @internal
	 * @brief This function is executed before the entity is deleted from
	 * the database.
	 */
	protected function _preDelete() {}

	/**
	 * @internal
	 * @brief Deletes the entity from the database.
	 */
	protected function _delete()
	{
		$this->_db->query('DELETE FROM `' . static::TABLE_NAME . '` WHERE id=?;',
			$this->_id);
		$this->_deleted = true;
	}

	/**
	 * @internal
	 * @brief This function is executed after the entity is deleted from the
	 * database.
	 */
	protected function _postDelete() {}

	/**
	 * @internal
	 * @brief This function is executed before the entity is updated on the
	 * database.
	 */
	protected function _preUpdate() {}

	/**
	 * @internal
	 * @brief Updates the entity on the database.
	 */
	protected function _update()
	{
		$query = 'UPDATE `' . static::TABLE_NAME . '` SET ';
		$realNames = [];
		foreach (array_keys($this->_changedValues) as $name) {
			$realName = "_$name";
			$values[] = $this->$realName;
			$query .= "$name = ?, ";
		}
		$query = trimSuffix($query, ', ') . ' WHERE id=?;';
		$values[] = $this->_id;
		$rowsAffected = $this->_db->query($query, ...$values);

	}

	/**
	 * @internal
	 * @brief This function is executed after the entity is updated on the
	 * database.
	 */
	protected function _postUpdate() {}

	/**
	 * @internal
	 * @brief This function is executed before the entity is saved (i.e.
	 * inserted, updated or deleted) on the database.
	 */
	protected function _preSave() {}

	/**
	 * @internal
	 * @brief Saves (i.e. inserts, deletes or updates) the entity on the
	 * database.
	 */
	public function save()
	{
		if ($this->_deleted || ($this->_toInsert && $this->_toDelete))
			goto ClearChangesAndExit;

		$this->_preSave();
		if ($this->_toInsert) {
			$this->_preInsert();
			$this->_insert();
			$this->_id = $this->_db->fetchColumn('SELECT LAST_INSERT_ID();');
			$this->_em->moveToSaved($this);
			$this->_postInsert();
			$this->_toInsert = false;
		} else if ($this->_toDelete) {
			$this->_preDelete();
			$this->_delete();
			$this->_postDelete();
			$this->_toDelete = false;
		} else if (!empty($this->_changedValues)) {
			$this->_preUpdate();
			$this->_update();
			$this->_postUpdate();
		}
		$this->_postSave();

	ClearChangesAndExit:
		$this->_changedValues = [];
	}

	/**
	 * @internal
	 * @brief This function is executed after the entity is saved (i.e.
	 * inserted, updated or deleted) on the database.
	 */
	protected function _postSave() {}
// }}}

// Protected Entity Methods {{{
	/**
	 * @internal
	 * @brief Fills the properties/columns of this entity with the values
	 * passed as array.
	 *
	 * @param[in] array $data	Associative array of key-value pairs
	 * 				where the key is the property/column's
	 * 				name.
	 */
	protected function _fillData(array $data)
	{
		foreach ($data as $name => $value) {
			$realName = "_$name";
			if (property_exists($this, $realName))
				$this->$realName = $value;
		}
		$this->_entityId = $this->_id;
	}
// }}}

}
