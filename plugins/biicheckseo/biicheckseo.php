<?php

/*
  Plugin Name: Biicheckseo
  Description: Ajoute des scripts premettant de vérifier l'optimisation SEO des pages
  Version: 1.1
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */

define('bii_checkseo_version', '1.1');

function biicheckseo_enqueueJS() {
	if (!get_option("bii_hideseo")) {
		update_option("bii_hideseo", 0);
		wp_enqueue_script('seoscript', plugins_url('js/seo.js', __FILE__), array('jquery', 'util'), false, true);
	}
}

biicheckseo_enqueueJS();
