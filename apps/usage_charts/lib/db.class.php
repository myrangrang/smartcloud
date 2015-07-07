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
 * This class manages usage_charts.
 */
class oc_usage_charts {
/**
 * @brief	Timestamp with milliseconds
 * @return	String
 */
	public static function milliStamp() {
//	                       ----------
		$myTime=microtime(true);
		$mySecs=floor($myTime);
		$myMilliSecs=substr('00'.round(($myTime - $mySecs)*1000), -3);
		return date('Y-m-d H:i:s.'.$myMilliSecs, $mySecs);
	}

/**
 * @brief	Backtrace to string (filenames and line numbers)
 * @return	String
 */
	public static function backTrace() {
		$myTrace = debug_backtrace();
		for ( $myResult = '', $i=0; $i<count($myTrace); $i++ ) {
			$myItem = $myTrace[$i];
			if ( array_key_exists('file',$myItem)
			&&   array_key_exists('line',$myItem) ){
				$myResult .= "  fn={$myItem['file']}(#{$myItem['line']}) ";
			}
		}
		return $myResult;
	}

/**
 * @brief	Record there's some work to do
 * @return	n.a.
 */
	public static function must_dirstat($path) {
//	                       ------------
// Fired by hooks "post_write" and/or "delete" -> size of user's storage changed
		$_SESSION['must_dirstat'] = true;
	}

/**
 * @brief	Regular task: Update or insert sizes of user's storage into DB
 * @return	n.a.
 */
	public static function cron_dirstat() {
//	                       ------------
		OCP\Util::writeLog('usage_charts', self::milliStamp().' begin ', OCP\Util::DEBUG);
		if ( array_key_exists('must_dirstat',$_SESSION)
		&&   $_SESSION['must_dirstat'] ) {
			$myUserID	= OCP\User::getUser();
			$myStorageInfo	= OC_Helper::getStorageInfo('/');
			$myUsed		= $myStorageInfo['used'];
			$myTotal	= $myStorageInfo['total'];
//			Update or insert user's storage into Database (at most one record per day)
			$myQuery = OCP\DB::prepare('SELECT stc_id FROM *PREFIX*usage_charts WHERE oc_uid=? AND stc_dayts=?');
			$myResult = $myQuery->execute(Array($myUserID, mktime(0,0,0)))->fetchRow();
			if ( $myResult ) {
				$myQuery = OCP\DB::prepare('UPDATE *PREFIX*usage_charts SET stc_used=?, stc_total=? WHERE stc_id=?');
				$myQuery->execute(Array($myUsed, $myTotal, $myResult['stc_id']));
			} else {
				$myQuery = OCP\DB::prepare('INSERT INTO *PREFIX*usage_charts (oc_uid,stc_month,stc_dayts,stc_used,stc_total) VALUES (?,?,?,?,?)');
				$myQuery->execute(Array($myUserID, date('Ym'), mktime(0,0,0), $myUsed, $myTotal));
			}
			OCP\Util::writeLog('usage_charts', self::milliStamp().' calc  '." $myUsed/$myTotal ", OCP\Util::ERROR);
		}
		$_SESSION['must_dirstat'] = false;
		OCP\Util::writeLog('usage_charts', self::milliStamp().' end   ', OCP\Util::DEBUG);
	}

/**
 * @brief	Compute the stats
 * @return	String
 */
	public static function true_dirstat() {
//	                       ------------
// Fired by hook "logout" -> check sizes of user's storage
		$_SESSION['must_dirstat'] = true;
		self::cron_dirstat();
	}

