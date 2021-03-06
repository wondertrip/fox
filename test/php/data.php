<?php
header('Content-type: text/html; charset=utf-8');
/*$arr_a = array("data" => array(
        array("1", "<a href=\"http://weibo.com/hjhh\">花荣</a>", "多加股票十大流通股股东", "<a href=\"http://localhost/php/stock_info.php\">百利电气</a><br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "4", "154.55%"),
		array("2", "龚凯杰", "音乐填词人、草根股评家", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "3", "154.55%"),
	array("3", "何军岳-期货人", "对冲基金经理", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "1", "154.55%"),
    array("4", "张学林", "用军事战略思维研究股票的股痴", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "4", "154.55%"),
    array("5", "股市骏马", "企业家股民、价值投机者", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "1", "154.55%"),
    array("6", "孟德斯鸠会说唱", "黑马孵化营第二期冠军", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "2", "154.55%"),
    array("7", "红肥绿瘦粥", "黑马孵化营第二期亚军", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "3", "154.55%"),
    array("8", "余癸生o周易股市探秘", "黑马孵化营第三期亚军", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "0", "154.55%"),
    array("9", "琛-SamWong", "挚爱追击龙头的电商创业家", "百利电气<br><br>中南重工", "13.9<br><br>17.94", "13.86<br><br>20.09", "-0.29%<br><br>11.98%", "14.69<br><br>20.9", "5.86%<br><br>16.50%", "8.47", "31", "0", "154.55%"),
	),
    );
echo urldecode(json_encode($arr_a));*/
define(LINK_TMPL, "<a href=\"./php/stock_info.php?code=sc1\" target=\"_blank\">STOCK1</a><br><br><a href=\"./php/stock_info.php?code=sc2\" target=\"_blank\">STOCK2</a>");
define(NAME_TMPL, "<a href=\"weibo_addr\" target=\"_blank\">name</a>");
chdir(dirname(__FILE__));
require_once("./utils.php");

function formatStockLink($origi_str, $isLink = false, $isRate = false) {
  $arr = explode(";", $origi_str);
  $str1 = explode(":", $arr[0]);
  $str2 = explode(":", $arr[1]);
  if ($isLink) {
        $tmp1 = str_replace("sc1", $str1[1], LINK_TMPL);
        $tmp2 = str_replace("STOCK1", $str1[0], $tmp1);
        $tmp3 = str_replace("sc2", $str2[1], $tmp2);
        $tmp4 = str_replace("STOCK2", $str2[0], $tmp3);
        if (ismobile()) {
          return $str1[0] . "<br><br>" . $str2[0];
        } else {
          return $tmp4;
        }
  } else if ($isRate) {
          $rate1 = $str1[0];
          if ($str1[0] != "" && $str1[0] != null){
              $rate1 = $str1[0]."%";
          } else {
              $rate1 = "-";
          }
          $rate2 = $str2[0];
          if ($str2[0] != "" && $str2[0] != null){
              $rate2 = $str2[0]."%";
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

function getDataByRound($round, $con, $with_round){
  $rows = array();
  $max_round_result = mysql_query_wrapper("select max(round) from trader_game");

  $max_round = 0;
  while($row = mysql_fetch_array($max_round_result)) {
    global $max_round;
    $max_round = $row['max(round)'];
  }

  if ($round > $max_round || empty($round)){
	global $round;
	$round = $max_round;
  }
  
  $result = mysql_query_wrapper("select * from trader_game where round = $round order by num asc");

  while($row = mysql_fetch_array($result)){
	if ($with_round == 'true') {
	  $record = array($row['round'], $row['num'], formatName($row['name'], $row['weibo']), $row['intro'], formatStockLink($row['target_stock'], true), formatStockLink($row['start_price']), formatStockLink($row['current_price']), formatStockLink($row['incr_rate'], false, true), formatStockLink($row['highest_price']), formatStockLink($row['highest_incr_rate'], false, true), $row['score'], $row['total_rounds'], $row['champion_num'], $row['total_score']);
	  array_push($rows, $record);
	} else {
	  $record = array($row['num'], formatName($row['name'], $row['weibo']), $row['intro'], formatStockLink($row['target_stock'], true), formatStockLink($row['start_price']), formatStockLink($row['current_price']), formatStockLink($row['incr_rate'], false, true), formatStockLink($row['highest_price']), formatStockLink($row['highest_incr_rate'], false, true), $row['score'], $row['total_rounds'], $row['champion_num'], $row['total_score']);
	  array_push($rows, $record);
	}
  }

  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

function getDataByName($name, $con, $with_round){
  $rows = array();
  
  $result = mysql_query_wrapper("select * from trader_game where name = \"$name\" order by round desc");

  while($row = mysql_fetch_array($result)){
    if ($with_round == 'true') {
	  $record = array($row['round'], $row['start_date']."<br><br>".$row['end_date'], formatName($row['name'], $row['weibo']), $row['intro'], formatStockLink($row['target_stock'], true), formatStockLink($row['start_price']), formatStockLink($row['current_price']), formatStockLink($row['incr_rate'], false, true), formatStockLink($row['highest_price']), formatStockLink($row['highest_incr_rate'], false, true), $row['score'], $row['total_rounds'], $row['champion_num'], $row['total_score']);
	  array_push($rows, $record);
	} else {
	  $record = array($row['start_date']."<br><br>".$row['end_date'], formatName($row['name'], $row['weibo']), $row['intro'], formatStockLink($row['target_stock'], true), formatStockLink($row['start_price']), formatStockLink($row['current_price']), formatStockLink($row['incr_rate'], false, true), formatStockLink($row['highest_price']), formatStockLink($row['highest_incr_rate'], false, true), $row['score'], $row['total_rounds'], $row['champion_num'], $row['total_score']);
	  array_push($rows, $record);
	}
  }

  mysql_close($con);

  echo urldecode(json_encode(array("data"=>$rows)));
}

$round = $_GET['round'];
$name = $_GET['name'];
$with_round = $_GET['with_round'];

app_log(DEBUG, "round:[" . $round . "]; name:[" . $name . "]; with_round:[" . $with_round . "].");

$con = mysql_connect("localhost","stock_reader","pasword");
if (!$con){
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("stock", $con);

if (empty($name)){
	return getDataByRound($round, $con, $with_round);
} else {
    return getDataByName($name, $con, $with_round);
}

?>