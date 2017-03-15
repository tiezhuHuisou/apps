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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css">
    <link rel="stylesheet" type="text/css" href="./Public/App/css/company_detail.css" />
</head>
<body>
    <!-- 头部 开始 -->
    <?php if(($appsign) != "1"): ?><header>
    <a class="header_left" href="javascript:history.go(-1);">
        <div class="lt">
            <div></div>
        </div>
    </a>
    <p class="header_title"><?php echo ($site_title); ?></p>
    <div class="top_nav"></div>
</header><?php endif; ?>
    <!-- 头部 结束 -->
    <!-- 主体内容 -->
    <section class="company_detail_wrapper <?php if(($appsign) != "1"): ?>no_footer<?php endif; ?>">
        <!-- 顶部 -->
        <div class="company_detail_top">
            <!-- 顶部图片 开始 -->
            <img class="delay" src="./Public/App/images/pixel.gif" data-echo="./Public/App/images/company_top_bg.jpg" width="100%" height="auto" />
            <!-- 顶部图片 结束 -->
            <div class="company_detail_top_pn">
                <!-- 顶部头像 开始 -->
                <a class="company_detail_top_sculpture" href="javascript:void(0);">
                    <img class="delay" src="./Public/App/images/pixel.gif" data-echo="<?php echo ($company_info['logo']); ?>" width="70" height="70" />
                    <!-- 顶部头像 结束 -->
                </a>
                <!-- 顶部昵称 开始 -->
                <p class="company_detail_top_nickname"><?php echo ($company_info['name']); ?>23424234</p>
                <p class="company_detail_top_nickname">23424234</p>
                <!-- 顶部昵称 结束 -->
                <input id="nid" type="hidden" value="<?php echo ($company_info["id"]); ?>" />
                <input id="uid" type="hidden" value="<?php echo ($user_id); ?>" />
                <input id="uuid" type="hidden" value="<?php echo ($_GET['uuid']); ?>" />
                <input id="sign" type="hidden" value="<?php echo ($sign); ?>" />
                <!-- <div class="collect <?php if(($sign) == "1"): ?>collected<?php endif; ?>"></div> -->
            </div>
        </div>
        <!-- 顶部 -->
        <!-- 头部导航 -->
        <div class="weui-tab">
            <div class="weui-navbar">
                <a href="javascript:;" class="weui-tabbar__item">
                    <div class="weui-tabbar__icon">
                        <img src="./Public/App/images/gsxq_nomal.png" alt="">
                    </div>
                    <p class="weui-tabbar__label">公司详情</p>
                </a>
                <a href="javascript:;" class="weui-tabbar__item">
                    <div class="weui-tabbar__icon">
                        <img src="./Public/App/images/wd_nomal.png" alt="">
                    </div>
                    <p class="weui-tabbar__label">全部网点</p>
                </a>
                <a href="javascript:;" class="weui-tabbar__item">
                    <div class="weui-tabbar__icon">
                        <img src="./Public/App/images/lxwd_nomal.png" alt="">
                    </div>
                    <p class="weui-tabbar__label">联系网点</p>
                </a>
            </div>
        </div>
        <!-- 头部导航结束 -->
        <!-- 公司信息、联系方式 -->
        <div class="company_detail_ic">
            <a class="company_detail_info company_detail_link" href="?g=app&m=company&a=info&id=<?php echo ($company_info['id']); if(($appsign) == "1"): ?>&appsign=1<?php endif; ?>">
                <p class="company_detail_info_title company_detail_link_title">公司信息</p>
            </a>
            <div class="company_detail">        hdfjgjdklfgjdfklgjkldfsjklgjialjgklsdjgkldsjgklsdjgkldjfgkljdfkljgkldjgkldfjgkdlfjklgj
            </div>
        </div>
    </section>
    <script type="text/javascript" src="./Public/static/js/delay.js"></script>
    <script type="text/javascript" src="./Public/App/js/company_detail.js"></script>
</body>
</html>