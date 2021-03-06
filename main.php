<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我是操盘手</title>
<meta name="Keywords" content="我是操盘手,股票游戏大赛,选股比赛,微博平台,花荣,股票学习,选股工具,选股助手,我是操盘手大赛,股票知识分享"/>
<meta name="Description" content="我是操盘手大赛是微博平台水平最高、影响力最大的选股比赛。汇聚了最顶尖的股市资深职业投资人和证券新秀。选手水平、比赛难度、赛事影响力均为国内顶尖水平。本网站提供的选股助手是最好用的选股系统，提供根据各种技术指标（十大流通股东、MACD、KDJ、价格均线、成交量均线等）进行选股的功能。" />

<!--base target="_blank" /-->
<!-- CSS -->
<link href="styles/main.css" rel="stylesheet" type="text/css">

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="js/jquery.js"></script>
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

<script type="text/javascript" class="init">
    $(document).ready(function() {
      $('#competition_tbl').dataTable( {
		"columns": [
            { "title": "序号", "class": "center" },
            { "title": "选手", "class": "dt-body-left dt-head-left" },
            { "title": "简介", "class": "dt-body-left" },
            { "title": "参赛股", "class": "center" },
            { "title": "起始价", "class": "center" },
            { "title": "现价", "class": "center" },
            { "title": "涨幅", "class": "center" },
            { "title": "周最高价", "class": "center" },
            { "title": "最高涨幅", "class": "center" },
			{ "title": "本周积分", "class": "center" },
			{ "title": "参赛周", "class": "center" },
			{ "title": "周冠军次数", "class": "center" },
			{ "title": "总积分", "class": "center" }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": "./php/data.php",
		"bFilter": false,
		"bInfo": false,
		"bPaginate": false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 1, 2, 3, 4, 5, 6, 7, 8 ] }
		],
		"bAutoWidth": false
		});
	  $('#reset_his1').hide();
	  $('#reset_his2').hide();	
	} );
	
	function showResult(tbl_name, optionValue, resetLable){
		if (optionValue == 0) {
		    $('#'+tbl_name).dataTable().fnClearTable();
		    $('#'+tbl_name).hide();
		    $('#'+resetLable).hide();
	    }
		
		if (optionValue != 0){
			var url = "./php/data.php"
			if (tbl_name == 'history_of_num') {
			  url = url + "?round=" + optionValue;
			} else if (tbl_name == 'history_of_person') {
			  url = url + "?name=" + encodeURIComponent(optionValue);
			}
			//url = encodeURIComponent(url + "&with_round=true");
			url = url + "&with_round=true";

			$('#'+tbl_name).dataTable( {
			"columns": [
			    { "title": "期数", "class": "center" },
				{ "title": "序号", "class": "center" },
				{ "title": "选手", "class": "dt-body-left dt-head-left" },
				{ "title": "简介", "class": "dt-body-left" },
				{ "title": "参赛股", "class": "center" },
				{ "title": "起始价", "class": "center" },
				{ "title": "现价", "class": "center" },
				{ "title": "涨幅", "class": "center" },
				{ "title": "周最高价", "class": "center" },
				{ "title": "最高涨幅", "class": "center" },
				{ "title": "本周积分", "class": "center" },
				{ "title": "参赛周", "class": "center" },
				{ "title": "周冠军次数", "class": "center" },
				{ "title": "总积分", "class": "center" }
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
		document.getElementById(optionId).selectedIndex=0;
	}
</script>

</head>
<body>
    <div id="wrapper">
        <div id="menu"/>
            <header id="topmenu">
                <nav id="mainnav">
                    <ul>
                        <li><a href="main.php">我是操盘手大赛</a></li>
                        <li><a href="stock_select.php">选股小助手</a></li>
                        <li><a href="learn.php">进阶学习</a></li>
                    </ul>
                </nav>
            </header>
        </div>
        <div id="site_info">
              <h1>我 是 操 盘 手</h1>
            <article>
                <h3>简介</h3>
                <p>我是操盘手是由著名股市操盘专家/财经作家<a href="http://weibo.com/hjhh">花荣</a>、新浪微博知名博主/职业投资人<a href="http://weibo.com/p/1005052436093373">金融侠女盈盈</a>等发起和组织的短线选股大赛游戏。是微博平台水平最高、最具影响力和观赏性的选股大赛。2014年4月开赛至今，亲自参加游戏的<a href="http://weibo.com/hjhh">花荣</a>以其持续稳定的出色表现和遥遥领先的总成绩，向我们展示了其强大又稳定的实力，印证着“股市不死鸟”的江湖传说；亦有多名证券投资新星在此平台脱颖而出、声名鹊起。</p>
                <h3>风险警示</h3>
                <p>本游戏赛事旨在加强股友间交流学习，丰富炒股生活。请切勿跟风操作，否则风险自担。</p>
            </article>
        </div>
    
    	<div id="tbl_header">
            <?php include './php/max_round.php'; ?>
        </div>
        <div id="tbl_content">
            <table id="competition_tbl" class="display" cellspacing="0" width="100%">
            </table>
        </div>
        <div id="history">
            <p>看看往期精彩故事？</p>
            <form id="history_by_num"> 
                按期数查看:
                <select id="num" onchange="showResult('history_of_num', this.value, 'reset_his1')">
                    <?php include './php/view_by_round.php'; ?>
                </select>
                
                <lable id="reset_his1" onClick="resetHistory('reset_his1', 'history_of_num', 'num')">收起</lable>
            </form>
            
            <table id="history_of_num" class="display" cellspacing="0" width="100%">
            </table>
            <form id="history_by_person"> 
                按选手查看:
                <select id="person" onchange="showResult('history_of_person', this.value, 'reset_his2')">
                    <?php include './php/view_by_name.php'; ?>
                </select>
                <lable id="reset_his2" onClick="resetHistory('reset_his2', 'history_of_person', 'person')">收起</lable>
            </form>
            
            <table id="history_of_person" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</body>
</html>
