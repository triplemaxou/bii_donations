<?php

//define('WP_MAX_MEMORY_LIMIT', '256M');
// <editor-fold desc="Include des classes">
function bii_listeClass() {
	$list = [
		"rpdo",
		"global_class",
		"bii_items",
		"donation",
		"cotisation",
		"pdf_template",
	];
	return $list;
}

function bii_includeClass() {
	$liste = bii_listeClass();
	$pdpf = plugin_dir_path(__FILE__);
	foreach ($liste as $item) {
		require_once($pdpf . "/class/$item.class.php");
	}
}

bii_includeClass();

//</editor-fold>
// <editor-fold desc="Gestion site satellite classes">


function bii_insertBDD($auto = true) {
	$_SESSION["bii_message"] = "";
	$allclass = bii_listeClass(); //liste des classes
	$sql = "";
	$list_extended = [];
	foreach ($allclass as $class) {
		if (method_exists($class, "classe_heritee") && $class::classe_heritee() == "bii_items") { //On récupère les classes enfants de bii_items
			$item = new $class();
			$list_extended[$class] = array_keys($item->tabPropValeurs());
		}
	}

	$extend_serialized = serialize($list_extended);
	$md5 = md5($extend_serialized);
	if (!get_option("bii_tables_created")) {
		$_SESSION["bii_message"] = "Tables insérées";
		//on insère les tables pour la première fois
		foreach ($list_extended as $item => $methods) {
			$sql.= $item::autoTable($auto); //requête création de table
		}
		update_option("bii_tables_created", 1);
		update_option("bii_tables", $extend_serialized);
		update_option("bii_tables_md5", $md5);
	} else {
		$md5_saved = get_option("bii_tables_md5");
		if ($md5 != $md5_saved) {
			bii_addmessage("Les tables ont été modifiées selon le code suivant : $md5");
			$list_saved = unserialize(get_option("bii_tables"));
			$liste_classes = [];
			foreach ($list_extended as $class => $methods) {
				if (!in_array($class, array_keys($list_saved))) { //une classe a été ajoutée
					$sql.=$class::autoTable($auto);
				} else {
					$sql.=$class::updateTable($auto);
				}
				$liste_classes[] = $class;
			}
			$deleted_classes = "";
			$countdel = 0;
			$sep = "";
			foreach ($list_saved as $class_saved => $foo) {
				if (!in_array($class_saved, $liste_classes)) {
					$sql.=bii_droptable($class_saved, $auto);
					$deleted_classes.= "$sep$class_saved ";
					$sep = ",";
					++$countdel;
				}
			}
			if ($countdel) {
				$s = "s";
				if ($countdel == 1) {
					$s = "";
				}
				bii_addmessage($deleted_classes . "table$s supprimmée$s", "warning");
			}
		}
		update_option("bii_tables", $extend_serialized);
		update_option("bii_tables_md5", $md5);
	}
	if (!$auto) {
		bii_addmessage(" requêtes " . $sql);
	}
}

bii_insertBDD(true);

//</editor-fold>
// <editor-fold desc="Gestion Back office">
function bii_menu() {

	add_menu_page(__(global_class::wp_slug_menu()), __(global_class::wp_titre_menu()), global_class::wp_min_role(), global_class::wp_nom_menu(), global_class::wp_dashboard_page(), global_class::wp_dashicon_menu());
	donation::displaySousMenu();
	cotisation::displaySousMenu();
}

add_action('admin_menu', 'bii_menu');

function bii_dashboard() {
	wp_enqueue_script('admin-init', plugins_url('/admin/js/dashboard.js', __FILE__), array('jquery'), null, true);
	include('admin/dashboard.php');
}

function bii_admin_styles() {
	wp_register_style('bii_admin_stylesheet', plugins_url('/admin/css/admin.css', __FILE__));
	wp_enqueue_style('bii_admin_stylesheet');
	wp_enqueue_script('bii_admin_custom', plugins_url('/admin/js/bii_custom.js', __FILE__), array('jquery'), null, true);
}

