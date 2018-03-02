<?php
include './download_utils.php';
$url = "http://www.cninfo.com.cn/jsnew/stocklist_stock.js";
$raw_content = file_get_contents($url);

$content = mb_convert_encoding($raw_content, "utf8", "gb2312");

//echo $content;

$content_array = explode("|", $content);

$conn = mysqli_connect("localhost", "root", "Wind+3569");
mysqli_select_db($conn, "stock");

foreach ($content_array as $record) {
	if (strpos(ltrim($record), "6") === 0 || strpos(ltrim($record), "0") === 0 || strpos(ltrim($record), "3") === 0) {
		$codeAndName = code2NameCN($record);
		
		$code = $codeAndName[0];
		$name = $codeAndName[2];
		
		if (strpos($code, "6") === 0 || strpos($code, "0") === 0 || strpos($code, "3") === 0) {
		  $insert_sql = "insert into stockcode2name values('$code', '$name')";
		  echo $insert_sql . "\n";
		  if (!mysqli_query($conn, $insert_sql)) {
		  	mysqli_query($conn, "update stockcode2name set name='$name' where code='$code'");
		  }
		}
	} 
}

mysqli_close($conn);

?>