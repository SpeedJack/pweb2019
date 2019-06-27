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

		$challs = $this->_em->getAllFromDb('Challenge', $this->_visitor->user);

		$this->_setTitle(__('Challenges'));
		$this->_addCss('challenges');
		$this->_addJs('accordion');
		$this->_show('challenges-list', ['challenges' => $challs, 'user' => $this->_visitor->user]);
	}

	public function actionOpen()
	{
		if (!$this->_visitor->isLoggedIn())
			$this->_app->redirect('login');

		$challid = $this->_visitor->param('challid');
		$chall = $this->_em->getFromDb('Challenge', $challid);
	}
}
