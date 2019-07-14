<?php
/**
 * @file
 * @author Niccolò Scatena <speedjack95@gmail.com>
 * @copyright GNU General Public License, version 3
 */

$topnavMenus = [
	__('Challenges') => ['page' => 'challenges'],
	__('Ranking') => ['page' => 'ranking'],
	__('Admin') => [
		'showCondition' => 'isAdmin',
		'menus' => [
			__('Edit Challenges') => ['page' => 'admin_challenges'],
			__('Edit Users') => ['page' => 'admin_users']
		]
	],
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

/**
 * @brief Generates the HTML of the menus.
 *
 * @param[in] array $menus			An array containing all infos
 * 						about the menus to display.
 * @param[in] Pweb::Entity::Visitor $visitor	The Visitor's instance.
 * @param[in] Pweb::App $application		The App's instance.
 */
function showMenus($menus, $visitor, $application)
{
	foreach ($menus as $menuName => $menuMeta):
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
			case 'isAdmin':
				if (!$visitor->isAdmin())
					continue 2;
				break;
			}
		$classes = [];
		if (isset($menuMeta['classes']))
			$classes = array_merge($classes,
				is_array($menuMeta['classes'])
					? $menuMeta['classes']
					: [ $menuMeta['classes'] ]
			);
		if (isset($menuMeta['menus'])):
			$classes[] = 'dropdown';
			foreach ($menuMeta['menus'] as $submenu)
				if ($visitor->isActivePage($submenu['page'])) {
					$classes[] = 'active';
					break;
				}
			$classStr = get_class_attribute($classes);
			$classStr = empty($classStr) ? '' : " $classStr"; ?>
			<div<?= $classStr ?>><button<?= $classStr ?>><?= $menuName ?></button>
			<div class="dropdown-content">
			<?php showMenus($menuMeta['menus'], $visitor, $application); ?>
			</div>
			</div>
		<?php continue; endif;
		if ($visitor->isActivePage($menuMeta['page']))
			$classes[] = 'active';
		$menuAction = isset($menuMeta['action'])
			? $menuMeta['action'] : null;
		$menuParams = isset($menuMeta['params'])
			? $menuMeta['params'] : [];
		$menuHref = 'href="' . $application->buildLink(
			$menuMeta['page'], $menuAction, $menuParams) . '"';
		$classStr = get_class_attribute($classes);
		$classStr = empty($classStr) ? '' : " $classStr";
		?>
		<a<?= "$classStr $menuHref" ?>>
			<?= $menuName ?>
		</a>
	<?php endforeach;
}
?>
<header>
<h1><?= $application->config['app_name'] ?></h1>
<p><?= $application->config['header_motd'] ?></p>
</header>
<nav>
	<?php
	showMenus($topnavMenus, $visitor, $application);
	if ($visitor->isLoggedIn()): ?>
		<p class="right"><?= __('Logged in as <span class="username">%s</span>.', $visitor->user->getUsername()) ?></p>
	<?php endif; ?>
		<canvas id="menu-bars" width="25" height="20"></canvas>
</nav>
