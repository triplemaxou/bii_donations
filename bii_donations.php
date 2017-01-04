<?php
/*
  Plugin Name: BiiDonations
  Description: Gestion des donations pour la ligue havraise
  Version: 0.1.0
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */

define ( 'bii_donation_version', '0.7.0' );

//Plugin biidebug, ajout de fonctions
require_once(plugin_dir_path(__FILE__) . "/plugins/biidebug/biidebug.php"); 

//Plugin biiadvanced admin, ajout de fonctionnalités ajax sur l'interface d'admin
require_once(plugin_dir_path(__FILE__) . "/plugins/biiadvanced-admin/biiadvanced-admin.php");

//Plugin biicss, ajout de bootstrap et font awesome
require_once(plugin_dir_path(__FILE__) . "/plugins/biicss/biicss.php");

//Plugin biicheckseo, ajout de scripts permettant de vérifier la SEO des pages parcourues
require_once(plugin_dir_path(__FILE__) . "/plugins/biicheckseo/biicheckseo.php");
//Plugin biicheckseo, ajout de shortcodes
require_once(plugin_dir_path(__FILE__) . "/plugins/biiadvanced_shortcodes/biiadvanced_shortcodes.php");

//Include du config
require_once(plugin_dir_path(__FILE__) . "config.php");
