<?php
//ini_set('display_errors', '1');
require_once(plugin_dir_path(__FILE__) . "../config.php");

if (isset($_REQUEST["id_post"])) {
	$id_post = $_REQUEST["id_post"];
	$nb = 1;
	if (isset($_REQUEST["nb"])) {
		$nb = $_REQUEST["nb"];
	}
	$produit = produit::fromIdPost($id_post);
	$produit->mettrePanier($nb);
	
}else{
	?><p class="warning">id_post manquant</p><?php
}