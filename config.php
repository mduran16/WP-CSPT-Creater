<?php 
/*
Plugin Name: WP CSCT Creater
>>>>>>> origin
Plugin URI: https://github.com/mduran16/WP-CSPT-Creater
Description: This plugin automatically generates content types for a WordPress blog
Version: 0.2
Author: Matthew Duran
Author URI: https://github.com/mduran16/
License: GPL2
*/

function cspt_plugin(){
	require ('post-types.php');
	require ('tax-types.php');
}
add_action('plugins_loaded', 'cspt_plugin');

?>