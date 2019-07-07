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
					<?= __('Showing <strong>%s</strong> users of <strong>%s</strong>.', count($users), $totalUsers) ?><br><br>
					<div class="table-navbar">
						<?php if ($page > 4): ?>
						<a href="<?= $application->buildLink('__current', '__current', ['pp' => $perPage, 'p' => 1]) ?>">&laquo;</a>
						<?php endif; ?>
						<?php for ($curPage = max($page - 3, 1); $curPage <= min($totalPages, $page + 3); $curPage++): ?>
							<a<?php if ($curPage === $page) echo " class=\"active\""; ?> href="<?= $application->buildLink('__current', '__current', ['pp' => $perPage, 'p' => $curPage]) ?>"><?= $curPage ?></a>
						<?php endfor; ?>
						<?php if ($page < $totalPages - 3): ?>
							<a href="<?= $application->buildLink('__current', '__current', ['pp' => $perPage, 'p' => $totalPages]) ?>">&raquo;</a>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
</main>
