<?php
require_once(dirname(__FILE__) . '/google-api-php-client-master/src/Google/Client.php');
require_once(dirname(__FILE__) . '/google-api-php-client-master/src/Google/autoload.php');
require_once(dirname(__FILE__) . '/google-api-php-client-master/src/Google/Service/Analytics.php');

function getAnalyticsService() {
//	$contrat = "Clair et Net";
//	$hebergeur = "";
//	if (isset($_REQUEST["contrat"])) {
////		$contrat = $_REQUEST["contrat"];
//	}
//	if (isset($_REQUEST["hebergeur"])) {
////		$hebergeur = $_REQUEST["hebergeur"];
//	}
//	return getAnalyticsServicePrivate($contrat, $hebergeur);
	return getAnalyticsServicePrivate();
//	return getAnalyticsServicePublic();
}

function getAnalyticsClairEtNet() {
	return getAnalyticsServicePrivate();
}

function getAnalyticsPatakes() {
	return getAnalyticsServicePrivate("Patakes");
}

function getAnalyticsRodacom() {
	return getAnalyticsServicePrivate("Patakes", "Rodacom");
}

function getAnalyticsServicePublic() {
	if (!isset($_SESSION["former_code"])) {
		$_SESSION["former_code"] = 0;
	}

// create client object and set app name
	$client = new Google_Client();
	$client->setApplicationName("Tableau de bord"); // name of your app
// set assertion credentials
	$client->setAssertionCredentials(
		new Google_Auth_AssertionCredentials(
		"960486930754-0k18aplmpiu4ea61t9l0t80527renkog@developer.gserviceaccount.com", // email you added to GA
		array('https://www.googleapis.com/auth/analytics.readonly'), file_get_contents(dirname(__FILE__) . "/Tableau de Bord-067d761e2f90.p12")  // keyfile you downloaded
	));

	$client->setClientId("960486930754-0k18aplmpiu4ea61t9l0t80527renkog@developer.gserviceaccount.com");	 // from API console
	$client->setAccessType('offline_access');  // this may be unnecessary?
// create service and get data

	
	$analytics = new Google_Service_Analytics($client);
	return $analytics;
}