	/**
	 * Get data to build the pie about the Free-Used space ratio
	 */
	public static function getPieFreeUsedSpaceRatio() {
// SQL engines prefer to have few calls with huge queries rather than a lot of calls with small queries
		if ( OC_Group::inGroup(OCP\User::getUser(),'admin') ) {
			$mySQL =<<<EoR
SELECT	oc_uid, stc_used, stc_total
FROM	oc_usage_charts
WHERE	stc_id in(
	SELECT	stc_id
	FROM	(
		SELECT	oc_uid, stc_id
		FROM	oc_usage_charts
		ORDER BY stc_dayts DESC
		)a
	GROUP BY oc_uid
) ORDER BY stc_total
EoR;
			$myQry = OCP\DB::prepare($mySQL);
			$return= $myQry->execute()->fetchAll();
		} else {
			$mySQL =<<<EoR
SELECT	oc_uid, stc_used, stc_total
FROM	oc_usage_charts
WHERE	stc_id in(
	SELECT	stc_id
	FROM	(
		SELECT	oc_uid, stc_id
		FROM	oc_usage_charts
where oc_uid=?
		ORDER BY stc_dayts DESC
		)a
	GROUP BY oc_uid
)
EoR;
			$myQry = OCP\DB::prepare($mySQL);
			$return= $myQry->execute(Array(OCP\User::getUser()))->fetchAll();
		}
		return $return;
	}

	/**
	 * Get data to build the line chart about last 7 days used space evolution
	 */
	public static function getUsedSpaceOverTime($time){
		$return = Array();
		if(OC_Group::inGroup(OCP\User::getUser(), 'admin')){
			foreach(OCP\User::getUsers() as $user){
				if(strcmp($time, 'daily') == 0){
					$return[$user] = self::getDataByUserToLineChart($user);
				}else{
					$return[$user] = self::getDataByUserToHistoChart($user);
				}
			}
		}else{
			if(strcmp($time, 'daily') == 0){
				$return[OCP\User::getUser()] = self::getDataByUserToLineChart(OCP\User::getUser());
			}else{
				$return[OCP\User::getUser()] = self::getDataByUserToHistoChart(OCP\User::getUser());
			}
		}
		return $return;
	}

	/**
	 * Get configuration values stored in the database
	 * @param $key The conf key
	 * @return Array The conf value
	 */
	public static function getUConfValue($key, $default = NULL){
		$query = OCP\DB::prepare("SELECT uc_id,uc_val FROM *PREFIX*usage_charts_uconf WHERE oc_uid = ? AND uc_key = ?");
		$result = $query->execute(Array(OCP\User::getUser(), $key))->fetchRow();
		if($result){
			return $result;
		}
		return $default;
	}

	/**
	 * @brief Put configuration values into the database
	 * @param $key The conf key
	 * @param $val The conf value
	 */
	public static function setUConfValue($key,$val){
		$conf = self::getUConfValue($key);
		if(!is_null($conf)){
			$query = OCP\DB::prepare("UPDATE *PREFIX*usage_charts_uconf SET uc_val = ? WHERE uc_id = ?");
			$query->execute(Array($val, $conf['uc_id']));
		}else{
			$query = OCP\DB::prepare("INSERT INTO *PREFIX*usage_charts_uconf (oc_uid,uc_key,uc_val) VALUES (?,?,?)");
			$query->execute(Array(OCP\User::getUser(), $key, $val));
		}
	}

	/**
	 * Parse an array and return data in the highCharts format
	 * @param $operation operation to do
	 * @param $elements elements to parse
	 */
	public static function arrayParser($operation, $elements, $l, $data_sep = ',', $ck = 'hu_size'){
		$return = "";
		switch($operation){
			case 'pie':
				$free = $total = 0;
				foreach($elements as $element){

					$total = $element['stc_total'];
					$free += $element['stc_used'];

					$return .= "['" . $element['oc_uid'] . "', " . $element['stc_used'] . "],";
				}
				$return .= "['" . $l->t('Free space') . "', " . ($total - $free) . "]";
			break;
			case 'histo':
			case 'line':
				$conf = self::getUConfValue($ck, Array('uc_val' => 3));
				$div = 1;
				switch($conf['uc_val']){
					case 4:
						$div = 1024;
					case 3:
						$div *= 1024;
					case 2:
						$div *= 1024;
					case 1:
						$div *= 1024;
				}

				foreach($elements as $user => $data){
					$return_tmp = '{"name":"' . $user . '","data":[';
					foreach($data as $number){
						$return_tmp .= round($number/$div, 2) . ",";
					}
					$return_tmp = substr($return_tmp, 0, -1) . "]}";

					$return .= $return_tmp . $data_sep;
				}
				$return = substr($return, 0, -(strlen($data_sep)));
			break;
		}
		return $return;
	}

