<?php
chdir(dirname(__FILE__));
require_once("./utils.php");

$code = $_GET['code'];
app_log(DEBUG, "dz permisson code to check is $code");

if ($code == "qnhddk") {
    echo "valid_dz_editor";
} else if ($code == "dzview") {
    echo "valid_dz_viewer";
} else if ($code == "predzedit") {
    echo "valid_predz_editor";
} else if ($code == "predzview") {
    echo "valid_predz_viewer";
} else { 
	echo "not valid";
}
?>
