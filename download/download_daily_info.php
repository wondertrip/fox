<?php
include './download_utils.php';

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

$start_date = 20140101;
$end_date = 20150316;

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
			$exchange = "sh";
		}
		
		$sina_url = "http://biz.finance.sina.com.cn/stock/flash_hq/kline_data.php?symbol=EXCHANGESTOCKCODE&end_date=ENDDATE&begin_date=STARTDATE";
		$dailyinfo_content = file_get_contents ( str_replace ( array (
				"EXCHANGE",
				"STOCKCODE",
				"ENDDATE",
				"STARTDATE" 
		), array (
				$exchange,
				$code,
				$end_date,
				$start_date 
		), $sina_url ) );
		$dailyinfo = iconv ( "gb2312", "utf-8", $dailyinfo_content );
		$trade_date = "";
		$open_price = "";
		$highest_price = "";
		$lowest_price = "";
		$close_price = "";
		$trade_amount = "";
		foreach ( explode ( "\n", $dailyinfo ) as $line ) {
			if (strpos ( $line, "content" ) !== false) {
				echo $line . "\n";
				$content_array = explode ( " ", $line );
				foreach ( $content_array as $field ) {
					if (strpos ( $field, "=" ) !== false) {
						$name_value = explode ( "=", $field );
						if ($name_value [0] === "d") {
							$trade_date = str_replace ( array("\"", "-"), array("", ""), $name_value [1] );
						}
						if ($name_value [0] === "o") {
							$open_price = str_replace ( "\"", "", $name_value [1] );
						}
						if ($name_value [0] === "h") {
							$highest_price = str_replace ( "\"", "", $name_value [1] );
						}
						if ($name_value [0] === "l") {
							$lowest_price = str_replace ( "\"", "", $name_value [1] );
						}
						if ($name_value [0] === "c") {
							$close_price = str_replace ( "\"", "", $name_value [1] );
						}
						if ($name_value [0] === "v") {
							$trade_amount = str_replace ( "\"", "", $name_value [1] );
						}
					}
				}
				$insert_sql = "insert into stockdailyinfo(stockcode, stockname, tradedate, openPrice, closePrice, highestPrice, lowestPrice, tradeAmount) " .
				              "values ('$code', '$name', $trade_date, $open_price, $close_price, $highest_price, $lowest_price, $trade_amount)";
				echo $insert_sql . "\n";
				mysqli_query($conn, $insert_sql);
			}
		}
	}
}

mysqli_close ( $conn );
echo "--------------------------END-----------------------------\n";
echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
?>