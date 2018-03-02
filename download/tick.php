<?php
echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";

//$conn = mysqli_connect("123.56.103.240", "root", "Wind+3569", "stock");
//$all_stockcodes = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");
$url = "http://market.finance.sina.com.cn/downxls.php?date=TargetDate&symbol=ExchangeStockCode";
$count = 0;
//while ($row = mysqli_fetch_array($all_stockcodes)) {
	//$stockcode = $row['stockcode'];
	$stockcode = "600125";
	if (strpos($stockcode, "6") === 0) {
		$url = str_replace(array("TargetDate", "Exchange", "StockCode"), array("2015-04-27", "sh", $stockcode), $url);
	} else {
		$url = str_replace(array("TargetDate", "Exchange", "StockCode"), array("2015-04-27", "sz", $stockcode), $url);
	}
	
	echo $url . "\n";
		
	$raw_content = file_get_contents ( $url );
		
	$content = mb_convert_encoding ( $raw_content, "utf8", "gb2312" );
		
	echo $content . "\n";
	
	$lines = explode("\n", $content);
	$counter = 0;
	
	
	$cp4 = 0;
	$op4 = 0;
	$amt4 = 0;
	$cp3 = 0;
	$op3 = 0;
	$amt3 = 0;
	$cp2 = 0;
	$op2 = 0;
	$amt2 = 0;
	$cp1 = 0;
	$op1 = 0;
	$amt1 = 0;
	$seperator = true;
	$forthEnd = true;
	$thirdEnd = true;
	$secondEnd = true;
	foreach ($lines as $line) {
		if ($counter === 0) {
			$counter ++;
			continue;
		}
		//0:time;1:price;2:price change;3:trade amount;4:trade money;5:trade type
		$columns = explode("\t", $line);
		
		if (count($columns) === 6) {
			$time = str_replace(":", "", $columns[0]);
			$price = $columns[1];
			$deal_amount = $columns[3];
			
			if ($time > 140000) {
				if ($seperator === true) {
				  echo "cp4 = " . $price . "\n";
				  $cp4 = $price;
				}
				$seperator = false;
				$op4 = $price;
				$amt4 += $deal_amount;
				continue;
			} elseif($forthEnd) {
				$seperator = true;
				$forthEnd = false;
			}
			
			if ($time > 130000) {
				if ($seperator === true) {
					echo "cp3 = " . $price . "\n";
					$cp3 = $price;
				}
				$seperator = false;
				$op3 = $price;
				$amt3 += $deal_amount;
				continue;
			} elseif($thirdEnd) {
				$seperator = true;
				$thirdEnd = false;
			}
			
			if ($time > 103000) {
				if ($seperator === true) {
					echo "cp2 = " . $price . "\n";
					$cp2 = $price;
				}
				$seperator = false;
				$op2 = $price;
				$amt2 += $deal_amount;
				continue;
			} elseif($secondEnd) {
				$seperator = true;
				$secondEnd = false;
			}
			
			if ($time > 92000) {
				if ($seperator === true) {
					echo "cp1 = " . $price . "\n";
					$cp1 = $price;
				}
				$seperator = false;
				$op1 = $price;
				$amt1 += $deal_amount;
			} else {
				$seperator = true;
			}
		}
		
	}
	
	echo "1 : \t" . $op1 . "\t" . $cp1 . "\t" . $amt1 . "\n";
	echo "2 : \t" . $op2 . "\t" . $cp2 . "\t" . $amt2 . "\n";
	echo "3 : \t" . $op3 . "\t" . $cp3 . "\t" . $amt3 . "\n";
	echo "4 : \t" . $op4 . "\t" . $cp4 . "\t" . $amt4 . "\n";
	
		
	$url="http://market.finance.sina.com.cn/downxls.php?date=TargetDate&symbol=ExchangeStockCode";
//}

//mysqli_close ( $conn );
echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------END-----------------------------\n";
?>