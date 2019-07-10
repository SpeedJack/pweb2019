<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

if (!$visitor->isAdmin())
	die();
?>
<main>
	<div class="search-form">
		<input type="text" id="search-text" name="search-text" placeholder="<?= __('Search..') ?>"><br>
		<label for="search-by-username"><?= __('Search By:') ?></label>
		<input type="radio" id="search-by-username" name="search-by" value="username" checked> <?= __('Username') ?></input>
		<input type="radio" id="search-by-email" name="search-by" value="email"> <?= __('Email') ?></input>
	</div>
	<hr>
	<table id="users-table">
	<tbody>
		<tr class="no-data">
			<td colspan="3" class="center-text"><?= __('No users to show.') ?></td>
		</tr>
	</tbody>
	</table>
</main>
