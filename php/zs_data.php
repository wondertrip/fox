<?php
header('Content-type: text/html; charset=utf-8');

define(LINK_TMPL, "<a href=\"./php/stock_info.php?code=sc1\" target=\"_blank\">sc1:STOCK1</a><br><br><a href=\"./php/stock_info.php?code=sc2\" target=\"_blank\">sc2:STOCK2</a>");

chdir(dirname(__FILE__));
require_once("./utils.php");

function formatStockLink($origi_str, $isLink = false, $isRate = false, $round = '') {
  $arr = explode(";", $origi_str);
  $str1 = explode(":", $arr[0]);
  $str2 = explode(":", $arr[1]);
  if ($isLink) {
  	    $query_flags1 = mysql_query_wrapper("select * from zs_game_stock_xdr where round = $round and stockcode = '$str1[1]'");
  	    $flag1 = "";
  	    while ($row = mysql_fetch_array($query_flags1)) {
			if ($row['flag2' === "S"] && $row['flag1'] !== "*") {
				$flag1 = "<font color=\"red\">" . $row['flag2'].$row['flag1'] . "</font>";	
			} else {
				$flag1 = $row['flag2'].$row['flag1'];	
			}
  	    } 
  	    $query_flags2 = mysql_query_wrapper("select * from zs_game_stock_xdr where round = $round and stockcode = '$str2[1]'");
  	    $flag2 = "";
  	    while ($row = mysql_fetch_array($query_flags2)) {
  	    	if ($row['flag2' === "S"] && $row['flag1'] !== "*") {
				$flag2 = "<font color=\"red\">" . $row['flag2'].$row['flag1'] . "</font>";	
			} else {
				$flag2 = $row['flag2'].$row['flag1'];	
			}
  	    }
        $tmp1 = str_replace("sc1", $str1[1], LINK_TMPL);
        $tmp2 = str_replace("STOCK1", $flag1 . $str1[0] . $flag1, $tmp1);
        $tmp3 = str_replace("sc2", $str2[1], $tmp2);
        $tmp4 = str_replace("STOCK2", $flag2 . $str2[0] . $flag2, $tmp3);
        if (ismobile()) {
          return $str1[0] . "<br><br>" . $str2[0];
        } else {
          return $tmp4;
        }
  } else if ($isRate) {
          $rate1 = $str1[0];
          if ($str1[0] != "" && $str1[0] != null){
              $rate1 = number_format($str1[0], 2) ."%";
          } else {
              $rate1 = "-";
          }
          $rate2 = $str2[0];
          if ($str2[0] != "" && $str2[0] != null){
              $rate2 = number_format($str2[0], 2) ."%";
          } else {
            $rate2 = "-";
          }
          return $rate1."<br><br>".$rate2;
  } else {
    $value1 = $str1[0];
    if ($str1[0] == "" || $str1[0] == null) {
      $value1 = "-";
    }
    $value2 = $str2[0];
    if ($str2[0] == "" || $str2[0] == null) {
      $value2 = "-";
    }
    return $value1."<br><br>".$value2;
  }
}

function formatName($name, $weibo) {
  $tmp1 = str_replace("weibo_addr", $weibo, NAME_TMPL);
  $result = str_replace("name", $name, $tmp1);
  return $result;
}

function getTopPlayerOrAll($round, $con, $with_round, $get_all_flag){
	$rows = array();
	$max_round_result = mysql_query_wrapper("select max(round) from zs_game where team = 1");

	$max_round = 0;
	while($row = mysql_fetch_array($max_round_result)) {
		global $max_round;
		$max_round = $row['max(round)'];
	}

	if ($round > $max_round || empty($round)){
		global $round;
		$round = $max_round;
	}

	if ($get_all_flag === true) {
		$top = mysql_query_wrapper("select * from zs_game where round = $round order by score desc");
	} else {
		$top = mysql_query_wrapper("select * from zs_game where round = $round order by total_score desc limit 0,8");
	}
	while ($row = mysql_fetch_array($top)) {
		if ($with_round == 'true') {
			if ($get_all_flag === true) {
				$record = array (
						$row ['round'],
						$row ['num'],
						$row ['name'],
						$row['team'],
						formatStockLink ( $row ['target_stock'], true, false, $round ),
						formatStockLink ( $row ['start_price'] ),
						formatStockLink ( $row ['current_price'] ),
						formatStockLink ( $row ['incr_rate'], false, true ),
						formatStockLink ( $row ['highest_price'] ),
						formatStockLink ( $row ['highest_incr_rate'], false, true ),
						number_format($row ['score'], 2),
						number_format($row ['total_score'], 2),
					    number_format($row ['score'] + $row ['total_score'], 2)
				);
			} else {
				$record = array (
						$row ['round'],
						$row ['num'],
						$row ['name'],
						formatStockLink ( $row ['target_stock'], true, false, $round ),
						formatStockLink ( $row ['start_price'] ),
						formatStockLink ( $row ['current_price'] ),
						formatStockLink ( $row ['incr_rate'], false, true ),
						formatStockLink ( $row ['highest_price'] ),
						formatStockLink ( $row ['highest_incr_rate'], false, true ),
						number_format($row ['score'], 2),
						number_format($row ['total_score'], 2),
					    number_format($row ['score'] + $row ['total_score'], 2)
				);
			}
			
			array_push ( $rows, $record );
		} else {
			if ($get_all_flag === true) {
				$record = array (
					$row ['num'],
					$row ['name'],
				    $row['team'],
					formatStockLink ( $row ['target_stock'], true, false, $round ),
					formatStockLink ( $row ['start_price'] ),
					formatStockLink ( $row ['current_price'] ),
					formatStockLink ( $row ['incr_rate'], false, true ),
					formatStockLink ( $row ['highest_price'] ),
					formatStockLink ( $row ['highest_incr_rate'], false, true ),
					number_format($row ['score'], 2),
					number_format($row ['total_score'], 2),
					number_format($row ['score'] + $row ['total_score'], 2)
			);
			} else {
				$record = array (
						$row ['num'],
						$row ['name'],
						formatStockLink ( $row ['target_stock'], true, false, $round ),
						formatStockLink ( $row ['start_price'] ),
						formatStockLink ( $row ['current_price'] ),
						formatStockLink ( $row ['incr_rate'], false, true ),
						formatStockLink ( $row ['highest_price'] ),
						formatStockLink ( $row ['highest_incr_rate'], false, true ),
						number_format($row ['score'], 2),
						number_format($row ['total_score'], 2),
					    number_format($row ['score'] + $row ['total_score'], 2)
				);
			}
			
			array_push ( $rows, $record );
		}
	}
	

	mysql_close($con);

	echo urldecode(json_encode(array("data"=>$rows)));
}

