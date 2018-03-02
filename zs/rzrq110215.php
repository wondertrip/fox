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

<script type="text/javascript" class="init">
    $(document).ready(function() {
      $('#competition_tbl').dataTable( {
		"columns": [
            { "title": "股票代码", "class": "center" },
            { "title": "股票名称", "class": "center" },
            { "title": "总融资余额", "class": "dt-body-right" },
            { "title": "最新融资买入额", "class": "dt-body-right" },
            { "title": "总融资额占流通市值百分比", "class": "dt-body-right" },
            { "title": "总融资余额连续增加天数", "class": "dt-body-right" }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": "./php/rzrq_data.php?enddate=20150211",
		"bFilter": true,
		"bInfo": true,
		"bPaginate": true,
		"bAutoWidth": false,
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

	} );
	
	function showResult(tbl_name, optionValue, resetLable){
		if (optionValue == 0) {
		    $('#'+tbl_name).dataTable().fnClearTable();
		    $('#'+tbl_name).hide();
		    $('#'+resetLable).hide();
			if (tbl_name == "history_by_num") {
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
			url = url + "&with_round=true";
			
			var dynamicColHeader = "序号";
			if (tbl_name == 'history_of_person'){
		      dynamicColHeader = "起始日期";
			}

			$('#'+tbl_name).dataTable( {
			"columns": [
			    { "title": "期数", "class": "center" },
				{ "title": dynamicColHeader, "class": "center" },
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
			  
			  var request = $.ajax({
					url: "./php/get_round_date.php?round="+optionValue,
					type: "GET",           
					dataType: "html"
				});
		 
				request.done(function(msg) {
					$('#history_by_num_header').html(msg);         
				});
		 
				request.fail(function(jqXHR, textStatus) {
					$('#history_by_num_header').html("<h3>我是操盘手短线选股游戏第" + optionValue + "周赛况表"); 
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
</script>

</head>
<body>
    <div id="wrapper">
    
    	<div id="tbl_header">
    	  <h3>截止2015-02-11所有股票融资余额全景数据</h3>
        </div>
        <div id="tbl_content">
            <table id="competition_tbl" class="display" cellspacing="0" width="100%">
            </table>
        </div>
        
    </div>
    <?php include("footer.html"); ?>
</body>
</html>
