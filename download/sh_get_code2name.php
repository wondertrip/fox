<?php
include './download_utils.php';
$url = "http://www.sse.com.cn/js/common/ssesuggestdataAll.js";
$content = file_get_contents($url);

//echo $content;

$content_array = explode("\n", $content);

$conn = mysqli_connect("localhost", "root", "Wind+3569");
mysqli_select_db($conn, "stock");

foreach ($content_array as $record) {
	if (strpos(ltrim($record), "_t.push") === 0) {
		$codeAndName = code2Namesh($record);
		
		$code = $codeAndName[0];
		$name = $codeAndName[1];
		
		if (strpos($code, "6") === 0) {
		  $insert_sql = "insert into stockcode2name values('$code', '$name')";
		  echo $insert_sql . "\n";
		  mysqli_query($conn, $insert_sql);
		}
	} 
}

mysqli_close($conn);

?>