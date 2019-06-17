<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<form id="register-form" class="register-login" data-actionurl="<?php echo $application->buildLink('register', 'register'); ?>" onsubmit="return false;">
		<label for="username"><?php echo __('Username'); ?></label>
		<input type="text" placeholder="<?php echo __('Choose a Username'); ?>" name="username" required>
		<label for="email"><?php echo __('Email'); ?></label>
		<input type="email" placeholder="<?php echo __('Enter your Email Address'); ?>" name="email" required>
		<label for="password"><?php echo __('Password'); ?></label>
		<input type="password" placeholder="<?php echo __('Choose a Password'); ?>" name="password" required>
		<label for="password-again"><?php echo __('Repeat Password'); ?></label>
		<input type="password" placeholder="<?php echo __('Retype your Password'); ?>" name="password-again" required>
		<button type="submit"><?php echo __('Register'); ?></button>
	</form>
	<div class="formfooter">
		<p><?php echo __('Already have an account? <a href="%s">Log In!</a>', $application->buildLink('login')); ?></p>
	</div>
</main>
