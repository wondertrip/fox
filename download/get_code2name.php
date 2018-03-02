<?php
include './download_utils.php';
$url = "http://quote.eastmoney.com/stocklist.html";
$raw_content = file_get_contents($url);

$content = mb_convert_encoding($raw_content, "utf8", "gb2312");
//echo $content;

$content_array = explode("\n", $content);

$prefix = "<li><a target=\"_blank\" href=\"http://quote.eastmoney.com/";

$conn = mysqli_connect("localhost", "root", "Wind+3569");
mysqli_select_db($conn, "stock");

foreach ($content_array as $record) {
	if (strpos(ltrim($record), $prefix) === 0) {
		$codeAndName = code2Name($record);
		$codenameArray = explode("(", $codeAndName);
		
		$code = explode(")", $codenameArray[1])[0];
		$name = $codenameArray[0];
		
		$insert_sql = "insert into stockcode2name values('$code', '$name')";
		echo $insert_sql . "\n";
		mysqli_query($conn, $insert_sql);
	}
}

mysqli_close($conn);

?>