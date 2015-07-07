<?php

if ((OCP\User::isLoggedIn())) { //and ((OC::$REQUESTEDAPP == 'dashboard') or (OC::$REQUESTEDAPP == 'files'))) {
	
//$sql = 'SELECT * FROM `*PREFIX*group_user` WHERE uid = ?';//"' .\OCP\User::getUser(). '"';
//$query = \OCP\DB::prepare($sql);
//$params = array(\OCP\User::getUser());
//$result = $query->execute($params);
//while($row = $result->fetchRow()) {
        OCP\Util::addScript('popup', 'toastr');
	OCP\Util::addScript('popup', 'popup');
	OCP\Util::addStyle('popup','toastr');
		OCP\Util::addStyle('popup','bs');
//}
        $sql = 'SELECT * FROM `*PREFIX*group_admin` ' .
            'WHERE `uid` = "'.\OCP\User::getUser().'"';
	$query = \OCP\DB::prepare($sql);
        $result = $query->execute();
	$data = $result->fetchAll();
if (count($data)) {
	\OCP\App::addNavigationEntry(array(
	    'id' => 'popup',
	    'order' => 10,
	    'href' => \OCP\Util::linkTo('popup','popup.php'),
	    'icon' => \OCP\Util::imagePath('popup', 'popup.svg'),
	    'name' => \OC_L10N::get('popup')->t('popup')
	));
}
//OCP\User::checkAdminUser() 
}


