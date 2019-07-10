<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

if (empty($users)): ?>
<tr class="no-data">
	<td colspan="3" class="center-text"><?= __('No users to show.') ?></td>
</tr>
<?php endif; foreach ($users as $user): 
	$admin = $user->isAdmin();
	$superadmin = $user->isSuperAdmin();
	$canDelete = !$superadmin && (!$admin || $visitor->isSuperAdmin());
	$canDemote = $admin && !$superadmin && $visitor->isSuperAdmin();
	$canPromote = !$admin;
	$colspan = 3 - (int)($canDemote || $canPromote) - (int)$canDelete;
?>
<tr class="data-row">
	<td colspan="<?= $colspan ?>"><span class="username"><?= htmlspecialchars($user->getUsername()) ?></span> <?= htmlspecialchars($user->getEmail()) ?>&nbsp;<?= ($superadmin ? __('(super-admin)') : ($admin ? __('(admin)') : '')) ?></td>
	<?php if ($canDemote || $canPromote): ?>
		<td><button id="<?= $canDemote ? 'demote' : 'promote' ?>-user-<?= $user->getId() ?>" type="button"><?= $canDemote ? __('Demote') : __('Promote') ?></button></td>
	<?php endif; ?>
	<?php if ($canDelete): ?>
		<td><button id="delete-user-<?= $user->getId() ?>" type="button"><?= __('Delete') ?></button></td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
<tr class="confirmbox">
	<td>
	<div>
		<span id="confirm-promote-user"><?= __('Promote user.') ?></span>
		<span id="confirm-demote-user"><?= __('Demote user.') ?></span>
		<span id="confirm-delete-user"><?= __('Delete user.') ?></span>
		<?= __('Are you sure?') ?>
	</div>
	</td>
	<td><div><button id="confirmbox-yes" type="button"><?= __('Yes') ?></button></div></td>
	<td><div><button id="confirmbox-no" type="button"><?= __('No') ?></button></div></td>
</tr>
