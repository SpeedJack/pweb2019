<?php
namespace Pweb\Pages\Admin;

/**
 * @brief Represents an admin page.
 *
 * This class overloads some methods of the base AbstractPage class to try
 * loading templates/css/js from the 'admin' subfolder.
 *
 * Note that with the 'show_all_exceptions' config option and Xdebug enabled,
 * this class may trigger various exception used for flow control.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
abstract class AbstractPage extends \Pweb\Pages\AbstractPage
{

// Protected Methods {{{
	/**
	 * @internal
	 * @brief Loads a template.
	 *
	 * This function first searches the template file in the admin
	 * subdirectory. If not found, searches it in the standard template
	 * directory.
	 *
	 * @param[in] string $templateName	The name of the template to
	 * 					load, without the extension.
	 * @param[in] array $params		Associative array of parameters
	 * 					to pass to the template.
	 * @retval bool				TRUE if the template was found;
	 * 					FALSE otherwise.
	 */
	protected function _loadTemplate($templateName, array $params = [])
	{
		if (!$this->_visitor->isAdmin())
			throw new \Exception(__('You don\'t have the rights to view this page.'));
		$success = parent::_loadTemplate("admin/$templateName", $params);
		if (!$success)
			return parent::_loadTemplate($templateName, $params);
		return $success;
	}

	/**
	 * @internal
	 * @brief Adds a CSS to the page.
	 *
	 * This function first searches the CSS file in the admin subdirectory.
	 * If not found, searches it in the standard css directory.
	 *
	 * @throws InvalidArgumentException	If the CSS file specified does
	 * 					not exists.
	 *
	 * @param[in] string $cssName	The CSS file name, without extension.
	 */
	protected function _addCss($cssName)
	{
		try {
			parent::_addCss("admin/$cssName");
		} catch (\InvalidArgumentException $e) {
			parent::_addCss($cssName);
		}
	}

	/**
	 * @internal
	 * @brief Adds a JavaScript file to the page.
	 *
	 * This function first searches the JavaScript file in the admin
	 * subdirectory. If not found, searches it in the standard JavaScript
	 * directory.
	 *
	 * @throws InvalidArgumentException	If the JavaScript file specified
	 * 					does not exists.
	 *
	 * @param[in] string $scriptName	The JavaScript file name,
	 * 					without extension.
	 * @param[in] bool $defer		Specifies if the script must be
	 * 					loaded when the page has
	 * 					finished parsing (improves
	 * 					performance).
	 */
	protected function _addJs($scriptName, $defer = true)
	{
		try {
			parent::_addJs("admin/$scriptName", $defer);
		} catch (\InvalidArgumentException $e) {
			parent::_addJs($scriptName, $defer);
		}
	}
// }}}

}
