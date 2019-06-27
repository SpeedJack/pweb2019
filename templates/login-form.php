<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<form id="login-form" class="register-login" autocomplete="on" data-actionurl="<?= $application->buildLink('login', 'login') ?>" onsubmit="return false;">
		<label for="loginname"><?= __('Username or Email') ?></label>
		<input type="text" placeholder="<?= __('Enter Username or Email') ?>" id="loginname" name="loginname" maxlength="255" autofocus required>
		<label for="password"><?= __('Password') ?></label>
		<input type="password" placeholder="<?= __('Enter Password') ?>" id="password" name="password" required>
		<label class="nobold">
			<input type="checkbox" checked="checked" name="rememberme" value="yes"> <?= __('Remember Me') ?>
		</label>
		<button type="submit"><?= __('Login') ?></button>
	</form>
	<div class="formfooter">
		<p><?= __('Don\'t have an account? <a href="%s">Register Now!</a>', $application->buildLink('register')) ?></p>
	</div>
</main>
