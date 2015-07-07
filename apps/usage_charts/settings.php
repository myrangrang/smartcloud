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
// Fired by user - click on icon 'Settings' (see also js/chartsUsedStorage.js)
OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('usage_charts');

$tmpl = new OCP\Template('usage_charts', 'settings.tpl');

$displays = oc_usage_charts::getUConfValue('c_disp', Array('uc_val' => 'a:3:{s:10:"cpie_rfsus";i:1;s:11:"clines_usse";i:1;s:9:"chisto_us";i:1;}'));
$tmpl->assign('displays', unserialize($displays['uc_val']));
return $tmpl->printPage();
?>