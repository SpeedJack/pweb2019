<?php
namespace Pweb\Pages\Admin;

/**
 * @brief Page used by ajax to query various infos.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class AjaxPage extends AbstractPage
{
	/**
	 * @brief This page should be accessed only by Ajax. Visiting the
	 * actionIndex redirects to the home.
	 */
	public function actionIndex()
	{
		$this->_app->redirectHome();
	}

	/**
	 * @brief Searches for users and returns the result as HTML table data.
	 */
	public function actionSearchUsers()
	{
		if (!$this->_visitor->isAdmin())
			return;
		$searchBy = $this->_visitor->param('search-by', 'POST');
		$searchText = $this->_visitor->param('search-text', 'POST');
		if (strlen($searchText) < 3)
			$this->_reply('usertabledata', ['users' => []]);
		switch ($searchBy) {
		case 'email':
			$users = $this->_em->getFromDbBy('User', 'getAllByEmailLike', $searchText);
			break;
		default:
			$users = $this->_em->getFromDbBy('User', 'getAllByUsernameLike', $searchText);
		}
		$users = $users === false ? [] : (is_array($users) ? $users : [ $users ]);
		$this->_reply('usertabledata', ['users' => $users]);
	}
}
