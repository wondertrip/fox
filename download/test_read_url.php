<?php 
//$url = "http://vip.stock.finance.sina.com.cn/q/go.php/vInvestConsult/kind/rzrq/index.phtml?symbol=600170&bdate=2015-01-01&edate=2015-02-10";
//$url = "http://table.finance.yahoo.com/table.csv?s=600960.ss";
//$url = "http://ichart.yahoo.com/table.csv?s=600960.SS&a=03&b=4&c=2015&d=03&e=30&f=2015&g=d";
//$url = "http://vip.stock.finance.sina.com.cn/corp/go.php/vMS_FuQuanMarketHistory/stockid/sh600960.phtml?year=2015&jidu=2"; 
$url = "http://data.gtimg.cn/flashdata/hushen/daily/16/sz000852.js";
/* $options = array(
		'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
				"Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
				"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
		)
);

$context = stream_context_create($options);
$contents = file_get_contents($url, false, $context); */
$contents = file_get_contents($url); 
//如果出现中文乱码使用下面代码 
//$getcontent = iconv("gb2312", "utf-8",$contents); 
/* echo $contents; 
echo count(explode ( "\\n\\", $contents )); */
 foreach ( explode ( "\\n\\\n", $contents ) as $line ) {
	if (strpos($line, "16") === false || strpos($line, "daily") !== false) {
		continue;
	}
	$fields = explode(" ", $line);
	echo $fields[0] . "---" . $fields[1] . "---" . $fields[2];
} 
?> 
