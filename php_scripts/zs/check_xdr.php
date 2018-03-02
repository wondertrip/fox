<?php
echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";

function recalc_fuquan_prices($conn, $stockcode) {
	$df = mysqli_query($conn, "select discountFactor from stockdailyinfo_fq where stockcode = '$stockcode' order by tradedate desc limit 0,1");
	$latestDF = number_format(mysqli_fetch_array($df)[0], 3, ".", "");
	echo $latestDF . "\n";
		
	$results = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$stockcode'");
	while ($record = mysqli_fetch_array($results)) {
		$fqOpenPrice = number_format($record['fqOpenPrice'], 3, ".", "");
		$fqClosePrice = number_format($record['fqClosePrice'], 3, ".", "");
		$fqHighestPrice = number_format($record['fqHighestPrice'], 3, ".", "");
		$fqLowestPrice = number_format($record['fqLowestPrice'], 3, ".", "");
		$tradeDate = $record['tradedate'];
			
		$openPrice = number_format(($fqOpenPrice/$latestDF), 2, ".", "");
		$closePrice = number_format(($fqClosePrice/$latestDF), 2, ".", "");
		$highestPrice = number_format(($fqHighestPrice/$latestDF), 2, ".", "");
		$lowestPrice = number_format(($fqLowestPrice/$latestDF), 2, ".", "");
			
		$update_sql = "update stockdailyinfo_fq set openPrice='$openPrice', closePrice='$closePrice', highestPrice='$highestPrice', lowestPrice='$lowestPrice' where stockcode='$stockcode' and tradeDate='$tradeDate'";
		echo $update_sql . "\n";
			
		mysqli_query($conn, $update_sql);
	}
}

$conn = mysqli_connect ( "123.56.103.240", "root", "Wind+3569" );
mysqli_select_db ( $conn, "stock" );

$date = date('Ymd', time());

$max_round_result = mysqli_query($conn, "select max(round) from zs_game");

$max_round = 0;
while($row = mysqli_fetch_array($max_round_result)) {
	$max_round = $row['max(round)'];
}
//echo "Max Round : " . $max_round;

$all_stocks = mysqli_query($conn, "select * from zs_game where round = $max_round");

//stock codes are in format: 中粮生化:000930;森源电气:002358
$stock_codes = array();

$start_date = "";
while($row = mysqli_fetch_array($all_stocks)) {
	$start_date = $row['start_date'];
	$stock_codes = $row['target_stock'];
	
	$tmp_code_arr = explode(";", $stock_codes);
	$code1 = explode(":", $tmp_code_arr[0]);
	$code2 = explode(":", $tmp_code_arr[1]);
	
	$stockcode1 = $code1[1];
	$stockcode2 = $code2[1];
	
	$prev_date1 = $start_date;
	$prev_tradedate = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$stockcode1' and tradedate < $start_date order by tradedate desc limit 0,1");
	while ($prev_record = mysqli_fetch_array($prev_tradedate)) {
		$prev_date1 = $prev_record['tradedate'];
	}
	
	$df = 0;
	$df_records = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$stockcode1' and tradedate >= $prev_date1");
	while ($df_record = mysqli_fetch_array($df_records)) {
		if ($df === 0) {
			$df = $df_record['discountFactor'];
			continue;
		}
		if ($df != $df_record['discountFactor']) {
			$insert_sql = "insert into zs_game_stock_xdr (stockcode, round, flag1) values ('$stockcode1', '$max_round', '*')";
			echo $insert_sql . "\n";
			if (mysqli_query($conn, $insert_sql)) {
				recalc_fuquan_prices($conn, $stockcode1);
			}
		}
	}
	
	$prev_date2 = $start_date;
	$prev_tradedate = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$stockcode2' and tradedate < $start_date order by tradedate desc limit 0,1");
	while ($prev_record = mysqli_fetch_array($prev_tradedate)) {
		$prev_date2 = $prev_record['tradedate'];
	}
	
	$df = 0;
	$df_records = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$stockcode2' and tradedate >= $prev_date2");
	while ($df_record = mysqli_fetch_array($df_records)) {
		if ($df === 0) {
			$df = $df_record['discountFactor'];
			continue;
		}
		if ($df != $df_record['discountFactor']) {
			$insert_sql = "insert into zs_game_stock_xdr (stockcode, round, flag1) values ('$stockcode2', '$max_round', '*')";
			echo $insert_sql . "\n";
			if (mysqli_query($conn, $insert_sql)) {
				recalc_fuquan_prices($conn, $stockcode2);
			}
			
		}
	}
}

echo "--------------------------END-----------------------------\n";
echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
mysqli_close($conn);
?>