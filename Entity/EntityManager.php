<?php
namespace Pweb\Entity;

require_once 'string-functions.php';
require_once 'array-functions.php';

/**
 * @brief The Entity Manager class.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class EntityManager extends \Pweb\AbstractSingleton
{

// Protected Properties {{{
	/**
	 * @internal
	 * @var	array $_savedEntities
	 * Array of entities fetched from the database or that will be saved to
	 * the database.
	 */
	protected $_savedEntities = [];
	/**
	 * @internal
	 * @var	array $_cachedEntities
	 * Array of entities that are created by the application and are
	 * intended to be deleted when the application exits.
	 */
	protected $_cachedEntities = [];
// }}}

	/**
	 * @internal
	 * @brief This class must be instantiated using getInstance().
	 */
	protected function __construct() { }

	/**
	 * @brief Flushes all the saved entities to the database and destroys
	 * the EntityManager.
	 */
	public function __destruct()
	{
		$this->flush();
	}

// Public Methods {{{
	/**
	 * @brief Creates the specified entity, or returns it from the cache if
	 * it was already created.
	 *
	 * @param[in] string $entityName	The entity's name to create.
	 * @param[in] int $entityId		The entity's id.
	 * @param[in] mixed $params		The list of parameters to pass
	 * 					to the entity's constructor.
	 * @retval AbstractEntity		The entity created.
	 */
	public function create($entityName, $entityId = 0, ...$params)
	{
		$found = $this->findCached($entityName, $entityId);
			if ($found !== false)
				return $found;
		$entityName = $this->_getEntityFullName($entityName);
		$entity = new $entityName($entityId, ...$params);
		$entity->insert();
		if (!isset($this->_cachedEntities[$entityName]))
			$this->_cachedEntities[$entityName] = [];
		$this->_cachedEntities[$entityName][$entityId] = $entity;
		return $entity;
	}

	/**
	 * @brief Creates the specified entity assigning to it the next
	 * available entity id.
	 *
	 * @param[in] string $entityName	The entity's name to create.
	 * @param[in] mixed $params		The list of parameters to pass
	 * 					to the entity's constructor.
	 * @retval AbstractEntity		The entity created.
	 */
	public function createNew($entityName, ...$params)
	{
		$entityName = $this->_getEntityFullName($entityName);
		if (!isset($this->_cachedEntities[$entityName]))
			$entityId = 0;
		else
			$entityId = array_key_last($this->_cachedEntities[$entityName]) + 1;
		return $this->create($entityName, $entityId, ...$params);
	}

	/**
	 * @brief Returns a cached entity.
	 *
	 * @param[in] string $entityName	The name of the entity to find.
	 * @param[in] int $entityId		The id of the entity to find.
	 * @retval AbstractEntity|false		The entity found or FALSE if no
	 * 					entity was found.
	 */
	public function findCached($entityName, $entityId = 0)
	{
		$entityName = $this->_getEntityFullName($entityName);
		if (isset($this->_cachedEntities[$entityName])
			&& isset($this->_cachedEntities[$entityName][$entityId]))
			return $this->_cachedEntities[$entityName][$entityId];
		return false;
	}

	/**
	 * @brief Returns a saved entity.
	 *
	 * @param[in] string $entityName	The name of the entity to find.
	 * @param[in] int $entityId		The id of the entity to find.
	 * @retval AbstractEntity|false		The entity found or FALSE if no
	 * 					entity was found.
	 */
	public function findSaved($entityName, $entityId)
	{
		$entityName = $this->_getEntityFullName($entityName);
		if (isset($this->_savedEntities[$entityName])
			&& isset($this->_savedEntities[$entityName][$entityId]))
			return $this->_savedEntities[$entityName][$entityId];
		return false;
	}

	/**
	 * @brief Fetches an entity from the database.
	 *
	 * @param[in] string $entityName	The name of the entity to fetch.
	 * @param[in] int $entityId		The id of the entity to fetch.
	 * @retval AbstractEntity|false		The entity fetched or false if
	 * 					the entity was not found.
	 */
	public function getFromDb($entityName, $entityId)
	{
		$entityName = $this->_getEntityFullName($entityName);
		$found = $this->findSaved($entityName, $entityId);
		if ($found !== false)
			return $found;
		return $this->getFromDbBy($entityName, 'getById', $entityId);
	}

	/**
	 * @brief Fetches all entities of the specified type from the database.
	 *
	 * @param[in] string $entityName	The name of the entity to fetch.
	 * @retval array|false			The entities fetched or FALSE if
	 * 					no entity was found.
	 */
	public function getAllFromDb($entityName)
	{
		$entityName = $this->_getEntityFullName($entityName);
		return $this->getFromDbBy($entityName, 'getAll');
	}

	/**
	 * @brief Fetches one or more entities from the database using the
	 * specified method.
	 *
	 * @throws BadMethodCallException	If the $byFunction method does
	 * 					not exists.
	 *
	 * @param[in] string $entityName	The name of the entity to fetch.
	 * @param[in] string $byFunction	The name to the method to call.
	 * @param[in] mixed $params		The parameters to pass to the
	 * 					method.
	 * @retval AbstractEntity|array|false	The entities fetched or FALSE if
	 * 					no entity was found.
	 */
	public function getFromDbBy($entityName, $byFunction, ...$params)
	{
		$entityName = $this->_getEntityFullName($entityName);
		if (!method_exists($entityName, $byFunction))
			throw new \BadMethodCallException(
				__('The method %s::%s does not exists.',
					$entityName, $byFunction)
			);
		$entities = $entityName::$byFunction(...$params);
		if (empty($entities))
			return false;
		$entities = is_array($entities) ? $entities : [ $entities ];
		foreach ($entities as $key => $entity) {
			$entityId = $entity->getEntityId();
			$entityName = $entity->getClassName();
			if (!isset($this->_savedEntities[$entityName]))
				$this->_savedEntities[$entityName] = [];
			if (isset($this->_savedEntities[$entityName][$entityId])) {
				$this->_savedEntities[$entityName][$entityId]->merge($entity);
				$entities[$key] = $this->_savedEntities[$entityName][$entityId];
			} else {
				$this->addToSaved($entity);
			}
		}
		return count($entities) > 1 ? $entities : array_pop($entities);
	}

	/**
	 * @brief Adds an entity to the $_savedEntities array.
	 *
	 * @param[in] AbstractEntity $entity	The entity.
	 */
	public function addToSaved($entity)
	{
		$this->_assertValidEntity($entity);
		$entityId = $entity->getId();
		$entityName = $entity->getClassName();
		if (isset($this->_savedEntities[$entityName])
			&& isset($this->_savedEntities[$entityName][$entityId]))
			throw new \LogicException(
				__('Can not add entity %s with id %d since another entity of the same type already uses this id.',
				$entityName, $entityId)
			);
		if (!isset($this->_savedEntities[$entityName]))
			$this->_savedEntitites[$entityName] = [];
		$this->_savedEntities[$entityName][$entityId] = $entity;
		$entity->setEntityId($entityId);
	}

	/**
	 * @brief Moves an entity from the $_cachedEntities to the
	 * $_savedEntities array.
	 *
	 * @throws LogicException	If $entity does not exists in the
	 * 				$_cachedEntities array.
	 *
	 * @param[in] AbstractEntity $entity	The entity.
	 */
	public function moveToSaved($entity)
	{
		$this->_assertValidEntity($entity);
		$entityId = $entity->getEntityId();
		$entityName = $entity->getClassName();
		if (!isset($this->_cachedEntities[$entityName])
			|| !isset($this->_cachedEntities[$entityName][$entityId]))
			throw new \LogicException(
				__('%s\'s entity id has changed unexpectedly.')
			);
		$this->addToSaved($entity);
		unset($this->_cachedEntities[$entityName][$entityId]);
		$entity->insert();
	}

	/**
	 * @brief Flushes all the saved entities to the database.
	 *
	 * If $entityName and $entityId are specified, it saves only the
	 * specified entity. If only $entityName is specified, it saves all
	 * entities of the specified type.
	 *
	 * @param[in] string|null $entityName	The name of the entity to
	 * 					flush.
	 * @param[in] string|null $entityId	The id of the entity to flush.
	 */
	public function flush($entityName = null, $entityId = null)
	{
		if (isset($entityName)) {
			$entityName = $this->_getEntityFullName($entityName);
			if (!isset($this->_savedEntities[$entityName]))
				return;
			if (!isset($entityId))
				foreach ($this->_savedEntities[$entityName] as $entity)
					$entity->save();
			else if (isset($this->_savedEntities[$entityName][$entityId]))
				$this->_savedEntities[$entityName][$entityId]->save();
			return;
		}
		foreach ($this->_savedEntities as $entities)
			foreach ($entities as $entity)
				$entity->save();
	}
