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

		$categories = \Pweb\Entity\Challenge::getAllCategories();

		$this->_setTitle(__('Edit Challenge: %s', $chall->getName()));
		$this->_showModal('challenge-form', null,
			['challenge' => $chall, 'categories' => $categories]);
	}

	public function actionCreate()
	{
		if (!$this->_visitor->isLoggedIn()) {
			$this->_redirectAjax('login');
			return;
		}
		if (!$this->_visitor->isAdmin()) {
			$this->_showMEssage(__('Insufficient privileges'),
				__('New challenges can be created only by admins.'));
			return;
		}

		$categories = \Pweb\Entity\Challenge::getAllCategories();

		$this->_setTitle(__('Create Challenge'));
		$this->_showModal('challenge-form', null,
			['challenge' => false, 'categories' => $categories]);
	}

	public function actionSave()
	{
		if (!$this->_visitor->isAdmin())
			return;

		$create = false;
		$id = $this->_visitor->param('challid', 'POST');
		$name = $this->_visitor->param('challname', 'POST');
		$category = $this->_visitor->param('challcategory', 'POST');
		$flag = $this->_visitor->param('challflag', 'POST');
		$points = $this->_visitor->param('challpoints', 'POST');
		$body = $this->_visitor->param('challbody', 'POST');

		if ($id === null) {
			$chall = $this->_em->createNew('Challenge');
			$create = true;
		} else if (is_numeric($id)) {
			$id = intval($id);
			$chall = $this->_em->getFromDb('Challenge', $id);
		} else {
			$this->_reply('message',
				['message' =>__('<span class="color-red">The requested challenge id is not valid.</span>')]);
			return;
		}

		if ($chall === false) {
			$this->_reply('message',
				['message' =>__('<span class="color-red">The requested challenge can not be found.</span>')]);
			return;
		}

		if (!$chall->setName($name)) {
			$this->_reply('message',
				['message' => __('<span class="color-red">Challenge name is too long. The name of the challenge can be long 32 characters at most.</span>')]);
			goto doNotSave;
		}

		if (!$chall->setCategory($category)) {
			$this->_reply('message',
				['message' => __('<span class="color-red">Category name is too long. The name of the category can be long 32 characters at most.</span>')]);
			goto doNotSave;
		}

		if (!$chall->setFlag($flag)) {
			$this->_reply('message',
				['message' => __('<span class="color-red">Flag must match the following regex and can not be more than %s characters long: %s.</span>',
				$this->_app->config['form_validation']['flag_maxlength'],
				$this->_app->config['form_validation']['flag_regex'])]);
			goto doNotSave;
		}

		$points = intval($points);
		if (!$chall->setPoints($points)) {
			$this->_reply('message',
				['message' => __('<span class="color-red">Challenge\'s points must be a valid positive non-zero integer.</span>')]);
			goto doNotSave;
		}

		if (!$chall->setBody($body)) {
			$this->_reply('message',
				['message' => __('<span class="color-red">Challenge\'s body text can not be empty.</span>')]);
			goto doNotSave;
		}

		if ($create)
			$chall->save();
		$this->_reply('message', ['message' => __('<span class="color-green">Saved!</span>')]);
		return;
	doNotSave:
		$this->_em->doNotSave($chall);
	}
}
