<?php
header('Content-type: text/html; charset=utf-8');
chdir(dirname(__FILE__));
define("LINK_TMPL", "<a href=\"./php/stock_info.php?code=stock_code\" target=\"_blank\">stock_code</a>");
require_once("./utils.php");

function formatStockLink($stock_code) {  
  return str_replace("stock_code", $stock_code, LINK_TMPL);
}

function getDataByShareHolder($share_holder, $time, $con){
  $rows = array();

  $result = mysql_query_wrapper("select * from stock_share_holders where enddate = $time and name like \"%$share_holder%\" ");

  while($row = mysql_fetch_array($result)) {
    $record = array(formatStockLink($row['stockcode']), $row['name'], $row['ranking'], $row['amount'], $row['ratio'], $row['category']);
	array_push($rows, $record);
  }
  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

function removeSpecialChars($share_holder) {
  $tmp1 = str_replace("<", "", $share_holder);
  $tmp2 = str_replace(">", "", $tmp1);
  $tmp3 = str_replace(";", "", $tmp2);
  $tmp4 = str_replace("=", "", $tmp3);
  $tmp5 = str_replace("&", "", $tmp4);
  return $tmp5;
}

$share_holder = $_GET['share_holder'];
$time = $_GET['time'];

app_log(INFO, "share_holder:[" . $share_holder . "]; time:[" . $time . "].");

$con = mysql_connect("localhost","stock_reader","pasword");
if (!$con){
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("stock", $con);

return getDataByShareHolder(removeSpecialChars($share_holder), $time, $with_round);

?>