// }}}

// Private Methods {{{
	/**
	 * @internal
	 * @brief Asserts that the specified entity is a valid entity.
	 *
	 * @throws InvalidArgumentException	If $entity is not a valid
	 * 					entity.
	 *
	 * @param[in] object|string $entity	The entity or entity's name to
	 * 					check.
	 */
	private function _assertValidEntity($entity)
	{
		if (is_string($entity) && !startsWith($entity, __NAMESPACE__))
			$entity = __NAMESPACE__ . "\\$entity";
		$parentName = __NAMESPACE__ . "\\AbstractEntity";
		if (!is_subclass_of($entity, $parentName))
			throw new \InvalidArgumentException(
				__('Invalid Entity %s.', $entity)
			);
	}

	/**
	 * @internal
	 * @brief Returns the entity's fully qualified name.
	 *
	 * @param[in] object|string $entity	The entity or entity's name.
	 * @retval string			The entity's fully qualified
	 * 					name.
	 */
	private function _getEntityFullName($entity)
	{
		$this->_assertValidEntity($entity);
		if (is_object($entity))
			$entityName = $entity->getClassName();
		else if (!startsWith($entity, __NAMESPACE__))
			$entityName = __NAMESPACE__ . "\\$entity";
		else
			$entityName = $entity;
		return $entityName;
	}

	/**
	 * @internal
	 * @brief Returns the entity's short name (i.e. the name of the class).
	 *
	 * @param[in] object|string $entity	The entity or entity's name.
	 * @retval string			The entity's short name.
	 */
	private function _getEntityShortName($entity)
	{
		$this->_assertValidEntity($entity);
		if (is_object($entity))
			$entityName = $entity->getClassName();
		else
			$entityName = $entity;
		return trimPrefix($entityName, __NAMESPACE__ . '\\');
	}
// }}}

}
