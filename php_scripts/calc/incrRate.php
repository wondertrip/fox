<?php 
include "./StockDailyInfo.php";

function fanji($stock_infos, $index) {
	return $stock_infos[$index]->incrRate >= abs($stock_infos[$index-1]->incrRate) &&
               $stock_infos[$index]->tradeAmount > $stock_infos[$index-1]->tradeAmount &&
	       $stock_infos[$index-1]->incrRate <= -5.0;
}

$conn = mysqli_connect ( "localhost", "root", "Wind+3569", "stock");

$all_stock_codes = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");

$target_stocks = array();

$count_back = 1;

while ($row = mysqli_fetch_array($all_stock_codes)) {
	$stockcode = $row['stockcode'];
	$stock_infos = mysqli_query($conn, "select * from stockdailyinfo where stockcode = '$stockcode' order by tradedate asc");
	$stock_info_array = array();
	while ($r = mysqli_fetch_array($stock_infos)) {
		$info = new StockDailyInfo();
		$info->stockCode = $r['stockcode'];
		$info->tradeDate = $r['tradedate'];
                $info->tradeAmount = $r['tradeAmount'];
		$info->diff = $r['diff'];
		$info->dea = $r['dea'];
		$info->macd = $r['macd'];
		$info->amtAvg5 = $r['amtAvg5'];
		$info->amtAvg10 = $r['amtAvg10'];
                $info->incrRate = $r['incrRate'];
		array_push($stock_info_array, $info);
	}
	
	$length = count($stock_info_array);
	for ($start = $length - 1; $start > 0 && $start >= $length - $count_back; $start --) {
		if (fanji($stock_info_array, $start)) {
                  $info = $stock_info_array[$start]->stockCode . "\t" . $stock_info_array[$start]->tradeDate . "\t" . $stock_info_array[$start - 1]->incrRate . "\t" . $stock_info_array[$start]->incrRate;
                  if ($start < $length - 1) {
                    $info = $info . "\t" . $stock_info_array[$start+1]->tradeDate . "\t" . $stock_info_array[$start+1]->incrRate;
                  }
		  array_push($target_stocks, $info);
		}
	}
}

foreach ($target_stocks as $target) {
	//echo $target->stockCode . "\t" . $target->tradeDate . "\t" . $target->incrRate . "\n";
        echo $target . "\n";
}

mysqli_close($conn);
?>
