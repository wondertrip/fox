<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>定向增发股票跟踪研究</title>
<meta name="Keywords" content="中暑山庄,股票游戏大赛,选股比赛,花荣,股票学习,选股工具,选股助手,定向增发"/>
<meta name="Description" content="中暑山庄定向增发研究" />

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
      if (validateUser() == false) {
          alert("你没有权限查看当前页面。");
          return;
      }
      
      $('#dz_tbl').dataTable( {
		"columns": [
            { "title": "名称", "class": "center", sWidth: '8%' },
            { "title": "代码", "class": "center", sWidth: '5%' },
            { "title": "实施时间", "class": "center", sWidth: '5%' },
            { "title": "解禁时间", "class": "center", sWidth: '5%' },
            { "title": "定增价格", "class": "dt-body-right", sWidth: '5%' },
            { "title": "定增总金额(亿)", "class": "dt-body-right", sWidth: '5%' },
            { "title": "最新价格", "class": "dt-body-right", sWidth: '5%' },
            { "title": "最新涨幅", "class": "dt-body-right", sWidth: '5%' },
            { "title": "溢折率", "class": "dt-body-right", sWidth: '5%' },
			{ "title": "备注", "class": "dt-body-left", sWidth: '27%' },
			{ "title": "最新公告", "class": "dt-body-left", sWidth: '25%' }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": "./php/dz_data.php",
		"bFilter": true,
        "bInfo": true,
        "bSortable": true,
        "bAutoWidth": false,
        "bDestroy": true,
        "iDisplayLength": 25,
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

		if (getCookie("valid_dz_editor") == "true") {
            $('#addEditBtn').show();
            $('#addEditBtn').removeAttr("disabled");
            $('#addCommentBtn').show();
            $('#addCommentBtn').removeAttr("disabled");
            $('#deleteBtn').show();
            $('#deleteBtn').removeAttr("disabled");
		}
	} );

	function validateUser() {
		var cv = getCookie("valid_dz_editor");
		if (cv == "true") {
			return true;
		}
		var cv2 = getCookie("valid_dz_viewer");
		if (cv2 == "true") {
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
				window.location.href="dz_track.php";
			} else if(msg == "valid_dz_viewer"){
				setCookie("valid_dz_viewer", "true", 1);
				window.location.href="dz_track.php";
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

	function checkCode(target_page) {
		var cv = getCookie("valid_dz_editor");
		if (cv == "true") {
			window.location.href=target_page;
			return;
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
				window.location.href=target_page;
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

    	<div id="tbl_header">
        </div>
        <div id="tbl_content">
            <table id="dz_tbl" class="display" cellspacing="0" width="100%">
            </table>
        </div>
        <div id="editBtnDiv">
            <button onClick="javascript:checkCode('dz_input.html')"  hidden="hidden" disabled="disabled" id="addEditBtn">添加或修改记录</button> 
            &nbsp; &nbsp; 
            <button onClick="javascript:checkCode('dz_input_comments.html')" hidden="hidden" disabled="disabled" id="addCommentBtn">添加备注/最新公告</button>
            &nbsp; &nbsp; 
            <button onClick="javascript:checkCode('dz_delete.html')" hidden="hidden" disabled="disabled" id="deleteBtn">删除记录</button>
        </div>
        
        
    </div>
    <?php include("footer.html"); ?>
</body>
</html>
