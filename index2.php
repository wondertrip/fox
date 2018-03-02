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
<script type="text/javascript" charset="utf8" src="js/utils.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

<script type="text/javascript" class="init">
    $(document).ready(function() {
      $('#competition_tbl').dataTable( {
		"columns": [
            { "title": "学号", "class": "center" },
            { "title": "选手", "class": "dt-body-left dt-head-left" },
            //{ "title": "简介", "class": "dt-body-left" },
            { "title": "参赛股", "class": "center" },
            { "title": "起始价", "class": "center" },
            { "title": "现价", "class": "center" },
            { "title": "涨幅", "class": "center" },
            { "title": "周最高价", "class": "center" },
            { "title": "最高涨幅", "class": "center" },
			{ "title": "本周积分", "class": "center" },
			//{ "title": "参赛周", "class": "center" },
			//{ "title": "周冠军次数", "class": "center" },
			{ "title": "原总积分", "class": "center" },
			{ "title": "总积分", "class": "center" }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": "./php/zs_data.php?team=2",
		"bFilter": false,
		"bInfo": false,
		"bPaginate": false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 1, 2, 3, 4, 5, 6, 7 ] }
		],
		"bAutoWidth": false
		});
	  $('#reset_his1').hide();
	  $('#reset_his2').hide();	
	  
	  var request = $.ajax({
					url: "./php/zs_max_round.php?team=2",
					type: "GET",           
					dataType: "html"
				});
		 
				request.done(function(msg) {
					$('#tbl_header').html(msg);         
				});
		 
				request.fail(function(jqXHR, textStatus) {
					$('#tbl_header').html("<h3>中暑山庄最强操盘手第2组赛况表"); 
				});
	} );
	
    function showResult(tbl_name, optionValue, resetLable){
		if (optionValue == 0) {
		    $('#'+tbl_name).dataTable().fnClearTable();
		    $('#'+tbl_name).hide();
		    $('#'+resetLable).hide();
			if (tbl_name == "history_of_num") {
			  $('#history_by_num_header').hide();	
			}
	    }
		
		if (optionValue != 0){
			var url = "./php/zs_data.php"
			if (tbl_name == 'history_of_num') {
			  url = url + "?round=" + optionValue;
			} else if (tbl_name == 'history_of_person') {
			  url = url + "?name=" + encodeURIComponent(optionValue);
			}
			//url = encodeURIComponent(url + "&with_round=true");
			url = url + "&with_round=true&team=2";
			
			var dynamicColHeader = "序号";
			if (tbl_name == 'history_of_person'){
		      dynamicColHeader = "起始日期";
			}

			$('#'+tbl_name).dataTable( {
			"columns": [
			    { "title": "期数", "class": "center" },
			    { "title": "学号", "class": "center" },
	            { "title": "选手", "class": "dt-body-left dt-head-left" },
	            //{ "title": "简介", "class": "dt-body-left" },
	            { "title": "参赛股", "class": "center" },
	            { "title": "起始价", "class": "center" },
	            { "title": "现价", "class": "center" },
	            { "title": "涨幅", "class": "center" },
	            { "title": "周最高价", "class": "center" },
	            { "title": "最高涨幅", "class": "center" },
				{ "title": "本周积分", "class": "center" },
				//{ "title": "参赛周", "class": "center" },
				//{ "title": "周冠军次数", "class": "center" },
				{ "title": "原总积分", "class": "center" }
			],
			"sAjaxDataProp": "data",
			"sAjaxSource": url,
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"aoColumnDefs": [
			  { "bSortable": false, "aTargets": [ 2, 3, 4, 5, 6, 7, 8, 9 ] }
			],
			"bAutoWidth": false,
			"bDestroy": true
			});
			$('#'+tbl_name).show();
			
			if (tbl_name == "history_of_num"){
			  $('#reset_his1').show();
			  
			  var request = $.ajax({
					url: "./php/get_round_date.php?team=2&round="+optionValue,
					type: "GET",           
					dataType: "html"
				});
		 
				request.done(function(msg) {
					$('#history_by_num_header').html(msg);         
				});
		 
				request.fail(function(jqXHR, textStatus) {
					$('#history_by_num_header').html("<h3>我是操盘手短线选股游戏第2组第" + optionValue + "周赛况表</h3>"); 
				});
			  $('#history_by_num_header').show();
			}
			
			if (tbl_name == "history_of_person") {
			  $('#reset_his2').show();
			}
		}
	}
	
	function resetHistory(srcId, targetId, optionId) {
		$('#'+targetId).dataTable().fnClearTable();
		$('#'+targetId).hide();
		$('#'+srcId).hide();
		if (srcId == "reset_his1") {
		    $('#history_by_num_header').hide();
		}
		document.getElementById(optionId).selectedIndex=0;
	}

	function checkCode(target_page) {
		var cv = getCookie("valid_user");
		if (cv == "true") {
			window.location.href=target_page+".php";
			return;
		}
		
		var code=prompt("请输入授权码:");
		var request = $.ajax({
			url: "./php/zs_check_permission.php?code="+code,
			type: "GET",           
			dataType: "html"
		});
 
		request.done(function(msg) {
			if (msg == "valid"){
				setCookie("valid_user", "true", 1);
				window.location.href=target_page+".html";
			} else {
                alert("授权码不正确。");
			}  
		});
 
		request.fail(function(jqXHR, textStatus) {
			alert("授权码不正确。");
		});
	}
</script>

</head>
<body>
    <div id="wrapper">
        <header id="topmenu">
            <nav id="mainnav">
                <ul>
                    <li><a href="index.php">一组</a></li>
                    <li><a href="index2.php">二组</a></li>
                    <li><a href="index3.php">三组</a></li>
                    <li><a href="index4.php">四组</a></li>
                    <li><a href="index5.php">五组</a></li>
                    <li><a href="index6.php">六组</a></li>
                    <li><a href="index7.php">七组</a></li>
                    <li><a href="index8.php">八强赛</a></li>
                    <li><a href="index9.php">总表</a></li>
                </ul>
            </nav>
        </header>
    
    	<div id="tbl_header">
           
        </div>
        <div id="tbl_content">
            <table id="competition_tbl" class="display" cellspacing="0" width="100%">
            </table>
        </div>
        <div id="editBtnDiv">
            <button onClick="javascript:checkCode('zs_input')">添加或修改</button>
            &nbsp;&nbsp;
            <button onClick="javascript:checkCode('zs_update_init_price')">修改起始价格</button>
        </div>
        
        <div id="history">
            <p>查看往期比赛记录</p>
            <form id="history_by_num"> 
                按期数查看:
                <select id="num" onchange="showResult('history_of_num', this.value, 'reset_his1')">
                    <?php 
					  chdir(dirname(__FILE__));
					  include './php/zs_view_by_round.php'; 
					?>
                </select>
                <lable id="reset_his1" hidden="true" onClick="resetHistory('reset_his1', 'history_of_num', 'num')">收起</lable>
            </form>
            <br>
            <div id="history_by_num_header">
            </div>
            <table id="history_of_num" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
    <?php include("footer.html"); ?>
</body>
</html>
