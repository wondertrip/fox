<?php
include "./StockDailyInfo.php";
function macd_jincha($stock_infos, $index) {
	if (count ( $stock_infos ) < 1 || $index < 1) {
		return false;
	} else {
		return $stock_infos [$index]->diff >= $stock_infos [$index]->dea && $stock_infos [$index - 1]->diff <= $stock_infos [$index - 1]->dea && $stock_infos [$index]->macd > 0 && $stock_infos [$index - 1]->macd <= 0 && $stock_infos [$index - 1]->diff < 0.2;
	}
}

function calcSellInfo($stock_infos, $index) {
	$sellInfo = $stock_infos[count($stock_infos) - 1];
	for ($start = $index; $start < count ($stock_infos); $start ++) {
		if ($stock_infos[$start]->macd < $stock_infos[$start - 1]->macd) {
			$sellInfo = $stock_infos[$start];
			break;
		}
	}
	return $sellInfo;
}

$conn = mysqli_connect ( "localhost", "root", "Wind+3569", "stock" );

$all_stock_codes = mysqli_query ( $conn, "select distinct stockcode from stockdailyinfo" );

$target_stocks = array ();

$count_back = 200;

while ( $row = mysqli_fetch_array ( $all_stock_codes ) ) {
	$stockcode = $row ['stockcode'];
	$stock_infos = mysqli_query ( $conn, "select * from stockdailyinfo where stockcode = '$stockcode' order by tradedate asc" );
	$stock_info_array = array ();
	while ( $r = mysqli_fetch_array ( $stock_infos ) ) {
		$info = new StockDailyInfo ();
		$info->stockCode = $r ['stockcode'];
		$info->tradeDate = $r ['tradedate'];
		$info->diff = $r ['diff'];
		$info->dea = $r ['dea'];
		$info->macd = $r ['macd'];
		$info->openPrice = $r ['openPrice'];
		$info->closePrice = $r ['closePrice'];
		array_push ( $stock_info_array, $info );
	}
	
	$length = count ( $stock_info_array );
	for($start = $length - 1; $start >= 0 && $start >= $length - $count_back; $start --) {
		if (macd_jincha ( $stock_info_array, $start )) {
			$target = $stock_info_array[$start];
			$target_stock = array();
			$target_stock['stockcode'] = $target->stockCode;
			$target_stock['buyDate'] = $target->tradeDate;
			$target_stock['buyPrice'] = $target->closePrice;
			$sellInfo = calcSellInfo($stock_info_array, $start);
			$target_stock['sellDate'] = $sellInfo->tradeDate;
			$target_stock['sellPrice'] = $sellInfo->closePrice;
			$target_stock['incrRate'] = number_format(($target_stock['sellPrice'] - $target_stock['buyPrice'])/$target_stock['buyPrice'] * 100, 2);
			array_push ( $target_stocks, $target_stock);
		}
	}
}

foreach ( $target_stocks as $target ) {
	echo $target['stockcode'] . "\t" . $target['buyDate'] . "\t" . $target['buyPrice'] . $target['sellDate'] . "\t" . $target['sellPrice'] . "\t" . $target['incrRate'] . "\n";
}

mysqli_close ( $conn );
?>
