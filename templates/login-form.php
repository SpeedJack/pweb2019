<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<form id="login-form" class="register-login" data-actionurl="<?php echo $application->buildLink('login', 'login'); ?>" onsubmit="return false;">
		<label for="loginname"><?php echo __('Username or Email'); ?></label>
		<input type="text" placeholder="<?php echo __('Enter Username or Email'); ?>" name="loginname" required>
		<label for="password"><?php echo __('Password'); ?></label>
		<input type="password" placeholder="<?php echo __('Enter Password'); ?>" name="password" required>
		<label class="nobold">
			<input type="checkbox" checked="checked" name="rememberme" value="yes"> <?php echo __('Remember Me'); ?>
		</label>
		<button type="submit"><?php echo __('Login'); ?></button>
	</form>
	<div class="formfooter">
		<p><?php echo __('Don\'t have an account? <a href="%s">Register Now!</a>', $application->buildLink('register')); ?></p>
	</div>
</main>
