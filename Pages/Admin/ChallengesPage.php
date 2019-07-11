<?php
namespace Pweb\Pages\Admin;

/**
 * @brief Represents the edit challenges admin page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class ChallengesPage extends AbstractPage
{
	/**
	 * @brief Shows the list of challenges with controls to edit and delete
	 * them.
	 */
	public function actionIndex()
	{
		if (!$this->_visitor->isAdmin())
			$this->_app->redirectHome();

		$challs = $this->_em->getAllFromDb('Challenge');
		$challs = $challs === false ? [] : (is_array($challs) ? $challs : [$challs]);

		$this->_setTitle(__('Admin: Edit Challenges'));
		$this->_addCss('table');
		$this->_addJs('form');
		$this->_addJs('confirmbox');
		$this->_addJs('challenges-edit');
		$this->_addCss('challenge-edit-form');
		$this->_show('challenges-table', ['challenges' => $challs]);
	}

	private function _getChallengeParam()
	{
		$challId = $this->_visitor->param('id', 'POST');
		if (!is_numeric($challId))
			return false;
		$challId = intval($challId);
		if ($challId === 0)
			return false;
		return $this->_em->getFromDb('Challenge', $challId);
	}

	public function actionDelete()
	{
		if (!$this->_visitor->isAdmin())
			return;
		$chall = $this->_getChallengeParam();
		if ($chall === false)
			return;

		$chall->delete();
	}

	public function actionEdit()
	{
		if (!$this->_visitor->isLoggedIn()) {
			$this->_redirectAjax('login');
			return;
		}
		if (!$this->_visitor->isAdmin()) {
			$this->_showMEssage(__('Insufficient privileges'),
				__('Challenges can be edited only by admins.'));
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

		$this->_setTitle(__('Edit Challenge: %s', $chall->getName()));
		$this->_showModal('challenge-edit-form',
			null,
			['challenge' => $chall]);
	}
}
