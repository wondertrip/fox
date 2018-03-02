<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="我是操盘手,股票游戏大赛,选股比赛,微博平台,花荣,股票学习,选股工具,选股助手,我是操盘手大赛,股票知识分享"/>
<meta name="Description" content="我是操盘手大赛是微博平台水平最高、影响力最大的选股比赛。这里汇聚了最顶尖的股市资深职业投资人如花荣和众多证券新秀。选手水平、比赛难度、赛事影响力均为国内顶尖水平。本网站提供的选股助手是最好用的选股系统，提供根据各种技术指标（十大流通股东、MACD、KDJ、价格均线、成交量均线等）进行选股的功能。" />
<title>我是操盘手</title>
<link href="styles/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">

<!--[if lt IE 9]>
    <script src="js/html5shiv-printshiv.js"></script>
<![endif]-->
  
<script type="text/javascript" charset="utf8" src="js/jquery.js"></script>
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
<script type="text/javascript">
  function showResult(share_holder, time) {
	if (share_holder == "") {
		alert("请输入要查询的十大流通股股东名称");
		return;
	}
	$('#result_tbl').hide();
	var url = "./php/by_share_holder.php?share_holder=" + encodeURIComponent(share_holder) + "&time=" + time;
    $('#result_tbl').dataTable( {
		"columns": [
            { "title": "股票代码", "class": "center" },
			{ "title": "股东名称", "class": "center" },
            { "title": "十大流通股东排名", "class": "center" },
            { "title": "持股数量", "class": "dt-body-right" },
            { "title": "持股占比", "class": "dt-body-right" },
            { "title": "持股类别", "class": "center" }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": url,
		"bFilter": true,
		"bInfo": true,
		"bSortable": true,
		"bAutoWidth": false,
		"bDestroy": true,
		language: {
			processing:     "正在加载数据...",
			search:         "查找",
			lengthMenu:    "每页显示 _MENU_ 条结果",
			info:           "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条",
			infoEmpty:      "找不到符合条件的数据",
			infoFiltered:   "共 _MAX_ 条数据",
			infoPostFix:    "",
			loadingRecords: "正在加载数据...",
			zeroRecords:    "没有符合条件的数据",
			emptyTable:     "没有符合条件的数据",
			paginate: {
				first:      "首页",
				previous:   "前一页",
				next:       "下一页",
				last:       "尾页"
			},
	   }
	});
	$('#result_tbl').show();
  }
</script>
        
<?php
  chdir(dirname(__FILE__));
  require_once("./php/utils.php");
  app_log(DEBUG, "Access stock_select.php");
?>
</head>
<body>
    <div id="wrapper">
        <div id="menu"/>
            <header id="topmenu">
                <nav id="mainnav">
                    <ul>
                        <li><a href="index.php">我是操盘手大赛</a></li>
                        <li><a href="stock_select.php">选股小助手</a></li>
                        <li><a href="learn.php">进阶学习</a></li>
                    </ul>
                </nav>
            </header>
        </div>
        <div id="intro">
            <br>
            <ul>
            <li>请牢记多位名人前辈的警言：股市没有免费午餐。<p/></li>
            <li>本选股小助手致力于提供个性化选股服务，目前开放的接口为根据十大流通股东来进行选股。<p/></li>
            <li>本选股系统业余娱乐、学习交流为主，勿据此结果进行交易！否则风险自负！<p/></li>
            </ul>
            <br>
        </div>
        <div id="search_condition">
            <form id="stock_select" method="post" action="javascript:showResult(share_holder.value, time.value)" style="line-height:300%">
                <table width="100%" border="0">
                  <tr>
                    <td width="15%">十大流通股东名称：</td>
                    <td width="50%"><input type="text" id="share_holder" style="width:90%"></td>
                    <td width="10%">公告日期：</td>
                    <td width="15%"><select id="time">
                        <option value="20140930">2014-09-30</option>
                        <option value="20140630">2014-06-30</option>
                        <option value="20140331">2014-03-31</option>
                        <option value="20131231">2013-12-31</option>
                        <option value="20130930">2013-09-30</option>
                        <option value="20130630">2013-06-30</option>
                        </select></td>
                    <td width="10%"><input value="开始选股" type="submit" onClick="javascript:showResult(share_holder.value, time.value)"/></td>
                  </tr>
                </table>
            </form>
        </div>
        <div>
          <a href="advanced_search.php" target="_blank">技术指标个性化选股?</a> <br><br>
        </div>
        <table id="result_tbl" class="display" cellspacing="0" width="100%">
        </table>
    </div>
    <?php include("footer.html"); ?>
</body>
</html>
