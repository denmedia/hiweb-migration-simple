<?php
/*
Plugin Name: hiWeb Migration Simple
Plugin URI: http://hiweb.moscow/migration-simple
Description: Plugin to automatically change the paths and links in the database of your site on wordpress. Just migrate files and the site database to a new hosting.
Version: 1.3.0.0
Author: Den Media
Author URI: http://hiweb.moscow
*/

require 'inc/define.php';
require 'inc/functions.php';
require 'inc/options.php';

_hw_migration_simple_init();