function getDataByRound($round, $con, $with_round, $team){
  $rows = array();
  $max_round_result = mysql_query_wrapper("select max(round) from zs_game where team = $team");

  $max_round = 0;
  while($row = mysql_fetch_array($max_round_result)) {
    global $max_round;
    $max_round = $row['max(round)'];
  }

  if ($round > $max_round || empty($round)){
	global $round;
	$round = $max_round;
  }
  
  $result = mysql_query_wrapper("select * from zs_game where round = $round and team = $team order by num asc");

  while($row = mysql_fetch_array($result)){
		if ($with_round == 'true') {
			$record = array (
					$row ['round'],
					$row ['num'],
					$row ['name'],
					formatStockLink ( $row ['target_stock'], true, false, $round ),
					formatStockLink ( $row ['start_price'] ),
					formatStockLink ( $row ['current_price'] ),
					formatStockLink ( $row ['incr_rate'], false, true ),
					formatStockLink ( $row ['highest_price'] ),
					formatStockLink ( $row ['highest_incr_rate'], false, true ),
					number_format($row ['score'], 2), 
					number_format($row ['total_score'], 2),
					number_format($row ['score'] + $row ['total_score'], 2)
			);
			array_push ( $rows, $record );
		} else {
			$record = array (
					$row ['num'],
					$row ['name'],
					formatStockLink ( $row ['target_stock'], true, false, $round ),
					formatStockLink ( $row ['start_price'] ),
					formatStockLink ( $row ['current_price'] ),
					formatStockLink ( $row ['incr_rate'], false, true ),
					formatStockLink ( $row ['highest_price'] ),
					formatStockLink ( $row ['highest_incr_rate'], false, true ),
					number_format($row ['score'], 2), 
					number_format($row ['total_score'], 2),
					number_format($row ['score'] + $row ['total_score'], 2)
			);
			array_push ( $rows, $record );
		}
	}

  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

function getDataByName($name, $con, $with_round, $team){
  $rows = array();
  
  $result = mysql_query_wrapper("select * from zs_game where name = \"$name\" and team = $team order by round desc");

  while($row = mysql_fetch_array($result)){
		if ($with_round == 'true') {
			$record = array (
					$row ['round'],
					$row ['start_date'] . "<br><br>" . $row ['end_date'],
					$row ['name'],
					formatStockLink ( $row ['target_stock'], true, false, $round ),
					formatStockLink ( $row ['start_price'] ),
					formatStockLink ( $row ['current_price'] ),
					formatStockLink ( $row ['incr_rate'], false, true ),
					formatStockLink ( $row ['highest_price'] ),
					formatStockLink ( $row ['highest_incr_rate'], false, true ),
					number_format($row ['score'], 2), 
					number_format($row ['total_score'], 2) 
			);
			array_push ( $rows, $record );
		} else {
			$record = array (
					$row ['start_date'] . "<br><br>" . $row ['end_date'],
					$row ['name'],
					formatStockLink ( $row ['target_stock'], true, false, $round ),
					formatStockLink ( $row ['start_price'] ),
					formatStockLink ( $row ['current_price'] ),
					formatStockLink ( $row ['incr_rate'], false, true ),
					formatStockLink ( $row ['highest_price'] ),
					formatStockLink ( $row ['highest_incr_rate'], false, true ),
					number_format($row ['score'], 2), 
					number_format($row ['total_score'], 2) 
			);
			array_push ( $rows, $record );
		}
	}

  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

$round = $_GET['round'];
$name = $_GET['name'];
$with_round = $_GET['with_round'];
$team = $_GET['team'];

app_log(DEBUG, "round:[" . $round . "]; name:[" . $name . "]; with_round:[" . $with_round . "]; team:[" . $team . "].");

$con = mysql_connect("localhost","stock_reader","pasword");
if (!$con){
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("stock", $con);

if (empty($name)){
	if ($team == 8) {
		return getTopPlayerOrAll($round, $con, $with_round, false);
	} elseif ($team == 9){
		return getTopPlayerOrAll($round, $con, $with_round, true);
	} else {
		return getDataByRound($round, $con, $with_round, $team);
	}
	
} else {
    return getDataByName($name, $con, $with_round, $team);
}

?>