<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$code = $_GET['code'];
app_log(DEBUG, "permisson code to check is $code");

if ($code == "zs2015") {
	echo "valid";
} else {
	echo "not valid";
}
?>