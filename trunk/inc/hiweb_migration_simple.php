<?php

	use hiweb_migration_simple\db;
	use hiweb_migration_simple\hooks;
	use hiweb_migration_simple\tools;


	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 21/11/2018
	 * Time: 19:42
	 */
	class hiweb_migration_simple{

		static $init = false;


		static function init(){
			if( !self::$init ){
				self::$init = true;
				hooks::init();
				///FIRST SET
				if( trim( get_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', '' ) ) == '' || trim( get_option( HW_MIGRATION_SIMPLE_PREFIX . '_baseurl', '' ) == '' ) ){
					update_option( HW_MIGRATION_SIMPLE_PREFIX . '_baseurl', tools::get_base_url() );
					update_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', tools::get_base_dir() );
				} elseif( self::is_trigger() && !wp_doing_ajax() && !wp_doing_cron() ) {
					include HW_MIGRATION_SIMPLE_DIR . '/template/frontend.php';
					die;
				}
			}
		}


		/**
		 * Return true then url, schema or root dir is changed
		 * @return bool
		 */
		static function is_trigger(){
			return tools::is_base_dir_changed();
		}


		/**
		 * @param string $newUrl
		 * @param string $oldUrl
		 * @return array
		 */
		static function do_site_migrate( $newUrl = '', $oldUrl = '' ){
			$oldBaseDir = tools::get_old_base_dir();
			$baseDir = tools::get_base_dir();
			update_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', $baseDir );
			update_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir_old', $oldBaseDir );
			update_option( HW_MIGRATION_SIMPLE_PREFIX . '_baseurl', tools::get_base_url() );
			update_option( HW_MIGRATION_SIMPLE_PREFIX . '_baseurl_old', tools::get_wp_old_base_url() );
			if( trim( $oldUrl ) == '' ) $oldUrl = tools::get_wp_old_base_url();
			if( trim( $newUrl ) == '' ) $newUrl = tools::get_base_url();
			///
			$R = db::do_DB_change( $oldUrl, $newUrl, $oldBaseDir, $baseDir );
			///
			tools::do_rewrite_rules( $baseDir );
			///
			return $R;
		}

	}