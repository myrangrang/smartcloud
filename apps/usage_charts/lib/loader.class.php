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

/**
 * This class load charts for usage_charts.
 */
class oc_usage_chartsLoader {

	private static $l;

	/**
	 * Loader
	 * @param $chart_id The chart iD
	 * @return String
	 */
	public static function loadChart($chart_id, $l){
		self::$l = $l;

		switch($chart_id){
			case 'cpie_rfsus':
				return self::loadPieFreeUsedSpaceRatioChart();
				break;
			case 'clines_usse':
				return self::loadLinesLastSevenDaysUsedSpaceChart();
				break;
			case 'chisto_us':
				return self::loadHistoMonthlyUsedSpaceChart();
				break;
		}
	}

	/**
	 * Get free/used space ratio chart (pie)
	 * @return String
	 */
	private static function loadPieFreeUsedSpaceRatioChart(){
		$myJSseries = oc_usage_charts::arrayParser('pie',oc_usage_charts::getPieFreeUsedSpaceRatio(), self::$l);
$myScript = <<<EOS
OC_DLSC.dgs	= [$myJSseries];
pieUsedStorage();
EOS;
		return $myScript;
	}

	/**
	 * Get the storage unit for $theGraph, depending on user preferences
	 * @return String
	 */
	private static function getUnitFromUserPrefs( $theGraphName ) {
		$myUnits	= Array('', 'KB', 'MB', 'GB', 'TB');
		$myResult	= oc_usage_charts::getUConfValue($theGraphName, Array('uc_val' => 3));
		return $myUnits[$myResult['uc_val']];
	} // getUnitFromUserPrefs

	/**
	 * Get seven days used space evolution chart (lines)
	 * @return String
	 */
	private static function loadLinesLastSevenDaysUsedSpaceChart(){
//		Retrieve the user preferred unit for this graph
		$u		= self::getUnitFromUserPrefs('hu_size');
//		Create a javascript string containing the graph subtitle
		$myJSsubTitle	= self::$l->t('Last 7 days');
//		Create a javascript string containing the graph title
		$myJStitle	= self::$l->t('Used space').' ('.$u.')';
//		Create a javascript array containing the series
		$myJSseries	= oc_usage_charts::arrayParser('line', oc_usage_charts::getUsedSpaceOverTime('daily'), self::$l);
$myScript = <<<EOS
OC_DLSC.wgst	= '$myJSsubTitle';
OC_DLSC.wgu	= '$u';
OC_DLSC.wgt	= '$myJStitle';
OC_DLSC.wgs	= [$myJSseries];
weeklyUsedStorage();
EOS;
		return $myScript;
	}

	/**
	 * Get monthly used space evolution chart (histo)
	 * @return String
	 */
	private static function loadHistoMonthlyUsedSpaceChart(){
//		Retrieve the user preferred unit for this graph
		$u		= self::getUnitFromUserPrefs('hu_size_hus');
//		Create a javascript string containing the graph title
		$myJStitle	= self::$l->t('Average used space').' ('.$u.')';
//		Create a javascript array containing the series
		$myJSseries	= oc_usage_charts::arrayParser('histo',oc_usage_charts::getUsedSpaceOverTime('monthly'),self::$l,',','hu_size_hus');
//		The js process to create the graph ownCloud
		$myScript = <<<EOS
OC_DLSC.ygt='$myJStitle';
OC_DLSC.ygs=[$myJSseries];
OC_DLSC.ygu='$u';
monthlyUsedStorage();
EOS;
		return $myScript;
	}
}