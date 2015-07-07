<?php

// check if the user has the right permissions to access the activities
\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('popup');

// activate the right navigation entry
\OCP\App::setActiveNavigationEntry('popup');

// load the needed js scripts and css
//\OCP\Util::addScript('activity', 'script');
//\OCP\Util::addStyle('activity', 'style');

//$navigation = new \OCA\Activity\Navigation(\OCP\Util::getL10N('popup'));
//$navigation->setRSSToken(\OCP\Config::getUserValue(\OCP\User::getUser(), 'activity', 'rsstoken'));

// get the page that is requested. Needed for endless scrolling
//$data = new \OCA\Activity\Data(
//        \OC::$server->getActivityManager()
//);
//$page = $data->getPageFromParam() - 1;
//$filter = $data->getFilterFromParam();

// show activity template
$tmpl = new \OCP\Template('popup', 'list', 'user');
//$tmpl->assign('filter', $filter);
//$tmpl->assign('appNavigation', $navigation->getTemplate($filter));
$tmpl->printPage();

