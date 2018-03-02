<?php
include "./StockDailyInfo.php";

function amt_avg($info_array, $days, $index) {
	$amt_tmp = 0;
	for ($j = 0; $j < $days; $j++) {
		$amt_tmp += $info_array[$index-$j]->tradeAmount;
	}
	$amtAvg = number_format($amt_tmp / $days, 0, ".", "");
	return $amtAvg;
}

function price_avg($info_array, $days, $index) {
	$price_tmp = 0;
	for ($j = 0; $j < $days; $j++) {
		$price_tmp += $info_array[$index-$j]->closePrice;
	}
	$priceAvg = number_format($price_tmp / $days, 2, ".", "");
	return $priceAvg;
}

function updateAvg($conn, $info_array, $count_back) {
	$length = count($info_array);
	for ($i = $length-1; $i >= 0 && $i >= $length-$count_back; $i--) {
		$info = $info_array[$i];
		$amtAvg5 = 0;
		$amtAvg10 = 0;
		$priceAvg5 = 0;
		$priceAvg10 = 0;
		$priceAvg20 = 0;
		$priceAvg30 = 0;
		$priceAvg60 = 0;
		if ($i >= 4) {
			$amtAvg5 = amt_avg($info_array, 5, $i);
			$priceAvg5 = price_avg($info_array, 5, $i);
		}
		if ($i >= 9) {
			$amtAvg10 = amt_avg($info_array, 10, $i);
			$priceAvg10 = price_avg($info_array, 10, $i);
		}
		if ($i >= 19) {
			$priceAvg20 = price_avg($info_array, 20, $i);
		}
		if ($i >= 29) {
			$priceAvg30 = price_avg($info_array, 30, $i);
		}
		if ($i >= 59) {
			$priceAvg60 = price_avg($info_array, 60, $i);
		}
	
		$update_sql = "update stockdailyinfo set amtAvg5 = $amtAvg5, amtAvg10 = $amtAvg10, ".
				"priceAvg5 = $priceAvg5, priceAvg10 = $priceAvg10, priceAvg20 = $priceAvg20, priceAvg30 = $priceAvg30, priceAvg60 = $priceAvg60 " .
				"where stockcode = '$info->stockCode' and tradedate = $info->tradeDate";
		echo $update_sql . "\n";
		mysqli_query($conn, $update_sql);
	}
}

function price_macd($conn, $info_array, $count_back) {
	$length = count($info_array);
	$a9 = 2.0/10.0;
	$a12 = 2.0/13.0;
	$a26 = 2.0/27.0;
	for ($i = $length-$count_back; $i >= 0 && $i < $length; $i++) {
		$ema12 = 0; $ema26 = 0; $diff = 0; $dea = 0; $macd = 0;
		$info = $info_array[$i];
		if ($i === 0) {
			$ema12 = $info->closePrice;
			$ema26 = $info->closePrice;
		} else {
			$ema12 = number_format((1 - $a12) * $info_array[$i-1]->ema12 + $a12 * $info->closePrice, 5, ".", "");
			$ema26 = number_format((1 - $a26) * $info_array[$i-1]->ema26 + $a26 * $info->closePrice, 5, ".", "");
		}
		
		$diff = $ema12 - $ema26;
		if ($i == 0) {
			$dea = $diff;
		} else {
			$dea = number_format((1 - $a9) * $info_array[$i-1]->dea + $a9 * $diff, 5, ".", "");
		}
		
		$macd = number_format(2 * ($diff - $dea), 5, ".", "");
		
		$info->ema12 = $ema12;
		$info->ema26 = $ema26;
		$info->diff = $diff;
		$info->dea = $dea;
		$info->macd = $macd;
		
		$update_sql = "update stockdailyinfo set ema12 = $ema12, ema26 = $ema26, diff = $diff, dea = $dea, macd = $macd where stockcode = '$info->stockCode' and tradedate = $info->tradeDate";
		echo $update_sql . "\n";
		mysqli_query($conn, $update_sql);
	}
}

function highestPriceInNDays($index, $days, $info_array) {
	$highestPrice = 0;
	$j = $index - $days + 1;
	if ($j < 0) {
		$j = 0;
	}
	for (; $j <= $index; $j++) {
		if ($info_array[$j]->highestPrice > $highestPrice) {
			$highestPrice = $info_array[$j]->highestPrice;
		}
	}
	return $highestPrice;
}

