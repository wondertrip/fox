#!/alidata/server/php/bin/php
<?php
  function isSuspended($prices){
    //response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
    return (number_format($prices[4],2) === "0.00" || $prices[4] === "0") && (number_format($prices[5],2) === "0.00" || $prices[5] === "0");
  }
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

  $all_stocks = mysql_query("select * from zs_game where round = $max_round");
  
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
    
    $s_code1 = "";
    $s_code2 = "";
    if (strpos($code1[1], "6") === 0) {
      $s_code1 = "sh" . $code1[1];
    } else {
      $s_code1 = "sz" . $code1[1];
    }
    //echo $s_code1 . "\n";
    if (strpos($code2[1], "6") === 0) {
      $s_code2 = "sh" . $code2[1];
    } else {
      $s_code2 = "sz" . $code2[1];
    }
    //echo $s_code2 . "\n";
    $url="http://hq.sinajs.cn/list=" . $s_code1 . "," . $s_code2;
    //echo $url . "\n";
    $content = mb_convert_encoding(file_get_contents($url), "utf8", "gb2312");
    //echo $content;

    //data -> 0:stock1; 1:stock2
    $data = explode("\n", $content);
    
    //existing_data  -> 0:start_price; 1:current_price; 2:incr_rate; 3:highest_price; 4:highest_incr_rate; 5:score
    $existing_data = explode("|", $code2prices[$stock_codes[$i]]);
    $start_price_arr = explode(";", $existing_data[0]);
    $old_current_price_arr = explode(";", $existing_data[1]);
    $old_incr_rate_arr = explode(";", $existing_data[2]);
    $old_highest_price_arr = explode(";", $existing_data[3]);
    $old_highest_incr_rate_arr = explode(";", $existing_data[4]);
    $old_score_arr = explode(";", $existing_data[5]);
    
    //response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
    $data1 = explode("=", $data[0]);
    $prices1 = explode(",", $data1[1]);
    echo $s_code1 . " --- " . "CurrentPrice:" . $prices1[3] . " HighestPrice:" . $prices1[4] . "\n";   

    $new_current_price1 = $old_current_price_arr[0];
    $new_incr_rate1 = $old_incr_rate_arr[0];
    if ($old_current_price_arr[0] != $prices1[3] && !isSuspended($prices1)) {
      $new_incr_rate1 = number_format(($prices1[3]-$start_price_arr[0])/$start_price_arr[0]*100, 5); 
      $new_current_price1 = number_format($prices1[3], 2);
    } else {
      if (isSuspended($prices1)){
        echo $code1[1] . " is suspended today!" . "\n";
      }
    } 
    $new_highest_price1 = $old_highest_price_arr[0];
    $new_highest_incr_rate1 = $old_highest_incr_rate_arr[0];
    if ($old_highest_price_arr[0] < $prices1[4] && !isSuspended($prices1)){
      $new_highest_incr_rate1 = number_format(($prices1[4]-$start_price_arr[0])/$start_price_arr[0]*100, 5);
      $new_highest_price1 = number_format($prices1[4], 2);
    } else {
      if (isSuspended($prices1)){
        echo $code1[1] . " is suspended today!" . "\n";
      }
    }
    //$new_incr_rate1_value = $new_incr_rate1;
    //$new_highest_incr_rate1_value = $new_highest_incr_rate1;
    $new_avg1 = number_format(($new_incr_rate1+$new_highest_incr_rate1)/2, 5);
    
    $data2 = explode("=", $data[1]);
    $prices2 = explode(",", $data2[1]);
    echo $s_code2 . " --- " . "CurrentPrice:" . $prices2[3] . " HighestPrice:" . $prices2[4] . "\n";

    $new_current_price2 = $old_current_price_arr[1];
    $new_incr_rate2 = $old_incr_rate_arr[1];
    if ($old_current_price_arr[1] != $prices2[3] && !isSuspended($prices2)) {
      $new_incr_rate2 = number_format(($prices2[3]-$start_price_arr[1])/$start_price_arr[1]*100, 5);
      $new_current_price2 = number_format($prices2[3], 2);
    } else {
      if (isSuspended($prices2)){
        echo $code2[1] . " is suspended today!" . "\n";
      }
    }
    //echo "===>>> new_current_price2: " . $new_current_price2 . "\n";
    //echo "===>>> new_incr_rate2: " . $new_incr_rate2 . "\n";
    $new_highest_price2 = $old_highest_price_arr[1];
    $new_highest_incr_rate2 = $old_highest_incr_rate_arr[1];
    if ($old_highest_price_arr[1] < $prices2[4] && !isSuspended($prices2)){
      $new_highest_incr_rate2 = number_format(($prices2[4]-$start_price_arr[1])/$start_price_arr[1]*100, 5);
      $new_highest_price2 = number_format($prices2[4], 2);
    } else {
      if (isSuspended($prices2)){
        echo $code2[1] . " is suspended today!" . "\n";
      }
    }
    //echo "===>>> new_current_price2: " . $new_current_price2 . "\n";
    //echo "===>>> new_incr_rate2: " . $new_incr_rate2 . "\n";

    //$new_incr_rate2_value = $new_incr_rate2;
    //$new_highest_incr_rate2_value = $new_highest_incr_rate2;
    $new_avg2 = number_format(($new_incr_rate2+$new_highest_incr_rate2)/2, 5);

    $new_score = number_format(($new_avg1+$new_avg2)/2, 5);
    echo $stock_codes[$i] . " -> new_incr_rate:" . $new_incr_rate1.";".$new_incr_rate2 . " | new highest_incr_rate:" . $new_highest_incr_rate1.";".$new_highest_incr_rate2 . " | new score:" . $new_score . "\n";
    
    $new_current_price = $new_current_price1 . ";" . $new_current_price2;
    $new_incr_rate = $new_incr_rate1 . ";" . $new_incr_rate2;
    $new_highest_price = $new_highest_price1 . ";" . $new_highest_price2;
    $new_highest_incr_rate = $new_highest_incr_rate1 . ";" . $new_highest_incr_rate2;
   
    $update_sql = "update zs_game set current_price=\"$new_current_price\", incr_rate=\"$new_incr_rate\", highest_price=\"$new_highest_price\", highest_incr_rate=\"$new_highest_incr_rate\", score=$new_score where round = $max_round and target_stock = \"$stock_codes[$i]\"";
    echo $update_sql . "\n";

    mysql_query($update_sql);
  }  

  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
