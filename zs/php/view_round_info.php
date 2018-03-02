<?php
  header('Content-type: text/html; charset=utf-8');
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);
  
  $content = "<p>当前最新期数为";
  $result = mysql_query_wrapper("select round, start_date, end_date from zs_game_round order by round desc limit 0,1");
  while($row = mysql_fetch_array($result)){
    $content .= $row['round'];
    $content .= ". 开始日期:".$row['start_date'].". 结束日期:".$row['end_date']."</p>";
  }
  
  mysql_close($con);
  
  echo $content;
?>