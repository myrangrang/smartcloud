<?php
	require_once('apps/files_counter/lib/runcounter.php');
	OCP\App::register( array('order' => 80,'id' => 'files_counter','name' => 'Counter' ));
	OCP\Util::addscript('files_counter','counter');
	OCP\Util::addStyle( 'files_counter','style');
?>