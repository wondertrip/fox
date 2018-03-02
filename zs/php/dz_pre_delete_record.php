<?php
chdir ( dirname ( __FILE__ ) );
require_once ("./utils.php");

$con = mysql_connect ( "localhost", "root", "Wind+3569" );
if (! $con) {
	die ( 'Could not connect: ' . mysql_error () );
}
mysql_select_db ( "stock", $con );

$stock_code = trim ( $_POST ['stock_code'] );
$dz_firm_approved_date = trim ( $_POST ['dz_firm_approved_date'] );

mysql_query_wrapper ( "delete from stock_dz_pre_track where stockcode='$stock_code' and dz_firm_approved_date=$dz_firm_approved_date" );

mysql_close ( $con );

echo "<script>alert('删除成功。');</script>";

echo "<script>location='/zs/dz_pre_track.php'</script>";
?>
