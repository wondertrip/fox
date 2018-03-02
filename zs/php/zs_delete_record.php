<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($usernum, $username) {
	$query_user = mysql_query_wrapper("select num, name from zs_game where num = $usernum or name like '$username' limit 0,1");
	$uname = "";
	while ($row = mysql_fetch_array($query_user)) {
		$uname = $row['name'];
		$unum = $row['num'];
		if (trim($username) != trim($uname) || trim($usernum) != trim($unum)) {
			return "选手[" . $username . "] 学号记录为[" . $unum . "]. 此次输入学号为[" . $usernum . "]. 请核对后重新输入。" ;
		}
	}
	
	return "VALID";
}

$round = trim($_POST['round']);
$team = trim($_POST['team']);
$usernum = trim($_POST['usernum']);
$username = trim($_POST['username']);


$validateResult = validateInput($usernum, $username);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	mysql_query_wrapper ( "delete from zs_game where round=$round and team=$team and num=$usernum" );
	
	mysql_close ( $con );
	
	echo "<script>alert('删除成功。');</script>";
	
	echo "<script>location='/zs/index.php'</script>";
}
?>
