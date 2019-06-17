<?php
namespace Pweb;

/**
 * @brief Thrown when the page requested from the user does not exists.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class InvalidRouteException extends \Exception
{

// Public Properties {{{
	/**
	 * @var string $pageName
	 * Name of the page of the invalid route.
	 */
	public $pageName;
	/**
	 * @var string $actionName
	 * Name of the action of the invalid route.
	 */
	public $actionName;
// }}}

	/**
	 * @brief Creates a new InvalidRouteException.
	 *
	 * @param string $pageName		Name of the page of the invalid
	 * 					route.
	 * @param string $actionName		Name of the action of the
	 * 					invalid route.
	 * @param Exception|null $previous	Previous exception if nested
	 * 					exception.
	 * @return				The InvalidRouteException
	 * 					instance.
	 */
	public function __construct($pageName, $actionName, \Exception $previous = null)
	{
		$this->pageName = $pageName;
		$this->actionName = $actionName;
		parent::__construct(__("Invalid route: the action '%s'::'%s' does not exists.",
			$pageName, $actionName), 404, $previous);
	}
}
