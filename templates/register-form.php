<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<form id="register-form" class="register-login" autocomplete="on" data-actionurl="<?php echo $application->buildLink('register', 'register'); ?>" onsubmit="return false;">
		<label for="username"><?php echo __('Username'); ?></label>
		<input type="text" placeholder="<?php echo __('Choose a Username'); ?>" name="username" title="<?php echo __('Username must contains at least 5 characters and no more than 32. It can contains only letters (lowercase and uppercase), numbers and . (dot), _ (underscore), - (minus).'); ?>" pattern="<?php echo $application->config['form_validation']['username_regex']; ?>" maxlength="<?php echo $application->config['form_validation']['username_maxlength']; ?>" autofocus="on" required>
		<span id="validatorfor-username"></span>
		<label for="email"><?php echo __('Email'); ?></label>
		<input type="email" placeholder="<?php echo __('Enter your Email Address'); ?>" name="email" title="<?php echo __('Insert a valid email address.'); ?>" maxlength="255" required>
		<span id="validatorfor-email"></span>
		<label for="password"><?php echo __('Password'); ?></label>
		<input type="password" placeholder="<?php echo __('Choose a Password'); ?>" name="password" title="<?php echo __('Enter a password of at least %s characters long.', $application->config['min_password_length']); ?>" autocomplete="off" minlength="<?php echo $application->config['min_password_length']; ?>" required>
		<span id="validatorfor-password"><?php echo __('Password must be at least %s characters long.', $application->config['min_password_length']); ?></span>
		<label for="password-again"><?php echo __('Repeat Password'); ?></label>
		<input type="password" placeholder="<?php echo __('Retype your Password'); ?>" name="password-again" title="<?php echo __('Retype your password, as above.'); ?>" autocomplete="off" minlength="<?php echo $application->config['min_password_length']; ?>" required>
		<span id="validatorfor-password-again"><?php echo __('Passwords do not match.'); ?></span>
		<button type="submit"><?php echo __('Register'); ?></button>
	</form>
	<div class="formfooter">
		<p><?php echo __('Already have an account? <a href="%s">Log In!</a>', $application->buildLink('login')); ?></p>
	</div>
</main>
