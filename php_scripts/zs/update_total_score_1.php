#!/alidata/server/php/bin/php
<?php
  $con = mysql_connect("localhost", "root", "Wind+3569");
  mysql_select_db("stock", $con);

  $max_round = 0;
  $max_round_result = mysql_query("select max(round) from zs_game");
  while ($row = mysql_fetch_array($max_round_result)){
    $max_round = $row['max(round)'];
    echo "max round is $max_round";
  }
  
  $prev_round = $max_round - 1;

  $latest_week_results = mysql_query("select * from zs_game where round = $max_round and num = 279");
  while($row = mysql_fetch_array($latest_week_results)) {
    $num = $row['num'];
    
    $prev_week_result = mysql_query("select * from zs_game where round < $max_round and num = $num order by round desc limit 0,1");
    while ($r = mysql_fetch_array($prev_week_result)) {
      $score = $r['score'];
      $total_score = $r['total_score'];
      $new_total = $score + $total_score;
      echo "$num ; $score ; $total_score ; $new_total\n";
      $update_sql = "update zs_game set total_score = $new_total where round = $max_round and num = $num";
      echo $update_sql . "\n";
      mysql_query($update_sql);
    }
  } 

  mysql_close($con);
?>
