<?php

require_once(dirname(__FILE__) . '/../class/token_facebook.php');

//public graph
function getAccountList() {
	$method = "data";
	return getFromGraph($method, "1569860316636037", "accounts");
}

function getCountLikeFacebook($start_date, $end_date,$page = "docks76") {
	$method = "likes";
	return getFromGraph($method,$start_date, $end_date, $page);
}

function getCountTalkingAboutFacebook($start_date, $end_date,$page = "docks76") {
	$method = "talking_about_count";
	return getFromGraph($method,$start_date, $end_date, $page);
}

function getEventsFacebook($start_date, $end_date, $page = "docks76",$limit = 250) {
	$method = "data";
	return getFromGraph($method,$start_date, $end_date, $page, "feed",$limit);
}
function getCommentsFacebook($start_date, $end_date, $page = "docks76") {
	$method = "data";
	$data = getFromGraph($method,null, null, $page, "comments",250);
	$data2 = array();
	foreach($data as $item){
		$date = fbDateToTimestamp($item->created_time);
		if($date < $end_date){
			$data2[] = $item;
		}
	}
	return $data2;
}

function fbDateToTimestamp($string){
	$expl1 = explode("+", $string);
	$expl2 = explode("T", $expl1[0]);
	$explDate = explode("-", $expl2[0]);
	$explHour = explode(":", $expl2[1]);
	$year = $explDate[0];
	$month = $explDate[1];
	$day = $explDate[2];
	$hour = $explHour[0];
	$minute = $explHour[1];
	$second = $explHour[2];
	return mktime($hour, $minute, $second, $month, $day, $year);
}

function getFromGraph($method,$start_date,$end_date, $page, $path = null, $limit = null) {
	$token = getFbToken();
	$pathstring = "";
	if($start_date > $end_date){
		$d = $end_date;
		$end_date = $start_date;
		$start_date = $d;
	}
	if($start_date != null){
		$start_date = "&since=$start_date";
	}
	if($end_date != null){
		$end_date = "&until=$end_date";
	}
	
	if($path != null){
		$pathstring = "/".urlencode($path)."/";
	}
	if($limit != null){
		$limit = "&limit=$limit";
	}
	
	$url = "https://graph.facebook.com/" . urlencode($page)."$pathstring?access_token=$token$start_date$end_date$limit";
//	echo $url ."<br />";
	$data = json_decode(file_get_contents($url));

	if (!isset($data->$method)) {
		return '0';
	}

	return $data->$method;
}



function fetchUrl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);

	$feedData = curl_exec($ch);
	curl_close($ch);

	return $feedData;
}

function getFbToken($app_id = "774037256043239", $app_secret = "f8bd2a5dc91b5185e0bff2b5bb024810") {
//	$date = time();
//	$where = "app_id = '$app_id' and app_secret = '$app_secret' and date_expiration > $date";
//	$id_token = 0;
//	$nb = token_facebook::nb($where);
//	if ($nb) {
//		foreach (token_facebook::all_id($where) as $id_token) {
//			$tf = new token_facebook($id_token);
//			$token = $tf->token();
//		}
//	} else {
//		$expiration = $date + 7 * 3600 - 1;
//		$url = "https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id=$app_id&client_secret=$app_secret";
//		echo "$url <br>";
//		$access_token = fetchUrl($url);
//		$token = substr($access_token, strpos($access_token, '=') + 1);
//		$tf = new token_facebook();
//		$tf->insert();
//		$tf->updateChamps($token, "token");
//		$tf->updateChamps($app_id, "app_id");
//		$tf->updateChamps($app_secret, "app_secret");
//		$tf->updateChamps($expiration, "date_expiration");
//	}
	$token = $app_id.'|'.$app_secret;
	return $token;
}
