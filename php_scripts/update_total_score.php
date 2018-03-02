#!/alidata/server/php/bin/php
<?php
  $con = mysql_connect("localhost", "root", "Wind+3569");
  mysql_select_db("stock", $con);

  $round = 37;
  $next_round = $round + 1;

  $prev_week_results = mysql_query("select * from trader_game where round = $round");
  while($row = mysql_fetch_array($prev_week_results)) {
    $score = $row['score'];
    $total_score = $row['total_score'];
    $new_total = $score + $total_score;
    $name = $row['name'];
    echo "$name ; $score ; $total_score ; $new_total\n";
    $update_sql = "update trader_game set total_score = $new_total where round = $next_round and name = '$name'";
    echo $update_sql . "\n";
    mysql_query($update_sql);
  } 

  mysql_close($con);
?>