	/**
	 * Get data by user for Seven Days Line Chart
	 * @param $user the user
	 * @return Array
	 */
	private static function getDataByUserToLineChart($user){
		$dates = Array(
			mktime(0,0,0,date('m'),date('d')-6),
			mktime(0,0,0,date('m'),date('d')-5),
			mktime(0,0,0,date('m'),date('d')-4),
			mktime(0,0,0,date('m'),date('d')-3),
			mktime(0,0,0,date('m'),date('d')-2),
			mktime(0,0,0,date('m'),date('d')-1),
			mktime(0,0,0,date('m'),date('d'))
		);
// todo : new design Last 7D : oc_uid,7D, {[date, stc_used],[],[],[],[],[],[]}
// todo : new design Last 5W : oc_uid,5W, {[date, stc_used],[],[],[],[]}
// todo : new design Last 12M : oc_uid,12M, {[date, stc_used],[],[],[],[],[],...,[]}

		$return = Array();
		foreach($dates as $kd => $date){
			$query = OCP\DB::prepare("SELECT stc_used FROM *PREFIX*usage_charts WHERE oc_uid = ? AND stc_dayts = ?");
			$result = $query->execute(Array($user, $date))->fetchAll();

			if(count($result) > 0){
				$return[] = $result[0]['stc_used'];
			}else{
				if($kd == 0){
					$query = OCP\DB::prepare("SELECT stc_used FROM *PREFIX*usage_charts WHERE oc_uid = ? AND stc_dayts < ? ORDER BY stc_dayts DESC");
					$result = $query->execute(Array($user, $date))->fetchAll();

					if(count($result) > 0){
						$return[] = $result[0]['stc_used'];
					}else{
						$return[] = 0;
					}
				}else{
					$return[] = 0;
				}
			}
		}

		$last = 0;
		foreach ($return as $key => $value) {
			if($value == 0){
				$return[$key] = $last;
			}
			$last = $return[$key];
		}
		return $return;
	}

	/**
	 * Get data by users for monthly evolution
	 * @param $user The user
	 * @return Array
	 */
	private static function getDataByUserToHistoChart($user){
		$months = Array(
			date('Ym',mktime(0,0,0,date('m')-11)),
			date('Ym',mktime(0,0,0,date('m')-10)),
			date('Ym',mktime(0,0,0,date('m')-9)),
			date('Ym',mktime(0,0,0,date('m')-8)),
			date('Ym',mktime(0,0,0,date('m')-7)),
			date('Ym',mktime(0,0,0,date('m')-6)),
			date('Ym',mktime(0,0,0,date('m')-5)),
			date('Ym',mktime(0,0,0,date('m')-4)),
			date('Ym',mktime(0,0,0,date('m')-3)),
			date('Ym',mktime(0,0,0,date('m')-2)),
			date('Ym',mktime(0,0,0,date('m')-1)),
			date('Ym',mktime(0,0,0,date('m')))
		);

		$return = Array();
		foreach($months as $km => $month){
			$query = OCP\DB::prepare("SELECT AVG(stc_used) as stc_used FROM *PREFIX*usage_charts WHERE oc_uid = ? AND stc_month = ?");
			$result = $query->execute(Array($user, $month))->fetchAll();

			if(count($result) > 0){
				$return[] = $result[0]['stc_used'];
			}else{
				$return[] = 0;
			}
		}

		$last = 0;
		foreach ($return as $key => $value) {
			if($value == 0){
				$return[$key] = $last;
			}
			$last = $return[$key];
		}
		return $return;
	}

}