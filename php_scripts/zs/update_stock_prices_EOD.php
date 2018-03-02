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

  $all_stocks = mysql_query("select * from zs_game where round = $max_round");
  
  //stock codes are in format: 中粮生化:000930;森源电气:002358
  $stock_codes = array();

  //prices are: current_price; incr_rate; highest_price; highest_incr_rate; score
  $code2prices = array();

  $start_date = "";
  while($row = mysql_fetch_array($all_stocks)) {
  	$start_date = $row['start_date'];
    $stock_codes[] = $row['target_stock'];
    $code2prices[$row['target_stock']] = $row['start_price'] . "|" . $row['current_price'] . "|" . $row['incr_rate'] . "|" . $row['highest_price'] . "|" . $row['highest_incr_rate'] . "|" . $row['score'];
  } 

  $arraylen = count($stock_codes);
  for ($i = 0; $i < $arraylen; $i++){
    echo $stock_codes[$i] . "\n";
    echo $start_date . "\n";
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
    
    //existing_data  -> 0:start_price; 1:current_price; 2:incr_rate; 3:highest_price; 4:highest_incr_rate; 5:score
    $existing_data = explode("|", $code2prices[$stock_codes[$i]]);
    $old_start_price_arr = explode(";", $existing_data[0]);
    $old_current_price_arr = explode(";", $existing_data[1]);
    $old_highest_price_arr = explode(";", $existing_data[3]);
    
    $new_start_price1 = $old_start_price_arr[0];
    echo $code1[1] . "\t start price:" . $new_start_price1."\n";
    $prev_info1 = mysql_query("select * from stockdailyinfo_fq where stockcode = '$code1[1]' and tradedate < $start_date order by tradedate desc limit 0,1");
    while ($prev_info1_row = mysql_fetch_array($prev_info1)) {
    	if ($prev_info1_row['closePrice'] != NULL) {
    		$new_start_price1 = number_format($prev_info1_row['closePrice'], 2);
    	}
    }
    echo $code1[1] . "\t start price:" . $new_start_price1."\n";
    
    $new_start_price2 = $old_start_price_arr[1];
    echo $code2[1] . "\t start price:" . $new_start_price2."\n";
    $prev_info2 = mysql_query("select * from stockdailyinfo_fq where stockcode = '$code2[1]' and tradedate < $start_date order by tradedate desc limit 0,1");
    while ($prev_info2_row = mysql_fetch_array($prev_info2)) {
    	if ($prev_info2_row['closePrice'] != NULL) {
    		$new_start_price2 = number_format($prev_info2_row['closePrice'], 2);
    	}
    }
    echo $code2[1] . "\t start price:" . $new_start_price2."\n";
    
    $new_current_price1 = $old_current_price_arr[0];
    echo $code1[1] . "\t current price:" . $new_current_price1."\n";
    $prev_info1 = mysql_query("select * from stockdailyinfo_fq where stockcode = '$code1[1]' and tradedate >= $start_date order by tradedate desc limit 0,1");
    while ($prev_info1_row = mysql_fetch_array($prev_info1)) {
    	if ($prev_info1_row['closePrice'] != NULL){
	    	$new_current_price1 = number_format($prev_info1_row['closePrice'], 2);
    	}
    }
    echo $code1[1] . "\t current price:" . $new_current_price1."\n";
    
  	$new_current_price2 = $old_current_price_arr[1];
  	echo $code2[1] . "\t current price:" . $new_current_price2."\n";
    $prev_info2 = mysql_query("select * from stockdailyinfo_fq where stockcode = '$code2[1]' and tradedate >= $start_date order by tradedate desc limit 0,1");
    while ($prev_info2_row = mysql_fetch_array($prev_info2)) {
    	if ($prev_info2_row['closePrice'] != NULL) {
    		$new_current_price2 = number_format($prev_info2_row['closePrice'], 2);
    	}
    }
    echo $code2[1] . "\t current price:" . $new_current_price2."\n";
    
    $new_incr_rate1 = number_format(($new_current_price1-$new_start_price1)/$new_start_price1*100, 5);
    $new_incr_rate2 = number_format(($new_current_price2-$new_start_price2)/$new_start_price2*100, 5);
    
    echo $code1[1] . "\t incr rate:" . $new_incr_rate1."\n";
    echo $code2[1] . "\t incr rate:" . $new_incr_rate2."\n";
    
    $new_highest_price1 = $old_highest_price_arr[0];
    echo $code1[1] . "\t highest price:" . $new_highest_price1."\n";
    $prev_info1 = mysql_query("select max(highestPrice) from stockdailyinfo_fq where stockcode = '$code1[1]' and tradedate >= $start_date");
    while ($prev_info1_row = mysql_fetch_array($prev_info1)) {
    	if ($prev_info1_row['max(highestPrice)'] != NULL) {
    		$new_highest_price1 = number_format($prev_info1_row['max(highestPrice)'], 2);
    	}
    }
    echo $code1[1] . "\t highest price:" . $new_highest_price1."\n";
    
    $new_highest_price2 = $old_highest_price_arr[1];
    echo $code2[1] . "\t highest price:" . $new_highest_price2."\n";
    $prev_info2 = mysql_query("select max(highestPrice) from stockdailyinfo_fq where stockcode = '$code2[1]' and tradedate >= $start_date");
    while ($prev_info2_row = mysql_fetch_array($prev_info2)) {
    	if ($prev_info2_row['max(highestPrice)'] != NULL) {
    		$new_highest_price2 = number_format($prev_info2_row['max(highestPrice)'], 2);
    	}
    }
    echo $code2[1] . "\t highest price:" . $new_highest_price2."\n";
    
    $new_highest_incr_rate1 = number_format(($new_highest_price1-$new_start_price1)/$new_start_price1*100, 5);
    $new_highest_incr_rate2 = number_format(($new_highest_price2-$new_start_price2)/$new_start_price2*100, 5);

    echo $code1[1] . "\t highest incr rate:" . $new_highest_incr_rate1."\n";
    echo $code2[1] . "\t highest incr rate:" . $new_highest_incr_rate2."\n";
    
    $new_avg1 = number_format(($new_incr_rate1+$new_highest_incr_rate1)/2, 5);
    $new_avg2 = number_format(($new_incr_rate2+$new_highest_incr_rate2)/2, 5);

    $new_score = number_format(($new_avg1+$new_avg2)/2, 5);
    echo $stock_codes[$i] . " -> new_incr_rate:" . $new_incr_rate1.";".$new_incr_rate2 . " | new highest_incr_rate:" . $new_highest_incr_rate1.";".$new_highest_incr_rate2 . " | new score:" . $new_score . "\n";
    
    $new_start_price = $new_start_price1 . ";" . $new_start_price2;
    $new_current_price = $new_current_price1 . ";" . $new_current_price2;
    $new_incr_rate = $new_incr_rate1 . ";" . $new_incr_rate2;
    $new_highest_price = $new_highest_price1 . ";" . $new_highest_price2;
    $new_highest_incr_rate = $new_highest_incr_rate1 . ";" . $new_highest_incr_rate2;
   
    $update_sql = "update zs_game set start_price='$new_start_price', current_price='$new_current_price', incr_rate='$new_incr_rate', highest_price='$new_highest_price', highest_incr_rate='$new_highest_incr_rate', score=$new_score where round = $max_round and target_stock = '$stock_codes[$i]'";
    echo $update_sql . "\n";

    mysql_query($update_sql);
  }  

  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
