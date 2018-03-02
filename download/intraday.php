<?php
echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------START-----------------------------\n";

$conn = mysqli_connect("123.56.103.240", "root", "Wind+3569", "stock");
$all_stockcodes = mysqli_query($conn, "select distinct stockcode from stockdailyinfo");
$url="http://hq.sinajs.cn/list=";
$count = 0;
while ($row = mysqli_fetch_array($all_stockcodes)) {
  $stockcode = $row['stockcode'];
  if (strpos($stockcode, "6") === 0) {
  	$url = $url . "sh" . $stockcode;
  } else {
  	$url = $url . "sz" . $stockcode;
  }
  
  $count ++;
  if ($count == 50) {
  	echo $url . "\n";
  	
  	$raw_content = file_get_contents ( $url );
  	
  	$content = mb_convert_encoding ( $raw_content, "utf8", "gb2312" );
  	
  	echo $content . "\n";
  	
  	$url="http://hq.sinajs.cn/list=";
  	$count = 0;
  	//break;
  } else {
  	$url = $url . ",";
  }
}

echo $url . "\n";
 
$raw_content = file_get_contents ( $url );
 
$content = mb_convert_encoding ( $raw_content, "utf8", "gb2312" );
 
echo $content . "\n";

mysqli_close ( $conn );
echo date('Y-m-d H:i:s',time()) . "\n";
echo "--------------------------END-----------------------------\n"; 
?>