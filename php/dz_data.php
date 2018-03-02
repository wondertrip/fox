<?php
header('Content-type: text/html; charset=utf-8');

define(LINK_TMPL, "<a href=\"./php/stock_info.php?code=sc\" target=\"_blank\">STOCK</a>");

chdir(dirname(__FILE__));
require_once("./utils.php");

function formatStockLink($name, $code, $isLink = false, $isRate = false) {
  if ($isLink) {
        $tmp = str_replace(array("sc", "STOCK"), array($code, $name), LINK_TMPL);
        if (ismobile()) {
          return $name;
        } else {
          return $tmp;
        }
  } else if ($isRate) {
          $rate = $name;
          if ($name != "" && $name != null){
              $rate = number_format($name, 2) ."%";
          } else {
              $rate = "-";
          }
          
          return $rate;
  } else {
    $value = $name;
    if ($name == "" || $name == null) {
      $value = "-";
    }
    
    return $value;
  }
}

function formatName($name, $weibo) {
  $tmp1 = str_replace("weibo_addr", $weibo, NAME_TMPL);
  $result = str_replace("name", $name, $tmp1);
  return $result;
}


function getData($con){
  $rows = array();
  $all_result = mysql_query_wrapper("select * from stock_dz_track order by dz_date desc");

  while($row = mysql_fetch_array($all_result)){
		$record = array (
				formatStockLink( $row ['stockname'], $row ['stockcode'], true, false),
				$row ['stockcode'],
				$row ['dz_date'],
				$row ['dz_unfreeze_date'],
				$row ['dz_price'],
				$row ['dz_amount'],
				$row ['current_price'],
				formatStockLink ( $row['latest_incr_rate'], '', false, true ),
				formatStockLink ( $row ['incr_rate'], '', false, true ),
				$row ['comments'],
				$row['latest_announce']
		);
		array_push ( $rows, $record );
	}

  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

$con = mysql_connect("localhost","stock_reader","pasword");
if (!$con){
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("stock", $con);

return getData($con);
?>