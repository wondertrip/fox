<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="Keywords" content="我是操盘手,股票游戏大赛,选股比赛,微博平台,花荣,股票学习,选股工具,选股助手,我是操盘手大赛,股票知识分享"/>
<meta name="Description" content="我是操盘手大赛是微博平台水平最高、影响力最大的选股比赛。这里汇聚了最顶尖的股市资深职业投资人如花荣和众多证券新秀。选手水平、比赛难度、赛事影响力均为国内顶尖水平。本网站提供的选股助手是最好用的选股系统，提供根据各种技术指标（十大流通股东、MACD、KDJ、价格均线、成交量均线等）进行选股的功能。" />
<title>我是操盘手</title>
<!-- CSS -->
<link href="../styles/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
  header('Content-Type:text/html;charset=utf-8');
  chdir(dirname(__FILE__));
  require_once("./utils.php");
  $stock_code = $_GET['code'];

  app_log(DEBUG, "showing stock_info for stockcode:".$stock_code);
    
  echo "<div id=\"stock_info\">";
  echo "<iframe id=\"iframe_stock_info\" src=\"http://q.stock.sohu.com/qp/index.html?cn_$stock_code\" ";
  echo "frameborder=\"no\" border=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"no\">";
  echo "</iframe>";
  echo "</div>";
?>
</body>
</html>