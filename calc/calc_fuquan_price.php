<?php

$conn = mysqli_connect ( "localhost", "root", "Wind+3569" );
mysqli_select_db ( $conn, "stock" );

$query_code = mysqli_query($conn, "select code,name from stockcode2name");

while ($row = mysqli_fetch_array($query_code)) {
	$code = $row['code'];
	
	$df = mysqli_query($conn, "select discountFactor from stockdailyinfo_fq where stockcode = '$code' order by tradedate desc limit 0,1");
	$latestDF = number_format(mysqli_fetch_array($df)[0], 3, ".", "");
	echo $latestDF;
	
	$results = mysqli_query($conn, "select * from stockdailyinfo_fq where stockcode = '$code'");
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
		
		$update_sql = "update stockdailyinfo_fq set openPrice='$openPrice', closePrice='$closePrice', highestPrice='$highestPrice', lowestPrice='$lowestPrice' where stockcode='$code' and tradeDate='$tradeDate'";
		echo $update_sql . "\n";
		
		mysqli_query($conn, $update_sql);
	}
}

mysqli_close($conn);

?>