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
  
	function checkInput(stock_code, name_field) {
		var request = $.ajax({
			url : "./php/get_name_by_code.php?code=" + stock_code,
			type : "GET",
			dataType : "html"
		});
		request.done(function(msg) {
			$('#'+name_field).val(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			$('#'+name_field).val(msg);
		});
	}
</script>
</head>

<body>	
  <div id="wrapper">
	<form method="post" action="./php/zs_add_record.php">
      <table>
        <tr>
          <td>
          <?php 
			chdir(dirname(__FILE__));
			include './php/view_round_info.php'; 
		  ?></td>
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
          <td>参赛股1代码:<input style="float:right" type="text" name="stock_code1" required="required" onchange="checkInput(this.value, 'sn1')"></td>
        </tr>
        
        <tr>
          <td>参赛股1名称:<input style="float:right" type="text" id="sn1" name="stock_name1" required="required"></td>
        </tr>
                
        <tr>
          <td>参赛股2代码:<input style="float:right" type="text" name="stock_code2" required="required" onchange="checkInput(this.value, 'sn2')"></td>
        </tr>

        <tr>
          <td>参赛股2名称: <input style="float:right" type="text" id="sn2" name="stock_name2" required="required"></td>
        </tr>


        <tr>
          <td><input type="submit" value="提交"></td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>
