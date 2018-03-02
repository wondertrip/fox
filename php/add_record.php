<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($username, $code1, $name1, $code2, $name2) {
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
$username = trim($_POST['username']);
$weibo = trim($_POST['weibo']);
$stock_name1 = trim($_POST['stock_name1']);
$stock_code1 = trim($_POST['stock_code1']);
$stock_name2 = trim($_POST['stock_name2']);
$stock_code2 = trim($_POST['stock_code2']);

$target_stock = $stock_name1.":".$stock_code1.";".$stock_name2.":".$stock_code2;

$validateResult = validateInput($username, $stock_code1, $stock_name1, $stock_code2, $stock_name2);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	$round = 1;
	$start_date = 20151012;
	$end_date = 201501016;
	
	$result = mysql_query_wrapper ( "select * from trader_game_new where round=$round and name = $username" );
	
	$record_updated = false;
	while ( $row = mysql_fetch_array ( $result ) ) {
		$t_round = $row ['round'];
		if (! empty ( $t_round )) {
			$update_sql = "update trader_game_new set target_stock = '$target_stock' where round = $round and name='$username'";
			mysql_query_wrapper ( $update_sql );
			$record_updated = true;
		}
	}
	
	if (! $record_updated) {
		$insert_sql = "insert into trader_game_new(round, name, target_stock, start_date, end_date," . 
		"intro, start_price, current_price, incr_rate, highest_price, highest_incr_rate, week_score, month_score, week_avg_score, total_score," . 
		"total_rounds, champion_num, weibo) values" . 
		" ($round, '$username', '$target_stock', $start_date, $end_date, '', ';', ';', ';', ';', ';', 0, 0, 0, 0, 0, 0, '')";
		mysql_query_wrapper ( $insert_sql );
	}
	
	mysql_close ( $con );
	
	echo "<script>alert('添加成功。');</script>";
	$converted_team = $team;
	if ($team == 1) {
		$converted_team = "";
	}
	echo "<script>location='/index.php'</script>";
}
?>
