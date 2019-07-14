<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<form id="challenge-form" class="modal-form" autocomplete="on" data-actionurl="<?= $application->buildLink('__current', 'solve') ?>" onsubmit="return false;">
	<input type="hidden" id="challid" name="challid" value="<?= $chall->getId() ?>">
	<input type="text" placeholder="<?= __('Enter Flag: flag{.....}') ?>" id="challflag" name="challflag" title="<?= __('Flag must have this format: flag{some text}.') ?>" pattern="<?= $application->config['form_validation']['flag_regex'] ?>" maxlength="<?= $application->config['form_validation']['flag_maxlength'] ?>" autofocus required>
	<button type="submit" onclick="if (document.getElementById('challflag').checkValidity()) setModalRedirect('<?= $application->buildLink('__current') ?>');"><?= __('Submit') ?></button>
	<button type="button" class="close-modal"><?= __('Close') ?></button>
</form>
<div id="response-container"></div>
