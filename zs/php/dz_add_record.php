<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($stockcode, $stockname) {
	$q1 = mysql_query_wrapper("select * from stockcode2name where code = '$stockcode'");
	$name = "";
	while ($row = mysql_fetch_array($q1)) {
		$name = $row['name'];
	}
	if (trim($name) != trim($stockname)) {
		return $stockcode . ":" . $stockname . " 股票名称与股票代码不一致。请检查/更正后再提交。";
	}
	
	return "VALID";
}

$stockcode = $_POST['stock_code'];
$stockname = $_POST['stock_name'];
$dz_date = $_POST['dz_date'];
$unfreeze_date = $_POST['unfreeze_date'];
$dz_price = $_POST['dz_price'];
$dz_total = $_POST['dz_total'];
$current_price = $_POST['current_price'];
$latest_incr_rate = $_POST['latest_incr_rate'];
$incr_rate = $_POST['incr_rate'];
$comments = $_POST['comments'];

if (trim($unfreeze_date) == ""){
  $unfreeze_date = 0;
}

if (trim($current_price) == ""){
  $current_price = 0;
}

if (trim($incr_rate) == ""){
  $incr_rate = 0;
}

if (trim($latest_incr_rate) == "") {
	$latest_incr_rate = 0;
}

$incr_rate = str_replace("%", "", $incr_rate);
$latest_incr_rate = str_replace("%", "", $latest_incr_rate);

$validateResult = validateInput($stockcode, $stockname);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	$result = mysql_query_wrapper ( "select * from stock_dz_track where stockcode = $stockcode and dz_date=$dz_date" );
	
	$record_updated = false;
	while ( $row = mysql_fetch_array ( $result ) ) {
		$t_code = $row ['stockcode'];
		$t_date = $row ['dz_date'];
		if (! empty ( $t_code ) && ! empty ( $t_date )) {
			$update_sql = "update stock_dz_track set dz_unfreeze_date = $unfreeze_date, dz_price=$dz_price, dz_amount=$dz_total, comments=concat(comments, '<br>$comments') where stockcode = $stockcode and dz_date = $dz_date";
			mysql_query_wrapper ( $update_sql );
			$record_updated = true;
		}
	}
	
	if (! $record_updated) {
		$insert_sql = "insert into stock_dz_track(stockcode, stockname, dz_date, dz_unfreeze_date, dz_price, " .
				"dz_amount, current_price, incr_rate, latest_incr_rate, comments) values" . 
		        " ('$stockcode', '$stockname', $dz_date, $unfreeze_date, $dz_price, $dz_total, $current_price, $latest_incr_rate, $incr_rate, '$comments')";
		mysql_query_wrapper ( $insert_sql );
	}
	
	mysql_close ( $con );
	
	echo "<script>alert('添加成功。');</script>";
	echo "<script>location='/zs/dz_track.php'</script>";
}
?>
