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
	
	onStatusChange : function(response) {
		if (response && response.status && response.status === 'connected') {
			FB.api('/me', FBwrapper.onProfileData);
		}
	},
	
	onProfileData : function(data){
		if (data && data.id){
			$('#facebook-data').replaceWith(
				$('<div id="facebook-data">' + '<img src="http://graph.facebook.com/'+data.id+'/picture" /><span style="padding-left:1em">' + data.name + '</span></div>' )
			);
		}
	},
	
	_init : function(response){
		FB.init({
			appId      : response.appId, // App ID
			channelUrl : '//'+window.location.hostname+'/channel', // Path to your Channel File
			status     : true, // check login status
			cookie     : false, // enable cookies to allow the server to access the session
			xfbml      : true  // parse XFBML
		});
		
		var facebook = document.createElement('div');
		facebook.id = 'fb-root';
		document.getElementsByTagName('body')[0].appendChild(facebook);
				
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