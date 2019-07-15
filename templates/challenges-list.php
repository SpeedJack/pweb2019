<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<?php
	if (empty($categories)): ?>
		<p><?= __('No challenges to show.'); ?></p>
	<?php endif;
	foreach ($categories as $categoryName => $challenges): ?>
		<button class="accordion"><?= $categoryName ?></button>
		<div class="chall-container">
		<?php foreach ($challenges as $chall):
			if ($this->_visitor->user->hasSolvedChallenge($chall))
				$classStr = get_class_attribute(['chall', 'solved-chall']);
			else
				$classStr = get_class_attribute(['chall']);
			?>
			<div <?= $classStr ?> id="chall-<?= $chall->getId() ?>">
				<span>
					<?= $chall->getName() ?><br>
					<?= __('(%s points)', $chall->getPoints()) ?>
				</span>
			</div>
		<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</main>
