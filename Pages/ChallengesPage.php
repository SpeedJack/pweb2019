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
	/** @brief Lists all challenges available. */
	public function actionIndex()
	{
		if (!$this->_visitor->isLoggedIn())
			$this->_app->reroute('login');

		$challs = $this->_em->getAllFromDb('Challenge', $this->_visitor->user);

		$this->_setTitle(__('Challenges'));
		$this->_addCss('challenges');
		$this->_addJs('accordion');
		$this->_addJs('challenge');
		$this->_addJs('form');
		$this->_show('challenges-list', ['challenges' => $challs, 'user' => $this->_visitor->user]);
	}

	/** @brief Opens a challenge in a modal window. */
	public function actionOpen()
	{
		if (!$this->_visitor->isLoggedIn()) {
			$this->_redirectAjax('login');
			return;
		}

		$challid = $this->_visitor->param('cid');
		if (!is_numeric($challid)) {
			$this->_showMessage(__('Invalid challenge'),
				__('The requested challenge id is not valid.'));
			return;
		}
		$challid = intval($challid);

		$chall = $this->_em->getFromDb('Challenge', $challid);

		if ($chall === false) {
			$this->_showMessage(__('Invalid challenge'),
				__('The requested challenge could not be found.'));
			return;
		}

		if ($this->_visitor->user->hasSolvedChallenge($chall)) {
			$this->_showMessage(__('Challenge solved'),
				__('You have already solved this challenge. Please choose another one.'));
			return;
		}

		$this->_setTitle(__('Challenge: %s', $chall->getName()));
		$this->_showModalWithFooter('challenge', 'challenge-form',
			null, ['chall' => $chall]);
	}

	/** @brief Solves a challenge for the visitor. */
	public function actionSolve()
	{
		if (!$this->_visitor->isLoggedIn()) {
			$this->_redirectAjax('login');
			return;
		}

		$challid = $this->_visitor->param('challid');
		$challflag = $this->_visitor->param('challflag');

		if (!is_numeric($challid)) {
			$this->_reply('message',
				['message' => __('<span class="color-blue">Invalid challenge. Please reload the page e try again.</span>')]);
			return;
		}
		$challid = intval($challid);

		$chall = $this->_em->getFromDb('Challenge', $challid);
		if ($chall === false) {
			$this->_reply('message',
				['message' => __('<span class="color-blue">The requested challenge could not be found. Please reload the page and try again.</span>')]);
			return;
		}

		switch ($this->_visitor->user->solveChallenge($challid, $challflag)) {
		case \Pweb\Entity\User::WRONG_FLAG:
			$this->_reply('message', ['message' => __('<span class="color-red">Wrong flag!</span>')]);
			return;
		case \Pweb\Entity\User::ALREADY_SOLVED:
			$this->_reply('message', ['message' => __('<span class="color-green">You have already solved this challenge.</span>')]);
			return;
		}

		$this->_reply('message', ['message' => __('<span class="color-green">Correct flag. You solved this challenge!</span>')]);
	}
}
