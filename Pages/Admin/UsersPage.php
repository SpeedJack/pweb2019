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
		if (!$this->_visitor->isAdmin())
			$this->_app->redirectHome();
		$this->_setTitle(__('Admin: Edit Users'));
		$this->_addCss('search');
		$this->_addCss('table');
		$this->_addJs('search');
		$this->_addJs('users-edit');
		$this->_show('multitemplate', ['templates' => ['users-search', 'users-table']]);
	}

	/**
	 * @brief Returns a User entity from the userid POST parameter.
	 *
	 * @retval Pweb::Entity::User	The User entity.
	 */
	private function _getUserParam()
	{
		$userId = $this->_visitor->param('userid', 'POST');
		if (empty($userId))
			return false;
		$userId = intval($userId);
		if ($userId === 0)
			return false;
		return $this->_em->getFromDb('User', $userId);
	}

	/** @brief Deletes a user. */
	public function actionDelete()
	{
		if (!$this->_visitor->isAdmin())
			return;
		$user = $this->_getUserParam();
		if ($user === false)
			return;
		/* only super admins can delete other admins; super admins
		 * are protected from deletion */
		if (!$user->isSuperAdmin() &&
			(!$user->isAdmin() || $this->_visitor->isSuperAdmin()))
			$user->delete();
	}

	/**
	 * @internal
	 * @brief If the user (which user id is provided by POST parameter) is
	 * admin, demotes it; If it is a standard user, promotes it.
	 */
	private function _toogleAdmin()
	{
		if (!$this->_visitor->isAdmin())
			return;
		$user = $this->_getUserParam();
		if ($user === false)
			return;
		if ($user->isAdmin()) {
			/* super admins are protected from demotion; only
			 * super admins can demote other admins */
			if (!$user->isSuperAdmin() && $this->_visitor->isSuperAdmin())
				$user->demoteAdmin();
		} else {
			$user->promoteAdmin();
		}
	}

	/** @brief Promotes a user to admin. */
	public function actionPromote()
	{
		$this->_toogleAdmin();
	}

	/** @brief Promotes an admin to user. */
	public function actionDemote()
	{
		$this->_toogleAdmin();
	}
}
