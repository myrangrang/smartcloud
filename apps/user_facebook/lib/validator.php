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

class Validator {
	const AUTHCODE_URL = 'https://www.facebook.com/dialog/oauth?';
	const GRAPH_URL = "https://graph.facebook.com/me?access_token=";

	private static $_facebookData = array();

	/**
	 * Send JSON response on successful login
	 * @param String $uid
	 */
	public static function postlogin_hook($uid){
		\OCP\Util::writeLog(App::APP_ID, 'Processing hook', \OCP\Util::DEBUG);
		//Have we logged with Facebook?
		if (!App::$isFacebookLogin){
			return;
		}

		if (is_array($uid) && $uid['uid']){
			$uid = $uid['uid'];
		}

		$userData = App::getFacebookUserData($uid);
		$b = $userData[App::FACEBOOK_USER_ID];
		$c = @self::$_facebookData['id'];

		$a = App::$needConnect;
		
		$user = self::Validate();
		$needLink = App::$needConnect && empty($userData[App::FACEBOOK_USER_ID]) && @self::$_facebookData['id'];
		if ($needLink){
			//Is this id already linked?
			
			if ($user){
				\OCP\Util::writeLog(App::APP_ID, 'this Facebook account is already linked to another user', \OCP\Util::DEBUG);
			} else {

				\OCP\Util::writeLog(App::APP_ID, 'Link with facebook account', \OCP\Util::DEBUG);
				App::setFacebookUserData(array(
				App::FACEBOOK_USER_ID => self::$_facebookData['id']
					), $uid);
			}
			return;	
		}

		\OCP\Util::writeLog(App::APP_ID, 'User logged in', \OCP\Util::DEBUG);
		\OCP\JSON::success(array('msg' => 'Access granted'));
		exit();
	}

	public static function Validate(){

		//Have we got Facebook Id?
		$facebookData = self::_getFacebookData();
		if (isset($facebookData['id'])){
			self::$_facebookData = $facebookData;
			$fbIdResult = self::_getConnectedUser($facebookData['id']);

			return $fbIdResult->fetchOne();
		}

		return false;
	}

	protected static function _getConnectedUser($facebookId){
		$query = \OCP\DB::prepare('SELECT userid FROM *PREFIX*preferences WHERE appid = ? AND configkey = ? AND configvalue  = ?');
		return $query->execute(array(App::APP_ID, App::FACEBOOK_USER_ID, $facebookId));
	}

	protected static function _getFacebookData(){
		$facebookData = array();

		$accessToken = self::_getAccessToken();
		$respData = self::_query(self::GRAPH_URL . urlencode($accessToken));
		\OCP\Util::writeLog(App::APP_ID, 'FB Response:' . $respData, \OCP\Util::DEBUG);

		$response = json_decode($respData, true);
		if (is_array($response) && @$response['id']){
			$facebookData = $response;
		}

		return $facebookData;
	}

	protected static function _getAccessToken(){
		if (App::$accessToken){
			return App::$accessToken;
		}

		//TODO: make it to work somehow ;)
		$respData = self::_query(self::AUTHCODE_URL
						. 'client_id=' . urlencode(App::getFacebookAppKey())
						. '&secret=' . urlencode(App::getFacebookAppSecret())
						. '&redirect_uri=' . urlencode('http://localhost/deo/oc403/')
						. '&state=' . urlencode('asd')
		);

	}

	protected static function _query($url){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);

		$error = curl_errno($ch);
		if ($error){
			\OCP\Util::writeLog(App::APP_ID, 'Curl reports the error: ' . curl_error($ch), \OCP\Util::WARN);
		}

		curl_close($ch);

		return $response;
	}

}