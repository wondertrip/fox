<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($usernum, $username, $code1, $name1, $code2, $name2) {
	$query_user = mysql_query_wrapper("select num, name from zs_game where num = $usernum or name like '$username' limit 0,1");
	$uname = "";
	while ($row = mysql_fetch_array($query_user)) {
		$uname = $row['name'];
		$unum = $row['num'];
		if (trim($username) != trim($uname) || trim($usernum) != trim($unum)) {
			return "选手[" . $username . "] 学号记录为[" . $unum . "]. 此次输入学号为[" . $usernum . "]. 请核对后重新输入。" ;
		}
	}
	if (trim($code1) == trim($code2)) {
		return "不能输入两只相同的参赛股。";
	}
	$q1 = mysql_query_wrapper("select * from stockcode2name where code = '$code1'");
	$name = "";
	while ($row = mysql_fetch_array($q1)) {
		$name = $row['name'];
	}
	if (trim($name) != trim($name1)) {
		return "参赛股" . $code1 . ":" . $name1 . " 股票名称与股票代码不一致。系统中记录的该代码的对应股票名称为：[" . $name . "]. 请检查/更正后再提交。";
	}
	$q2 = mysql_query_wrapper("select * from stockcode2name where code = '$code2'");
	$name = "";
	while ($row = mysql_fetch_array($q2)) {
		$name = $row['name'];
	}
	if (trim($name) != trim($name2)) {
		return "参赛股" . $code2 . ":" . $name2 . " 股票名称与股票代码不一致。系统中记录的该代码的对应股票名称为：[" . $name . "]. 请检查/更正后再提交。";
	}
	return "VALID";
}

$round = trim($_POST['round']);
$team = trim($_POST['team']);
$usernum = trim($_POST['usernum']);
$username = trim($_POST['username']);
$stock_name1 = trim($_POST['stock_name1']);
$stock_code1 = trim($_POST['stock_code1']);
$stock_name2 = trim($_POST['stock_name2']);
$stock_code2 = trim($_POST['stock_code2']);

$target_stock = $stock_name1.":".$stock_code1.";".$stock_name2.":".$stock_code2;

$validateResult = validateInput($usernum, $username, $stock_code1, $stock_name1, $stock_code2, $stock_name2);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	$round = 43;
	$start_date = 20151109;
	$end_date = 20151113;
	
	$result = mysql_query_wrapper ( "select * from zs_game where round=$round and team = $team and num=$usernum" );
	
	$record_updated = false;
	while ( $row = mysql_fetch_array ( $result ) ) {
		$t_round = $row ['round'];
		$t_team = $row ['team'];
		$t_usernum = $row ['num'];
		if (! empty ( $t_round ) && ! empty ( $t_team ) && ! empty ( $t_usernum )) {
			$update_sql = "update zs_game set target_stock = '$target_stock', name='$username' where round = $round and team = $team and num = $usernum";
			mysql_query_wrapper ( $update_sql );
			$record_updated = true;
		}
	}
	
	if (! $record_updated) {
		$insert_sql = "insert into zs_game(round, team, num, name, target_stock, start_date, end_date," . "intro, start_price, current_price, incr_rate, highest_price, highest_incr_rate, score," . "total_rounds, champion_num, total_score, weibo) values" . " ($round, $team, $usernum, '$username', '$target_stock', $start_date, $end_date," . "'', ';', ';', ';', ';', ';', 0, 0, 0, 0, '')";
		mysql_query_wrapper ( $insert_sql );
	}
	
	mysql_close ( $con );
	
	echo "<script>alert('添加成功。');</script>";
	$converted_team = $team;
	if ($team == 1) {
		$converted_team = "";
	}
	echo "<script>location='/zs/index" . $converted_team . ".php'</script>";
}
?>
