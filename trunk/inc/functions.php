<?php


	function _hw_migrate_simple_getStrRequestUrl() {
		return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}


	function _hw_migrate_simple_getBaseDir() {
		$full_path = getcwd();
		$ar = explode("wp-", $full_path);

		return rtrim($ar[0], '\\/');
	}

	function _hw_migrate_simple_getBaseUrl() {
		$root = _hw_migrate_simple_getBaseDir();
		$query = ltrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
		$rootArr = array();
		$queryArr = array();
		foreach (array_reverse(explode('/', $root)) as $dir) {
			$rootArr[] = rtrim($dir . '/' . end($rootArr), '/');
		}
		foreach (explode('/', $query) as $dir) {
			$queryArr[] = ltrim(end($queryArr) . '/' . $dir, '/');
		}
		$rootArr = array_reverse($rootArr);
		$queryArr = array_reverse($queryArr);
		$r = '';
		foreach ($queryArr as $dir) {
			foreach ($rootArr as $rootDir) {
				if ($dir == $rootDir) {
					$r = $dir;
					break 2;
				}
			}
		}
		$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

		return (rtrim('http' . ($https ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . $r, '/'));
	}


	function _hw_migrate_simple_getBaseDirChange() {
		$oldpath = get_option(HW_MIGRATION_SIMPLE_PREFIX . '_basedir', _hw_migrate_simple_getBaseDir());

		return $oldpath != _hw_migrate_simple_getBaseDir();
	}

	function _hw_migrate_simple_getPluginUrl() {
		$baseUrl = _hw_migrate_simple_getBaseUrl();
		$baseDir = _hw_migrate_simple_getBaseDir();
		$sufix = str_replace($baseDir, '', HW_MIGRATION_SIMPLE_DIR);

		return $baseUrl . '/' . $sufix;
	}


	function _hw_migration_simple_doSiteMigrate($newUrl = '') {
		$oldBaseDir = get_option(HW_MIGRATION_SIMPLE_PREFIX . '_basedir');
		$baseDir = _hw_migrate_simple_getBaseDir();
		update_option(HW_MIGRATION_SIMPLE_PREFIX . '_basedir', $baseDir);
		$oldUrl = get_option('siteurl');
		if (trim($newUrl) == '') $newUrl = _hw_migrate_simple_getBaseUrl();
		update_option('siteurl', $newUrl);
		update_option('home', $newUrl);
		///
		_hw_migration_simple_doDBChange($oldUrl, $newUrl, $oldBaseDir, $baseDir);
		///
		_hw_migration_rewriteRules($baseDir);
		///
		include_once HW_MIGRATION_SIMPLE_DIR . '/template/frontend.php';
		die;
	}

	function _hw_migration_simple_doDBChange($oldUrl, $newUrl, $oldBaseDir, $newBaseDir) {
		global $wpdb;
		foreach ($wpdb->tables() as $table) {
			$columns = $wpdb->get_results('SHOW COLUMNS FROM ' . $table);
			foreach ($columns as $column) {
				$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldUrl','$newUrl')";
				$wpdb->query($query);
				$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldBaseDir','$newBaseDir')";
				$wpdb->query($query);
			}
		}
	}


	function _hw_migration_simple_init() {
		if (trim(get_option(HW_MIGRATION_SIMPLE_PREFIX . '_basedir', '')) == '') {
			update_option(HW_MIGRATION_SIMPLE_PREFIX . '_basedir', _hw_migrate_simple_getBaseDir());
		} elseif (_hw_migrate_simple_getBaseDirChange()) {
			add_action('init', '_hw_migration_simple_doSiteMigrate');
		}
	}


	function _hw_migration_rewriteRules($baseDir) {
		require_once $baseDir . '/wp-admin/includes/file.php';
		require_once $baseDir . '/wp-admin/includes/misc.php';
		if (function_exists('save_mod_rewrite_rules')) save_mod_rewrite_rules();
		if (function_exists('iis7_save_url_rewrite_rules')) iis7_save_url_rewrite_rules();
	}