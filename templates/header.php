<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

$topnavMenus = [
	__('Challenges') => ['page' => 'challenges'],
	__('Ranking') => ['page' => 'ranking'],
	__('About') => ['page' => 'about'],
	__('Sign Up') => [
		'page' => 'register',
		'showCondition' => 'notLoggedIn',
		'classes' => 'right'
	],
	__('Login') => [
		'page' => 'login',
		'showCondition' => 'notLoggedIn',
		'classes' => 'right'
	],
	__('Logout') => [
		'page' => 'login',
		'action' => 'logout',
		'showCondition' => 'loggedIn',
		'classes' => 'right'
	]
];
?>
<header>
<h1><?php echo $application->config['app_name']; ?></h1>
<p><?php echo $application->config['header_motd']; ?></p>
</header>
<nav>
	<?php
	foreach ($topnavMenus as $menuName => $menuMeta):
		if (isset($menuMeta['showCondition']))
			switch ($menuMeta['showCondition']) {
			case 'loggedIn':
				if (!$visitor->isLoggedIn())
					continue 2;
				break;
			case 'notLoggedIn':
				if ($visitor->isLoggedIn())
					continue 2;
				break;
			}
		$classes = [];
		if ($visitor->isActivePage($menuMeta['page']))
			$classes[] = 'active';
		if (isset($menuMeta['classes']))
			$classes = array_merge($classes,
				is_array($menuMeta['classes'])
					? $menuMeta['classes']
					: [ $menuMeta['classes'] ]
			);
		$classStr = getClassesString($classes);
		$menuAction = isset($menuMeta['action'])
			? $menuMeta['action'] : null;
		$menuParams = isset($menuMeta['params'])
			? $menuMeta['params'] : null;
		$menuHref = 'href="' . $application->buildLink(
			$menuMeta['page'], $menuAction, $menuParams) . '"';
	?>
		<a <?php echo "$classStr $menuHref"; ?>>
			<?php echo $menuName; ?>
		</a>
	<?php endforeach; ?>
	<?php if ($visitor->isLoggedIn()): ?>
		<p class="right"><?php echo __('Logged in as <span class="username">%s</span>.', $visitor->user->getUsername()); ?></p>
	<?php endif; ?>
</nav>
