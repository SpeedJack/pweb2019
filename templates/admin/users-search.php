<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<div class="search-form">
	<input type="text" id="search-text" name="search-text" placeholder="<?= __('Search..') ?>"><br>
	<label for="search-by-username"><?= __('Search By:') ?></label>
	<input type="radio" id="search-by-username" name="search-by" value="username" checked> <?= __('Username') ?></input>
	<input type="radio" id="search-by-email" name="search-by" value="email"> <?= __('Email') ?></input>
</div>
