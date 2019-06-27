<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<main>
	<?php
	$currentCat = "";
	foreach ($challenges as $chall) {
		$challCat = $chall->getCategoryName();
		$solved = false;
		if ($this->_visitor->user->hasSolvedChallenge($chall))
			$solved = true;
		if ($currentCat !== $challCat):
			if (!empty($currentCat)): ?>
				</div>
			<?php endif; ?>
			<button class="accordion"><?= $challCat ?></button>
			<div class="chall-container">
		<?php endif;
		$classStr = ' ';
		if ($solved)
			$classStr .= getClassesString(['chall', 'solved-chall']);
		else
			$classStr .= getClassesString(['chall']);
		?>
			<div<?= $classStr ?> id="chall-<?= $chall->getId() ?>"><span><?php
				echo $chall->getName();
				if ($solved)
					echo '<br>(' . __('solved') . ')';
				?></span></div>
		<?php
		$currentCat = $challCat;
	}
	if (!empty($challenges)): ?>
		</div>
	<?php endif; ?>
</main>
