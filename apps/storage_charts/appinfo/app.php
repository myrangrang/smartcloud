<?php

/**
* ownCloud - DjazzLab Storage Charts plugin
*
* @author	Xavier Beurois
* @copyright	2013-2014 Pierre Fauconnier
* @copyright	2012 Xavier Beurois www.djazz-lab.net
* @site		http://oc-apps2.sourceforge.net/
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
	'id'	=> 'storage_charts',
	'order'	=> 60,
	'name'	=> 'Storage Charts'
));

OCP\App::addNavigationEntry(Array(
	'id'	=> 'storage_charts',
	'order'	=> 60,
	'href'	=> OCP\Util::linkTo('storage_charts', 'charts.php'),
	'icon'	=> OCP\Util::imagePath('storage_charts', 'chart.png'),
	'name'	=> 'DL Charts'
));

OC::$CLASSPATH['OC_DLStCharts'] = OC_App::getAppPath('storage_charts').'/lib/db.class.php';
OC_Hook::connect('OC_Filesystem','post_write',	'OC_DLStCharts','must_dirstat');
OC_Hook::connect('OC_Filesystem','postdelete',	'OC_DLStCharts','must_dirstat');
OC_Hook::connect('OC_User'	,'logout',	'OC_DLStCharts','true_dirstat');
OCP\BackgroundJob::AddRegularTask(		'OC_DLStCharts','cron_dirstat');
if ( array_key_exists('HTTP_RAW_POST_DATA',$GLOBALS) )	OC_DLStCharts::cron_dirstat();
/*
$myMsg = '...  GLOBALS';
foreach ( $GLOBALS as $k => $v ) {
	$myMsg .= "[$k]=>$v, ";
}
OCP\Util::writeLog('storage_charts', OC_DLStCharts::milliStamp()."  $myMsg  ", OCP\Util::ERROR);
*/
?>