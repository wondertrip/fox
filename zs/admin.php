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

<script type="text/javascript" charset="utf8" src="js/utils.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
	    if (validateUser() == false) {
	        alert("你没有权限查看当前页面。");
	        return;
	    }
	    if (getCookie("valid_dz_editor") == "true") {
            //valid
		}
	} );

	function validateUser() {
		var cv = getCookie("valid_dz_editor");
		if (cv == "true") {
			return true;
		}
		
		var code=prompt("请输入授权码:");
		var request = $.ajax({
			url: "./php/dz_check_permission.php?code="+code,
			type: "GET",           
			dataType: "html"
		});
 
		request.done(function(msg) {
			if (msg == "valid_dz_editor"){
				setCookie("valid_dz_editor", "true", 1);
				window.location.href="admin.php";
			} else {
                alert("授权码不正确。");
                window.location.href="no_permission.html";
			}  
		});
 
		request.fail(function(jqXHR, textStatus) {
			alert("授权码不正确。");
			window.location.href="no_permission.html";
		});
	}
</script>

<body>	
  <div id="wrapper">
    <?php 
		chdir(dirname(__FILE__));
		include './php/view_round_info.php'; 
	?>
	<form method="post" action="./php/zs_update_round.php">
      <table>
        <tr>
          <td>更新比赛期数:<input style="float:right" type="text" name="round" required="required"></td>
        </tr>
        <tr>
          <td>本期开始时间:<input style="float:right" type="text" name="start_date" required="required"></td><td>格式：yyyyMMdd。如20160101.</td>
        </tr>
        <tr>
          <td>本期结束时间:<input style="float:right" type="text" name="end_date" required="required"></td><td>格式：yyyyMMdd。如20160105.</td>
        </tr>
        <tr>
          <td><input type="submit" value="提交"></td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>