#!/alidata/server/php/bin/php
<?php
  function isSuspended($prices){
    //response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
    return (number_format($prices[1],2) === "0.00" || $prices[1] === "0") && (number_format($prices[3],2) === "0.00" || $prices[3] === "0") && (number_format($prices[4],2) === "0.00" || $prices[4] === "0") && (number_format($prices[5],2) === "0.00" || $prices[5] === "0");
  }
  echo date('Y-m-d H:i:s',time()) . "\n";
  echo "--------------------------START-----------------------------\n";
  $con = mysql_connect("localhost","root","Wind+3569");
  mysql_select_db("stock", $con);

  $all_stocks = mysql_query("select * from stock_dz_pre_track");

  while($row = mysql_fetch_array($all_stocks)) {
    $stockcode = $row['stockcode'];
	$dz_price = $row['dz_price'];
	$dz_date = $row['dz_firm_approved_date'];
	
	$sina_code = "";
	if (strpos ( $stockcode, "6" ) === 0) {
		$sina_code = "sh" . $stockcode;
	} else {
		$sina_code = "sz" . $stockcode;
	}
	
	$url = "http://hq.sinajs.cn/list=" . $sina_code;
	// echo $url . "\n";
	$content = mb_convert_encoding ( file_get_contents ( $url ), "utf8", "gb2312" );
	// echo $content;
	
	// response from sina -> 0:name; 1:openP; 2:prevCloseP; 3:currentP; 4:highestP; 5:lowestP;
	$data = explode ( "=", $content );
	$prices = explode ( ",", $data [1] );
	$current_price = $prices [3];
	$prev_close_price = $prices [2];
	echo $stockcode . " --- " . "CurrentPrice:" . $prices [3] . " PrevClosePrice:" . $prices [2] . "\n";
	
	$latest_incr_rate = number_format ( ($current_price - $prev_close_price) / $prev_close_price * 100, 2);
	$incr_rate = number_format( ($current_price - $dz_price) / $dz_price * 100, 2);
	
	$update_sql = "update stock_dz_pre_track set current_price=$current_price, latest_incr_rate=$latest_incr_rate, incr_rate=$incr_rate where stockcode='$stockcode' and dz_firm_approved_date=$dz_date";
	echo $update_sql . "\n";
	
	mysql_query ( $update_sql );
  }
  echo "--------------------------END-----------------------------\n";
  echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
  mysql_close($con);
?>
