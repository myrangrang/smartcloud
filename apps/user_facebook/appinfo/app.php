<?php

/**
 * ownCloud - Facebook plugin
 * 
 * @author Victor Dubiniuk
 * @copyright 2012 Victor Dubiniuk victor.dubiniuk@gmail.com
 * 
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 */


namespace OCA_User_Facebook;

class App {

	const APP_ID = 'user_facebook';
	const APP_PATH = 'apps/user_facebook/';
	const FACEBOOK_APP_KEY = 'facebook_app_id';
	const FACEBOOK_APP_SECRET = 'facebook_app_secret';
	const FACEBOOK_APP_AUTOLOGIN = 'facebook_app_autologin';
	const FACEBOOK_USER_ID = 'facebook_user_id';

	public static $isFacebookLogin = false;
	public static $isAutoMode = false;
	public static $needConnect = false;
	public static $accessToken = false;

	public static function init() {
		//check if curl extension installed
		if (!in_array('curl', get_loaded_extensions())) {
			\OCP\Util::writeLog(self::APP_ID, 'This app needs cUrl PHP extension', \OCP\Util::DEBUG);
			return false;
		}

		//Allow config page
		\OC_APP::registerAdmin(self::APP_ID, 'admin');

		//Check Configuration
		if (!self::getFacebookAppKey() || !self::getFacebookAppSecret()) {
			\OCP\Util::writeLog(self::APP_ID, 'Please configure the application at the admin area', \OCP\Util::DEBUG);
			return false;
		}
		
		\OC_APP::registerPersonal(self::APP_ID, 'personal');

		//Response with Facebook appId
		if (@$_POST['authService'] == 'facebook' && @$_POST['action'] == 'getFBAppConfig') {
			\OCP\JSON::success(array(
				'appId' => self::getFacebookAppKey(),
				'autologin' => self::getFacebookAppAutologin()
			));
			exit();
		}

		self::$isFacebookLogin = @$_POST['authService'] == 'facebook';
		
		//This is our login provider
		if (!\OCP\User::isLoggedIn()) {
			\OCP\Util::addScript(self::APP_ID, 'utils');
			if (self::$isFacebookLogin) {
				self::$isAutoMode = isset($_POST['facebook_autologin']);
				self::$needConnect = isset($_POST['facebook_connect']);
				if (isset($_POST['facebook_access_token'])){
					self::$accessToken = $_POST['facebook_access_token'];
				}

				\OC::$CLASSPATH['OCA_User_Facebook\Validator'] = self::APP_PATH . 'lib/validator.php';
				\OC::$CLASSPATH['OC_USER_FACEBOOK'] = self::APP_PATH . 'user_facebook.php';

				\OCP\Util::connectHook('OC_User', 'post_login', "OCA_User_Facebook\Validator", "postlogin_hook");
				\OC_User::useBackend('facebook');
			}
		}
	}

	public static function getFacebookUserData($uid=null) {
		if (!$uid){
			$uid = \OCP\User::getUser();
		}

		return array(
			self::FACEBOOK_USER_ID => \OCP\Config::getUserValue($uid, self::APP_ID, self::FACEBOOK_USER_ID, '')
		);
	}

	public static function setFacebookUserData($data, $uid=null) {
		if (!$uid){
			$uid = \OCP\User::getUser();
		}
		\OCP\Config::setUserValue($uid, self::APP_ID, self::FACEBOOK_USER_ID, @$data[self::FACEBOOK_USER_ID]);
	}

	public static function getFacebookAppKey() {
		return \OCP\Config::getAppValue(self::APP_ID, self::FACEBOOK_APP_KEY, '');
	}

	public static function setFacebookAppKey($key) {
		$key = self::_cleanValue($key);
		\OCP\Config::setAppValue(self::APP_ID, self::FACEBOOK_APP_KEY, $key);
	}

	public static function getFacebookAppSecret() {
		return \OCP\Config::getAppValue(self::APP_ID, self::FACEBOOK_APP_SECRET, '');
	}

	public static function setFacebookAppSecret($secret) {
		$secret = self::_cleanValue($secret);
		\OCP\Config::setAppValue(self::APP_ID, self::FACEBOOK_APP_SECRET, $secret);
	}

	public static function getFacebookAppAutologin() {
		return \OCP\Config::getAppValue(self::APP_ID, self::FACEBOOK_APP_AUTOLOGIN, '');
	}

	public static function setFacebookAppAutologin($value) {
		\OCP\Config::setAppValue(self::APP_ID, self::FACEBOOK_APP_AUTOLOGIN, intval($value));
	}
	
	protected static function _cleanValue($value) {
		return preg_replace('/[^0-9a-zA-Z\.\-_]*/i', '', $value);
	}

}

//Startup
if (\OCP\App::isEnabled(App::APP_ID)) {
	App::init();
}
