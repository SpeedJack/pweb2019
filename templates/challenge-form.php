<form id="challenge-form" class="modal-form" autocomplete="on" data-actionurl="<?= $application->buildLink('challenges', 'solve') ?>" onsubmit="return false;">
	<input type="hidden" id="challid" name="challid" value="<?= $chall->getId() ?>">
	<input type="text" placeholder="<?= __('Enter Flag: flag{.....}') ?>" id="challflag" name="challflag" title="<?= __('Flag must have this format: flag{some text}.') ?>" pattern="<?= $application->config['form_validation']['flag_regex'] ?>" maxlength="<?= $application->config['form_validation']['flag_maxlength'] ?>" autofocus required>
	<button type="submit" onclick="if (document.getElementById(&quot;challflag&quot;).checkValidity()) setModalRedirect(&quot;<?= $application->buildLink('challenges') ?>&quot;);"><?= __('Submit') ?></button>
	<button type="button" class="close-modal"><?= __('Close') ?></button>
</form>
<div id="response-container"></div>
