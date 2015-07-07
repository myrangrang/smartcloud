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

class OC_USER_FACEBOOK extends OC_User_Backend {

	public function createUser($uid, $password) {
		//We can't create user
		OCP\Util::writeLog('OC_USER_FACEBOOK', 'Facebook does not support user creation ', OCP\Util::WARN);
		return false;
	}

	public function deleteUser($uid) {
		//We can't delete user
		OCP\Util::writeLog('OC_USER_FACEBOOK', 'Facebook does not support user deletion', OCP\Util::WARN);
		return false;
	}

	public function setPassword($uid, $password) {
		// We can't change user password
		OCP\Util::writeLog('OC_USER_FACEBOOK', 'Facebook does not support changing password', OCP\Util::WARN);
		return false;
	}

	public function checkPassword($uid, $password) {
		if (OCA_User_Facebook\App::$isFacebookLogin && !OCA_User_Facebook\App::$needConnect) {
			$user = OCA_User_Facebook\Validator::Validate();
			if ($user) {
				OCP\Util::writeLog('OC_USER_FACEBOOK', 'Login with Facebook succeeded.', OCP\Util::DEBUG);
				return $user;
				
			} elseif (!$user && OCA_User_Facebook\App::$isAutoMode){
				//Automatic login attempt. Response with error
				OCP\JSON::error(array('msg'=>'autologin failed'));
				exit();
			}
			
			OCP\Util::writeLog('OC_USER_FACEBOOK', 'Login with Facebook failed.', OCP\Util::DEBUG);
		}
		
		return false;
	}

	public function userExists($uid) {
		// We dunno
		return false;
	}

	public function getUsers() {
		OCP\Util::writeLog('OC_USER_FACEBOOK', 'Facebook does not support user listing', OCP\Util::WARN);
		return array();
	}

}
