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
	/**
	 * @brief Show the user's leaderboard.
	 */
	public function actionIndex()
	{
		$this->_setTitle(__('Ranking'));
		$this->_show('ranking');
	}
}