function getAnalyticsServicePrivate($contrat = "Clair et Net", $hebergeur = "") {
	if (!isset($_SESSION["former_code"])) {
		$_SESSION["former_code"] = 0;
	}

	$client = new Google_Client();

	if (isset($_SESSION["compteSelectionne"])) {
		unset($_SESSION["compteSelectionne"]);
	}

	if ($contrat == "Clair et Net") {
		$client->setApplicationName("Tableau de bord");
		$client->setDeveloperKey("stoked-dominion-92810");
		$client->setClientId('864571014554-ljtr7afpnu56kc98ef761n67mmohhuet.apps.googleusercontent.com');
		$client->setClientSecret('VwPconYEOmKljMCXU755swNF');
		$client->setRedirectUri('http://www.agence-digitale-rouen.fr/kokpit-api/connectgoogle.php');
		$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

		$_SESSION["compteSelectionne"] = $contrat;
	} elseif ($contrat == "Patakes") {
		if ($hebergeur == "Rodacom") {
			$client->setApplicationName("Tableau de bord");
			$client->setDeveloperKey("x-pivot-93008");
			$client->setClientId('960486930754-elgg37rlosj079tp1hhbop46kisubtjr.apps.googleusercontent.com');
			$client->setClientSecret('HpHAQauHRePlTRkoPrIdEc0Q');
			$client->setRedirectUri('http://www.agence-digitale-rouen.fr/kokpit-api/connectgoogle.php');
			$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

			$_SESSION["compteSelectionne"] = $hebergeur;
		} else {
			$client->setApplicationName("Kokpit Api");
			$client->setDeveloperKey("fabled-zone-93008");
			$client->setClientId('679388405559-fmb20ngu53pgm3dsbhphkkhqfp92ns3i.apps.googleusercontent.com');
			$client->setClientSecret('LI9qRTUhlrNG-lCB9iViuJCz');
			$client->setRedirectUri('http://www.agence-digitale-rouen.fr/kokpit-api/connectgoogle.php');
			$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

			$_SESSION["compteSelectionne"] = $contrat;
		}
	}
//For loging out.
	if (isset($_GET['logout']) && $_GET['logout'] == "1") {
		unset($_SESSION['token']);
	}

// Step 2: The user accepted your access now you need to exchange it.
	if (isset($_GET['code'])) {
		if ($_GET['code'] == $_SESSION["former_code"]) {
			return lien_auth($client);
		}
		$_SESSION["former_code"] = $_GET['code'];
//		 echo "f : ".$_SESSION["former_code"];
		$client->authenticate($_GET['code']);
		$_SESSION['token'] = $client->getAccessToken();
		$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?refresh=1";
		?>
		<div class="left">

			<div class="index-post">
				<div class="title">Information</div>
				<div class="triangle"></div>
				<div class="contenu">
					<p class="warning">
						Veuillez patienter, la suite est automatique
					</p>
				</div>
			</div>

		</div>
		<script> document.location.href = "<?php echo $redirect; ?>";</script><?php
//		header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	}

// Step 1:  The user has not authenticated we give them a link to login    
	if (!$client->getAccessToken() && !isset($_SESSION['token'])) {
		return lien_auth($client, $contrat, $hebergeur);
	}

// Step 3: We have access we can now create our service
	if (isset($_SESSION['token'])) {
		$tmtp_token = substr($_SESSION['token'], -11, 10); //La date de création du token
		if ($tmtp_token + 3599 < time()) { //si le token est expiré
			return lien_auth($client, $contrat, $hebergeur);
		}

//		print "<a class='bouton-google logout' href='" . $_SERVER['PHP_SELF'] . "?logout=1'>Déconnexion</a><br>";

		$client->setAccessToken($_SESSION['token']);

		$analytics = new Google_Service_Analytics($client);

		return $analytics;
//		runMainDemo($service);
	}
}

function lien_auth($client, $contrat, $hebergeur = "") {
	$authUrl = $client->createAuthUrl();
	$class_button = "primary";
	if ($contrat == "Patakes") {
		$class_button = "success";
	}
	if ($hebergeur != "") {
		$hebergeur = "pour l'hébergeur $hebergeur";
		$class_button = "info";
	}

	return "<a class='btn btn-$class_button bouton-google login' href='$authUrl'>Se connecter sur le compte Google Analytics <i class='fa fa-firefox'></i><i class='fa fa-opera'></i><i class='fa fa-chrome'></i></a>";
}

function getFromAnalytics(&$analytics, $profileId, $gaDimension = "sessions", $start_date = -1, $end_date = -1, $filters = array()) {
	$start_string = date("Y-m-d", setDate($start_date));
	$end_string = date("Y-m-d", setDate($end_date));

	if (count($filters)) {
		$filters = traduction_filters($filters);
		$optParams = array(
			'filters' => $filters
		);
		$results = $analytics->data_ga->get(
			'ga:' . $profileId, $start_string, $end_string, 'ga:' . $gaDimension, $optParams);
	} else {
		$results = $analytics->data_ga->get(
			'ga:' . $profileId, $start_string, $end_string, 'ga:' . $gaDimension);
	}
	return getResult($results);
//	var_dump($results->getRows());
}

function analyticsGetSources(&$analytics, $profileId, $start_date = -1, $end_date = -1) {
	$start_string = date("Y-m-d", setDate($start_date));
	$end_string = date("Y-m-d", setDate($end_date));

	$optParams = array(
		'dimensions' => "ga:medium",
		'sort' => '-ga:sessions',
	);
	$results = $analytics->data_ga->get(
		'ga:' . $profileId, $start_string, $end_string, 'ga:sessions', $optParams);

	return getResultArray($results);
}

function analyticsGetBounceRate(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "bounceRate", $start_date, $end_date, $filters);
}

function analyticsGetSessions(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "sessions", $start_date, $end_date, $filters);
}

function analyticsGetSessionsFromCountry(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "sessions", $start_date, $end_date, $filters);
}

function analyticsGetSessionsFromRegion(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "sessions", $start_date, $end_date, $filters);
}

function analyticsGetPageView(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "pageviews", $start_date, $end_date, $filters);
}

function analyticsGetUniquePageView(&$analytics, $profileId, $start_date = -1, $end_date = -1, $filters = array()) {
	return getFromAnalytics($analytics, $profileId, "uniquePageviews", $start_date, $end_date, $filters);
}

function analyticsGetSessionsListe(&$analytics, $profileId, $start_date = -1, $end_date = -1) {
	$page_path = "/immobilier/";
	return analyticsGetSessions($analytics, $profileId, $start_date, $end_date, $page_path);
}

function analyticsGetCountries(&$analytics, $profileId, $start_date, $end_date, $limit = -1) {
	$start_string = date("Y-m-d", setDate($start_date));
	$end_string = date("Y-m-d", setDate($end_date));
	$optParams = array(
		'dimensions' => "ga:country",
		'sort' => '-ga:sessions'
	);
	if ($limit > 0) {
		$optParams["max-results"] = $limit;
	}
	$results = $analytics->data_ga->get(
		'ga:' . $profileId, $start_string, $end_string, 'ga:sessions', $optParams);

	return getResultArray($results);
//	var_dump($results->getRows());
}

