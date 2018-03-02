<?php
function getContent($record) {
	$sub_record1 = strrchr($record, "\">");
	return str_replace(array("\">", "</td>", "-"), array("", "", ""), $sub_record1);
}

function getContentNum($record) {
	$sub_record1 = strrchr($record, "\">");
	$content = str_replace(array("\">", "</td>", "-", ".0000"), array("", "", "", ""), $sub_record1);
	if (empty($content)) {
		return 0;
	} else {
		return $content;
	}
}

function code2Name($record) {
	$sub_record1 = strrchr($record, "html\">");
	$content = str_replace(array("html\">", "</a></li>"), array("", ""), $sub_record1);
	return $content;
}

//_t.push({val:"600000",val2:"浦发银行",val3:"pfyx"});
function code2Namesh($record) {
	$sub_record1 = str_replace(array("\"", "_t.push({val:", "val2:"), array("", "", ""), $record);
	return explode(",", $sub_record1);
	
}

//000001:-000001-平安银行|平安银行:-000001-平安银行|PAYH:-000001-平安银行
function code2NameCN($record) {
	$sub_record1 = str_replace(array(":-", "-"), array(":", ":"), $record);
	//echo $sub_record1 . "\n";
	return explode(":", $sub_record1);

}

/* $arr = code2Namesh("_t.push({val:\"600000\",val2:\"浦发银行\",val3:\"pfyx\"});");

echo $arr[0] . ":" . $arr[1] . "\n"; */
?>