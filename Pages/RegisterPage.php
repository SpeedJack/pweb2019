<?php
namespace Pweb\Pages;

/**
 * @brief Represents the registration page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class RegisterPage extends AbstractPage
{
	/**
	 * @brief Shows the registration form.
	 */
	public function actionIndex()
	{
		if ($this->_visitor->isLoggedIn())
			$this->_app->redirectHome();
		$this->_setTitle(__('Register an account'));
		$this->_addCss('login-register-form');
		$this->_addJs('form');
		$this->_addJs('form-validation');
		$this->_show('register-form');
	}

	/**
	 * @brief Registers a new user with the data provided by the
	 * registration form.
	 */
	public function actionRegister()
	{
		if ($this->_visitor->isLoggedIn())
			$this->_app->redirectHome();

		$username = $this->_visitor->param('username', 'POST');
		$email = $this->_visitor->param('email', 'POST');
		$password = $this->_visitor->param('password', 'POST');
		$passwordAgain = $this->_visitor->param('password-again', 'POST');

		if (empty($username) || empty($email)
			|| empty($password) || empty($passwordAgain)) {
			$this->_showMessage(__('Error'),
				__('Please, fill out all fields.'));
			return;
		}
		if ($password !== $passwordAgain) {
			$this->_showMessage(__('Wrong password'),
				__('Passwords do not match.'));
			return;
		}

		$user = $this->_em->createNew('User');

		if (!$this->_userSetUsername($user, $username)
			|| !$this->_userSetEmail($user, $email))
			return;
		if (!$user->setPassword($password)) {
			$this->_showMessage(__('Password too short'),
				__('Password must be at least %s characters long.',
					$this->_app->config['min_password_length']));
			return;
		}

		$user->save();

		$this->_showMessage(__('Account Created!'),
			__('Account created. <a href="%s">Log In!</a>',
				$this->_app->buildLink('login')),
			$this->_app->buildAbsoluteLink('login'));
	}

// Private Methods {{{
	/**
	 * @internal
	 * @brief Sets the new user's username with the one provided. Shows the
	 * user a message in case of failure.
	 *
	 * @param[in] User $user	The User entity.
	 * @param[in] string $username	The username to set.
	 * @retval bool			TRUE if the username was set
	 * 				successfully; FALSE otherwise.
	 */
	private function _userSetUsername($user, $username) {
		switch ($user->setUsername($username)) {
		case \Pweb\Entity\User::INVALID:
			$this->_showMessage(__('Invalid username'),
				__('Username must contains at least 5 characters and no more than 32. It can contains only letters (lowercase and uppercase), numbers and . (dot), _ (underscore), - (minus).'));
			return false;
		case \Pweb\Entity\User::ALREADY_IN_USE:
			$this->_showMessage(__('Username already in use'),
				__('This username is already in use. Please choose another username.'));
			return false;
		}
		return true;
	}

	/**
	 * @internal
	 * @brief Sets the new user's email with the one provided. Shows the
	 * user a message in case of failure.
	 *
	 * @param[in] User $user	The User entity.
	 * @param[in] string $email	The email to set.
	 * @retval bool			TRUE if the email was set successfully;
	 * 				FALSE otherwise.
	 */
	private function _userSetEmail($user, $email) {
		switch($user->setEmail($email)) {
		case \Pweb\Entity\User::INVALID:
			$this->_showMessage(__('Invalid email'),
				__('Invalid email address. Please insert a valid email address.'));
			return false;
		case \Pweb\Entity\User::ALREADY_IN_USE:
			$this->_showMessage(__('Email already in use'),
				__('This email is already in use. If you wish to log in, <a href="%s">click here!</a>',
					$this->_app->buildLink('login')));
			return false;
		}
		return true;
	}
// }}}
}
