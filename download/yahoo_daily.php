<?php
include './download_utils.php';

ini_set("max_execution_time",0); 
set_time_limit(0);

echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";

$url = "http://quote.eastmoney.com/stocklist.html";
$raw_content = file_get_contents ( $url );

$content = mb_convert_encoding ( $raw_content, "utf8", "gb2312" );
// echo $content;

$content_array = explode ( "\n", $content );

$prefix = "<li><a target=\"_blank\" href=\"http://quote.eastmoney.com/";

$conn = mysqli_connect ( "localhost", "root", "Wind+3569" );
mysqli_select_db ( $conn, "stock" );

//$content_array = array("<li><a target=\"_blank\" href=\"http://quote.eastmoney.com/sh600960.html\">渤海活塞(002697)</a></li>");

foreach ( $content_array as $record ) {
	if (strpos ( ltrim ( $record ), $prefix ) === 0) {
		$codeAndName = code2Name ( $record );
		$codenameArray = explode ( "(", $codeAndName );

		$code = explode ( ")", $codenameArray [1] )[0];
		$name = $codenameArray [0];

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
		echo "Date\tOpen\tHigh\tLow\tClose\n";
		foreach ( explode ( "\n", $dailyinfo ) as $line ) {
			echo $line . "\n";
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
					$trade_amount = number_format ( $content_array [5], 2 );
					$adj_close_price = number_format ( $content_array [6], 2 );
					
					$dicount_factor = $adj_close_price / $close_price;
					
					$adj_open_price = number_format($open_price * $dicount_factor, 2);
					$adj_highest_price = number_format($highest_price * $dicount_factor, 2);
					$adj_lowest_price = number_format($lowest_price * $dicount_factor, 2);
				}
				
				echo $trade_date . "\t" . $adj_open_price . "\t" . $adj_highest_price . "\t" . $adj_lowest_price . "\t" . $adj_close_price . "\n";
				$insert_sql = "insert into stockdailyinfo_yh(stockcode, stockname, tradedate, openPrice, closePrice, highestPrice, lowestPrice, tradeAmount, adjClosePrice) " . "values ('$code', '$name', $trade_date, $open_price, $close_price, $highest_price, $lowest_price, $trade_amount, $adj_close_price)";
				echo $insert_sql . "\n";
				//mysqli_query ( $conn, $insert_sql );
			}
		}
	}
}

mysqli_close ( $conn );
echo "--------------------------END-----------------------------\n";
echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
?>