<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我是操盘手</title>
<meta name="Keywords" content="我是操盘手,股票游戏大赛,选股比赛,微博平台,花荣,股票学习,选股工具,选股助手,我是操盘手大赛,股票知识分享"/>
<meta name="Description" content="我是操盘手大赛是微博平台水平最高、影响力最大的选股比赛。这里汇聚了最顶尖的股市资深职业投资人如花荣和众多证券新秀。选手水平、比赛难度、赛事影响力均为国内顶尖水平。本网站提供的选股助手是最好用的选股系统，提供根据各种技术指标（十大流通股东、MACD、KDJ、价格均线、成交量均线等）进行选股的功能。" />

<link href="styles/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<!--[if lt IE 9]>
    <script src="js/html5shiv-printshiv.js"></script>
<![endif]-->

<script type="text/javascript" charset="utf8" src="js/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

<script type="text/javascript">
  function checkInput(team, usernum, usernmae, stock_name1, stock_code1, stock_name2, stock_code2) {
	  alert("checkInput:" + team + ";" + usernum);
    if (team == "") {
    	alert("请输入组号");
    	
    }
    return false;
  }
</script>

<body>	
  <div id="wrapper">
	<form method="post" action="./php/zs_update_init_price.php">
      <table>
        <tr>
          <td>第？期             :<input style="float:right" type="text" name="round" required="required"></td>
        </tr>
        <tr>
          <td>第？组            :<input style="float:right" type="text" name="team" required="required"></td>
        </tr>
        <tr>
          <td>学号                :<input style="float:right" type="text" name="usernum" required="required"></td>
        </tr>
  
        <tr>
          <td>选手网名        :<input style="float:right" type="text" name="username" required="required"></td>
        </tr>

        <tr>
          <td>参赛股名称:<input style="float:right" type="text" name="stock_name" required="required"></td>
        </tr>
        
        <tr>
          <td>参赛股代码:<input style="float:right" type="text" name="stock_code" required="required"></td>
        </tr>
        
        <tr>
          <td>修正后初始价格:<input style="float:right" type="text" name="new_init_price" required="required"></td>
        </tr>
        
        <tr>
          <td>修正后周最高价格:<input style="float:right" type="text" name="new_highest_price" required="required"></td>
        </tr>

		<tr>
          <td>修正后现在价格:<input style="float:right" type="text" name="new_current_price" required="required"></td>
        </tr>

        <tr>
          <td><input type="submit" value="提交"></td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>