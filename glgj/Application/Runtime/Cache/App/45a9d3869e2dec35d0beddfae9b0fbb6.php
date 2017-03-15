<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="wap-font-scale" content="no" />
	<meta name="Keywords" content="<?php echo ($site_keywords); ?>" />
	<meta name="Description" content="<?php echo ($site_description); ?>" />
	<title><?php echo ($site_title); ?></title>
	<link rel="stylesheet" type="text/css" href="./Public/static/css/base_red.css" />
	<link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-weui/1.0.1/css/jquery-weui.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/find.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/product_index.css"/>
</head>
<body>
<?php if(($appsign) != "1"): ?><header>
    <a class="header_left" href="javascript:history.go(-1);">
        <div class="lt">
            <div></div>
        </div>
    </a>
    <p class="header_title"><?php echo ($site_title); ?></p>
    <div class="top_nav"></div>
</header><?php endif; ?>
<!-- 可视区域 开始 -->
<section class="viewport">
    <!-- 头部公告开始 -->
    <div class="header">
        <div class="header_info">
            <div class="info_title">公告信息</div>
            <div class="info_content">
                <ul class="list">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a href="?g=app&m=product&a=announceDetail&id=<?php echo ($vo["id"]); ?>"><?php echo ($vo["desc"]); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="more_info"><a href="?g=app&m=product&a=announce"> 更多公告>></a></div>
        </div>
    </div>
    <!-- 头部公告结束 -->
    <!-- 中间分类选择 -->
    <div class="weui-grids">
        <a href="?g=app&m=product&a=comapnyList" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="./Public/App/images/wlxx.png" alt="">
            </div>
            <p class="weui-grid__label">
                物流信息
            </p>
        </a>
        <a href="?g=app&m=product&a=truckList" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="./Public/App/images/hcxx.png" alt="">
            </div>
            <p class="weui-grid__label">
                货车信息
            </p>
        </a>
        <a href="?g=app&m=product&a=goodsList" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="./Public/App/images/hwxx.png" alt="">
            </div>
            <p class="weui-grid__label">
                货物信息
            </p>
        </a>
        <a href="?g=app&m=product&a=depotList" class="weui-grid js_grid">
            <div class="weui-grid__icon">
                <img src="./Public/App/images/ccxx.png" alt="">
            </div>
            <p class="weui-grid__label">
                仓储信息
            </p>
        </a>
    </div>
    <!-- 中间分类选择 -->
    <!-- 底部 开始 -->
    	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
    <!-- 底部 结束 -->
</section>
<script type="text/javascript" src="./Public/static/js/carousel.js"></script>
<script type="text/javascript" src="./Public/App/js/product_index.js"></script>
</body>
</html>