<?php
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);

  $result = mysql_query_wrapper("select max(round) from trader_game");

  $max_round = 0;
  while($row = mysql_fetch_array($result)) {
	global $max_round;
    $max_round = $row['max(round)'];
  }
  
  $start_date = "";
  $end_date = "";
  
  $dates = mysql_query_wrapper("select * from trader_game where round = $max_round limit 0,1");
  while ($row = mysql_fetch_array($dates)) {
	  $start_date = $row['start_date'];
	  $end_date = $row['end_date'];
  }
  
  mysql_close($con);

  echo "<h3>我是操盘手短线选股游戏第".$max_round."周赛况表($start_date ~ $end_date)</h3>";
  echo "<h6>每个交易日上午/下午收盘后5分钟自动更新数据</h6>";
?>