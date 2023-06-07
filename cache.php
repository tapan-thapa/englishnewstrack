<?php
define('BASEPATH', "foobar");
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
include(__DIR__."/application/config/database.php");

$active_db = $argv[1];
$dbDetail = $db[$active_db];

$conn = new mysqli($dbDetail["hostname"], $dbDetail["username"], $dbDetail["password"], $dbDetail["database"], $dbDetail["port"]);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if ($result = $conn -> query("SELECT * FROM cache_invalidation limit 100")) {
	$urlsArr = $urlsIds = [];
	while($row = $result -> fetch_assoc()){
		$urlsIds[] = $row["id"];
		$lang_id = $row["lang_id"];
		if($lang_id == 1){
			$domain = "newstrack.com";
		}elseif($lang_id == 2){
			$domain = "english.newstrack.com";	
		}elseif($lang_id == 3){
			$domain = "apnabharat.org";	
		}
		if($active_db == "staging" && $lang_id == 1){
			$domain = "alpha.newstrack.com";
		}elseif($active_db == "staging" && $lang_id == 2){
			$domain = "eng.newstrack.com";
		}elseif($active_db == "staging" && $lang_id == 3){
			$domain = "apnabharat.newstrack.com";
		}

		$urls = $row["urls"]; 
		if(!empty($urls)){
			$urls = json_decode($urls);
			foreach ($urls as $key => $value) {
				$urlsArr[$value] = $value;
				$output = shell_exec("/usr/bin/varnishadm 'ban req.http.host == $domain &&  req.url ~ $value'"); 
			}
		}
	}
	//print_r($urlsArr);
	if(is_array($urlsIds) && count($urlsIds)){
		$conn -> query("DELETE FROM cache_invalidation WHERE id IN (". implode(",",$urlsIds).")");
	}
	//print_r($urlsIds);
	$result -> free_result();
}


?>
