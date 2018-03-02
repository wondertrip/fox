<?php
chdir ( dirname ( __FILE__ ) );
require_once ("./utils.php");

$con = mysql_connect ( "localhost", "root", "Wind+3569" );
if (! $con) {
	die ( 'Could not connect: ' . mysql_error () );
}
mysql_select_db ( "stock", $con );

$round = trim ( $_POST ['round'] );
$start_date = trim ( $_POST ['start_date'] );
$end_date = trim ( $_POST ['end_date'] );

mysql_query_wrapper ( "insert into zs_game_round values ($round, $start_date, $end_date, '')" );
mysql_query_wrapper ( "update zs_game_round set start_date=$start_date, end_date=$end_date where round=$round" );

mysql_close ( $con );

echo "<script>alert('更新成功。');</script>";

echo "<script>location='/zs/admin.php'</script>";
?>