<?php
  header('Content-type: text/html; charset=utf-8');
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);
  
  $content = "<option value=\"0\">--</option>";
  $result = mysql_query_wrapper("select distinct round from zs_game order by round desc");
  while($row = mysql_fetch_array($result)){
    $content .= "<option value=\"" . $row['round'] . "\">" . $row['round'] . "</option>";
  }
  
  mysql_close($con);
  
  echo $content;
?>