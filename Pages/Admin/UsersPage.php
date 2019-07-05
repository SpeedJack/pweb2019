<?php
namespace Pweb\Pages\Admin;

class UsersPage extends AbstractPage
{
	public function actionIndex()
	{
		$this->_setTitle(__('Admin: Edit Users'));
		$this->_addCss('search');
		$this->_addCss('itembox');
		$this->_addJs('search');
		$this->_show('editusers');
	}
}
