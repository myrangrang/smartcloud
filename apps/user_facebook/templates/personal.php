<?php
/**
 * ownCloud - browserId plugin
 * 
 * @author Victor Dubiniuk
 * @copyright 2012 Victor Dubiniuk victor.dubiniuk@gmail.com
 * 
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */
?>
<fieldset class="personalblock">
	<strong><?php echo $l->t('Linked with Facebook account:') ?></strong><br />
	<?php if (!empty($_['facebook_user_id'])){ ?>
	<div id="facebook-data"></div>
	<br />
	<button id="facebook_account_unlink"><?php echo $l->t('Forget me') ?></button>
	<?php } else { ?>
	<strong><?php echo $l->t('Not linked yet') ?></strong>
	<?php } ?>
</fieldset>
<script type="text/javascript">
    $(document).ready(function(){
		$('#facebook_account_unlink').click(function(){
			$.post(OC.filePath('user_facebook', 'ajax', 'personal.php'),
			{ facebook_account_unlink : 1},
			function(){
				window.location.reload();
			}
		);
		});
	});
</script>