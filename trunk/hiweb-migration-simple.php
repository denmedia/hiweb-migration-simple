<?php
	/*
	Plugin Name: hiWeb Migration Simple
	Plugin URI: http://hiweb.moscow/migration-simple
	Description: Plugin to automatically change the paths and links in the database of your site on wordpress. Just migrate files and the site database to a new hosting.
	Version: 2.0.0.1
	Author: Den Media
	Author URI: http://hiweb.moscow
	*/

	require_once __DIR__ . '/inc/define.php';
	require_once __DIR__ . '/inc/hiweb_migration_simple.php';
	require_once __DIR__ . '/inc/hooks.php';
	require_once __DIR__ . '/inc/tools.php';
	require_once __DIR__ . '/inc/db.php';

	hiweb_migration_simple::init();