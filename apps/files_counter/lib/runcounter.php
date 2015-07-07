<?php

/**
* Copyright (c) 2014 Masoud KHorram <usef62@owncloud.com>
* This file is licensed under the Affero General Public License version 3 or
* later.
* See the COPYING-README file.
*/

class OC_FilesCounter {
    public static function getIPDate($fileid) {
        $query = OCP\DB::prepare("SELECT * FROM *PREFIX*files_counter_ip WHERE `fileid`=?");
        $result = $query->execute(array($fileid));
        while($row = $result->fetchRow()) {
	    echo '<option style="color:#009933" value="'.$row['id'].'">IP: '.$row['ipaddress'].' Time: '.$row['timestamp'].' User: '.$row['user'].'</option>';
        }
    }
    
    public static function getCounter($fileid) {
        $query = OCP\DB::prepare("SELECT `counter` FROM *PREFIX*files_counter WHERE `fileid`=?");
        $result = $query->execute(array($fileid))->fetchRow();
        echo $result['counter'];
    }
    
    public static function setIPDate($fileid, $user) {
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$getdate = date("Y-m-d H:i:s");
        $query = OCP\DB::prepare("INSERT INTO *PREFIX*files_counter_ip (`fileid`, `ipaddress`, `timestamp`, `user`) VALUES(?,?,?,?) ");
	$query->execute(array($fileid, $ipaddress, $getdate, $user));
    }
    
    public static function setCounter($fileid) {
        $query = OCP\DB::prepare("SELECT * FROM *PREFIX*files_counter  WHERE `fileid`=?");
        $result = $query->execute(array($fileid))->fetchAll();
        if($result) {
	  $query = OCP\DB::prepare("UPDATE *PREFIX*files_counter SET counter = counter + 1 WHERE fileid =?");
	  $query->execute(array($fileid));
	} else {
	  $query = OCP\DB::prepare("INSERT INTO *PREFIX*files_counter (`fileid`, `counter`) VALUES(?,?) ");
	  $query->execute(array($fileid, "1"));
	}
        
    }
}
;