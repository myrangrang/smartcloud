<?php
/**
 * Copyright (c) 2014 Masoud KHorram <usef62@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
	OCP\JSON::checkAppEnabled('files_counter');
	require_once('apps/files_counter/lib/runcounter.php');
	$userDirectory = "/".OCP\USER::getUser()."/files";
	$uid = OCP\USER::getUser();
	$fileid = $_POST['fileid'];
	OC_FilesCounter::setCounter($fileid);
	OC_FilesCounter::setIPDate($fileid, $uid);
	OCP\JSON::success();
?>