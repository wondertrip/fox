<?php
  $startTime = time();
  echo "separator\n";
  $endTime = time();
  echo "Time on this db " . ($endTime - $startTime). "\n"; 
  
  $prefix = "<li><a target=\"_blank\" href=\"http://quote.eastmoney.com/";
  $record = "<li><a target=\"_blank\" href=\"http://quote.eastmoney.com/sh600067.html\">冠城大通(600067)</a></li>";
  
  echo strpos($record, $prefix) . "\n" ;
  
?>
