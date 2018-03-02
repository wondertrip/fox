<?php
$rate = "3.6%";
echo str_replace("%", "", $rate);
echo "\n";
echo date('Ymd') . "\n";
$start_price = "10.12;22.31";
echo $start_price . "\n";
$prices = explode(";", $start_price);
$prices[0] = 22.31;
$prices[1] = 10.12;
echo implode(";", $prices) . "\n";

echo date('m') . "\n";

echo date('Y') . "\n";
?>