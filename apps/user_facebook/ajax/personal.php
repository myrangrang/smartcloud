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

\OCP\JSON::checkLoggedIn();

$l = new \OC_L10N('core');

if (isset($_POST['facebook_account_unlink'])){
	App::setFacebookUserData(array(
		APP::FACEBOOK_USER_ID => ''
	));
} else {
	\OCP\JSON::error(array("data" => array("message" => $l->t("Invalid request"))));
	exit();
}

\OCP\JSON::success(array("data" => array("message" => $l->t("Done"))));	
