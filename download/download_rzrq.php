<?php
include './download_utils.php';
$url_template = "http://vip.stock.finance.sina.com.cn/q/go.php/vInvestConsult/kind/rzrq/index.phtml?symbol=StockCode&bdate=StartDate&edate=EndDate";

$start_date = "2015-01-05";
$end_date = "2016-05-04";

$conn = mysqli_connect("localhost", "root", "Wind+3569");
mysqli_select_db($conn, "stock");

$all_stock_codes = mysqli_query($conn, "select stockcode from stock_rzrq_targets");

// echo "stock_code" . "\t" . "tradeDate" . "\t" . "cashTotal"  . "\t" .
// 		"cashBuy" . "\t" . "cashReturn" . "\t" . /* "stockTotalInCash" . "\t" . */
// 		"stockTotal" . "\t" . "stockSell" . "\t" .
// 		"stockReturn" . /* "\t". "stockLeft" . */ "\n";
while($row = mysqli_fetch_array($all_stock_codes)) {
	$stock_code = $row['stockcode'];
	$url = str_replace(array("StockCode", "StartDate", "EndDate"), array($stock_code, $start_date, $end_date), $url_template);
	
	//echo $url . "\n";
	
	$raw_content = file_get_contents($url);
	$content = iconv("gb2312", "utf-8", $raw_content);
	$content_array = explode("\n", $content);
	
	$start_flag = false;
	$stockname;
	$line_counter = 1;
	$tradeDate = 0;
	$cashTotal = 0;
	$cashBuy = 0;
	$cashReturn = 0;
	$stockTotalInCash = 0;
	$stockTotal = 0;
	$stockSell = 0;
	$stockReturn = 0;
	$stockLeft = 0;
	foreach ($content_array as $record) {
		if (strpos($record, "融资融券交易明细") !== false) {
			$stockname = str_replace(array(" ", "\n", "融资融券交易明细"), array("", "", ""), $record);
		}
		if (strpos($record, "融券余额") !== false) {
			$start_flag = true;
			continue;
		}
		if ($start_flag) {
			if (strpos ( $record, "</td>" ) !== false) {
				// line 1 序号 
				if ($line_counter == 1) {
				}
				
				// line 2日期
				if ($line_counter == 2) {
					$tradeDate = getContent ( $record );
				}
				
				// line 3融资余额
				if ($line_counter == 3) {
					$cashTotal = getContentNum ( $record );
				}
				
				// line 4融资买入额
				if ($line_counter == 4) {
					$cashBuy = getContentNum ( $record );
				}
				
				// line 5融资偿还额
				if ($line_counter == 5) {
					$cashReturn = getContentNum ( $record );
				}
				
				// line 6融券余量金额
				if ($line_counter == 6) {
					$stockTotalInCash = getContentNum ( $record );
				}
				
				// line 7融券余量
				if ($line_counter == 7) {
					$stockTotal = getContentNum ( $record );
				}
				
				// line 8融券卖出量
				if ($line_counter == 8) {
					$stockSell = getContentNum ( $record );
				}
				
				// line 9融券偿还量
				if ($line_counter == 9) {
					$stockReturn = getContentNum ( $record );
				}
				
				// line 10融券余额
				if ($line_counter == 10) {
					$stockLeft = getContentNum ( $record );
					$line_counter = 0;
					//echo $stock_code . "\t" . $tradeDate . "\t" . $cashTotal  . "\t" . $cashBuy . "\t" . $cashReturn . "\t" . /* $stockTotalInCash . "\t" . */ $stockTotal . "\t" . $stockSell . "\t" .	$stockReturn ./*  "\t". $stockLeft . */ "\n";
					$insert_sql = "insert into stock_rzrq values('$stock_code', '$stockname', $tradeDate, $cashTotal, $cashBuy, $cashReturn, $stockTotal, $stockSell, $stockReturn)";
					echo $insert_sql . "\n";
					mysqli_query($conn, $insert_sql);
					$line_counter = 0;
				}
				$line_counter ++ ;
			}
		}
		if (stripos($record, "</table>") !== false) {
			$contentStart = false;
		}
	}
}
mysqli_close($conn);
?>