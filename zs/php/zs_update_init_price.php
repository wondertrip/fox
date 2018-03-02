<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($round, $usernum, $username, $stockcode, $stockname) {
	$max_round = 0;
	$max_round_query = mysql_query_wrapper("select max(round) from zs_game");
	while ($row = mysql_fetch_array($max_round_query)) {
		$max_round = $row['max(round)'];
	}
	if ($round < $max_round) {
		return "只能更新最新期数的初始价格，不能更新历史记录的初始价格。最新期数为[" . $max_round . "]. 输入的期数为[" . $round . "].";
	}
	
	$query_user = mysql_query_wrapper("select num, name from zs_game where num = $usernum or name like '$username' limit 0,1");
	$uname = "";
	while ($row = mysql_fetch_array($query_user)) {
		$uname = $row['name'];
		$unum = $row['num'];
		if (trim($username) != trim($uname) || trim($usernum) != trim($unum)) {
			return "选手[" . $username . "] 学号记录为[" . $unum . "]. 此次输入学号为[" . $usernum . "]. 请核对后重新输入。" ;
		}
	}
	
	$q1 = mysql_query_wrapper("select * from stockcode2name where code = '$stockcode'");
	$name = "";
	while ($row = mysql_fetch_array($q1)) {
		$name = $row['name'];
	}
	if (trim($name) != trim($stockname)) {
		return "参赛股" . $stockcode . ":" . $stockname . " 股票名称与股票代码不一致。系统中记录的该代码的对应股票名称为：[" . $name . "]. 请检查/更正后再提交。";
	}
	
	return "VALID";
}

function update_stats($round, $usernum, $stockcode, $new_init_price, $new_h_price, $new_c_price) {
	$result = mysql_query_wrapper ( "select * from zs_game where round=$round and num=$usernum" );
	$return_message = "ok";
	while ( $row = mysql_fetch_array ( $result ) ) {
		$t_round = $row ['round'];
		$t_team = $row ['team'];
		$t_usernum = $row ['num'];
		$t_target_stock = $row['target_stock'];
		$t_start_price = $row['start_price'];
		$t_current_price = $row['current_price'];
		$t_highest_price = $row['highest_price'];
		$t_incr_rate = $row['incr_rate'];
		$t_highest_incr_rate = $row['highest_incr_rate'];
		$t_score = $row['score'];
	
		$target_stocks = explode(";", $t_target_stock);
		$start_prices = explode(";", $t_start_price);
		$current_prices = explode(";", $t_current_price);
		$highest_prices = explode(";", $t_highest_price);
		$incr_rates = explode(";", $t_incr_rate);
		$highest_incr_rates = explode(";", $t_highest_incr_rate);
	
	
		if (strpos($target_stocks[0], $stockcode) !== false) {
			$start_prices[0] = $new_init_price;
			$highest_prices[0] = $new_h_price;
			$current_prices[0] = $new_c_price;
			$incr_rates[0] = number_format(($current_prices[0] - $start_prices[0])/$start_prices[0]*100, 5);
			$highest_incr_rates[0] = number_format(($highest_prices[0] - $start_prices[0])/$start_prices[0]*100, 5);
		} elseif(strpos($target_stocks[1], $stockcode) !== false) {
			$start_prices[1] = $new_init_price;
			$highest_prices[1] = $new_h_price;
			$current_prices[1] = $new_c_price;
			$incr_rates[1] = number_format(($current_prices[1] - $start_prices[1])/$start_prices[1]*100, 5);
			$highest_incr_rates[1] = number_format(($highest_prices[1] - $start_prices[1])/$start_prices[1]*100, 5);
		} else {
			$return_message = "股票[" . $stockcode . "] 不是选手 [" .  $username . "]的参赛股。";
			app_log(DEBUG, $return_message);
		}
	
		if ($return_message == "ok") {
			$new_score = number_format((($incr_rates[0] + $highest_incr_rates[0])/2 + ($incr_rates[1] + $highest_incr_rates[1])/2)/2, 5);
				
			$new_start_price = implode(";", $start_prices);
			$new_highest_price = implode(";", $highest_prices);
			$new_current_price = implode(";", $current_prices);
			$new_incr_rate = implode(";", $incr_rates);
			$new_highest_incr_rate = implode(";", $highest_incr_rates);
				
			if (! empty ( $t_round ) && ! empty ( $t_team ) && ! empty ( $t_usernum )) {
				$update_sql = "update zs_game set start_price = '$new_start_price', " .
				"current_price = '$new_current_price', " .
				"highest_price = '$new_highest_price', " .
				"incr_rate = '$new_incr_rate', " .
				"highest_incr_rate = '$new_highest_incr_rate', " .
				"score = '$new_score' " .
				"where round = $round and num = $usernum";
				mysql_query_wrapper ( $update_sql );
				$insert_sql = "insert into zs_game_stock_xdr (stockcode, round, flag1) values ('$stockcode', '$round', '*')";
				mysql_query_wrapper ( $insert_sql );
				$return_message = "修改成功。";
			} else {
				$return_message = "数据库中找不到相应的记录： 期数：" . $round . "; 学号:" . $usernum ;
			}
		}
	}
	
	return $return_message;
}

$round = trim($_POST['round']);
$team = trim($_POST['team']);
$usernum = trim($_POST['usernum']);
$username = trim($_POST['username']);
$stockname = trim($_POST['stock_name']);
$stockcode = trim($_POST['stock_code']);
$new_init_price = trim($_POST['new_init_price']);
$new_h_price = trim($_POST['new_highest_price']);
$new_c_price = trim($_POST['new_current_price']);

$validateResult = validateInput($round, $usernum, $username, $stockcode, $stockname);

$return_message = "ok";

if ($validateResult != "VALID") {
	mysql_close ( $con );
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	$list = mysql_query_wrapper("select * from zs_game where round = $round and target_stock like '%$stockcode%'");
	while ($row = mysql_fetch_array($list)) {
		$unum = $row['num'];
		$return_message = update_stats($round, $unum, $stockcode, $new_init_price, $new_h_price, $new_c_price);
	}
	
	echo "<script>alert('" . $return_message . "');</script>";
	$converted_team = $team;
	if ($team == 1) {
		$converted_team = "";
	}
	mysql_close ( $con );
	echo "<script>location='/zs/index" . $converted_team . ".php'</script>";
}
?>
