<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<div class="search-form">
		<input type="text" id="search-text" name="search-text" placeholder="<?= __('Search..') ?>"><br>
		<label for="search-by-username"><?= __('Search By:') ?></label>
		<input type="radio" id="search-by-username" name="search-by" value="username" checked> <?= __('Username') ?></input>
		<input type="radio" id="search-by-email" name="search-by" value="email"> <?= __('Email') ?></input>
	</div>
	<hr>
	<table>
	<tbody>
		<tr>
			<td colspan="3" class="center-text"><?= __('No users to show.') ?></td>
		</tr>
		<tr>
			<td><span class="username">SpeedJack</span> speedjack95@gmail.com (admin)</td>
			<td><button id="promote-user" type="button"><?= __('Promote') ?></button></td>
			<td><button id="delete-user" type="button"><?= __('Delete') ?></button></td>
		</tr>
		<tr class="confirmbox">
			<td>
			<div>
				<span id="confirm-promote-user"><?= __('Promote user.') ?></span>
				<span id="confirm-delete-user"><?= __('Delete user.') ?></span>
				<?= __('Are you sure?') ?>
			</div>
			</td>
			<td><div><a href=""><?= __('Yes') ?></a></div></td>
			<td><div><a href=""><?= __('No') ?></a></div></td>
		</tr>
	</tbody>
	</table>
</main>
