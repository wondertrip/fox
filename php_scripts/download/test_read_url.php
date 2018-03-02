<?php 
//$url = "http://vip.stock.finance.sina.com.cn/q/go.php/vInvestConsult/kind/rzrq/index.phtml?symbol=600170&bdate=2015-01-01&edate=2015-02-10";
//$url = "http://table.finance.yahoo.com/table.csv?s=600960.ss";
$url = "http://ichart.yahoo.com/table.csv?s=600960.SS&a=03&b=4&c=2015&d=03&e=30&f=2015&g=d"; 
$contents = file_get_contents($url); 
//如果出现中文乱码使用下面代码 
$getcontent = iconv("gb2312", "utf-8",$contents); 
echo $getcontent; 
?> 
