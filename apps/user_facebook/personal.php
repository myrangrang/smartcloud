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

\OCP\User::checkLoggedIn();

$userData = App::getFacebookUserData();
if (!empty($userData[App::FACEBOOK_USER_ID])){
	\OCP\Util::addScript(App::APP_ID, 'personal');
}

$tmpl = new \OCP\Template(App::APP_ID, 'personal');
$tmpl->assign(App::FACEBOOK_USER_ID, $userData[App::FACEBOOK_USER_ID]);

return $tmpl->fetchPage();