<?php
namespace Pweb\Pages;

/**
 * @brief Represents the challenges page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class ChallengesPage extends AbstractPage
{
	/**
	 * @brief Lists all challenges available.
	 */
	public function actionIndex()
	{
		if (!$this->_visitor->isLoggedIn())
			$this->_app->reroute('login');

		$challs = $this->_em->getAllFromDb('Challenge');

		$this->_setTitle(__('Challenges'));
		$this->_show('challenges-list', ['challenges' => $challs]);
	}
}
