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

  $all_stocks = mysql_query("select * from zs_game where round = $max_round and (start_price like '%;0.00' or start_price like '0.00;%')");
  
  //stock codes are in format: 中粮生化:000930;森源电气:002358
  $stock_codes = array();

  //prices are: current_price; incr_rate; highest_price; highest_incr_rate; score
  $code2prices = array();

  while($row = mysql_fetch_array($all_stocks)) {
    $stock_codes[] = $row['target_stock'];
    $code2prices[$row['target_stock']] = $row['start_price'] . "|" . $row['current_price'] . "|" . $row['incr_rate'] . "|" . $row['highest_price'] . "|" . $row['highest_incr_rate'] . "|" . $row['score'];
  } 

  $arraylen = count($stock_codes);
  for ($i = 0; $i < $arraylen; $i++){
    echo $stock_codes[$i] . "\n";
    echo $code2prices[$stock_codes[$i]] . "\n";
    $tmp_code_arr = explode(";", $stock_codes[$i]);
    $code1 = explode(":", $tmp_code_arr[0]);
    $code2 = explode(":", $tmp_code_arr[1]);
    //echo $tmp_code_arr[0] . "\n";    
    //echo $tmp_code_arr[1] . "\n";
    
    
    //existing_data  -> 0:start_price; 1:current_price; 2:incr_rate; 3:highest_price; 4:highest_incr_rate; 5:score
    $existing_data = explode("|", $code2prices[$stock_codes[$i]]);
    $start_price_arr = explode(";", $existing_data[0]);
    $old_current_price_arr = explode(";", $existing_data[1]);
    $old_incr_rate_arr = explode(";", $existing_data[2]);
    $old_highest_price_arr = explode(";", $existing_data[3]);
    $old_highest_incr_rate_arr = explode(";", $existing_data[4]);
    $old_score_arr = explode(";", $existing_data[5]);
    
    if ($start_price_arr[0] === "0.00") {
      $init_price1 = mysql_query("select * from stockdailyinfo where stockcode = '$code1[1]' and tradedate < 20150713 order by tradedate desc limit 0,1");
      while ($r = mysql_fetch_array($init_price1)) {
        $start_price_arr[0] = $r['closePrice'];
      }
    }
    echo $code1[1] . " start price : " . $start_price_arr[0] . "\n";

    if ($start_price_arr[1] === "0.00") {
      $init_price2 = mysql_query("select * from stockdailyinfo where stockcode = '$code2[1]' and tradedate < 20150713 order by tradedate desc limit 0,1");
      while ($r = mysql_fetch_array($init_price2)) {
        $start_price_arr[1] = $r['closePrice'];
      }
    }
    echo $code2[1] . " start price : " . $start_price_arr[1] . "\n";

    $new_current_price1 = $old_current_price_arr[0];
    $new_incr_rate1 = $old_incr_rate_arr[0];
    if ($start_price_arr[0] !== "0.00"){
      $new_incr_rate1 = number_format(($new_current_price1-$start_price_arr[0])/$start_price_arr[0]*100, 5); 
    }

    $new_highest_price1 = $old_highest_price_arr[0];
    $new_highest_incr_rate1 = $old_highest_incr_rate_arr[0];
    if ($start_price_arr[0] !== "0.00"){
      $new_highest_incr_rate1 = number_format(($new_highest_price1-$start_price_arr[0])/$start_price_arr[0]*100, 5);
    }

    //$new_incr_rate1_value = $new_incr_rate1;
    //$new_highest_incr_rate1_value = $new_highest_incr_rate1;
    $new_avg1 = number_format(($new_incr_rate1+$new_highest_incr_rate1)/2, 5);
    

    $new_current_price2 = $old_current_price_arr[1];
    $new_incr_rate2 = $old_incr_rate_arr[1];
    if ($start_price_arr[1] !== "0.00"){
      $new_incr_rate2 = number_format(($new_current_price2-$start_price_arr[1])/$start_price_arr[1]*100, 5);
    }
    //echo "===>>> new_current_price2: " . $new_current_price2 . "\n";
    //echo "===>>> new_incr_rate2: " . $new_incr_rate2 . "\n";
    $new_highest_price2 = $old_highest_price_arr[1];
    $new_highest_incr_rate2 = $old_highest_incr_rate_arr[1];
    if ($start_price_arr[1] !== "0.00"){
      $new_highest_incr_rate2 = number_format(($new_highest_price2-$start_price_arr[1])/$start_price_arr[1]*100, 5);
    }
    //echo "===>>> new_current_price2: " . $new_current_price2 . "\n";
    //echo "===>>> new_incr_rate2: " . $new_incr_rate2 . "\n";

    //$new_incr_rate2_value = $new_incr_rate2;
    //$new_highest_incr_rate2_value = $new_highest_incr_rate2;
    $new_avg2 = number_format(($new_incr_rate2+$new_highest_incr_rate2)/2, 5);

    $new_score = number_format(($new_avg1+$new_avg2)/2, 5);
    echo $stock_codes[$i] . " -> new_incr_rate:" . $new_incr_rate1.";".$new_incr_rate2 . " | new highest_incr_rate:" . $new_highest_incr_rate1.";".$new_highest_incr_rate2 . " | new score:" . $new_score . "\n";
    
    $new_start_price = $start_price_arr[0].";".$start_price_arr[1];
    $new_current_price = $new_current_price1 . ";" . $new_current_price2;
    $new_incr_rate = $new_incr_rate1 . ";" . $new_incr_rate2;
    $new_highest_price = $new_highest_price1 . ";" . $new_highest_price2;
    $new_highest_incr_rate = $new_highest_incr_rate1 . ";" . $new_highest_incr_rate2;
   
    $update_sql = "update zs_game set start_price=\"$new_start_price\", current_price=\"$new_current_price\", incr_rate=\"$new_incr_rate\", highest_price=\"$new_highest_price\", highest_incr_rate=\"$new_highest_incr_rate\", score=$new_score where round = $max_round and target_stock = \"$stock_codes[$i]\"";
    echo $update_sql . "\n";

    mysql_query($update_sql);
  }  

  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
