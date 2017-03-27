<?php

	if( !function_exists( 'hw_migration_simple' ) ){

		/**
		 * @return hw_migration_simple
		 */
		function hiweb_migration_simple(){
			static $class;
			if( !$class instanceof hw_migration_simple )
				$class = new hw_migration_simple();
			return $class;
		}


		class hw_migration_simple{


			/**
			 *
			 */
			public function init(){
				if( trim( get_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', '' ) ) == '' ){
					update_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', $this->get_base_dir() );
				} elseif( $this->get_base_dir_change() ) {
					add_action( 'init', array(hiweb_migration_simple(), 'do_site_migrate') );
				}
			}


			/**
			 * @return string
			 */
			public function get_request_url(){
				return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			}


			/**
			 *
			 */
			public function the_request_url(){
				echo $this->get_request_url();
			}


			/**
			 * @return string
			 */
			public function get_base_dir(){
				$full_path = getcwd();
				$ar = explode( "wp-", $full_path );
				return rtrim( $ar[0], '\\/' );
			}


			public function the_base_dir(){
				echo $this->get_base_dir();
			}


			/**
			 * @return string
			 */
			public function get_base_url(){
				$root = $this->get_base_dir();
				$query = ltrim( str_replace( '\\', '/', dirname( $_SERVER['PHP_SELF'] ) ), '/' );
				$rootArr = array();
				$queryArr = array();
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
				$https = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443;

				return ( rtrim( 'http' . ( $https ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'] . '/' . $r, '/' ) );
			}


			public function the_base_url(){
				echo $this->get_base_url();
			}


			/**
			 * Return TRUE, if base dir is change
			 * @return bool
			 */
			public function get_base_dir_change(){
				$oldpath = get_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', $this->get_base_dir() );

				return $oldpath != $this->get_base_dir();
			}


			/**
			 * @return string
			 */
			public function get_plugin_url(){
				$baseUrl = $this->get_base_url();
				$baseDir = $this->get_base_dir();
				$sufix = str_replace( $baseDir, '', HW_MIGRATION_SIMPLE_DIR );
				return $baseUrl . '/' . $sufix;
			}


			/**
			 * @param string $newUrl
			 * @param string $oldUrl
			 */
			public function do_site_migrate( $newUrl = '', $oldUrl = '' ){
				$oldBaseDir = get_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir' );
				$baseDir = $this->get_base_dir();
				update_option( HW_MIGRATION_SIMPLE_PREFIX . '_basedir', $baseDir );
				if( trim( $oldUrl ) == '' )
					$oldUrl = get_option( 'siteurl' );
				if( trim( $newUrl ) == '' )
					$newUrl = $this->get_base_url();
				///
				$this->do_DB_change( $oldUrl, $newUrl, $oldBaseDir, $baseDir );
				///
				$this->do_rewrite_rules( $baseDir );
				///
				include_once HW_MIGRATION_SIMPLE_DIR . '/template/frontend.php';
				die;
			}


			/**
			 * @param      $oldUrl
			 * @param      $newUrl
			 * @param null $oldBaseDir
			 * @param null $newBaseDir
			 */
			public function do_DB_change( $oldUrl, $newUrl, $oldBaseDir = null, $newBaseDir = null ){
				global $wpdb;
				///
				$oldUrl = rtrim( $oldUrl, '/\\' );
				$newUrl = rtrim( $newUrl, '/\\' );
				$oldUrlEncode = urlencode( rtrim( $oldUrl, '/\\' ) );
				$newUrlEncode = urlencode( rtrim( $newUrl, '/\\' ) );
				$oldUrlTrim = preg_replace( '/^http(s)?:\/\//', '', $oldUrl );
				$newUrlTrim = preg_replace( '/^http(s)?:\/\//', '', $newUrl );
				$compareUrls = ( strpos( $oldUrlTrim, $newUrlTrim ) !== false || strpos( $newUrlTrim, $oldUrlTrim ) );
				///
				$oldBaseDir = rtrim( $oldBaseDir, '/\\' );
				$newBaseDir = rtrim( $newBaseDir, '/\\' );
				foreach( $wpdb->tables() as $table ){
					$columns = $wpdb->get_results( 'SHOW COLUMNS FROM ' . $table );
					foreach( $columns as $column ){
						$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldUrlEncode','$newUrlEncode')";
						$wpdb->query( $query );
						$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldUrl','$newUrl')";
						$wpdb->query( $query );
						if( !$compareUrls ){
							$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldUrlTrim','$newUrlTrim')";
							$wpdb->query( $query );
						}
						$query = "UPDATE " . $table . " SET $column->Field = REPLACE($column->Field, '$oldBaseDir','$newBaseDir')";
						$wpdb->query( $query );
					}
				}
			}


			/**
			 * Return all URLs from DB
			 * @return array
			 */
			public function get_DB_urls(){
				global $wpdb;
				$R = array();
				foreach( $wpdb->tables() as $table ){
					$columns = $wpdb->get_results( 'SHOW COLUMNS FROM ' . $table );
					foreach( $columns as $column ){
						$regex = 'https?(://|%3A%2F%2F)';
						$query = 'SELECT `' . $column->Field . '` FROM `' . $table . '` WHERE `' . $column->Field . '` REGEXP \'' . $regex . '\'';
						$result = $wpdb->get_results( $query );
						if( is_array( $result ) && count( $result ) > 0 ){
							foreach( $result as $item ){
								preg_match_all( '#\bhttp(s)?(://|%3A%2F%2F)[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', reset( $item ), $match );
								if( isset( $match[0] ) && is_array( $match[0] ) && count( $match[0] ) > 0 ){
									foreach( $match[0] as $url ){
										$url = urldecode( $url );
										$parse = parse_url( $url );
										$url = $parse['scheme'] . '://' . $parse['host'];
										if( strlen( $url ) > 128 )
											continue;
										if( !isset( $R[ $url ] ) )
											$R[ $url ] = 0;
										$R[ $url ] ++;
									}
								}
							}
						}
					}
				}
				arsort( $R );
				return $R;
			}


			/**
			 * @param $baseDir
			 */
			public function do_rewrite_rules( $baseDir ){
				require_once $baseDir . '/wp-admin/includes/file.php';
				require_once $baseDir . '/wp-admin/includes/misc.php';
				if( function_exists( 'save_mod_rewrite_rules' ) )
					save_mod_rewrite_rules();
				if( function_exists( 'iis7_save_url_rewrite_rules' ) )
					iis7_save_url_rewrite_rules();
			}


			/**
			 * @return hw_migration_simple_hooks
			 */
			public function hooks(){
				static $class;
				if( !$class instanceof hw_migration_simple_hooks )
					$class = new hw_migration_simple_hooks();
				return $class;
			}

		}


		class hw_migration_simple_hooks{

			public function init(){
				$B = load_plugin_textdomain( 'hw-migration-simple', false, HW_MIGRATION_SIMPLE_DIR . '/languages' );
			}


			/**
			 * @return false|string
			 */
			public function add_submenu_page(){
				return add_submenu_page( HW_MIGRATION_SIMPLE_AM_SHOWINMENU, 'hiWeb Migration Simple', 'hiWeb Migration S', 'manage_options', HW_MIGRATION_SIMPLE_AM_SLUG, array( $this, 'add_submenu_page_echo' ) );
			}


			public function add_submenu_page_echo(){
				if( isset( $_POST['new_domain'] ) ){
					$old_domain = $_POST['old_domain'];
					$new_domain = $_POST['new_domain'];
					if( isset( $_POST['confirm'] ) ){
						if( trim( $new_domain ) == '' ){
							exit( 'Domain is not set...' );
						} else {
							hiweb_migration_simple()->do_site_migrate( $new_domain, $old_domain );
							include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate-done.php';
						}
					} else {
						if( trim( $new_domain ) == '' ){
							$new_domain = hiweb_migration_simple()->get_base_url();
							$_POST['new_domain'] = $new_domain;
						}
						if( trim( $old_domain ) == '' ){
							$old_domain = get_option( 'siteurl' );
							$_POST['old_domain'] = $old_domain;
						}
						include HW_MIGRATION_SIMPLE_DIR . '/template/force-re-migrate.php';
					}
				} else {
					include HW_MIGRATION_SIMPLE_DIR . '/template/options.php';
				}
			}


			public function plugin_action_links( $actions = array(), $plugin_file = '', $plugin_data = array(), $context = null ){
				if( $plugin_file == 'hiweb-migration-simple/hiweb-migration-simple.php' ){
					$actions['tool_panel'] = '<a href="' . self_admin_url( 'tools.php?page=hw_migration_simple' ) . '" class="tool_panel" aria-label="Открыть панель hiWeb Migration Simple">' . __( 'Migration Panel', 'hw-migration-simple' ) . '</a>';
				}
				return $actions;
			}
		}
	}