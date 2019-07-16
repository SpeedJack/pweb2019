<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>500 - Internal Server Error</title>
	</head>
	<body>
		<h1>Error 500 - Internal Server Error</h1>
		<p><?= __('The server encountered an internal error and was unable to process the request.') ?></p>
		<?php if (!empty($errorMsg)): ?>
			<p><?= __('Error Message: %s', htmlspecialchars($errorMsg, ENT_COMPAT | ENT_HTML5)) ?>.</p>
		<?php endif; ?>
	</body>
</html>