add_action('admin_enqueue_scripts', 'bii_admin_styles');

//</editor-fold>
// <editor-fold desc="Gestion Ajax">
function bii_ajax_change_value() {
	include("ajax/ajax_value.php");
	die();
}

function bii_ajax_delete() {
	include("ajax/ajax_delete.php");
	die();
}

function bii_ajax_synchronize_photos() {
	include("ajax/ajax_synchronize_photos.php");
	die();
}

function bii_ajax_addtocart() {
	include("ajax/ajax_add_to_cart.php");
	die();
}

function bii_ajax_deleteincart() {
	include("ajax/ajax_delete_in_cart.php");
	die();
}
function bii_add_cotisation() {
	include("ajax/ajax_add_cotisation.php");
	die();
}

function bii_ajax_change_instance() {
	if (isset($_REQUEST["newinstance"])) {
		update_option("bii_instance", $_REQUEST["newinstance"]);
	} else {
		?><p class="warning">pas d'instance selectionnée</p><?php
	}
	die();
}

//add_action('wp_ajax_bii_change_value', 'bii_ajax_change_value');
//add_action('wp_ajax_bii_delete', 'bii_ajax_delete');
//add_action('wp_ajax_bii_synchronize_photos', 'bii_ajax_synchronize_photos');
//
//add_action('wp_ajax_bii_addtocart', 'bii_ajax_addtocart');
//add_action('wp_ajax_nopriv_bii_addtocart', 'bii_ajax_addtocart');
//add_action('wp_ajax_bii_deleteincart', 'bii_ajax_deleteincart');
//add_action('wp_ajax_nopriv_bii_deleteincart', 'bii_ajax_deleteincart');

add_action('wp_ajax_bii_add_cotisation', 'bii_add_cotisation');
add_action('wp_ajax_nopriv_bii_add_cotisation', 'bii_add_cotisation');

/*function bii_send_mail() {
    include("ajax/ajax_test_send_mail.php");
    die();
}
add_action('wp_ajax_bii_test_mail', 'bii_send_mail');
*/

if (!bii_items::onSatellite()) {
	add_action('wp_ajax_bii_change_instance', 'bii_ajax_change_instance');
}

//</editor-fold>
// <editor-fold desc="Gestion Synchronisation">
//Synchronisation des produits
function bii_synchro_product() {
	
}

//Synchronisation des paniers
function bii_synchro_carts() {
	
}

function bii_cart_remove() {
	
}

function bii_cart_update($postcart) {

}

add_action('init', 'bii_synchro_product');
add_action('init', 'bii_synchro_carts');

//</editor-fold>
//<editor-fold desc="Shortcodes">
function bii_SC_messageremerciement($atts,$content = null){
	$migla = $_REQUEST["id"];
	bii_custom_log("Remerciement : ".$migla);
	$nom_classe = "donation";
	if(isset($_REQUEST["cotisation"])){
		$nom_classe = "cotisation";
	}
	return $nom_classe::static_message_remerciement($migla);
}
add_shortcode('bii_message_remerciement', 'bii_SC_messageremerciement');


function bii_sc_validate_don() {
    
    if (isset($_REQUEST['don'])) {
        $item = donation::from_token($_REQUEST['don']);
    } elseif (isset($_REQUEST['cotisation'])) {
        $item = cotisation::from_token($_REQUEST['cotisation']);
    }
    
    if ($item !== false) {
        $item->updateChamps(1, 'is_validate');
        $item->updateChamps('', 'key_validate');
        return "<p>La donation viens d'être validé.</p>";
    } else {
        return "<p>Erreur ! Le token n'est pas valide.</p>";
    }
}
add_shortcode('bii_sc_validate_don', 'bii_sc_validate_don');

