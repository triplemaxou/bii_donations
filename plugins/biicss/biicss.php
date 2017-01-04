<?php

/*
  Plugin Name: Biicss
  Description: Ajoute bootstrap et font awesome sur le site et son back office
  Version: 1.2
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('bii_css_version', '1.2');

function biicss_enqueueCSS() {
	if (isset($_GET["page"]) && (strpos($_GET["page"], "bii") !== false)||(strpos($_GET["page"], "_list") !== false)||(strpos($_GET["page"], "_edit") !== false) ) {
		wp_enqueue_style('bootstrap', plugins_url('css/bootstrap.css', __FILE__));
//	wp_enqueue_style('bootstrap-theme', plugins_url('css/bootstrap-theme.css', __FILE__));
		wp_enqueue_style('font-awesome', plugins_url('css/font-awesome.min.css', __FILE__));
//	wp_enqueue_style('stylepage', plugins_url('css/style.css', __FILE__));
	}
}

add_action('admin_enqueue_scripts', 'biicss_enqueueCSS');
