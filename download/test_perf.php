<?php

ini_set("max_execution_time",0);
set_time_limit(0);

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; 4399Box.560; .NET4.0C; .NET4.0E)');

//sina url for FuQuan Data
//example : http://vip.stock.finance.sina.com.cn/corp/go.php/vMS_FuQuanMarketHistory/stockid/sh600960.phtml?year=2015&jidu=3
//EXCHANGTE: sh | sz
//YYYY: 2015,2014 etc
//JIDU: 1,2,3,4
$sina_url = "http://vip.stock.finance.sina.com.cn/corp/go.php/vMS_FuQuanMarketHistory/stockid/Ex_Code_.phtml?year=Year_&jidu=Jidu_";

/*
 * Example, for the below content block, this function should return array(2015,2014,...2004)
 * 
<select name="year">
<option value="2015" selected>2015</option>
<option value="2014" >2014</option>
<option value="2013" >2013</option>
<option value="2012" >2012</option>
<option value="2011" >2011</option>
<option value="2010" >2010</option>
<option value="2009" >2009</option>
<option value="2008" >2008</option>
<option value="2007" >2007</option>
<option value="2006" >2006</option>
<option value="2005" >2005</option>
<option value="2004" >2004</option>
</select>
*/
function get_years($page_content) {
	$content_array = explode("\n", $page_content);
	$years = array();
	$index = 0;
	foreach ($content_array as $line) {
		$is_year_list = preg_match("/<option value=(.)*[0-9]{4}(.)*>([0-9]{4})<(.)*/", $line, $year);
		if ($is_year_list) {
			$years[$index++] = $year[3];
		}
	}
	return $years;
}

function init_downlowd($sina_url) {
	echo date('Y-m-d H:i:s',time()) . "\n";
	echo "--------------------------START-----------------------------\n";
	
	$conn = mysqli_connect ( "localhost", "root", "Wind+3569" );
	mysqli_select_db ( $conn, "stock" );
	
	$query_code = mysqli_query($conn, "select code,name from stockcode2name where code = '002560'");
	
	while ($row = mysqli_fetch_array($query_code)) {
		$code = $row['code'];
	
		if (strpos($code, "6") !== 0 && strpos($code, "0") !== 0 && strpos($code, "3") !== 0) {
			continue;
		}
	
		$exchange = "sz";
		if (strpos ( $code, "6" ) === 0) {
			$exchange = "sh";
		}
		
		$jidu_content = file_get_contents( str_replace ( array (
				"Ex_",
				"Code_",
				"Year_",
				"Jidu_" 
		), array (
				$exchange,
				$code,
				"2015",
				"3" 
		), $sina_url ) );
		$jiduinfo = iconv ( "gb2312", "utf-8", $jidu_content );
		$years = get_years($jiduinfo);
		$jidus = array(1,2,3,4);
		foreach ($years as $year) {
			if ($year < 2006) {
				continue;
			}
			foreach ($jidus as $jidu) {
				$target_url = str_replace ( array (
						"Ex_",
						"Code_",
						"Year_",
						"Jidu_" 
				), array (
						$exchange,
						$code,
						$year,
						$jidu 
				), $sina_url );
				echo $target_url . "\n";
				//echo "日期\t开盘价\t收盘价\t最高价\t最低价\t交易量（股）\t交易金额\t复权因子\n";
				$jidu_content = file_get_contents( $target_url );
				$jiduinfo = iconv ( "gb2312", "utf-8", $jidu_content );
				$lines = explode("\n", $jiduinfo);
				
				$record_start = false;
				$line_number = 1;
				//expect to get one trading day record like below:
				/*
				 * <tr >
						<td><div align="center">
						
					<a target='_blank' href='http://vip.stock.finance.sina.com.cn/quotes_service/view/vMS_tradehistory.php?symbol=sh600960&date=2015-06-30'>
								2015-06-30						</a>
									</div></td>
						<td><div align="center">55.050</div></td> 开盘价
						<td><div align="center">62.914</div></td> 最高价
						<td><div align="center">61.691</div></td> 收盘价
						<td class="tdr"><div align="center">51.467</div></td> 最低价
						<td class="tdr"><div align="center">41408352.000</div></td> 交易量(股)
						<td class="tdr"><div align="center">527292320.000</div></td> 交易金额(元)
						<td class="tdr"><div align="center">4.369</div></td> 复权因子
					  </tr>
				 */
				
				$trade_date = "";
				$open_price = "";
				$highest_price = "";
				$close_price = "";
				$lowest_price = "";
				$trade_shares = "";
				$trade_money = "";
				$discount_factor = "";
				foreach ($lines as $line) {
					
					if (!$record_start) {
						$start_matcher = strstr($line, "<a target='_blank' href=") && strstr($line, "$exchange$code");
						if ($start_matcher) {
							$record_start = true;
							continue;
						}
					}
					
					//parse recode lines
					if ($record_start) {
						if ($line_number === 1){
							$date = preg_match("/(.)*([0-9]{4}-[0-9]{2}-[0-9]{2})(.)*/", $line, $result);
							if ($date) {
								$trade_date = str_replace("-", "", $result[2]);
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 2){
							$op = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($op) {
								$open_price = $result[2];
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 3){
							$hp = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($hp) {
								$highest_price = $result[2];
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 4){
							$cp = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($cp) {
								$close_price = $result[2];
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 5){
							$lp = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($lp) {
								$lowest_price = $result[2];
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 6){
							$ts = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($ts) {
								$trade_shares = number_format($result[2], 0, ".", "");;
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 7){
							$tm = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($tm) {
								$trade_money = number_format($result[2], 0, ".", "");;
								$line_number ++;
								continue;
							}
						}
						
						if ($line_number === 8){
							$df = preg_match("/<td(.)*>([0-9]+.[0-9]+)<\/div><\/td>/", $line, $result);
							if ($df) {
								$discount_factor = $result[2];
								
								$record_start = false;
								$line_number = 1;
								
								//echo $trade_date . "\t" . $open_price . "\t" . $close_price . "\t" . $highest_price . "\t" . $lowest_price . "\t" . $trade_shares . "\t" . $trade_money . "\t" . $discount_factor . "\n";
								
								$insert_sql = "insert into stockdailyinfo_fq(stockcode, tradedate, fqOpenPrice, fqClosePrice, fqHighestPrice, fqLowestPrice, tradeAmount, tradeMoney, discountFactor) ".
										      "values('$code', $trade_date, $open_price, $close_price, $highest_price, $lowest_price, $trade_shares, $trade_money, $discount_factor)";
								
								echo $insert_sql . "\n";
								
								mysqli_query($conn, $insert_sql);
							}
						}
						
					}
				}
			}
		}
		
	}
	
	mysqli_close ( $conn );
	echo "--------------------------END-----------------------------\n";
	echo date('Y-m-d H:i:s',time()) . "\n\n\n\n\n";
}

init_downlowd($sina_url);
//get_years("<option value=\"2014\" >2014</option>");
?>