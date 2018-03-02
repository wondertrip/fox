#!/alidata/server/php/bin/php
<?php 
header('Content-type: text/html; charset=utf-8');
$url="http://hq.sinajs.cn/list=sh600789";
$content=file_get_contents($url);
echo mb_convert_encoding($content, "utf8", "gb2312");
echo mb_convert_encoding(file_get_contents("http://hq.sinajs.cn/list=sz000636"), "utf8", "gb2312");
echo number_format("0.000", 2) . "\n";
echo (number_format("0.000", 2) === "0.00") . "\n";
echo number_format("0", 2) . "\n";
//echo date('Y-m-d H:i:s',time()) . "\n";
?>
