<?php
/**
 * Ghost theme plugin
 *
 * @package GhostTheme
 */

elgg_register_event_handler('init','system','ghost_theme_init');

function ghost_theme_init() {

	elgg_register_event_handler('pagesetup', 'system', 'ghost_theme_pagesetup', 1000);

	// theme specific CSS
	elgg_extend_view('elgg.css', 'ghost-theme/css');

	elgg_unextend_view('page/elements/header', 'search/header');
	elgg_extend_view('page/elements/sidebar', 'search/header', 0);
	
	elgg_register_plugin_hook_handler('head', 'page', 'ghost_theme_setup_head');

	// non-members do not get visible links to RSS feeds
	if (!elgg_is_logged_in()) {
		elgg_unregister_plugin_hook_handler('output:before', 'layout', 'elgg_views_add_rss_link');
	}
        elgg_extend_view('elgg.css', 'resources/index.css');
elgg_register_plugin_hook_handler('index','system','ghost_theme_index');

if (elgg_is_active_plugin('file')) {
elgg_register_menu_item('homepage', array(
        'name' => 'files',
        'href' => 'file/all',
        'priority' => '3',
        'class' => 'inline-block py-2 px-2 text-white no-underline hover:underline',
        'text' => elgg_echo('file'),
));
}


if (elgg_is_active_plugin('tidypics')) {
elgg_register_menu_item('homepage', array(
        'name' => 'photos',
        'href' => 'photos/siteimagesall',
        'priority' => '2',
        'class' => 'inline-block py-2 px-2 text-white no-underline hover:underline',
        'text' => elgg_echo('photos'),
));
}


elgg_register_menu_item('homepage', array(
        'name' => 'login',
        'href' => '/login',
        'priority' => '1',
        'class' => 'inline-block py-2 px-2 text-white no-underline hover:underline',
        'text' => elgg_echo('Sign in'),
));

}

/**
 * Rearrange menu items
 */
function ghost_theme_pagesetup() {

	if (elgg_is_logged_in()) {

		elgg_register_menu_item('topbar', array(
			'name' => 'account',
			'text' => elgg_echo('account'),
			'href' => "#",
			'priority' => 100,
			'section' => 'alt',
			'link_class' => 'elgg-topbar-dropdown',
		));

		if (elgg_is_active_plugin('dashboard')) {
			$item = elgg_unregister_menu_item('topbar', 'dashboard');
			if ($item) {
				$item->setText(elgg_echo('dashboard'));
				$item->setSection('default');
				elgg_register_menu_item('site', $item);
			}
		}
		
		$item = elgg_get_menu_item('topbar', 'usersettings');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('settings'));
			$item->setPriority(103);
		}

		$item = elgg_get_menu_item('topbar', 'logout');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('logout'));
			$item->setPriority(104);
		}

		$item = elgg_get_menu_item('topbar', 'administration');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('admin'));
			$item->setPriority(101);
		}

		if (elgg_is_active_plugin('site_notifications')) {
			$item = elgg_get_menu_item('topbar', 'site_notifications');
			if ($item) {
				$item->setParentName('account');
				$item->setText(elgg_echo('site_notifications:topbar'));
				$item->setPriority(102);
			}
		}

		if (elgg_is_active_plugin('reportedcontent')) {
			$item = elgg_unregister_menu_item('footer', 'report_this');
			if ($item) {
				$item->setText(elgg_view_icon('report-this'));
				$item->setPriority(500);
				$item->setSection('default');
				elgg_register_menu_item('extras', $item);
			}
		}
	}
}

/**
 * Register items for the html head
 *
 * @param string $hook Hook name ('head')
 * @param string $type Hook type ('page')
 * @param array  $data Array of items for head
 * @return array
 */
function ghost_theme_setup_head($hook, $type, $data) {
	$data['metas']['viewport'] = array(
		'name' => 'viewport',
		'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0',
	);

	$data['links']['apple-touch-icon'] = array(
		'rel' => 'apple-touch-icon',
		'href' => elgg_get_simplecache_url('ghost-theme/homescreen.png'),
	);

	return $data;
}

function ghost_theme_index() {
    if (!include_once(dirname(dirname(__FILE__)) . "/ghost-theme/views/default/custom_index/home.php"))
        return false;
 
    return true;
}
