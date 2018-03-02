#!/alidata/server/php/bin/php
<?php
  $con = mysql_connect("localhost", "root", "Wind+3569");
  mysql_select_db("stock", $con);
  
  $round = 40;
  $next_round = $round + 1;
  $prev_champion = mysql_query("select * from trader_game where round = $round order by score desc limit 0, 1");
  while ($row = mysql_fetch_array($prev_champion)){
    $champion_num = $row['champion_num'];
    $new_champion_num = $champion_num + 1;
    $name = $row['name'];
    $update_sql = "update trader_game set champion_num = $new_champion_num where round = $next_round and name = \"$name\"";
    echo $update_sql . "\n";
    mysql_query($update_sql);
  }
  mysql_close($con);
?>
