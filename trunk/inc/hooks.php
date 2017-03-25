<?php

	add_action( 'admin_menu', array( hiweb_migration_simple()->hooks(), 'add_submenu_page' ) );
	add_action( 'init', array( hiweb_migration_simple()->hooks(), 'init' ) );
	add_filter( 'plugin_action_links', array( hiweb_migration_simple()->hooks(), 'plugin_action_links' ), 10, 4 );