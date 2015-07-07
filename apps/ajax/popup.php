<?php

$sql = 'SELECT * FROM `*PREFIX*group_user` WHERE uid = ?';//"' .\OCP\User::getUser(). '"';
$query = \OCP\DB::prepare($sql);
$params = array(\OCP\User::getUser());
$result = $query->execute($params);
while($row = $result->fetchRow()) {
	$sql2 = 'SELECT * FROM `*PREFIX*popup` WHERE gid = ?';
	$query2 = \OCP\DB::prepare($sql2);
	$params2 = array($row['gid']);
	$result2 = $query2->execute($params2);
	$row2  = $result2->fetchRow();
	if ($row2['message'] == "") 
	{
	echo "";
	}else{
	echo $row2['message'] . "<br>" ;
	}
}
//echo "<div align='center'>hello world</div>";
?>
