<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<?php
	if (empty($challenges)): ?>
		<p><?= __('No challenges to show.'); ?></p>
	<?php endif;
	$currentCat = "";
	foreach ($challenges as $chall) {
		$challCat = $chall->getCategoryName();
		if ($currentCat !== $challCat):
			if (!empty($currentCat)): ?>
				</div>
			<?php endif; ?>
			<button class="accordion"><?= $challCat ?></button>
			<div class="chall-container">
		<?php endif;
		$classStr = ' ';
		if ($this->_visitor->user->hasSolvedChallenge($chall))
			$classStr .= get_class_attribute(['chall', 'solved-chall']);
		else
			$classStr .= get_class_attribute(['chall']);
		?>
			<div<?= $classStr ?> id="chall-<?= $chall->getId() ?>"><span><?php
				echo $chall->getName() . '<br>' . __('(%s points)', $chall->getPoints());
				?></span></div>
		<?php
		$currentCat = $challCat;
	}
	if (!empty($challenges)): ?>
		</div>
	<?php endif; ?>
</main>
