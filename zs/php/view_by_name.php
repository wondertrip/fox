<?php
  header('Content-type: text/html; charset=utf-8');
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  
  
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);

  $result = mysql_query_wrapper("select distinct name from trader_game order by total_rounds desc, num asc;");

  $content = "<option value=\"0\">--</option>";
  while($row = mysql_fetch_array($result)){
    $content .= "<option value=\"" . $row['name'] . "\">" . $row['name'] . "</option>";
  }
  
  mysql_close($con);
  
  echo $content;
?>