function analyticsGetFrenchRegions(&$analytics, $profileId, $start_date, $end_date, $limit = -1) {
	$start_string = date("Y-m-d", setDate($start_date));
	$end_string = date("Y-m-d", setDate($end_date));
	$optParams = array(
		'dimensions' => "ga:region",
		'filters' => "ga:country==France",
		'sort' => '-ga:sessions',
	);
	if ($limit > 0) {
		$optParams["max-results"] = $limit;
	}
	$results = $analytics->data_ga->get(
		'ga:' . $profileId, $start_string, $end_string, 'ga:sessions', $optParams);

	return getResultArray($results);
//	var_dump($results->getRows());
}

function analyticsGetWebPages(&$analytics, $profileId, $start_date, $end_date, $limit = -1) {
	$start_string = date("Y-m-d", setDate($start_date));
	$end_string = date("Y-m-d", setDate($end_date));

	$optParams = array(
		'dimensions' => "ga:pagePath",
		'sort' => '-ga:sessions',
	);
	if ($limit > 0) {
		$optParams["max-results"] = $limit;
	}
	$results = $analytics->data_ga->get(
		'ga:' . $profileId, $start_string, $end_string, 'ga:sessions', $optParams);

	return getResultArray($results);
//	var_dump($results->getRows());
}

function getResult($results) {
	$return = 0;
	if (count($results->getRows()) > 0) {
//		$profileName = $results->getProfileInfo()->getProfileName();
		$rows = $results->getRows();
		$sessions = $rows[0][0];

		$return = $sessions;
	}
	return $return;
}

function getResultArray($results) {
	$return = array();
	if (count($results->getRows()) > 0) {
//		$profileName = $results->getProfileInfo()->getProfileName();
		$rows = $results->getRows();
		foreach ($rows as $val) {
			$key = $val[0];
			$value = $val[1];
			$return[$key] = $value;
		}
	}
	return $return;
}

function setDate($date = -1) {
	if ($date == -1) {
		$date = time();
	}
	return $date;
}

function accountSummaries(&$analytics) {
	$accounts = $analytics->management_accountSummaries->listManagementAccountSummaries();
	foreach ($accounts->getItems() as $item) {
		if ($item["name"] == "Compte6") {
			foreach ($item->getWebProperties() as $wp) {
				$wpid = $wp["id"];
				$wpname = $wp["name"];
				$value = str_ireplace("-", "- ", $wpname);
				$value = ucwords(strtolower($value));
				$value = str_ireplace("- ", "-", $value);
				$value = str_ireplace("- ", "-", $value);
				$value = str_ireplace(" -", "-", $value);


				$views = $wp->getProfiles();
				if (!is_null($views)) {
					foreach ($wp->getProfiles() as $view) {
						$vid = $view["id"];
						echo "£$vid ___ $value<br />";
					}
				}
			}
		}
	}
}

function traduction_filters($filters = array(), $combine = "or") {
	$traduction = "";
	$operator = ","; // Opérateur or
	if ($combine == "and") {
		$operator = ";";
	}
	$pagePath = array("chemin", "page", "pagePath");
	$country = array("pays", "country");
	$region = array("région", "region");
	$city = array("ville", "city");
	$i = 0;
	foreach ($filters as $key => $value) {
		if ($i) {
			$traduction .= $operator;
		}
		if (in_array($key, $pagePath)) {
			$val = traduction_value($value);
			$traduction.= "ga:pagePath$val";
		}
		if (in_array($key, $country)) {
			$val = traduction_value($value);
			$traduction.= "ga:country$val";
		}

		if (in_array($key, $region)) {
			$val = traduction_value($value);
			$traduction.= "ga:region$val";
		}

		if (in_array($key, $city)) {
			$val = traduction_value($value);
			$traduction.= "ga:city$val";
		}
		$i++;
	}
	return $traduction;
}

function traduction_value($value) {
	$string = "";
	if (is_array($value)) {
		$op = $value[0];
		$operator = "==";  //Opérateur égal
		if ($op == "not equal") {
			$operator = "!=";
		}
		if ($op == "like") {
			$operator = "=@"; //Contient la sous chaine
		}
		if ($op == "not like") {
			$operator = "!@"; //Ne contient pas la sous chaine
		}
		if ($op == "match") {
			$operator = "=~"; //Match avec l'expression régulière (value)
		}
		if ($op == "not match") {
			$operator = "!~"; //Ne match pas avec l'expression régulière (value)
		}
		$val = $value[1];
	} else {
		$operator = "==";
		$val = $value;
	}
	$string = $operator . $val;
	return $string;
}
?>

