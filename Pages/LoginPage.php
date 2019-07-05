<?php
namespace Pweb\Pages;

/**
 * @brief Represents the login page.
 *
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
class LoginPage extends AbstractPage
{
	/**
	 * @brief Shows the login form.
	 */
	public function actionIndex()
	{
		if ($this->_visitor->isLoggedIn())
			$this->_app->redirectHome();
		$this->_setTitle(__('Login Page'));
		$this->_addCss('form');
		$this->_addJs('form');
		$this->_show('login-form');
	}

	/**
	 * @brief Logs in the user with the credentials provided by the login
	 * form.
	 */
	public function actionLogin()
	{
		if ($this->_visitor->isLoggedIn())
			$this->_app->redirectHome();

		$loginname = $this->_visitor->param('loginname', 'POST');
		$password = $this->_visitor->param('password', 'POST');
		$rememberme = $this->_visitor->param('rememberme');
		if (empty($loginname) || empty($password)) {
			$this->_showMessage(__('Error'),
				__('Please, fill out all fields.'));
			return;
		}

		$user = $this->_em->getFromDbBy('User', 'getByEmail', $loginname);
		if ($user === false)
			$user = $this->_em->getFromDbBy('User', 'getByUsername', $loginname);

		if ($user === false) {
			$this->_showMessage(__('User not found'),
				__('The user <em>%s</em> can not be found. If you wish to register, <a href="%s">click here!</a>',
				htmlspecialchars($loginname),
				$this->_app->buildLink('register')));
			return;
		}

		if (!$user->verifyPassword($password)) {
			$this->_showMessage(__('Wrong password'),
				__('Wrong password.'));
			return;
		}

		if (isset($rememberme) && $rememberme === 'yes') {
			$authToken = $this->_em->createNew('AuthToken');
			$authToken->setUser($user);
			$validator = $authToken->generateToken();
			$authToken->save();
			$this->_visitor->setAuthTokenCookie($authToken, $validator);
		}

		$this->_visitor->setSessionUser($user);
		$this->_redirectAjax();
	}

	/**
	 * @brief Logs out the user.
	 */
	public function actionLogout()
	{
		$this->_visitor->logout();
		$this->_app->redirectHome();
	}
}
