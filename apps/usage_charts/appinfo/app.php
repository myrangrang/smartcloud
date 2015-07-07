<?php

/**
* ownCloud - Usage Charts plugin Forked from DjazzLab Storage Charts Plugin
* @author Alan Vallance
* @author Xavier Beurois
* @copyright 2013 Pierre Fauconnier
* @copyright 2012 Xavier Beurois www.djazz-lab.net
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/
// ownCloud calls appinfo/app.php every time it runs. This is the place to
// Register navigation entries, and connect signals and slots

OCP\App::register(Array(
	'id'	=> 'usage_charts',
	'order'	=> 60,
	'name'	=> 'Usage Charts'
));

OCP\App::addNavigationEntry(Array(
	'id'	=> 'usage_charts',
	'order'	=> 60,
	'href'	=> OCP\Util::linkTo('usage_charts', 'charts.php'),
	'icon'	=> OCP\Util::imagePath('usage_charts', 'chart.png'),
	'name'	=> 'Usage'
));

OC::$CLASSPATH['oc_usage_charts'] = OC_App::getAppPath('usage_charts').'/lib/db.class.php';
OC_Hook::connect('OC_Filesystem','post_write',	'oc_usage_charts','must_dirstat');
OC_Hook::connect('OC_Filesystem','postdelete',	'oc_usage_charts','must_dirstat');
OC_Hook::connect('OC_User'	,'logout',	'oc_usage_charts','true_dirstat');
OCP\BackgroundJob::AddRegularTask(		'oc_usage_charts','cron_dirstat');
if ( array_key_exists('HTTP_RAW_POST_DATA',$GLOBALS) )	oc_usage_charts::cron_dirstat();
/*
$myMsg = '...  GLOBALS';
foreach ( $GLOBALS as $k => $v ) {
	$myMsg .= "[$k]=>$v, ";
}
OCP\Util::writeLog('usage_charts', oc_usage_charts::milliStamp()."  $myMsg  ", OCP\Util::ERROR);
*/
?>