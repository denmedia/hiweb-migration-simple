<?php

	add_action('admin_menu', '_hw_migration_simple_add_submenu_page');
	
	function _hw_migration_simple_add_submenu_page() {
		add_submenu_page(HW_MIGRATION_SIMPLE_AM_SHOWINMENU, 'hiWeb Migration Simple', 'hiWeb Migration S', 'manage_options', HW_MIGRATION_SIMPLE_AM_SLUG, '_hw_migration_simple_add_submenu_page_echo');
	}

	function _hw_migration_simple_add_submenu_page_echo() {
		if (isset($_POST['new_domain'])) {
			$new_domain = $_POST['new_domain'];
			if (isset($_POST['confirm'])) {
				if (trim($new_domain) == '') {
					exit('Domain is not set...');
				} else {
					_hw_migration_simple_doSiteMigrate($_POST['new_domain']);
				}
			} else {
				if (trim($new_domain) == '') {
					_hw_migration_simple_doSiteMigrate();
				} else {
					include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate.php';
				}
			}

		} else {
			include HW_MIGRATION_SIMPLE_DIR . '/template/options.php';
		}
	}