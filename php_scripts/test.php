#!/alidata/server/php/bin/php
<?php 
header('Content-type: text/html; charset=utf-8');
$url="http://hq.sinajs.cn/list=sh110017";
$content=file_get_contents($url);
echo mb_convert_encoding($content, "utf8", "gb2312");
//echo number_format("2.1356%", 2) . "\n";
//echo date('Y-m-d H:i:s',time()) . "\n";
?>
