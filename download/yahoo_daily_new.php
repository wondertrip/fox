<?php
include './download_utils.php';

ini_set("max_execution_time",0); 
set_time_limit(0);

//ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; 4399Box.560; .NET4.0C; .NET4.0E)');
ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;http://www.yahoo.com)');

echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";

$conn = mysqli_connect ( "localhost", "root", "Wind+3569" );
mysqli_select_db ( $conn, "stock" );

$query_code = mysqli_query($conn, "select code,name from stockcode2name where code > 603169");

while ($row = mysqli_fetch_array($query_code)) {
	$code = $row['code'];
	$name = $row['name'];
	
	if (strpos($code, "6") !== 0 && strpos($code, "0") !== 0 && strpos($code, "3") !== 0) {
		continue;
	}
	
	$exchange = "sz";
	if (strpos ( $code, "6" ) === 0) {
		$exchange = "ss";
	}
	
	$yahoo_url = "http://table.finance.yahoo.com/table.csv?s=StockCode.Exchange";
	$dailyinfo_content = file_get_contents ( str_replace ( array (
			"StockCode",
			"Exchange"
	), array (
			$code,
			$exchange
	), $yahoo_url ) );
	$dailyinfo = iconv ( "gb2312", "utf-8", $dailyinfo_content );
	$trade_date = "";
	$open_price = "";
	$highest_price = "";
	$lowest_price = "";
	$close_price = "";
	$trade_amount = "";
	$adj_open_price = "";
	$adj_highest_price = "";
	$adj_lowest_price = "";
	$adj_close_price = "";
	//Date,Open,High,Low,Close,Volume,Adj Close
	//echo "Date\tOpen\tHigh\tLow\tClose\n";
	foreach ( explode ( "\n", $dailyinfo ) as $line ) {
		//echo $line . "\n";
		if (strpos($line, "Date") !== false)
			continue;
		$content_array = explode ( ",", $line );
		if (count($content_array) === 7) {
			foreach ( $content_array as $field ) {
				$trade_date = str_replace ( "-", "", $content_array [0] );
				$open_price = number_format ( $content_array [1], 2 );
				$highest_price = number_format ( $content_array [2], 2 );
				$lowest_price = number_format ( $content_array [3], 2 );
				$close_price = number_format ( $content_array [4], 2 );
				$trade_amount = number_format ( $content_array [5], 2, ".", "");
				$adj_close_price = number_format ( $content_array [6], 2 );
					
				$dicount_factor = $adj_close_price / $close_price;
					
				$adj_open_price = number_format($open_price * $dicount_factor, 2);
				$adj_highest_price = number_format($highest_price * $dicount_factor, 2);
				$adj_lowest_price = number_format($lowest_price * $dicount_factor, 2);
			}
			
			if ($open_price === $close_price && $highest_price === $lowest_price && $trade_amount === "0.00") {
				//no data
			} else {
				//echo $trade_date . "\t" . $adj_open_price . "\t" . $adj_highest_price . "\t" . $adj_lowest_price . "\t" . $adj_close_price . "\n";
				$insert_sql = "insert into stockdailyinfo_yh(stockcode, stockname, tradedate, openPrice, closePrice, highestPrice, lowestPrice, tradeAmount) " . "values ('$code', '$name', $trade_date, $adj_open_price, $adj_close_price, $adj_highest_price, $adj_lowest_price, $trade_amount)";
				echo $insert_sql . "\n";
				mysqli_query ( $conn, $insert_sql );
			}
		}
	}
}

mysqli_close ( $conn );
echo "--------------------------END-----------------------------\n";
echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
?>