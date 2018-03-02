function data_loading(tbl, team) {
      $('#'+tbl).dataTable( {
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
			{ "title": "原总积分", "class": "center" }
        ],
		"sAjaxDataProp": "data",
		"sAjaxSource": "../php/zs_data.php?team="+team,
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
					url: "../php/zs_max_round.php?team="+team,
					type: "GET",           
					dataType: "html"
				});
		 
				request.done(function(msg) {
					$('#tbl_header').html(msg);         
				});
		 
				request.fail(function(jqXHR, textStatus) {
					$('#tbl_header').html("<h3>中暑山庄最强操盘手第"+team+"组赛况表"); 
				});
	} 