//</editor-fold>
//<editor-fold desc="Gestion Woocommerce">
function bii_new_commande($args) {
//	client_commande::addClient($args);
}

function bii_cart_add($cart_item_key) {
	global $woocommerce;
//	pre($cart_item_key,"blue");
	$itemcart = $woocommerce->cart->cart_contents[$cart_item_key];
	$quantity = $itemcart["quantity"];
//	pre($itemcart,"purple");
	$produit = produit::translateCartItemKey($cart_item_key);
//	pre($produit,"blue");
	$produit->mettrePanier($quantity);
//	panier::synchroPaniers();
}

//add_action('woocommerce_after_checkout_validation', 'bii_new_commande');

//add_action('woocommerce_add_to_cart', 'bii_cart_add');
//</editor-fold>

add_filter("bii_after_wlb_capabilities","bii_after_wlb_capabilities",2,10);

function bii_after_wlb_capabilities($idinlist,$role_id){
	$output = "<div class='idinlist'>$idinlist</div>";	
		$output .= "<div class='ajoutcustom'><h3>Insérez un tableau à cocher</h3><input id='$role_id-bulk' class='bulk-values' value='' />&nbsp;"
			. '<input type="button" class="button-primary bulk-add" id="'.$role_id.'-bulk-add" value="Cocher les cases suivantes" />&nbsp;'
			. '<input type="button" class="button-primary bulk-remove" id="'.$role_id.'-bulk-remove" value="Déocher les cases suivantes" />&nbsp;'
			. '<input type="button" class="button-primary bulk-uncheck" id="'.$role_id.'-bulk-uncheck" value="Tout décocher" />&nbsp;'
			. ""
			. "</div>";	
		return $output;
}

// <editor-fold desc="Fonctions utilitaires">


function bii_droptable($table_name, $is_autoinserted = false) {
	$sql = "";
	if (strpos($table_name, "bii_") !== false) {
		global $wpdb;
		$sql = "DROP TABLE IF_EXISTS $table_name;";
		if ($is_autoinserted) {
			$wpdb->query($sql);
		}
	}

	return $sql;
}

function bii_addmessage($message, $type = "notice") {
	$message = "<div class='bii_info $type is-dismissible'><p>$message</p></div>";

	$_SESSION["bii_message"].= $message;
}

function setFilter(&$limit = "") {
	$filter = "";
	if (isset($_REQUEST["filter"])) {
		$filterbrut = $_REQUEST["filter"];

		$expl1 = explode('$AND$', $filterbrut);
		foreach ($expl1 as $item) {
			$expl = explode("$", $item);
			$champ_filter = $expl[0];
			$operator = $expl[1];
			$value_filter = '"' . $expl[2] . '"';

			if ($operator == "EQ") {
				$operator = "=";
			}
			if ($operator == "NOT") {
				$operator = "NOT IN (";
				$value_filter .= ")";
			}
			if ($operator == "IN") {
				$operator = "IN (;
			$value_filter .= )";
			}
			if ($operator == "LT") {
				$operator = "<";
			}
			if ($operator == "GT") {
				$operator = ">";
			}
			if ($operator == "LIKE") {
				$operator = "LIKE ";
				$value_filter = '"%' . $expl[2] . '%"';
				$value_filter .= "";
			}
			if ($operator == "BEGINWITH") {
				$operator = "LIKE ";
				$value_filter = '"' . $expl[2] . '%"';
				$value_filter .= "";
			}
			if ($operator == "ENDWITH") {
				$operator = "LIKE ";
				$value_filter = '"%' . $expl[2] . '"';
				$value_filter .= "";
			}

			$filter .= " and $champ_filter $operator $value_filter";
		}
	}
	if (isset($_REQUEST["limit"])) {
		$limit.= " limit " . $_REQUEST["limit"];
	}
	return $filter;
}

