<?php
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  
  $round = $_GET['round'];
  
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);

  $result = mysql_query_wrapper("select * from trader_game where round = $round limit 0,1");

  $start_date = 0;
  $end_date = 0;
  while($row = mysql_fetch_array($result)) {
    $start_date = $row['start_date'];
	$end_date = $row['end_date'];
  }
  
  mysql_close($con);

  $info_header = "<h3>我是操盘手短线选股游戏第".$round."周赛况表($start_date ~ $end_date)</h3>";
  
  app_log(DEBUG, $info_header);
  
  echo $info_header;
?>