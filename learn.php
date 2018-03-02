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
<?php
  chdir(dirname(__FILE__));
  require_once("./php/utils.php");
  app_log(DEBUG, "Access learn.php");
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
        
        <div id="site_info">
            <div id="memos">
                <article>
                <p>&#8226;&nbsp;&nbsp;请牢记多位名人前辈的警言：股市没有免费午餐。</p>
                <p>&#8226;&nbsp;&nbsp;如果你想在中国股市中成为赢家，最好先修炼自己的内功。参考书目：《千炼成妖》、《百战成精》、《操盘手2》、《操盘手1》。遇其中不懂的问题，请先自百度之。如若还是不懂，可以通过微博向作者<a href="http://weibo.com/hjhh" target="_blank">花荣</a>请教。</p>
                <p>&#8226;&nbsp;&nbsp;关注财经名博<a href="http://weibo.com/hjhh" target="_blank">花荣</a>、<a href="http://weibo.com/p/1005052436093373" target="_blank">金融侠女盈盈</a>等，并从其分享的经验和思路中学习、领悟，也是很好的学习手段。</p>
                <h4>花荣精彩语录</h4>
                <p>&#8226;&nbsp;&nbsp;许多投资者相比他们的资金投入来说，智力技能的投入明显不足。合格操盘手的出师，花费的精力不能次于高中生为高考所做的准备。</p>
                <p>&#8226;&nbsp;&nbsp;在沪深股市，技术面服从基本面，基本面服从主力面，主力面服从题材面，题材是第一生产力。</p>
                <p>&#8226;&nbsp;&nbsp;花氏股技浓缩：</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;1. 操作系统=线上做多+线下做空+仓位控制</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;2. 赢利模式=无风险套利+主趋势爆破点+人生赌注股</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;3. 个股评判原则=大盘+题材+量能+均线+MACD</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;4. 高概率爆破点=时间确定or价格确定</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;5. K线逻辑=超越+连续+反击+逆反</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;6. 专业阅历=千炼成妖+百战成精+操盘手</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;7. 炒股=自由+花天酒地</p>
                <p>&#8226;&nbsp;&nbsp;花氏万能公式：</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;评判个股的标准：大盘+题材+量能+均线+MACD+K线逻辑+克服心理障碍</p>
                <p>&#8226;&nbsp;&nbsp;股民中有无操作系统的差别: </p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;无操作系统的经典表现: </p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;1. 极左或极右的逆反市场趋势且一再挨大嘴巴还嘴硬，一朝被蛇咬十年怕井绳</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;2. 涨也急跌也急，热锅蚂蚁，永动机中的战斗机，白天装股神晚上内心流血，赚小钱赔大钱</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;有操作系统的人: </p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;该空仓时空仓，该重仓时重仓，稳如泰山动如脱兔，快乐投资</p>
                </article>
            </div>
            
            <div id="vedios">
                <article>
                <h4>花荣视频集锦</h4>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjYwNDA3NzI4.html" target="_blank">财经名人堂对话花荣</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwNTg0.html" target="_blank">花荣谈选股01</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwNjQ0.html" target="_blank">花荣谈选股02</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwNzA4.html" target="_blank">花荣谈选股03</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XNDYwOTk2ODUy.html" target="_blank">花荣股市实战心得1</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XNDY0NzEzNjMy.html" target="_blank">花荣股市实战心得2</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XNDY1MjA4NzM2.html" target="_blank">花荣股市实战心得3</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XNDY3NjIwMzAw.html" target="_blank">花荣股市实战心得4</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XNDY3OTE4NjE2.html" target="_blank">花荣股市实战心得5</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjYwNDAyMTI0.html" target="_blank">花荣：不死鸟的股市生存法则</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMTcyNzA3Njk2.html" target="_blank">花荣讲解《套利狐狸》</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMTcyNzA2NjI0.html" target="_blank">花荣讲解《百战狐狸》</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjM0MjYyNjI0.html" target="_blank">花荣谈资金管理01</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjM0MjY0MjU2.html" target="_blank">花荣谈资金管理02</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwOTY0.html" target="_blank">花荣：看清大势01</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMxMTYw.html" target="_blank">花荣：看清大势02</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMxMjA0.html" target="_blank">花荣：看清大势03</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwODQ0.html" target="_blank">花荣谈资产管理01</a></p>
                <p>&#8226;&nbsp;&nbsp;<a href="http://v.youku.com/v_show/id_XMjMyMzMwODky.html" target="_blank">花荣谈资产管理02</a></p>
                </article>
            </div>
        </div>
    </div>
    <?php include("footer.html"); ?>
</body>
</html>