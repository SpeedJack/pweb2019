<?php
namespace Pweb\Pages\Admin;

/**
 * @brief Represents the edit challenges admin page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class UsersPage extends AbstractPage
{
	/**
	 * @brief Shows the list of users with controls to promote/demote to
	 * admin and delete them.
	 */
	public function actionIndex()
	{
		$this->_setTitle(__('Admin: Edit Users'));
		$this->_addCss('search');
		//$this->_addCss('itembox');
		$this->_addCss('table');
		$this->_addJs('search');
		$this->_show('editusers');
	}
}
