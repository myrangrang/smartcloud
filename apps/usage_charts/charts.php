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

OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('usage_charts');
OCP\App::setActiveNavigationEntry('usage_charts');

// Save the new settings (graphs to draw) - see also app/templates/settings.tpl.php
if ( array_key_exists('usage_charts_disp', $_POST) ) {
	$myPost = $_POST['usage_charts_disp'];
	if (	isset($myPost)
	&&	( count($myPost) <= 3 ) ) {
		$myPost	= $_POST['usage_charts_disp'];
		$myDflt	= Array('cpie_rfsus'=>0,'clines_usse'=>0,'chisto_us'=>0);
		foreach ( array_keys($myDflt) as $myChart) {
			if ( in_array($myChart, $myPost) ) {
				$myDflt[$myChart] = 1;
			}
		}
		oc_usage_charts::setUConfValue('c_disp', serialize($myDflt));
	}
}

// Draw the graph(s)
$tmpl = new OCP\Template('usage_charts', 'charts.tpl', 'user');

// Get data for all users if admin or just for the current user
$displays = oc_usage_charts::getUConfValue('c_disp', Array('uc_val' => 'a:3:{s:10:"cpie_rfsus";i:1;s:11:"clines_usse";i:1;s:9:"chisto_us";i:1;}'));
$displays = unserialize($displays['uc_val']);
$tmpl->assign('c_disp', $displays);

$sc_sort = oc_usage_charts::getUConfValue('sc_sort', Array('uc_val' => 'a:3:{i:0;s:10:"cpie_rfsus";i:1;s:11:"clines_usse";i:2;s:9:"chisto_us";}'));
$tmpl->assign('sc_sort', unserialize($sc_sort['uc_val']));

if($displays['clines_usse']){
	$hu_size = oc_usage_charts::getUConfValue('hu_size', Array('uc_val' => 3));
	$tmpl->assign('hu_size', $hu_size['uc_val']);
}
if($displays['chisto_us']){
	$hu_size_hus = oc_usage_charts::getUConfValue('hu_size_hus', Array('uc_val' => 3));
	$tmpl->assign('hu_size_hus', $hu_size_hus['uc_val']);
}

$tmpl->printPage();
?>