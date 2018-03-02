#!/alidata/server/php/bin/php
<?php 
header('Content-type: text/html; charset=utf-8');
$url="http://vip.stock.finance.sina.com.cn/q/go.php/vInvestConsult/kind/rzrq/index.phtml?symbol=000970&bdate=2015-02-05&edate=2015-02-06";
$content=file_get_contents($url);
echo mb_convert_encoding($content, "utf8", "gb2312");
echo mb_convert_encoding(file_get_contents("http://hq.sinajs.cn/list=sz000636"), "utf8", "gb2312");
?>
