<?php 
/*
Plugin Name: CSCT Creater
Plugin URI: http://code-net.com/
Description: This plugin automatically generates content types for a WordPress blog
Version: .5
Author: Matthew Duran
Author URI: http://code-net.com
License: GPL2
*/

function cspt_plugin(){
	$tax_options = [
	
	];
	require ('post-types.php');
	require ('tax-types.php');
}


add_action('plugins_loaded', 'cspt_plugin');

?>