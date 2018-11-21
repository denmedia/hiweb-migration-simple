<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 21/11/2018
	 * Time: 19:55
	 */

	namespace hiweb_migration_simple;


	class tools{


		/**
		 * Return http or https string
		 * @return string
		 */
		static function get_request_schema(){
			return ( ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https' : 'http';
		}


		/**
		 * @return string
		 */
		static function get_request_url(){

			return self::get_request_schema() . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		}


		/**
		 * Print request url
		 */
		static function the_request_url(){
			echo self::get_request_url();
		}


		/**
		 * @return string
		 */
		static function get_base_dir(){
			$full_path = getcwd();
			$ar = explode( "wp-", $full_path );
			return rtrim( $ar[0], '\\/' );
		}


		/**
		 *
		 */
		static function the_base_dir(){
			echo self::get_base_dir();
		}


		/**
		 * @return string
		 */
		static function get_base_url(){
			$root = self::get_base_dir();
			$query = ltrim( str_replace( '\\', '/', dirname( $_SERVER['PHP_SELF'] ) ), '/' );
			$rootArr = [];
			$queryArr = [];
			foreach( array_reverse( explode( '/', $root ) ) as $dir ){
				$rootArr[] = rtrim( $dir . '/' . end( $rootArr ), '/' );
			}
			foreach( explode( '/', $query ) as $dir ){
				$queryArr[] = ltrim( end( $queryArr ) . '/' . $dir, '/' );
			}
			$rootArr = array_reverse( $rootArr );
			$queryArr = array_reverse( $queryArr );
			$r = '';
			foreach( $queryArr as $dir ){
				foreach( $rootArr as $rootDir ){
					if( $dir == $rootDir ){
						$r = $dir;
						break 2;
					}
				}
			}

			return ( self::get_request_schema() . '://' . rtrim( $_SERVER['HTTP_HOST'] . '/' . $r, '/' ) );
		}


		/**
		 *
		 */
		static function the_base_url(){
			echo self::get_base_url();
		}


		/**
		 * @return string
		 */
		static function get_plugin_url(){
			$baseUrl = self::get_base_url();
			$baseDir = self::get_base_dir();
			$sufix = str_replace( $baseDir, '', HW_MIGRATION_SIMPLE_DIR );
			return $baseUrl . '/' . $sufix;
		}


		/**
		 * @return string
		 */
		static function get_old_base_url(){
			return (string)get_option( HW_MIGRATION_SIMPLE_PREFIX . '_baseurl', tools::get_base_url() );
		}


		/**
		 * Get current wp site url
		 * @return string
		 */
		static function get_wp_old_base_url(){
			return get_option( 'siteurl' );
		}


		/**
		 * @return string
		 */
		static function get_old_base_dir(){
			return (string)get_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', tools::get_base_url() );
		}


		/**
		 * Return TRUE, if base dir is changed
		 * @return bool
		 */
		static function is_base_dir_changed(){
			return tools::get_old_base_dir() != tools::get_base_dir();
		}


		/**
		 * Return TRUE, if base url is changed
		 * @return bool
		 */
		static function is_base_url_changed(){
			if( !filter_var( tools::get_base_url(), FILTER_VALIDATE_URL ) ) return false;
			return tools::get_old_base_url() != tools::get_base_url();
		}


		/**
		 * @param string $baseDir
		 */
		static function do_rewrite_rules( $baseDir ){
			require_once $baseDir . '/wp-admin/includes/file.php';
			require_once $baseDir . '/wp-admin/includes/misc.php';
			if( function_exists( 'save_mod_rewrite_rules' ) ) save_mod_rewrite_rules();
			if( function_exists( 'iis7_save_url_rewrite_rules' ) ) iis7_save_url_rewrite_rules();
		}


	}