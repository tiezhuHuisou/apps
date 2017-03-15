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
	<link rel="stylesheet" type="text/css" href="./Public/App/css/invite_friends.css" />
</head>
<body>
	<!-- 头部 -->
    <?php if(($appsign) != "1"): ?><header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title mgr50"><?php echo ($site_title); ?></p>
    </header><?php endif; ?>

    
	<img src="./Public/App/images/组-20@3x.png" width="100%" height="100%"/>
	<a href="##" class="btn_img">
		<img src="./Public/App/images/按钮@3x.png" width="100%" height="100%"/>
	</a>
	
    <!-- 底部 结束 -->
    <script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
    <!--<script type="text/javascript" src="./Public/static/js/invite_friends.js"></script>-->
</body>
</html>