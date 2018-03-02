<?php
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);
  
  $enddate = $_GET['enddate'];

  $result = mysql_query_wrapper("select * from stock_rzrq_stat where enddate = $enddate");

  $result_set = array();
  while($row = mysql_fetch_array($result)) {
	$rs = array($row['stockcode'], $row['stockname'], $row['cashTotal'], $row['cashBuy'], $row['cashRatio'], $row['continueIncrDays']);
	array_push($result_set, $rs);
  }
  
  mysql_close($con);
  
  echo urldecode(json_encode(array("data"=>$result_set)));
?>