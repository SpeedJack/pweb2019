<?php
namespace Pweb\Pages;

/**
 * @brief Represents the ranking page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class RankingPage extends AbstractPage
{
	/** @brief Shows the user's leaderboard. */
	public function actionIndex()
	{
		$perPage = $this->_visitor->param('pp');
		$page = $this->_visitor->param('p');

		if (is_numeric($perPage))
			$perPage = intval($perPage);
		else
			$perPage = null;
		if ($perPage === null || $perPage <= 0)
			$perPage = $this->_app->config['default_per_page'];

		if (is_numeric($page))
			$page = intval($page);
		else
			$page = 1;
		$page = $page > 0 ? $page : 1;

		$orderBy = [
			['column' => 'points', 'ascending' => false],
			['column' => 'username', 'ascending' => true]
		];
		$users = $this->_em->getAllPagedFromDb('User', $orderBy, $page, $perPage);
		$this->_setTitle(__('Ranking'));
		if ($users === false) {
			$this->_show('message', ['message' => __('No users to show.')]);
			return;
		}

		$users = is_array($users) ? $users : [ $users ];
		$totalUsers = $this->_em->countDbEntities('User');
		$this->_addCss('table');
		$this->_show('ranking', [
			'startingPos' => ($page - 1)*$perPage + 1,
			'users' => $users,
			'totalUsers' => $totalUsers,
			'page' => $page,
			'perPage' => $perPage,
			'totalPages' => ceil($totalUsers/$perPage)
		]);
	}
}
