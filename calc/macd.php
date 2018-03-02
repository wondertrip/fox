<?php 
include "./StockDailyInfo.php";
function macd_jincha($stock_infos, $index) {
	return $stock_infos[$index]->diff >= $stock_infos[$index]->dea && 
	       $stock_infos[$index-1]->diff <= $stock_infos[$index-1]->dea &&
	       $stock_infos[$index]->macd > 0 &&
	       $stock_infos[$index-1]->macd <= 0 &&
	       $stock_infos[$index-1]->diff < 0;
}

function amt_avg_jincha($stock_infos, $index) {
	return $stock_infos[$index]->amtAvg5 >= $stock_infos[$index]->amtAvg10 &&
	       $stock_infos[$index-1]->amtAvg5 <= $stock_infos[$index-1]->amtAvg10;
}

$conn = mysqli_connect ( "123.56.103.240", "root", "Wind+3569", "stock");

$all_stock_codes = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");

$target_stocks = array();

$count_back = 5;

while ($row = mysqli_fetch_array($all_stock_codes)) {
	$stockcode = $row['stockcode'];
	$stock_infos = mysqli_query($conn, "select * from stockdailyinfo where stockcode = '$stockcode' order by tradedate asc");
	$stock_info_array = array();
	while ($r = mysqli_fetch_array($stock_infos)) {
		$info = new StockDailyInfo();
		$info->stockCode = $r['stockcode'];
		$info->tradeDate = $r['tradedate'];
		$info->diff = $r['diff'];
		$info->dea = $r['dea'];
		$info->macd = $r['macd'];
		$info->amtAvg5 = $r['amtAvg5'];
		$info->amtAvg10 = $r['amtAvg10'];
		array_push($stock_info_array, $info);
	}
	
	$length = count($stock_info_array);
	for ($start = $length - 1; $start >= 0 && $start >= $length - $count_back; $start --) {
		if ((macd_jincha($stock_info_array, $start) || macd_jincha($stock_info_array, $start-1) || macd_jincha($stock_info_array, $start-2)) &&
			 (amt_avg_jincha($stock_info_array, $start) || amt_avg_jincha($stock_info_array, $start-1) || amt_avg_jincha($stock_info_array, $start-2))) {
			 	array_push($target_stocks, $stock_info_array[$start]);
			 }
	}
}

foreach ($target_stocks as $target) {
	echo $target->stockCode . "\t" . $target->tradeDate . "\t" . $target->macd . "\n";
}

mysqli_close($conn);
?>