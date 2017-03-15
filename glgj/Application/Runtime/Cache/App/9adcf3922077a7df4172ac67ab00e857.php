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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/identity.css" />
</head>
<body>
    <?php if(($appsign) != "1"): ?><header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title mgr50"><?php echo ($site_title); ?></p>
    </header><?php endif; ?>

    <!-- 可视区域 开始 -->
    <section class="viewport">
        <div class="weui-cells">
            <a class="weui-cell" href="?g=app&m=member&a=identitySuccess">
                <div class="weui-cell__hd"><img src="./Public/App/images/v1.png" width="35" height="35"></div>
                <div class="weui-cell__bd">
                    <p>成为个体货主</p>
                </div>
                <div class="weui-cell__ft">
                    <span class="blue_38f">审核成功</span>
                    <div class="gt_btn"></div>
                </div>
            </a>
            <a class="weui-cell" href="?g=app&m=member&a=identitySubmit">
                <div class="weui-cell__hd"><img src="./Public/App/images/v2.png" width="35" height="35"></div>
                <div class="weui-cell__bd">
                    <p>成为公司货主</p>
                </div>
                <div class="weui-cell__ft">
                    <span class="gray_999">审核中</span>
                    <div class="gt_btn"></div>
                </div>
            </a>
            <a class="weui-cell" href="?g=app&m=member&a=identityLose">
                <div class="weui-cell__hd"><img src="./Public/App/images/v3.png" width="35" height="35"></div>
                <div class="weui-cell__bd">
                    <p>个体车主</p>
                </div>
                <div class="weui-cell__ft">
                    <span class="gray_999">审核失败</span>
                    <div class="gt_btn"></div>
                </div>
            </a>
            <a class="weui-cell" href="?g=app&m=member&a=applyComTruck">
                <div class="weui-cell__hd"><img src="./Public/App/images/v4.png" width="35" height="35"></div>
                <div class="weui-cell__bd">
                    <p>公司车主</p>
                </div>
                <div class="weui-cell__ft">
                    <div class="gt_btn"></div>
                </div>
            </a>
            <a class="weui-cell" href="?g=app&m=member&a=applyDepot">
                <div class="weui-cell__hd"><img src="./Public/App/images/v5.png" width="35" height="35"></div>
                <div class="weui-cell__bd">
                    <p>仓储主</p>
                </div>
                <div class="weui-cell__ft">
                    <div class="gt_btn"></div>
                </div>
            </a>
        </div>
    </section>
    <!-- 可视区域 结束 -->
    <!-- 底部 开始 -->
    	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
    <!-- 底部 结束 -->
    <script type="text/javascript">
        var city_name  = "<?php echo ($city_name); ?>";
        var defaultImg = './Public/static/images/default_icon.jpg';
        var imgUrl     = './Public/App/images';
    </script>
</body>
</html>