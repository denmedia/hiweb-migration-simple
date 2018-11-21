<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 21/11/2018
	 * Time: 20:52
	 */

	namespace hiweb_migration_simple;


	class db{



		/**
		 * @param $string
		 * @return bool
		 */
		static function is_serialize( $string ){
			$data = @unserialize( $string );
			return ( $data !== false );
		}


		/**
		 * @param $columns
		 * @return bool|string
		 */
		static private function get_columns_pri_key( $columns ){
			if( !is_array( $columns ) ) return false;
			foreach( $columns as $column ){
				if( $column->Key == 'PRI' ){
					return $column->Field;
				}
			}
			return false;
		}


		/**
		 * Return queries array, like [query => bool]
		 * @param      $oldUrl
		 * @param      $newUrl
		 * @param null $oldBaseDir
		 * @param null $newBaseDir
		 * @return array
		 */
		static function do_DB_change( $oldUrl, $newUrl, $oldBaseDir = null, $newBaseDir = null ){
			/** @var \wpdb $wpdb */
			global $wpdb;
			///
			$replacePairs = [
				rtrim( $oldBaseDir, '/\\' ) => rtrim( $newBaseDir, '/\\' ),
				rtrim( $oldUrl, '/\\' ) => rtrim( $newUrl, '/\\' ),
				urlencode( rtrim( $oldUrl, '/\\' ) ) => urlencode( rtrim( $newUrl, '/\\' ) )
			];
			///
			$R = [];
			foreach( $wpdb->tables() as $table ){
				$columns = $wpdb->get_results( 'SHOW COLUMNS FROM ' . $table );
				$pri = self::get_columns_pri_key( $columns );
				if( is_string( $pri ) ){
					foreach( $columns as $column ){
						foreach( $replacePairs as $from => $to ){
							if( $from != $to ){
								$query = 'SELECT * FROM ' . $table . ' WHERE `' . $column->Field . '` LIKE \'%' . $from . '%\'';
								$result = $wpdb->get_results( $query );
								if( is_array( $result ) && count( $result ) > 0 ){
									foreach( $result as $row ){
										$row = (array)$row;
										if( isset( $row[ $column->Field ] ) ){
											$content_from = $row[ $column->Field ];
											if( self::is_serialize( $content_from ) && strpos( $content_from, $from ) !== false ){
												$json_from = json_encode( unserialize( $content_from ) );
												$json_to = str_replace( str_replace( '/', '\/', $from ), str_replace( '/', '\/', $to ), $json_from );
												$content_to = serialize( json_decode( $json_to, true ) );
												$query = $wpdb->prepare( 'UPDATE ' . $table . ' SET ' . $column->Field . '="%s" WHERE ' . $pri . '="%d"', $content_to, $row[ $pri ] );
											} else {
												$content_to = str_replace( $from, $to, $content_from );
												$query = $wpdb->prepare( 'UPDATE ' . $table . ' SET ' . $column->Field . '="%s" WHERE ' . $pri . '="%d"', $content_to, $row[ $pri ] );
											}
											$B = $wpdb->query( $query );
											if($B) $R[ $query ] = $B;
										}
									}
								}
							}
						}
					}
				}
			}
			return $R;
		}




		/**
		 * Return all URLs from DB
		 * @return array
		 */
		static function get_DB_urls(){
			/** @var \wpdb $wpdb */
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

	}