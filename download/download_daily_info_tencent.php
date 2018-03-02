<?php
include './download_utils.php';

echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";


$conn = mysqli_connect ( "localhost", "root", "Wind+3569" );
mysqli_select_db ( $conn, "stock" );

$all_stocks = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");

while ($row = mysqli_fetch_array($all_stocks)) {
	
	$code = $row['stockcode'];
	$name = "";
	
	if (strpos($code, "6") !== 0 && strpos($code, "0") !== 0 && strpos($code, "3") !== 0) {
		continue;
	}
	
	$exchange = "sz";
	if (strpos ( $code, "6" ) === 0) {
		$exchange = "sh";
	}
	
	$qq_url = "http://data.gtimg.cn/flashdata/hushen/daily/16/EXStockCode.js";
	$dailyinfo_content = file_get_contents ( str_replace ( array (
			"EX",
			"StockCode"
	), array (
			$exchange,
			$code
	), $qq_url ) );
	//$dailyinfo = iconv ( "gb2312", "utf-8", $dailyinfo_content );
	$dailyinfo = $dailyinfo_content;
	$trade_date = "";
	$open_price = "";
	$highest_price = "";
	$lowest_price = "";
	$close_price = "";
	$trade_amount = "";
	foreach ( explode ( "\\n\\\n", $dailyinfo ) as $line ) {
		
		if (strpos($line, "16") === false || strpos($line, "daily") !== false) {
			continue;
		}
		$fields = explode(" ", $line);
		
		//fields 0:date; 1:openPrice; 2:closePrice; 3:highestPrice; 4:lowestPrice; 5:tradeAmount
		$trade_date = "20" . $fields[0];
		$open_price = $fields[1];
		$close_price = $fields[2];
		$highest_price = $fields[3];
		$lowest_price = $fields[4];
		$trade_amount = $fields[5];
		
		$insert_sql = "insert into stockdailyinfo(stockcode, stockname, tradedate, openPrice, closePrice, highestPrice, lowestPrice, tradeAmount) " .
			              "values ('$code', '$name', $trade_date, $open_price, $close_price, $highest_price, $lowest_price, $trade_amount)";
		echo $insert_sql . "\n";
		mysqli_query($conn, $insert_sql);
	}
}

mysqli_close ( $conn );
echo "--------------------------END-----------------------------\n";
echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
?>