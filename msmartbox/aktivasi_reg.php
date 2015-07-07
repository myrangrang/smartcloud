<?php
require_once("db.php");
$lanjut = true;

$uid = $_REQUEST['id'];
$kdaktivasi = $_REQUEST['q'];
$notifGagal = "<p style='color:red'><h1>Aktivasi User SmartCloud</h1></p><p><h2>Maaf Aktivasi Gagal. Terimakasih.</h2></p>";
$notifDone = "<p style='color:red'><h1>Aktivasi User SmartCloud</h1></p><p><h2>Anda telah melakukan aktivasi. Terimakasih.</h2></p>";
$notifSukses = "<p style='color:red'><h1>Aktivasi User SmartCloud</h1></p><p><h2>Aktivasi user SmartCloud berhasil. Silahkan login. Terimakasih.</h2></p>";

if (isset($uid)) {
    $sql = "SELECT `uid`, `displayname`, `password`, `email`, `devid`, `gender`, `birthday`, `flag` FROM `oc_users_reg` WHERE `uid` = :uid AND `kdaktivasi` = :kdaktivasi";
	$qry = $dbp->prepare($sql);
	$qry->bindParam(":uid", $uid, PDO::PARAM_STR);
    $qry->bindParam(":kdaktivasi", $kdaktivasi, PDO::PARAM_STR);
    if($qry->execute()){
		if($qry->rowCount()>0){
            list($userid,$displayname,$password,$email,$devid,$gender,$birthday,$flag) = $qry->fetch(); 
		}else{
			$lanjut = false;
		}
	}else{
		$lanjut = false;
	}    
    if(! $lanjut){
		echo ($notifGagal);
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}
	
	if($flag != 'Y'){
		$lanjut = true;
	}else{
		echo ($notifDone);
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}
	
    //simpan Preferences Email
	$sql = "INSERT INTO `oc_preferences`(`userid`,`appid`,`configkey`,`configvalue`) VALUES (:uid,'settings','email',:mail)";
	$qry = $dbp->prepare($sql);
	$qry->bindParam(":uid", $userid, PDO::PARAM_STR);
	$qry->bindParam(":mail", $email, PDO::PARAM_STR);
	if ($qry->execute()){
		if ($qry->rowCount()>0){
			$lanjut = true;
		}else{
			$lanjut = false;
		}
	}else{
		$lanjut = false;
	}
	
	if(! $lanjut){
		echo ($notifGagal);
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}	
	
    //simpanRegister -- oc_users_reg -> oc_users
	$sql = "INSERT INTO `oc_users` (`uid`,`displayname`,`password`,`email`,`devid`,`gender`,`birthday`) VALUES (:uid,:displayname,:password,:email,:devid,:gender,:birthday)";
	$qry = $dbp->prepare($sql);
	$qry->bindParam(":uid", $userid, PDO::PARAM_STR);
	$qry->bindParam(":displayname", $displayname, PDO::PARAM_STR);
    $qry->bindParam(":password", $password, PDO::PARAM_STR);
    $qry->bindParam(":email", $email, PDO::PARAM_STR);
	$qry->bindParam(":devid", $devid, PDO::PARAM_STR);
	$qry->bindParam(":gender", $gender, PDO::PARAM_STR);
	$qry->bindParam(":birthday", $birthday, PDO::PARAM_STR);
       
	if($qry->execute()){
		if($qry->rowCount()>0){
            $sql = "DELETE FROM `oc_users_reg` WHERE `uid` = :uid";
            $qry = $dbp->prepare($sql);
            $qry->bindParam(":uid", $userid, PDO::PARAM_STR);
            if($qry->execute()){
                echo ($notifSukses);  
				header('Refresh: 2; URL=https://103.233.157.243/');
				exit();				
            }else{
                $lanjut = false;
            }
		}else{
			$lanjut = false;
		}
	}else{
		$lanjut = false;
	}
	
	if(! $lanjut){
		echo ($notifGagal);
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}		
    
} else {
    echo ($notifGagal);
	header('Refresh: 2; URL=https://103.233.157.243/');
	exit();
}

