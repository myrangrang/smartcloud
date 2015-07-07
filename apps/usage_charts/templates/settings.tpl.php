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
// Template from app/settings.php (see also app/js/settings.js)
?>
<form id="usage_charts" method="POST" action="<?php print OC_Helper::linkTo('usage_charts','charts.php'); ?>">
	<fieldset class="personalblock">
		<span class="message"><?php print $l->t('Select the desired graphs (at least one)'); ?></span>
		<?php foreach ( $_['displays'] as $myChart => $is_enable) {
			switch ($myChart) {
			case 'cpie_rfsus':
				$myTitle = 'Current ratio free space / used space';
				break;
			case 'clines_usse':
				$myTitle = 'Daily Used Space Evolution';
				break;
			default://'chisto_us_e'
				$myTitle = 'Monthly Used Space Evolution';
			} ?>
			<div><input type="checkbox" name="usage_charts_disp[]" id="<?php print $myChart; ?>_e" style="margin-right:10px;"<?php print $is_enable?' checked':'';?> value="<?php print $myChart; ?>" /><?php print $l->t($myTitle); ?></div>
		<?php } ?>
		<div id="settings_ctrl">
		<input type="submit" id="settings_xmit" value="<?php print $l->t('Save'); ?>" />
		</div>
	</fieldset>
</form>