<?php
/*
  Plugin Name: BiiDebug
  Description: Ajoute des fonctions de débug, invisibles pour le public
  Version: 1.2
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('bii_debug_version', '1.2');

function biidebug_enqueueJS() {
	wp_enqueue_script('util', plugins_url('js/util.js', __FILE__), array('jquery'), false, true);

	wp_enqueue_script('lazyload2', plugins_url('js/lazyload.js', __FILE__), array('jquery'), false, true);
	wp_enqueue_script('manual-lazyload', plugins_url('js/manual-lazyload.js', __FILE__), array('jquery', 'lazyload2', 'util'), false, true);
}

biidebug_enqueueJS();

function bii_showlogs() {
	?>
	<script type="text/javascript" src="http://l2.io/ip.js?var=myip"></script>
	<script type="text/javascript">
		var ajaxurl = '<?= admin_url('admin-ajax.php'); ?>';
		var bloginfourl = '<?= get_bloginfo("url") ?>';
		var bii_showlogs = false;
		var ip_client = myip;
		if (ip_client == "77.154.194.84") {
			bii_showlogs = true;
		}
	</script>
	<?php
}

function get_user_role() {
	if(current_user_can("bii_admin")){
		return "admin";
	}
	if(current_user_can("edit_others_pages") && current_user_can("manage_job_listings") && current_user_can("bii_access_plugin")){
		return "adminlh";
	}
	if(current_user_can("edit_others_pages")){
		return "editeur";
	}
	if(current_user_can("manage_job_listings")){
		return "drh";
	}
	if(current_user_can("bii_access_plugin")){
		return "gestionnaire";
	}
	return "user";
}

function bii_admin_head() {
	?>
	<script type="text/javascript" src="http://l2.io/ip.js?var=myip"></script>
	<script type="text/javascript">

		var bloginfourl = '<?= get_bloginfo("url") ?>';
		var bii_showlogs = false;
		var ip_client = myip;
		if (ip_client == "77.154.194.84") {
			bii_showlogs = true;
		}
		var bii_userrole = '<?= get_user_role() ?>';
	</script>
	<?php
}

add_action('wp_head', 'bii_showlogs');
add_action('admin_head', 'bii_admin_head');


/* Retirer emojis */

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');


if (!function_exists("debugEcho")) {

	function debugEcho($string) {
		if ($_SERVER["REMOTE_ADDR"] == "77.154.194.84") {
			echo $string;
		}
	}

	function pre($item, $color = "#000") {
		if ($_SERVER["REMOTE_ADDR"] == "77.154.194.84") {
			echo "<pre style='color:$color'>";
			var_dump($item);
			echo "</pre>";
		}
	}

	function consoleLog($string) {
		if ($_SERVER["REMOTE_ADDR"] == "77.154.194.84") {
			$string = addslashes($string);
			?><script>console.log('<?php echo $string; ?>');</script><?php
		}
	}

	function consoleDump($var) {
		if ($_SERVER["REMOTE_ADDR"] == "77.154.194.84") {
//	ob_start();
//	var_dump($var);
//	$string = ob_get_contents();
//	ob_end_clean();
			?><script>console.log('<?php serialize($var); ?>');</script><?php
		}
	}

	function logQueryVars($afficherNull = false) {
		global $wp_query;
		foreach ($wp_query->query_vars as $key => $item) {
			if (!is_array($item)) {
				$$key = urldecode($item);
				if ($afficherNull) {
					consoleLog("$key => $item");
				} else {
					if ($item != "") {
						consoleLog("$key => $item");
					}
				}
			}
		}
	}

	function logRequestVars() {
		foreach ($_REQUEST as $key => $item) {
			if (!is_array($item)) {
				$$key = urldecode($item);
				consoleLog("$key => $item");
			}
		}
	}

	function logSESSIONVars() {
		foreach ($_SESSION as $key => $item) {
			if (!is_array($item)) {
				$$key = urldecode($item);
				pre("$key => $item");
			}
		}
	}

	function logGETVars() {
		foreach ($_GET as $key => $item) {
			if (!is_array($item)) {
				$$key = urldecode($item);
				consoleLog("$key => $item");
			} else {
				$log = "$key => {";
				foreach ($item as $key2 => $val) {
					$log .= " $key2=>$val";
				}
				$log .= "}";
				consoleLog($log);
			}
		}
	}

	function headersOK($url) {
		error_log("URL : " . $url);
		$return = false;
		$headers = @get_headers($url, 1);

		error_log("HEADER : " . print_r($headers, true));
		if ($headers[0] == 'HTTP/1.1 200 OK') {
			$return = true;
		}

		return $return;
	}

	function isHTTP($url) {
		$return = false;
		if (substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://') {
			$return = true;
		}
		return $return;
	}

	function startVoyelle($string) {
		$voyelle = false;
		$string = strtolower(remove_accents($string));
		$array_voyelles = array("a", "e", "i", "o", "u");
		if (in_array($string[0], $array_voyelles)) {
			$voyelle = true;
		}
		return $voyelle;
	}

	function stripAccents($string) {
		$string = htmlentities($string, ENT_NOQUOTES, 'utf-8');
		$string = preg_replace('#\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;#', '\1', $string);
		$string = preg_replace('#\&([A-za-z]{2})(?:lig)\;#', '\1', $string);
		$string = preg_replace('#\&[^;]+\;#', '', $string);
		return $string;
	}

	function stripAccentsLiens($string) {
		$string = mb_strtolower($string, 'UTF-8');
		$string = stripAccents($string);

		$search = array('@[ ]@i', '@[\']@i', '@[^a-zA-Z0-9_-]@');
		$replace = array('-', '-', '');

		$string = preg_replace($search, $replace, $string);
		$string = str_replace('--', '-', $string);
		$string = str_replace('--', '-', $string);

		return $string;
	}

	function stripAccentsToMaj($string) {
		$string = stripAccentsLiens($string);
		$string = str_replace('-', ' ', $string);
		$string = strtoupper($string);
		return $string;
	}

	function url_exists($url) {
		$file_headers = @get_headers($url);
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		} else {
			$exists = true;
		}
		return $exists;
	}

	function bii_write_log($log) {
		if (WP_DEBUG_LOG) {
			if (is_array($log) || is_object($log)) {
				error_log(print_r($log, true));
			} else {
				error_log($log);
			}
		}
	}

}