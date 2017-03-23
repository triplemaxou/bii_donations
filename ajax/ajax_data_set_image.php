<?php
//ini_set('display_errors', '1');
require_once(plugin_dir_path(__FILE__) . "../config.php");

if (isset($_REQUEST["url"])) {
	$url = $_REQUEST["url"];
	$url = urldecode($url);
	
//	echo $url;
	$item = produit_image::fromUrl($url);
	$count = 0;
	if(isset($_REQUEST["count"])){
		$count = $_REQUEST["count"];
	}
	if(isset($_REQUEST["alt"])){
		$alt = $_REQUEST["alt"];
	}
	$item->dataSet($count,$url,$alt);
}else{
	?><p class="warning">class_name seems to be uninitialized</p><?php
}