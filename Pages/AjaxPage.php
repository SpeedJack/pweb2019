<?php
namespace Pweb\Pages;

/**
 * @brief Page used by ajax to query various infos.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class AjaxPage extends AbstractPage
{
	/**
	 * @brief This page should be accessed only by Ajax. Visiting th
	 * actionIndex redirects to the home.
	 */
	public function actionIndex()
	{
		$this->_app->redirectHome();
	}

	/**
	 * @brief Replies with a JSON with infos about username's validity.
	 */
	public function actionValidateUsername()
	{
		$username = $this->_visitor->param('username', 'POST');
		$reply = [
			'valid' => false,
			'fieldName' => 'username',
			'value' => $username
		];
		if (empty($username)) {
			$reply['message'] = __('Username can not be empty');
			$this->_replyJson($reply);
			return;
		}

		switch (\Pweb\Entity\User::isValidUsername($username, true)) {
		case \Pweb\Entity\User::INVALID:
			$reply['message'] = __('Username must contains at least 5 characters and no more than 32. It can contains only letters (lowercase and uppercase), numbers and . (dot), _ (underscore), - (minus).');
			break;
		case \Pweb\Entity\User::ALREADY_IN_USE:
			$reply['message'] = __('This username is already in use. Please choose another username.');
			break;
		case \Pweb\Entity\User::VALID:
			$reply['message'] = '';
			$reply['valid'] = true;
		}
		$this->_replyJson($reply);
	}

	/**
	 * @brief Replies with a JSON with infos about email's validity.
	 */
	public function actionValidateEmail()
	{
		$email = $this->_visitor->param('email', 'POST');
		$reply = [
			'valid' => false,
			'fieldName' => 'email',
			'value' => $email
		];
		if (empty($email)) {
			$reply['message'] = __('Email can not be empty');
			$this->_replyJson($reply);
			return;
		}

		switch (\Pweb\Entity\User::isValidEmail($email, true)) {
		case \Pweb\Entity\User::INVALID:
			$reply['message'] = __('Invalid email address. Please insert a valid email address.');
			break;
		case \Pweb\Entity\User::ALREADY_IN_USE:
			$reply['message'] = __('This email is already in use. If you wish to log in, <a href="%s">click here!</a>',
				$this->_app->buildLink('login'));
			break;
		case \Pweb\Entity\User::VALID:
			$reply['message'] = '';
			$reply['valid'] = true;
		}
		$this->_replyJson($reply);
	}
}
