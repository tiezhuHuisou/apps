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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/goodsList.css"/>
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
    <!-- 列表页 -->
    <div class="weui-panel weui-panel_access">

        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="weui-panel__bd">
                <div href="?g=app&m=product&a=truckDetail&id=<?php echo ($vo["id"]); ?>" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" src="<?php echo ((isset($vo["head_pic"]) && ($vo["head_pic"] !== ""))?($vo["head_pic"]):'./Public/App/images/member_default.png'); ?>">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title"><?php echo ($vo["after"]); ?>--<?php echo ($vo["end"]); ?></h4>
                        <p class="weui-media-box__desc"><?php echo ($vo["name"]); ?></p>
                    </div>

                    <a href="tel:<?php echo ($vo["phone"]); ?>">
                        <div class="tele">
                            <img src="./Public/App/images/dh.png">
                            <span>联系TA</span>
                        </div>
                    </a>
                </div>
            </div>
            <div class="weui-panel__bd">
                <a href="?g=app&m=product&a=truckDetail&id=<?php echo ($vo["id"]); ?>">
                    <div href="?g=app&m=product&a=truckDetail&id=<?php echo ($vo["id"]); ?>" class="weui-media-box weui-media-box_appmsg">
                        <div class="weui-media-box__bd">
                            <p class="weui-media-box__desc">
                                <span class="phone">车牌号：<?php echo ($vo["truck_no"]); ?></span>
                            </p>
                            <p class="weui-media-box__desc">车辆信息：<?php echo ($vo["source_type"]); echo ($vo["truck_length"]); ?></p>
                            <p class="weui-media-box__desc">车辆类型：<?php echo ($vo["truck_type"]); ?></p>
                        </div>
                        <div class="weui-cell__ft">
                            <div class="tl"></div>
                        </div>
                    </div>
                </a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
</section>
</body>
</html>