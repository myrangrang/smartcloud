<?php
/**
* ownCloud - Usage Charts plugin Forked from DjazzLab Storage Charts Plugin
* @author Alan Vallance
*/
// +-----------------------------------------------------------------------+
// | Copyright (C) 2013, ed-237                                            |
// +-----------------------------------------------------------------------+
// | This file is free software; you can redistribute it and/or modify     |
// | it under the terms of the CeCIll v2 as published by three French      |
// | public research organisations, the CEA, the CNRS and INRIA            |
// | This file is distributed in the hope that it will be useful           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of        |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the          |
// | CeCIll License for more details.                                      |
// | http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt              |
// +-----------------------------------------------------------------------+
// | Author: pFa                                                           |
// +-----------------------------------------------------------------------+
//
// Fired thru ajax (see chartsUsedStorage.js)

OCP\JSON::checkLoggedIn();
//OCP\JSON::checkAppEnabled('usage_charts');

OC::$CLASSPATH['oc_usage_chartsLoader'] = OC_App::getAppPath('usage_charts').'/lib/loader.class.php';

$l = new OC_L10N('usage_charts');

// Retrieve data for graph - fired via ajax (on documentready or on unitchange)
if ( array_key_exists('k',$_POST) ) {
	$c = $_POST['k'];
	if ( array_key_exists('u',$_POST)
	&&   is_numeric($_POST['u']) ) {
		switch($c) {
			case 'clines_usse':
				$myKey = 'hu_size';
				break;
			case 'chisto_us':
				$myKey = 'hu_size_hus';
				break;
			}
		if ( isset($myKey) ) {
// Optionally, set a new unit for the graph (KiloBytes, MegaBytes, etc.)
			oc_usage_charts::setUConfValue($myKey, $_POST['u']);
		}
	}
	switch ($c) {
	case 'clines_usse':
	case 'chisto_us':
	case 'cpie_rfsus':
		OCP\JSON::encodedPrint(Array('r' => oc_usage_chartsLoader::loadChart($c, $l)));
		break;
	}
}
?>