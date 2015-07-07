/**
 * ownCloud - Facebook plugin
 * 
 * @author Victor Dubiniuk
 * @copyright 2012 Victor Dubiniuk victor.dubiniuk@gmail.com
 * 
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */

var FBwrapper = {
	autologin : true,
	
	init : function(){
		var data = 	{
			'authService' : 'facebook',
			'action' : 'getFBAppConfig'
		};
		
		$.post(OC.filePath('user_facebook', '', 'index.php'), data, FBwrapper._init);
	},
	
	login : function() {
		if (!FB.getUserID()){
		} else {
			
		}
		return false;
	},
	
	getProfileData : function(){
		FB.api('/me', FBwrapper.onProfileData);
	},
	
	onStatusChange : function(response) {
		if (response && response.status && response.status === 'connected') {
			// user has auth'd your app and is logged into Facebook
			FBwrapper.onStatusConnected();
		} else if (response.status === 'not_authorized') {
			FBwrapper.onStatusNotConnected();
		} else {
			FBwrapper.onStatusNotConnected();			
		}
	},
	
	onStatusNotConnected : function(){
			$('<div class="fb-login-button"></div>') .css(
			{
				cursor : 'pointer',
				'float' : 'right'
			}).appendTo('form');
    
			$('.fb-login-button').click(FBwrapper.login);			
	},
	
	onStatusConnected : function(){
		FBwrapper.getProfileData();
			
		var data = {
			'authService' : 'facebook',
			'user' : $('#user').val(),
			'password' : $('#password').val(),
			'facebook_access_token' : FB.getAccessToken(),
			sectoken : $('#sectoken').val()
		};

		if (FBwrapper.autologin){
			data.facebook_autologin = '1';
		}

		$.post('index.php', data,
			function(data) {
				console.log(data);
				if (data && data.msg=='Access granted'){ 
					//login
					window.location.reload();
				} else if (data && data.msg=='autologin failed'){
					FBwrapper.autologin = false;
				} else {
					OC.dialogs.alert(t('user_facebook', 'User Not found'), t('user_facebook', 'Please log in to link your account with Facebook account'));
				}
			}
			);
		
	},
	
	onProfileData : function(data){
		if (data && data.id){
			if ($('.fb-login-button').length){
				$('.fb-login-button').replaceWith(
				$('<div id="connect">' + '<img src="http://graph.facebook.com/'+data.id+'/picture" /><span style="padding-left:1em">' + data.name + '</span><br /><input type="checkbox" checked="checked" id="fb-connect" /><label for="fb-connect">' + t('user_facebook', 'Connect account') + '</label></div>' )
					);
			} else {
				$('<div id="connect">' + '<img src="http://graph.facebook.com/'+data.id+'/picture" /><span style="padding-left:1em">' + data.name + '</span><br /><input type="checkbox" checked="checked" id="fb-connect" /><label for="fb-connect">' + t('user_facebook', 'Connect account') + '</label></div>' ).appendTo('form');
			}
			
			$('<input type="hidden" id="facebook_connect" name="facebook_connect" value="' + FB.getUserID() + '" />').appendTo('form fieldset');
			$('<input type="hidden" id="facebook_access_token" name="facebook_access_token" value="' + FB.getAccessToken() + '" />').appendTo('form fieldset');
			$('<input type="hidden" id="authService" name="authService" value="facebook" />').appendTo('form fieldset');
			
			$('#fb-connect').click(function(){
				if($('#fb-connect').attr('checked')){
					$('#authService').val('facebook');
					$('#facebook_connect').val(FB.getUserID());
					$('#facebook_access_token').val(FB.getAccessToken());
				} else {
					$('#authService').val(null);
					$('#facebook_connect').val(null);
					$('#facebook_access_token').val(null);
				}
			});
		}
	},
	
	_init : function(response){
		FB.init({
			appId      : response.appId, // App ID
			channelUrl : '//'+window.location.hostname+'/channel', // Path to your Channel File
			status     : response.autologin, // check login status
			cookie     : false, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
		});
		
		var facebook = document.createElement('div');
		facebook.id = 'fb-root';
		document.getElementsByTagName('body')[0].appendChild(facebook);
		
		FBwrapper.onStatusNotConnected();
				
		FB.Event.subscribe('auth.statusChange', FBwrapper.onStatusChange);
	}
};

(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {
		return;
	}
	js = d.createElement('script');
	js.id = id;
	js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

window.fbAsyncInit = FBwrapper.init;
