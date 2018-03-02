#!/alidata/server/php/bin/php
<?php
  function isSuspended($prices){
    //response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
    return ($prices[4] === "0.00" || $prices[4] === "0") && ($prices[5] === "0.00" || $prices[5] === "0");
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
    echo $content;

    //data -> 0:stock1; 1:stock2
    $data = explode("\n", $content);
    
    
    //response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
    $data1 = explode("=", $data[0]);
    $prices1 = explode(",", $data1[1]);
    echo $s_code1 . " --- " . "CurrentPrice:" . $prices1[3] . " HighestPrice:" . $prices1[4] . "\n";   

    $data2 = explode("=", $data[1]);
    $prices2 = explode(",", $data2[1]);
    echo $s_code2 . " --- " . "CurrentPrice:" . $prices2[3] . " HighestPrice:" . $prices2[4] . "\n";

    $start_price1 = number_format($prices1[1], 2);
    $start_price2 = number_format($prices2[1], 2);
   
    $update_sql = "update zs_game set start_price=\"$start_price1;$start_price2\" where round = $max_round and target_stock = \"$stock_codes[$i]\"";
    echo $update_sql . "\n";

    //mysql_query($update_sql);
  }  

  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
