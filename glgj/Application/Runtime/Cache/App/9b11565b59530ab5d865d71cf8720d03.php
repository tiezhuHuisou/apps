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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/companyListArea.css"/>
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
        <!-- 头部导航开始 -->
        <div class="weui-navbar">
            <div class="weui-navbar__item">
                物<span>流分</span>类
            </div>
            <div class="weui-navbar__item active">
                地<span>区分</span>类
            </div>
        </div>
        <!-- 头部导航结束 -->
        <!-- 中间地区选择 -->
        <div class="area">
            <div class="left_area">
                <ul>
                    <li class="active">北京</li>
                    <li>北京</li>
                </ul>
            </div>
            <div class="right_area">
                <div class="weui-flex">
                    <div class="weui-flex__item"><a href="javascript:void(0);">北京</a></div>
                    <div class="weui-flex__item"><a href="javascript:void(0);">北京北京</a></div>
                    <div class="weui-flex__item"><a href="javascript:void(0);">北京北京</a></div>
                </div>
            </div>
        </div>
        <!-- 中间地区选择 -->
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
    <script type="text/javascript" src="./Public/App/js/companyListArea.js"></script>
</body>
</html>