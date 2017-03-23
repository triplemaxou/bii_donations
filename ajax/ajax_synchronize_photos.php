<?php

//ini_set('display_errors', '1');
require_once(plugin_dir_path(__FILE__) . "../config.php");
$where = "";
if (bii_items::onSatellite()) {
	$id_vendeur = get_option("bii_user_id");
	$where = "id_vendeur = $id_vendeur";
}
$liste = produit::all_id($where);
foreach ($liste as $id) {
	$produit = new produit($id);
	$produit->synchronizeImages();
}