<?php
include "./StockDailyInfo.php";

function updateIncrRate($conn, $info_array, $count_back) {
        $length = count($info_array);
        for ($i = $length-1; $i >= 0 && $i >= $length-$count_back; $i--) {
                $info = $info_array[$i];
                $incrRate = "";
                if ($i >= 1) {
                  $prevClosePrice = $info_array[$i - 1]->closePrice;
                  $incrRate = number_format( ($info_array[$i]->closePrice - $prevClosePrice) / $prevClosePrice * 100, 2); 
                }

                $update_sql = "update stockdailyinfo set incrRate = $incrRate ".
                                "where stockcode = '$info->stockCode' and tradedate = $info->tradeDate";
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
	
	updateIncrRate($conn, $stock_daily_info_array, $count_back);
}



mysqli_close($conn);
?>
