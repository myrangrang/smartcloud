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

\OCP\User::checkAdminUser();

$l = new \OC_L10N('core');

if (isset($_POST[App::FACEBOOK_APP_KEY])) {
	App::setFacebookAppKey($_POST[App::FACEBOOK_APP_KEY]);
} elseif (isset($_POST[App::FACEBOOK_APP_SECRET])) {
	App::setFacebookAppSecret($_POST[App::FACEBOOK_APP_SECRET]);
} elseif (isset($_POST[App::FACEBOOK_APP_AUTOLOGIN])) {
	App::setFacebookAppAutologin($_POST[App::FACEBOOK_APP_AUTOLOGIN] != 'false');
} else {
	\OCP\JSON::error(array("data" => array("message" => $l->t("Invalid request"))));
	exit();
}

\OCP\JSON::success(array("data" => array("message" => $l->t("Saved"))));	

