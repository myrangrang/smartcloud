<?php error_reporting(E_ALL); ?>
<table>
<?php
/*    print_r($_POST);
    foreach ($_POST as $key => $value)
        {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
        echo "</tr>";
    }
*/
   //mysqli_real_escape_string($_POST['message']);
  if (isset($_POST['title'])) $title = $_POST['title'];
  else $title = "";
  $message = $_POST['message'];
  if (isset($_POST['closeBtn'])) $closeBtn = "true";
  else $closeBtn = "false";
  if (isset($_POST['type'])) $type = $_POST['type'];
  else $type = "";
  if (isset($_POST['position'])) $position = $_POST['position'];
  else $position = "";
  if (isset($_POST['timeout'])) $timeout = $_POST['timeout'];
  else $timeout = "5000"; 
   foreach ($_POST['group'] as $gid)
	{
	$key = $gid; //mysqli_real_escape_string($gid);
	$sql = "select 1 from `oc_popup` where `gid`=?"; //'".$key."'";
	$query = \OCP\DB::prepare($sql);
	$params = array($key);
	$result = $query->execute($params);
	$row = $result->fetchRow();
	//echo $row[0]. "<br>";
//	print_r($row);
	if ($row[1] == 1)
	{
//		$sql2 = "UPDATE `oc_popup` SET `message`='".$message."' where `gid`='".$key."'";

		$sql2 = "UPDATE `oc_popup` SET `message` = ?, `title` = ?,`closeBtn` = ?,`type` = ?,`position` = ?,`timeout` = ?  where `gid`=?";
	        $query2 = \OCP\DB::prepare($sql2);
		$params = array($message,$title,$closeBtn,$type,$position,$timeout,$key);
	        $result2 = $query2->execute($params);
	} else {
//		$sql = "INSERT INTO `oc_popup` (`gid`, `message`) VALUES ('".$key."', '".$message."')";
		$sql = "INSERT INTO `oc_popup` (`gid`, `message`, `title`,`closeBtn`,`type` ,`position` ,`timeout` ) VALUES (?,?,?,?,?,?,?)";
		$query = \OCP\DB::prepare($sql);
		$params = array($key,$message,$title,$closeBtn,$type,$position,$timeout);
		$result = $query->execute($params);
	}
header("Location: popup.php?ok");
exit;
	echo "<tr><td>key : ";
	echo $key . " : " . $gid;
	echo "</td></tr><tr><td>message : ";
	echo $message . " : " . $_POST['message'];
	echo "</td></tr><tr><td>title : ";
	echo $title . " : " . $_POST['title'];
	echo "</td></tr><tr><td>closeBtn : ";
	echo $closeBtn . " : " . $_POST['closeBtn'];
	echo "</td></tr><tr><td>type : ";
	echo $type . " : " . $_POST['type'];
	echo "</td></tr><tr><td>position : ";
	echo $position . " : " . $_POST['position'];
	echo "</td></tr><tr><td>timeout : ";
	echo $timeout . " : " . $_POST['timeout'];
	echo "</td></tr>";
	}
?>
</table>
