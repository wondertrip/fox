<?php
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  $team = $_GET['team'];
  
  mysql_select_db("stock", $con);

  $tmp_team = $team;
  
  if ($team == 8 || $team == 9) {
  	$tmp_team = 1;
  	$result = mysql_query_wrapper("select max(round) from zs_game where team = $tmp_team");
  } else {
  	$result = mysql_query_wrapper("select max(round) from zs_game where team = $team");
  }
  

  $max_round = 0;
  while($row = mysql_fetch_array($result)) {
    $max_round = $row['max(round)'];
  }
  
  $start_date = "";
  $end_date = "";
  $week = 0;
  $dates = mysql_query_wrapper("select * from zs_game where round = $max_round and team = $tmp_team limit 0,1");
  while ($row = mysql_fetch_array($dates)) {
	  $start_date = $row['start_date'];
	  $end_date = $row['end_date'];
  }
  
  mysql_close($con);

  if ($team == 9) {
  	echo "<h3>中暑山庄最强操盘手第".$max_round."周赛况总表($start_date ~ $end_date)</h3>";
  } elseif ($team == 8) {
  	echo "<h3>中暑山庄最强操盘手八强赛第".$max_round."周赛况表($start_date ~ $end_date)</h3>";
  } else {
  	echo "<h3>中暑山庄最强操盘手第".$team."组第".$max_round."周赛况表($start_date ~ $end_date)</h3>";
  }
  
  echo "<h6>每个交易日上午/下午收盘后5分钟自动更新数据</h6>";
?>