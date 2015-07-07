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

\OCP\JSON::checkAdminUser();

$tmpl = new \OCP\Template(App::APP_ID, 'admin');

$tmpl->assign(App::FACEBOOK_APP_KEY, App::getFacebookAppKey());
$tmpl->assign(App::FACEBOOK_APP_SECRET, App::getFacebookAppSecret());
$tmpl->assign(App::FACEBOOK_APP_AUTOLOGIN, App::getFacebookAppAutologin());

return $tmpl->fetchPage();