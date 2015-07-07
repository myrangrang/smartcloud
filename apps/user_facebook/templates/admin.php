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
<fieldset class="personalblock" id="facebook-settings">
    <legend><strong><?php echo $l->t('Facebook login settings') ?></strong>:</legend><br />
		<label for="facebook_app_id"><strong>App Id:</strong></label>
		<input type="text" id="facebook_app_id" value="<?php echo $_['facebook_app_id'] ?>" placeholder="<?php echo $l->t('App Id') ?>" />
		<span class="msg"></span>
		<label fro="facebook_app_secret"><strong>App Secret:</strong></label>
		<input type="text" id="facebook_app_secret" value="<?php echo $_['facebook_app_secret'] ?>" placeholder="<?php echo $l->t('App Secret') ?>" />
		<span class="msg"></span>
		<input type="checkbox" id="facebook_app_autologin" <?php echo $_['facebook_app_autologin'] ?  'checked="checked"' : ''; ?> />
		<label for="facebook_app_autologin"><strong><?php echo $l->t('Autologin') ?></strong></label>
		<span class="msg"></span>
</fieldset>
<script type="text/javascript">
    $(document).ready(function(){
		$('#facebook_app_id').blur(function(){
			//OC.msg.startSaving('#facebook-settings .msg');
			$.post(OC.filePath('user_facebook', 'ajax', 'admin.php'),
			{ facebook_app_id : $('#facebook_app_id').val()},
			function(){
				//OC.msg.finishedSaving('#facebook-settings .msg');
			}
		)
		});
		$('#facebook_app_secret').blur(function(){
			//OC.msg.startSaving('#facebook-settings .msg');
			$.post(OC.filePath('user_facebook', 'ajax', 'admin.php'),
			{ facebook_app_secret : $('#facebook_app_secret').val()},
			function(){
				//OC.msg.finishedSaving('#facebook-settings .msg');
			}
		)
		});
		$('#facebook_app_autologin').click(function(){
			$.post(OC.filePath('user_facebook', 'ajax', 'admin.php'),
			{ facebook_app_autologin : !!$('#facebook_app_autologin').attr('checked')},
			function(){
				
			});
		});
    });    
</script>