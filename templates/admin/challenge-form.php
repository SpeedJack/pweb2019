<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
if ($challenge === false) {
	$category = '';
	$name = '';
	$flag = '';
	$points = 0;
	$body = '';
} else {
	$category = htmlspecialchars($challenge->getCategoryName(), ENT_COMPAT | ENT_HTML5);
	$name = htmlspecialchars($challenge->getName(), ENT_COMPAT | ENT_HTML5);
	$flag = htmlspecialchars($challenge->getFlag(), ENT_COMPAT | ENT_HTML5);
	$points = $challenge->getPoints();
	$body = htmlspecialchars($challenge->getBody(), ENT_COMPAT | ENT_HTML5);
}
?>
<form id="challenge-edit-form" class="modal-form" autocomplete="off" data-actionurl="<?= $application->buildLink('__current', 'save') ?>" onsubmit="return false;">
	<?php if ($challenge !== false): ?>
		<input type="hidden" id="challid" name="challid" value="<?= $challenge->getId() ?>">
	<?php endif; ?>
	<input type="text" placeholder="<?= __('Enter Challenge Category..') ?>" id="challcategory" name="challcategory" value="<?= $category ?>" list="categories" maxlength="32" required>
	<input type="text" placeholder="<?= __('Enter Challenge Title..') ?>" id="challname" name="challname" maxlength="32" value="<?= $name ?>" required>
	<input type="text" placeholder="<?= __('Enter Flag: flag{.....}') ?>" id="challflag" name="challflag" title="<?= __('Flag must have this format: flag{some text}.') ?>" pattern="<?= $application->config['form_validation']['flag_regex'] ?>" maxlength="<?= $application->config['form_validation']['flag_maxlength'] ?>" value="<?= $flag ?>" required>
	<input type="number" id="challpoints" name="challpoints" min="1" value="<?= $points ?>" required>
	<datalist id="categories">
		<?php foreach ($categories as $cat): ?>
			<option value="<?= $cat ?>">
		<?php endforeach; ?>
	</datalist>
	<textarea placeholder="<?= __('Enter Body Text..') ?>" rows="5" cols="40" id="challbody" name="challbody" required><?= $body ?></textarea>
	<button type="submit" onclick="if (document.getElementById('challenge-edit-form').checkValidity()) setModalRedirect('<?= $application->buildLink('__current') ?>');"><?= __('Submit') ?></button>
	<button type="button" class="close-modal"><?= __('Close') ?></button>
</form>
<div id="response-container"></div>
