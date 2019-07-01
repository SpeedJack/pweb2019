<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<article id="modal-content"<?php if (!empty($modalRedirect)) echo ' data-closeredirect="' . $modalRedirect . '"'; ?>>
	<div id="modal-title">
		<h2><?= $this->_getTitle() ?><span id="modal-closebtn" class="close-modal" title="<?= __('Close') ?>">&times;</span></h2>
	</div>
	<div id="modal-body"<?php if (!isset($footerTemplate)) echo ' class="no-footer"'; ?>>
		<?php $this->_loadTemplate($bodyTemplate, $params); ?>
	</div>
	<?php if (isset($footerTemplate)): ?>
	<div id="modal-footer"><?php $this->_loadTemplate($footerTemplate, $params); ?></div>
	<?php endif; ?>
</article>
