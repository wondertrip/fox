<?php

function calcIncrRate($stock_info_array, $index, $days) {
  if ($index + $days >= count($stock_info_array)) {
    return $days . ": NA";
  }
  $incr_rate = ($stock_info_array[$index + $days]->closePrice - $stock_info_array[$index]->closePrice) / $stock_info_array[$index]->closePrice;
  return number_format($incr_rate * 100, 2); 
}
?>
