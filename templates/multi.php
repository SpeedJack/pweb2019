<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>

<main>
	<?php foreach ($templates as $template)
		$this->_loadTemplate($template, $params);
	?>
</main>
