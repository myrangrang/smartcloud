<?php
$sql = 'SELECT * FROM `*PREFIX*group_user` WHERE uid = ?';//"' .\OCP\User::getUser(). '"';
$query = \OCP\DB::prepare($sql);
$params = array(\OCP\User::getUser());
$result = $query->execute($params);
$i = 0;
echo "{\"messages\" : {";
while($row = $result->fetchRow()) {
	echo  "\"" . $i++ . "\":{";
	
	$sql2 = 'SELECT * FROM `*PREFIX*popup` WHERE gid = ?';
	$query2 = \OCP\DB::prepare($sql2);
	$params2 = array($row['gid']);
	$result2 = $query2->execute($params2);
	$row2  = $result2->fetchRow();
//	if ($row2['message'] == "") 
//	{
//	echo "";
//	}else{
	echo "\"message\":\"" . $row2['message'] . "\",";
	echo "\"title\":\"" . $row2['title'] . "\",";
	echo "\"options\":{\"closeButton\": \"" . $row2['closeBtn']."\",\"debug\": \"false\",\"positionClass\": \"". $row2['position']."\",\"onclick\": \"null\",\"showDuration\": \"300\",\"hideDuration\": \"1000\",\"timeOut\":\"". $row2['timeout']."\",\"extendedTimeOut\": \"1000\",\"showEasing\": \"swing\",\"hideEasing\": \"linear\",\"showMethod\": \"fadeIn\",\"hideMethod\": \"fadeOut\"},";
	echo "\"messageType\":\"" . $row2['type'] . "\",";
//	echo $row2['message'] . "<br>" ;
//	}
	echo "\"EOM\":\"EOM\"},";
}
echo "\"". $i++ ."\":{\"message\":\"\"}}}";
//echo "<div align='center'>hello world</div>";
?>
