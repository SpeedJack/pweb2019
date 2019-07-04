<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<table>
		<caption><?= __('Top Users') ?></caption>
		<thead>
			<tr>
				<th><?= __('Pos') ?></th>
				<th><?= __('Username') ?></th>
				<th><?= __('Points') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$posCounter = $startingPos;
			foreach ($users as $user): ?>
			<tr>
				<td><?= $posCounter ?></td>
				<td><?= htmlspecialchars($user->getUsername()) ?></td>
				<td><?= htmlspecialchars($user->getPoints()) ?></td>
			</tr>
			<?php $posCounter++; endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">
					<?= __('Showing <strong>%s</strong> users of <strong>%s</strong>.', $perPage, $totalUsers) ?><br>
					<?= __('Page <strong>%s</strong> of <strong>%s</strong>.', $page, $totalPages) ?>
				</td>
			</tr>
		</tfoot>
	</table>
	<ul class="table-navbar">
		<?php if ($page > 1): ?>
			<li id="tablectrl-first"><a href="<?= $application->buildLink('ranking', null, ['pp' => $perPage]) ?>">&lt;&lt; <?= __('First') ?></a></li>
			<li id="tablectrl-prev"><a href="<?= $application->buildLink('ranking', null, ['p' => $page - 1, 'pp' => $perPage]) ?>">&lt; <?= __('Prev.') ?></a></li>
		<?php endif; if ($page < $totalPages): ?>
			<li id="tablectrl-last"><a href="<?= $application->buildLink('ranking', null, ['p' => $totalPages, 'pp' => $perPage]) ?>"><?= __('Last') ?> &gt;&gt;</a></li>
			<li id="tablectrl-next"><a href="<?= $application->buildLink('ranking', null, ['p' => $page + 1, 'pp' => $perPage]) ?>"><?= __('Next') ?> &gt;</a></li>
		<?php endif; ?>
	</ul>
</main>
