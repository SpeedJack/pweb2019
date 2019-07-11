<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<form id="challenge-edit-form" class="modal-form" data-actionurl="<?= $application->buildLink('__current', 'save') ?>" onsubmit="return false;">
	<input type="hidden" id="challid" name="challid" value="<?= $challenge->getId() ?>">
	<input type="text" placeholder="<?= __('Enter Challenge Title..') ?>" id="challname" name="challflag" maxlength="32" value="<?= htmlspecialchars($challenge->getName()) ?>" required>
	<input type="text" id="challcategory" name="challcategory" value="<?= htmlspecialchars($challenge->getCategoryName()) ?>" list="categories" maxlength="32" required>
	<input type="number" id="challpoints" name="challpoints" min="0" value="<?= $challenge->getPoints() ?>" required>
	<datalist id="categories">
		<?php $categories = []; foreach ($categories as $cat): ?>
			<option value="<?= $cat ?>">
		<?php endforeach; ?>
	</datalist>
	<textarea type="text" rows="5" cols="40" id="challbody" name="challbody" required><?= htmlspecialchars($challenge->getBody()) ?></textarea>
	<button type="submit" onclick="if (document.getElementById(&quot;challenge-edit-form&quot;).checkValidity()) setModalRedirect(&quot;<?= $application->buildLink('__current') ?>&quot;);"><?= __('Submit') ?></button>
	<button type="button" class="close-modal"><?= __('Close') ?></button>
</form>

