<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

if (empty($users)): ?>
<tr class="no-data">
	<td class="center-text"><?= __('No users to show.') ?></td>
</tr>
<?php endif; foreach ($users as $user):
	$sameUser = $user->getId() === $visitor->getId();
	$admin = $user->isAdmin();
	$superadmin = $user->isSuperAdmin();
	$canDelete = !$superadmin && (!$admin || $visitor->isSuperAdmin()) && !$sameUser;
	$canDemote = $admin && !$superadmin && $visitor->isSuperAdmin() && !$sameUser;
	$canPromote = !$admin && !$sameUser;
	$colspan = 3 - (int)($canDemote || $canPromote) - (int)$canDelete;
?>
<tr class="data-row">
	<td colspan="<?= $colspan ?>"><span class="username"><?= htmlspecialchars($user->getUsername(), ENT_COMPAT | ENT_HTML5) ?></span> <?= htmlspecialchars($user->getEmail(), ENT_COMPAT | ENT_HTML5) ?>&nbsp;<?= ($superadmin ? __('(super-admin)') : ($admin ? __('(admin)') : '')) ?></td>
	<?php if ($canDemote || $canPromote): ?>
		<td><button id="<?= $canDemote ? 'demote' : 'promote' ?>-user-<?= $user->getId() ?>" type="button"><?= $canDemote ? __('Demote') : __('Promote') ?></button></td>
	<?php endif; ?>
	<?php if ($canDelete): ?>
		<td><button id="delete-user-<?= $user->getId() ?>" type="button"><?= __('Delete') ?></button></td>
	<?php endif; ?>
</tr>
<?php endforeach;
$actions = [
	'promote-user' => __('Promote user.'),
	'demote-user' => __('Demote user.'),
	'delete-user' => __('Delete user.')
];
$this->_loadTemplate('confirmbox', ['actions' => $actions]);
?>
