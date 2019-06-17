<?php
namespace Pweb;

/**
 * @brief Represents a singleton object.
 *
 * You can extend this class to create a singleton class.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractSingleton
{
	/**
	 * @internal
	 * @var array	$_instances
	 * Container for all singleton instances.
	 */
	private static $_instances = [];

	/**
	 * @brief Returns the singleton instance of the class.
	 *
	 * @param mixed $params	List of parameters to pass to the class
	 * 			constructor if the instance needs to be created.
	 * @retval object	The singleton instance.
	 */
	public static function getInstance(...$params)
	{
		$class = get_called_class();
		if (!isset(self::$_instances[$class]))
			self::$_instances[$class] = new $class(...$params);
		return self::$_instances[$class];
	}
}
