<?php
  header('Content-type: text/html; charset=utf-8');
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  
  $con = mysql_connect("localhost","stock_reader","pasword");
  if (!$con){
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("stock", $con);
  
  $code = trim($_GET['code']);
  
  $name = "";
  $result = mysql_query_wrapper("select name from stockcode2name where code = '$code'");
  while($row = mysql_fetch_array($result)){
    $name = $row['name'];
  }
  
  mysql_close($con);
  
  echo $name;
?>
