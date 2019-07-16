<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
<button type="button" id="create-challenge"><?= __('Create New Challenge') ?></button>
<table id="challenges-table">
<tbody>
	<?php if (empty($challenges)): ?>
		<tr class="no-data">
			<td colspan="3" class="center-text"><?= __('No challenges to show.') ?></td>
		</tr>
	<?php endif;
	foreach ($challenges as $chall): ?>
		<tr class="data-row">
			<td>
				<span class="chall-category"><?= htmlspecialchars($chall->getCategoryName(), ENT_COMPAT | ENT_HTML5) ?></span>
				<span class="chall-name"><?= htmlspecialchars($chall->getName(), ENT_COMPAT | ENT_HTML5) ?></span>
				<?= $chall->getPoints() ?>&nbsp;<?= __('points'); ?>
			</td>
			<td><button id="edit-chall-<?= $chall->getId() ?>" type="button"><?= __('Edit') ?></button></td>
			<td><button id="delete-chall-<?= $chall->getId() ?>" type="button"><?= __('Delete') ?></button></td>
		</tr>
	<?php endforeach;
	$this->_loadTemplate('confirmbox', ['actions' => ['delete-chall' => __('Delete challenge.')]]);
	?>
</tbody>
</table>
</main>
