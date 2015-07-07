<?php

// Are we enabled?
OCP\JSON::checkAppEnabled('virustotal');
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();

// Load VirusTotal API Class
include("virustotal/ajax/VirusTotalApiV2.php");

// Get our target
$source = $_GET['source'];
$dir = $_GET['dir'];
$file = $dir.$source;
if($info = \OC\Files\Filesystem::getLocalFile($file)) {
	$md5 = md5_file($info);
	$api = new VirusTotalAPIV2('ADD_YOUR_VT_API_HERE');
	$results = $api->getFileReport($md5);
	$checks = $api->getTotalNumberOfChecks($results);
	$hits = $api->getNumberHits($results);
	if ($hits >= 0 && $checks >= 0) {
		$buffer = sprintf("Score: %d/%d", 
				$hits,
				$checks);
	}
	else {
		$buffer = "File not found.";
	}
	OCP\JSON::success(array('data' => array($buffer)));
} else {
  OCP\JSON::error(array('data' => array('An Error occured.')));
};
