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
		return "[". $stockcode . "]不在已有定增跟踪列表中，无法单独添加备注。";
	}
	
	return "VALID";
}

$stockcode = $_POST['stock_code'];
$dz_firm_approved_date = $_POST['dz_firm_approved_date'];
$comments = $_POST['comments'];
$latest_announce = $_POST['announce'];

$validateResult = validateInput($stockcode, $stockname);

if ($validateResult != "VALID") {
	echo "<script type=\"text/javascript\">alert('$validateResult'); history.go(-1);</script>";
} else {
	if (trim($comments) !== ""){
		$update_sql = "update stock_dz_pre_track set comments=concat(comments, '<br>$comments') where stockcode = '$stockcode' and dz_firm_approved_date = $dz_firm_approved_date";
		mysql_query_wrapper ( $update_sql );
	}
	if (trim($latest_announce) !== "") {
		$update_sql = "update stock_dz_pre_track set latest_announce='$latest_announce' where stockcode = '$stockcode' and dz_firm_approved_date = $dz_firm_approved_date";
		mysql_query_wrapper ( $update_sql );
	}
	
	
	mysql_close ( $con );
	
	echo "<script>alert('添加成功。');</script>";
	echo "<script>location='/zs/dz_pre_track.php'</script>";
}
?>
