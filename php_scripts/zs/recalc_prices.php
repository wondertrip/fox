#!/alidata/server/php/bin/php
<?php
  echo date('Y-m-d H:i:s',time()) . "\n";
  echo "--------------------------START-----------------------------\n";
  $con = mysql_connect("localhost","root","Wind+3569");
  mysql_select_db("stock", $con);
  $max_round_result = mysql_query("select max(round) from zs_game");

  $max_round = 0;
  while($row = mysql_fetch_array($max_round_result)) {
    $max_round = $row['max(round)'];
  }
  //echo "Max Round : " . $max_round;

  $all_stocks = mysql_query("select * from zs_game where round = $max_round and num = 279");
  
  //stock codes are in format: 中粮生化:000930;森源电气:002358
  $stock_codes = array();

  //prices are: current_price; incr_rate; highest_price; highest_incr_rate; score
  $code2prices = array();

  $start_date = "";
  $end_date = "";
  while($row = mysql_fetch_array($all_stocks)) {
    $stock_codes[] = $row['target_stock'];
    $code2prices[$row['target_stock']] = $row['start_price'] . "|" . $row['current_price'] . "|" . $row['incr_rate'] . "|" . $row['highest_price'] . "|" . $row['highest_incr_rate'] . "|" . $row['score'];
    $start_date = $row['start_date'];
    $end_date = $row['end_date'];
  } 

  $arraylen = count($stock_codes);
  for ($i = 0; $i < $arraylen; $i++){
    echo $stock_codes[$i] . "\n";
    echo $code2prices[$stock_codes[$i]] . "\n";
    $tmp_code_arr = explode(";", $stock_codes[$i]);
    $code1 = explode(":", $tmp_code_arr[0]);
    $code2 = explode(":", $tmp_code_arr[1]);
    $stockcode1 = $code1[1];
    $stockcode2 = $code2[1]; 
    $start_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode1' and tradedate < $start_date order by tradedate desc limit 0,1");
    $start_price1 = "";
    while ($sp1 = mysql_fetch_array($start_price)) {
      $start_price1 = $sp1['closePrice'];
    }

    $start_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode2' and tradedate < $start_date order by tradedate desc limit 0,1");
    $start_price2 = "";
    while ($sp2 = mysql_fetch_array($start_price)) {
      $start_price2 = $sp2['closePrice'];
    }

    $current_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode1' and tradedate >= $start_date order by tradedate desc limit 0,1");
    $current_price1 = "";
    while ($cp1 = mysql_fetch_array($current_price)) {
      $current_price1 = $cp1['closePrice'];
    }

    $current_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode2' and tradedate >= $start_date order by tradedate desc limit 0,1");
    $current_price2 = "";
    while ($cp2 = mysql_fetch_array($current_price)) {
      $current_price2 = $cp2['closePrice'];
    }

    $highest_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode1' and tradedate >= $start_date order by tradedate desc ");
    $highest_price1 = 0;
    while ($hp1 = mysql_fetch_array($highest_price)) {
      $tmp = $hp1['highestPrice'];
      if ($tmp > $highest_price1) {
        $highest_price1 = $tmp;
      }
    }

    $highest_price = mysql_query("select * from stockdailyinfo where stockcode = '$stockcode2' and tradedate >= $start_date order by tradedate desc ");
    $highest_price2 = 0;
    while ($hp2 = mysql_fetch_array($highest_price)) {
      $tmp = $hp2['highestPrice'];
      if ($tmp > $highest_price2) {
        $highest_price2 = $tmp;
      }
    }
    $incr_rate1 = number_format(($current_price1-$start_price1)/$start_price1*100, 5);
    $highest_incr_rate1 = number_format(($highest_price1-$start_price1)/$start_price1*100, 5);
    $avg1 = number_format(($incr_rate1+$highest_incr_rate1)/2, 5);
    
    $incr_rate2 = number_format(($current_price2-$start_price2)/$start_price2*100, 5);
    $highest_incr_rate2 = number_format(($highest_price2-$start_price2)/$start_price2*100, 5);
    $avg2 = number_format(($incr_rate2+$highest_incr_rate2)/2, 5);

    $new_score = number_format(($avg1+$avg2)/2, 5);
    echo $stock_codes[$i] . " -> incr_rate:" . $incr_rate1.";".$incr_rate2 . " | highest_incr_rate:" . $highest_incr_rate1.";".$highest_incr_rate2 . " | score:" . $new_score . "\n";
    
    $new_current_price = $current_price1 . ";" . $current_price2;
    $new_incr_rate = $incr_rate1 . ";" . $incr_rate2;
    $new_highest_price = $highest_price1 . ";" . $highest_price2;
    $new_highest_incr_rate = $highest_incr_rate1 . ";" . $highest_incr_rate2;
   
    $update_sql = "update zs_game set current_price=\"$new_current_price\", incr_rate=\"$new_incr_rate\", highest_price=\"$new_highest_price\", highest_incr_rate=\"$new_highest_incr_rate\", score=$new_score where round = $max_round and target_stock = \"$stock_codes[$i]\"";
    echo $update_sql . "\n";

    mysql_query($update_sql);
  }  

  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
