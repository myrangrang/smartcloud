<?php
require_once("db.php");
$lanjut = true;

$uid = $_REQUEST['id'];
$kdaktivasi = $_REQUEST['q'];

if (isset($uid)) {
    $sql = "SELECT `uid`, `displayname`, `password`, `email`, `flag` FROM `oc_users_reg` WHERE `uid` = :uid AND `kdaktivasi` = :kdaktivasi";
	$qry = $dbp->prepare($sql);
	$qry->bindParam(":uid", $uid, PDO::PARAM_STR);
    $qry->bindParam(":kdaktivasi", $kdaktivasi, PDO::PARAM_STR);
    if($qry->execute()){
		if($qry->rowCount()>0){
            list($userid,$displayname,$password,$email,$flag) = $qry->fetch(); 
		}else{
			$lanjut = false;
		}
	}else{
		$lanjut = false;
	}    
    if(! $lanjut){
		echo ("Maaf Aktivasi Gagal. Terimakasih.");
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}
	
	if($flag != 'Y'){
		$lanjut = true;
	}else{
		echo ("Anda telah melakukan aktivasi. Terimakasih.");
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}
    
    //simpanRegister -- oc_users_reg -> oc_users
	$sql = "INSERT INTO `oc_users` (`uid`,`displayname`,`password`,`email`) VALUES (:uid,:displayname,:password,:email)";
	$qry = $dbp->prepare($sql);
	$qry->bindParam(":uid", $userid, PDO::PARAM_STR);
	$qry->bindParam(":displayname", $displayname, PDO::PARAM_STR);
    $qry->bindParam(":password", $password, PDO::PARAM_STR);
    $qry->bindParam(":email", $email, PDO::PARAM_STR);
       
	if($qry->execute()){
		if($qry->rowCount()>0){
            $sql = "UPDATE `oc_users_reg` SET `flag`='Y' WHERE `uid` = :uid";
            $qry = $dbp->prepare($sql);
            $qry->bindParam(":uid", $userid, PDO::PARAM_STR);
            if($qry->execute()){
                echo ("Aktivasi user smartCloud berhasil. Terimakasih.");  
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
		echo ("Maaf Aktivasi Gagal. Terimakasih.");
		header('Refresh: 2; URL=https://103.233.157.243/');
		exit();
	}		
    
} else {
    echo ("Maaf Aktivasi Gagal. Terimakasih.");
	header('Refresh: 2; URL=https://103.233.157.243/');
	exit();
}

