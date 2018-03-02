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
<?php
  chdir(dirname(__FILE__));
  require_once("./php/utils.php");
  app_log(DEBUG, "Access advanced_search.php");
?>
</head>

<body>
	<div id="wrapper">
        <div id="site_info">
        <article>
            <p><a href="index.php">返回首页</a></p>
            <h2>技术指标选股功能介绍</h2>
            <p>永远的第一条：风险提示。该选股系统主要用于业余娱乐、学习交流，请勿用本系统的选股功能进行实际交易，否则风险自负。</p>
            <p>只要你的选股条件可以用数据量化，本系统都有可能来根据你的条件来进行选股。</p>
            <p>目前支持的技术指标有：成交量、价格均线、MACD、KDJ。即将支持根据融资融券数据进行选股的功能。</p>
            <p>由于是程序选股，比较难处理模糊条件，比较擅长处理数据逻辑关系明晰的条件。</p>
            <p>比如容易做到的条件有：</p>
            <p>&raquo; 成交量：</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;成交量比前一交易日大，并且大于5日平均成交量的1.5倍。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;5日成交量均线金叉10日成交量均线。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;净买单大于净卖单。说明：本系统统计净买/卖单为某个交易日全部主动性买入/卖出的总和，相比于绝大部分的软件按照一分钟价格上涨/下跌来将该分钟成交量全部计算入净买单/卖单，可能更精确？</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;目前系统支持的成交量均线有5日、10日、20日、30日、60日，如果你需要其他N日均线，也可以做到。</p>
            <p>&raquo; 价格均线：</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;某天价格创N日新高。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;5日均价金叉10日均价。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;均线收敛：P1日均线与P2日均线的差值小于x%, Pn日均线与Pm日均线的差值小于y% 等。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;在N天内，P1均线连续金叉P2、P3、P4均线 等。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;目前系统支持的价格均线有5日、10日、20日、30日、60日，，如果你需要其他N日均线，也可以做到。</p>
            <p>&raquo; MACD：</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;MACD金叉。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;红柱放大。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;绿柱缩小。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;DIFF、DEA、MACD数值符合某某条件。</p>
            <p>&raquo; KDJ：</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;KDJ金叉。</p>
            <p>&nbsp;&nbsp;&#8226;&nbsp;&nbsp;K值、D值、KDJ数值符合某某条件。</p>
            <p>&raquo; 以上条件的任意组合</p>
            <p>比如不太容易做到的条件有：</p>
            <p>&raquo;&nbsp;&nbsp;底部堆量。--请定义底部标准？堆量的衡量方法？</p>
            <p>&raquo;&nbsp;&nbsp;MACD良性。--请问何为良性？金叉？红柱放大？抑或是绿柱缩小也算？</p>
            <p>&raquo;&nbsp;&nbsp;突破长期整理平台，股价开始放量突破！--哇！这听起来真不错！请问能否用数据来量化衡量？比如长期为多久？整理平台的上下限波动区间是多少？成交量达到什么条件算放量？突破多少算有效？如果可以，请告诉您的标准；如果不能，还是得麻烦您挨个浏览人工选择。祝你好运！</p>
            <p><br></p>
            <p>关于数据精确度问题的说明：本系统的数据全部来自于公开信息，如各大财经网站、上交所、深交所，但是极其偶尔会出现极端数据遗漏问题，但是经过本人比对大部分主流软件，最终处理结果基本一致。目前发现的问题有：部分股票的开盘第一分钟内瞬间最高价有遗漏情况（比例小于0.1%），那么该数据的偏差便可能会引起KDJ指标的波动，如果您对此敏感，敬请留意！</p>
            <p><br></p>
            <p>如果你有以上选股需求，可以先将需求整理为可以用技术指标量化的条件，通过<a href="http://weibo.com/u/1286936351" target="_blank">微博联系我</a>。如果你愿意公开你的选股绝招，可以<a href="http://weibo.com/u/1286936351" target="_blank">@王小栋</a>，如果暂时不想公开，可以私信我。由于本人目前还是上班族，业余时间有限，可能没有那么快回复，不便之处，敬请谅解。</p>
        </article>
        </div>
    </div>
    <?php include("footer.html"); ?>
</body>
</html>