function autoRemplissageFilter() {
	$filter = array();
	if (isset($_REQUEST["filter"])) {
		$filterbrut = $_REQUEST["filter"];

		$expl1 = explode('$AND$', $filterbrut);
		foreach ($expl1 as $item) {
			$expl = explode("$", $item);
			$champ_filter = $expl[0];
			$operator = $expl[1];
			$value_filter = $expl[2];
			$filter[] = array(
				"champ_filter" => $champ_filter,
				"operator" => $operator,
				"value_filter" => $value_filter,
			);
		}
	}

	return $filter;
}

function firstDayOfMonth($format = "timestamp", $mois = null, $annee = null) {
	if ($annee == null) {
		$annee = date('Y');
	}
	if ($mois == null) {
		$mois = date('n');
	}

	$firstday = mktime(0, 1, 0, $mois, 1, $annee);
	//$mois : 
	//Les valeurs inférieures à 1 (y compris les valeurs négatives) font références aux mois de l'année précédente dans l'ordre inverse,
	// aussi, 0 correspond à décembre, -1 à novembre, etc. 
	if ($format == "timestamp") {
		return $firstday; //On a le timestamp du premier du mois à 00h01
	} else {
		return date($format, $firstday);
	}
}


function bii_cvnbst($nombre){
    $nb1 = Array('un','deux','trois','quatre','cinq','six','sept','huit','neuf','dix','onze','douze','treize','quatorze','quinze','seize','dix-sept','dix-huit','dix-neuf');

    $nb2 = Array('vingt','trente','quarante','cinquante','soixante','soixante','quatre-vingt','quatre-vingt');
    
    # Décomposition du chiffre
    # Séparation du nombre entier et des décimales
    if (preg_match("/\b,\b/i", $nombre)){
        $nombre = explode(',',$nombre);
    }else{
        $nombre = explode('.',$nombre);
    }
    $nmb = $nombre[0];
    
    # Décomposition du nombre entier par tranche de 3 nombre (centaine, dizaine, unitaire)
    $i=0;
    while(strlen($nmb)>0){
        $nbtmp[$i]=substr($nmb,-3);
        if( strlen($nmb)>3  ){
            $nmb=substr($nmb,0,strlen($nmb)-3);
        }else{
            $nmb='';
        }
        $i++;
    }
    $nblet='';
    ## Taitement du côté entier
    for($i=1;$i>=0;$i--){
        if(strlen(trim($nbtmp[$i]))==3){
            $ntmp=substr($nbtmp[$i],1);

            if(substr($nbtmp[$i],0,1)<>1 && substr($nbtmp[$i],0,1)<>0){
                $nblet.=$nb1[substr($nbtmp[$i],0,1)-1];
                if( $ntmp<>0 ){
                    $nblet.=' centime ';
                }else{
                    $nblet.=' centimes ';
                }
            }elseif( substr($nbtmp[$i],0,1)<>0 ){
                $nblet.='centime ';
            }
            
        }else{
          $ntmp=$nbtmp[$i];
        }

        if($ntmp>0 && $ntmp<20){
            if( !($i==1 && $nbtmp[$i]==1) ){
                $nblet.=$nb1[$ntmp-1].' ';
            }
        }

        if($ntmp>=20 && $ntmp<60){
            switch(substr($ntmp,1,1)){
                case 1 :    $sep=' et ';
                            break;
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }
            $nblet.=$nb2[substr($ntmp,0,1)-2].$sep.$nb1[substr($ntmp,1,1)-1].' ';
        }

        if($ntmp>=60 && $ntmp<80){
            $nblet.=$nb2[4];
            switch(substr($ntmp,1,1)){
                case 1 :    $sep=' et ';
                            break;
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }

                if(substr($ntmp,0,1)<>7){
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)-1].' ';
                }else{
                    if(substr($ntmp,1,1)+9==9)$sep='-';
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)+9].' ';
                }
            
        }

        if($ntmp>=80 && $ntmp<100){
            $nblet.=$nb2[6];
            switch(substr($ntmp,1,1)){
                case 1 :    $sep=' et ';
                            break;
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }
            
            //if(substr($ntmp,1,1)<>0){
                if(substr($ntmp,0,1)<>9){
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)-1];
                    if(substr($ntmp,1,1)==0)$nblet.='s';
                }else{
                    if(substr($ntmp,1,1)==0)$sep='-';
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)+9];
                }
            $nblet.=' ';
            //}elseif(substr($ntmp,0,1)<>9){
            //    $nblet.='s ';
            //}else{
            //    $nblet.=' ';
            //}
        }
        
        if($i==1 && $nbtmp[$i]<>0){
            if($nbtmp[$i]>1){
              $nblet.='milles ';
            }else{
              $nblet.='mille ';
            }
        }

    }

    if( $nombre[0]>1 )$nblet.='euros ';
    if( $nombre[0]==1 )$nblet.='euro ';

    ## Traitement du côté décimale
    if( $nombre[0]>0 && $nombre[1]>0 )$nblet.=' et ';
    $nombre1 = substr($nombre[1],0,2);
	if(strlen($nombre1) == 1){
		$nombre1 .= 0;
	}
	$ntmp=$nombre1;
	
    if( !empty($ntmp) ){
        if($ntmp>0 && $ntmp<20){
            $nblet.=$nb1[$ntmp-1].' ';
        }

        if($ntmp>=20 && $ntmp<60){
            switch(substr($ntmp,1,1)){
                case 1 :    $sep=' et ';
                            break;
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }
            $nblet.=$nb2[substr($ntmp,0,1)-2].$sep.$nb1[substr($ntmp,1,1)-1].' ';
        }

        if($ntmp>=60 && $ntmp<80){
            $nblet.=$nb2[4];
            switch(substr($ntmp,1,1)){
                case 1 :    $sep=' et ';
                            break;
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }

                if(substr($ntmp,0,1)<>7){
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)-1].' ';
                }else{
                    if(substr($ntmp,1,1)+9==9)$sep='-';
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)+9].' ';
                }
                
        }

        if($ntmp>=80 && $ntmp<100){
            $nblet.=$nb2[6];
            switch(substr($ntmp,1,1)){
                case 0 :    $sep='';
                            break;
                default:    $sep='-';
            }

                if(substr($ntmp,0,1)<>9){
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)-1];
                    if(substr($ntmp,1,1)==0)$nblet.='s';
                }else{
                    if(substr($ntmp,1,1)==0)$sep='-';
                    $nblet.=$sep.$nb1[substr($ntmp,1,1)+9];
                }
            $nblet.=' ';

        }

        if($ntmp<>0 && !empty($ntmp) ){
            if($ntmp>1){
                $nblet.='cents ';
            }else{
                $nblet.='cent ';
            }
        }
    }
        
return $nblet;

}
// </editor-fold>

function bii_generate_fiscal() {
    
    $where = " etat = 'paye' AND is_validate = 1 AND recu_send = 0 AND date_insert > ".strtotime("-1 month");
    
    $donsToSend = donation::all_id($where);
    if (is_array($donsToSend) && count($donsToSend) > 0) {
        foreach ($donsToSend as $idDon) {
            $don = new donation($idDon);
            $don->sendFiscal();
        }
    }
    
    $cotisationToSend = cotisation::all_id($where);
    if (is_array($cotisationToSend) && count($cotisationToSend) > 0) {
        foreach ($cotisationToSend as $idCot) {
            $cot = new donation($idCot);
            $cot->sendFiscal();
        }
    }
    
}

add_action('bii_generate_fiscal', 'bii_generate_fiscal');
if (!wp_next_scheduled('bii_generate_fiscal')) {
    wp_schedule_event(time(), 'daily', 'bii_generate_fiscal');
}

//wp_clear_scheduled_hook('bii_generate_fiscal');
//wp_clear_scheduled_hook('my_schedule_hook');