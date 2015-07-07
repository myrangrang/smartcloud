<?php

/**
* ownCloud - Usage Charts plugin Forked from DjazzLab Storage Charts Plugin
* @author Alan Vallance
* @author Xavier Beurois
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

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('usage_charts');

$l = new OC_L10N('usage_charts');

// Update and save the new configuration
if(is_numeric($_POST['s']) && in_array($_POST['k'], Array('hu_size','hu_size_hus'))){
	oc_usage_charts::setUConfValue($_POST['k'], $_POST['s']);
	if(strcmp($_POST['k'],'hu_size') == 0){
		OCP\JSON::encodedPrint(Array('r' => oc_usage_chartsLoader::loadChart('clines_usse', $l)));
	}else{
		OCP\JSON::encodedPrint(Array('r' => oc_usage_chartsLoader::loadChart('chisto_us', $l)));
	}
}
