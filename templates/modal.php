<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<article id="modal-content"<?php if (!empty($modalRedirect)) echo ' data-closeredirect="' . $modalRedirect . '"'; ?>>
	<section id="modal-title">
		<h2><?php echo $this->_getTitle(); ?><span id="modal-closebtn" class="close-modal" title="<?php echo __('Close'); ?>">&times;</span></h2>
	</section>
	<section id="modal-body">
		<?php $this->_loadTemplate($bodyTemplate, $params); ?>
	</section>
</article>
