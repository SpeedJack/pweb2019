<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<form id="register-form" class="register-login" autocomplete="on" data-actionurl="<?= $application->buildLink('register', 'register') ?>" onsubmit="return false;">
		<label for="username"><?= __('Username') ?></label>
		<input type="text" placeholder="<?= __('Choose a Username') ?>" id="username" name="username" title="<?= __('Username must contains at least 5 characters and no more than 32. It can contains only letters (lowercase and uppercase), numbers and . (dot), _ (underscore), - (minus).') ?>" pattern="<?= $application->config['form_validation']['username_regex'] ?>" maxlength="<?= $application->config['form_validation']['username_maxlength'] ?>" autofocus required>
		<span id="validatorfor-username"></span>
		<label for="email"><?= __('Email') ?></label>
		<input type="email" placeholder="<?= __('Enter your Email Address') ?>" id="email" name="email" title="<?= __('Insert a valid email address.') ?>" maxlength="255" required>
		<span id="validatorfor-email"></span>
		<label for="password"><?= __('Password') ?></label>
		<input type="password" placeholder="<?= __('Choose a Password') ?>" id="password" name="password" title="<?= __('Enter a password of at least %s characters long.', $application->config['min_password_length']) ?>" autocomplete="off" minlength="<?= $application->config['min_password_length'] ?>" required>
		<span id="validatorfor-password"><?= __('Password must be at least %s characters long.', $application->config['min_password_length']) ?></span>
		<label for="password-again"><?= __('Repeat Password') ?></label>
		<input type="password" placeholder="<?= __('Retype your Password') ?>" id="password-again" name="password-again" title="<?= __('Retype your password, as above.') ?>" autocomplete="off" minlength="<?= $application->config['min_password_length'] ?>" required>
		<span id="validatorfor-password-again"><?= __('Passwords do not match.') ?></span>
		<button type="submit"><?= __('Register') ?></button>
	</form>
	<div class="formfooter">
		<p><?= __('Already have an account? <a href="%s">Log In!</a>', $application->buildLink('login')) ?></p>
	</div>
</main>
