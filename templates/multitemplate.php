<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>

<main>
	<?php $first = true;
	foreach ($templates as $template):
		if (!$first): ?>
			<hr>
		<?php endif;
		$first = false;
		$this->_loadTemplate($template, $params);
	endforeach; ?>
</main>
