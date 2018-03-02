<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$con = mysql_connect("localhost","root","Wind+3569");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db("stock", $con);

function validateInput($stockcode, $stockname) {
	$q1 = mysql_query_wrapper("select * from stock_dz_pre_track where stockcode = '$stockcode'");
	$name = "";
	while ($row = mysql_fetch_array($q1)) {
		$name = $row['stockname'];
	}
	if (trim($name) == "") {
		return "[". $stockcode . "]不在已有定增跟踪列表中，无法更新。";
	}
	
	return "VALID";
}

$stockcode = $_POST['stock_code'];
$dz_firm_approved_date = $_POST['dz_firm_approved_date'];
$dz_current_status = $_POST['dz_current_status'];
$dz_status_approved_date = $_POST['dz_status_approved_date'];

$validateResult = validateInput($stockcode, $stockname);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	$insert_sql = "insert into stock_dz_pre_track_props (stockcode, dz_firm_approved_date, prop_name, prop_value, update_time) " .
	              "values ('$stockcode', $dz_firm_approved_date, '$dz_current_status', '$dz_status_approved_date', NOW())";
	mysql_query_wrapper ( $insert_sql );
	
	
	mysql_close ( $con );
	
	echo "<script>alert('更新成功。');</script>";
	echo "<script>location='/zs/dz_pre_track.php'</script>";
}
?>