function lowestPriceInNDays($index, $days, $info_array) {
	$lowestPrice = 10000;
	$j = $index - $days + 1;
	if ($j < 0) {
		$j = 0;
	}
	for (; $j <= $index; $j++) {
		if ($info_array[$j]->lowestPrice < $lowestPrice) {
			$lowestPrice = $info_array[$j]->lowestPrice;
		}
	}
	return $lowestPrice;
}

function price_kdj($conn, $info_array, $count_back) {
	$length = count($info_array);
	
	for ($i = $length-$count_back; $i >= 0 && $i < $length; $i++) {
		$k = 0; $d = 0; $j = 0; $rsv = 0;
		$info = $info_array[$i];
		
		if ($i < 9) {
			$k = 50.0;
			$d = 50.0;
			$j = 50.0;
			$rsv = 50.0;
		} else {
			$highestPrice = highestPriceInNDays($i, 9, $info_array);
			$lowestPrice = lowestPriceInNDays($i, 9, $info_array);
			$rsv = ($info_array[$i]->closePrice - $lowestPrice) / ($highestPrice - $lowestPrice) * 100 ;
				
			$k = 2.0/3.0 * $info_array[$i-1]->k + 1.0/3.0 * $rsv;
			if ($k < 0) {
				$k = 0.0;
			} else if ($k > 100) {
				$k = 100.0;
			}
				
			$d = 2.0/3.0 * $info_array[$i-1]->d + 1.0/3.0 * $k;
			if ($d < 0) {
				$d = 0.0;
			} else if ($d > 100) {
				$d = 100.0;
			}
				
			$j = 3 * $k - 2 * $d;
			if ($j < 0) {
				$j = 0.0;
			} else if ($j > 100) {
				$j = 100.0;
			}
		}
		
		$k = number_format($k, 5, ".", "");
		$d = number_format($d, 5, ".", "");
		$j = number_format($j, 5, ".", "");
		$rsv = number_format($rsv, 5, ".", "");
		
		$info->k = $k;
		$info->d = $d;
		$info->j = $j;
		$info->rsv = $rsv;

		$update_sql = "update stockdailyinfo set k = $k, d = $d, j = $j, rsv = $rsv where stockcode = '$info->stockCode' and tradedate = $info->tradeDate";
		echo $update_sql . "\n";
		mysqli_query($conn, $update_sql);
	}
}

$conn = mysqli_connect("localhost", "root", "Wind+3569", "stock");

$all_stockcodes = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");

$count_back = 1;

while ($r = mysqli_fetch_array($all_stockcodes)) {
	$stockcode = $r['stockcode'];
	
	$stock_daily_info_array = array();
	$target_records = mysqli_query($conn, "select * from stockdailyinfo where stockcode = '$stockcode' order by tradedate asc");
	while ($row = mysqli_fetch_array($target_records)) {
		$dailyInfo = new StockDailyInfo();
		$dailyInfo->stockCode = $row['stockcode'];
		$dailyInfo->tradeDate = $row['tradedate'];
		$dailyInfo->openPrice = $row['openPrice'];
		$dailyInfo->closePrice = $row['closePrice'];
		$dailyInfo->highestPrice = $row['highestPrice'];
		$dailyInfo->lowestPrice = $row['lowestPrice'];
		$dailyInfo->tradeAmount = $row['tradeAmount'];
		
		$dailyInfo->ema12 = $row['ema12'];
		$dailyInfo->ema26 = $row['ema26'];
		$dailyInfo->diff = $row['diff'];
		$dailyInfo->dea = $row['dea'];
		$dailyInfo->macd = $row['macd'];
		
		$dailyInfo->k = $row['k'];
		$dailyInfo->d = $row['d'];
		$dailyInfo->j = $row['j'];
		$dailyInfo->rsv = $row['rsv'];
		
		array_push($stock_daily_info_array, $dailyInfo);
	}
	
	updateAvg($conn, $stock_daily_info_array, $count_back);
	price_macd($conn, $stock_daily_info_array, $count_back);
	price_kdj($conn, $stock_daily_info_array, $count_back);
}



mysqli_close($conn);
?>
