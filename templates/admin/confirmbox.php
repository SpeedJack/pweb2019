<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<tr class="confirmbox">
	<td>
	<div>
		<?php foreach ($actions as $actionId => $actionText): ?>
			<span id="confirm-<?= $actionId ?>"><?= $actionText ?></span>
		<?php endforeach; ?>
		<?= __('Are you sure?') ?>
	</div>
	</td>
	<td><div><button id="confirmbox-yes" type="button"><?= __('Yes') ?></button></div></td>
	<td><div><button id="confirmbox-no" type="button"><?= __('No') ?></button></div></td>
</tr>
