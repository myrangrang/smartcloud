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
* JS minified by http://fmarcia.info/jsmin/test.html
*
*/

OCP\Util::addStyle('3rdparty','chosen');
OCP\Util::addStyle('usage_charts', 'styles');
OCP\Util::addScript('3rdparty','chosen/chosen.jquery.min');
OCP\Util::addScript('usage_charts', 'highCharts/highcharts');
OCP\Util::addScript('usage_charts', 'chartsUsedStorage');

// if (version_compare($installedVersion, '0.6.1', '<=')) {
//
//	OC_JSON::success(array('data' => array('token' => $token)));
//	OC_JSON::error(array('data' => array('message' => $exception->getMessage())));
//
//	OCP\Util::sendMail($to_address, $to_address, $subject, $text, $from_address, $displayName);
//
//	\OCP\Util::writeLog('OCP\Share', \OC_DB::getErrorMessage($result), \OC_Log::ERROR);
//
?>

<div id="storage-charts">
	<table class="topblock"><tr>
		<td class="company">Usage Charts<?php echo'<br> &nbsp; v'.OC_Appconfig::getValue('usage_charts','installed_version').' / '.OC_Util::getVersionString()?></td>
		<td class="message"><?php print $l->t('Drag\'N\'Drop on the chart title to re-order'); ?></td>
		<td class="settings">
			<a title="<?php echo $l->t('Settings');?>">
			<img class="svg" src="/core/img/actions/settings.svg" alt="<?php echo $l->t('Settings');?>"/>
			</a>
		</td></tr>
	</table>
	<div id="appsettings" class="popup topright hidden"></div>
</div>
<div id="stc_frame">
	<div id="stc_sortable">
		<?php foreach($_['sc_sort'] as $sc_sort){
			if(strcmp($sc_sort, 'cpie_rfsus') == 0){
				$sc_sort_title = 'Current ratio free space / used space';
			}elseif(strcmp($sc_sort, 'clines_usse') == 0){
				$sc_sort_title = 'Daily Used Space Evolution';
			}else{
				$sc_sort_title = 'Monthly Used Space Evolution';
			}
			if($_['c_disp'][$sc_sort]){ ?>
			<div id="<?php print($sc_sort); ?>" class="personalblock">
				<h3><img src="<?php print(OCP\Util::imagePath('usage_charts', 'move.png')); ?>" /><?php print($l->t($sc_sort_title).' '.$l->t('for')); ?> "<?php print(OC_Group::inGroup(OCP\User::getUser(), 'admin')?$l->t('all users'):OCP\User::getUser()); ?>"
				<?php if ( $sc_sort == 'clines_usse') {?>
					<div class="selUnits"><span id="selLoader"></span>
					<select id="chunits">
					<?php for ( $i=1,$a=array('','Kilobytes (KB)','Megabytes (MB)','Gigabytes (GB)','Terabytes (TB)'); $i<count($a); $i++) {
						echo '<option value="'.$i.'"'.($i==$_['hu_size']?' selected':'').'>'.$l->t($a[$i]).'</option>';
					}
					?>
					</select>
					</div>
				<?php }?>
				<?php if ( $sc_sort == 'chisto_us') {?>
					<div class="selUnits"><span id="selLoader_hus"></span>
					<select id="chunits_hus">
					<?php for ( $i=1,$a=array('','Kilobytes (KB)','Megabytes (MB)','Gigabytes (GB)','Terabytes (TB)'); $i<count($a); $i++) {
						echo '<option value="'.$i.'"'.($i==$_['hu_size_hus']?' selected':'').'>'.$l->t($a[$i]).'</option>';
					}
					?>
					</select>
					</div>
				<?php }?>
				</h3>
				<div id="<?php print(substr($sc_sort, 1)); ?>" style="max-width:100%;height:400px;margin:0 auto"></div>
			</div>
			<?php }
		} ?>
	</div>
</div>