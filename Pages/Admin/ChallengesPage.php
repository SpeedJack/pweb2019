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
		$this->_addJs('confirmbox');
		$this->_addJs('challenges-edit');
		$this->_show('challenges-table', ['challenges' => $challs]);
	}

	private function _getChallengeParam()
	{
		$challId = $this->_visitor->param('id', 'POST');
		if (empty($challId))
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
}
