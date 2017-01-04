<?php

/*
  Plugin Name: Bii advanced shortcodes
  Description: Ajoute des shortcodes avancés
  Version: 1.0
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('biiadvanced_shortcodes', '1.0');

function bii_SC_displaywhenrequest($atts,$content = null){
	$display = true;
	foreach($atts as $attr=>$value){
		$display = false;
		if(isset($_REQUEST[$attr]) && ($_REQUEST[$attr] == $value || $value=="all")){
			$display = true;
		}
	}
	$return  = "";
	if($display){
		$return = do_shortcode($content);
	}
	return $return;
}
function bii_SC_notdisplaywhenrequest($atts,$content = null){
	foreach($atts as $attr=>$value){
		$display = true;
		if(isset($_REQUEST[$attr]) && ($_REQUEST[$attr] == $value || $value=="all")){
			$display = false;
		}
	}
	$return  = "";
	if($display){
		$return = do_shortcode($content);
	}
	return $return;
}

add_shortcode('bii_displaywhenrequest', 'bii_SC_displaywhenrequest');
add_shortcode('bii_notdisplaywhenrequest', 'bii_SC_notdisplaywhenrequest');