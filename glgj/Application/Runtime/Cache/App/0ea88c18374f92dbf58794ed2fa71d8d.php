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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/company_collect.css"/>
</head>
<body>
<!-- 头部 开始-->
<header>
    <a class="header_left" href="javascript:history.go(-1);">
        <div class="lt">
            <div></div>
        </div>
    </a>
    <p class="header_title"><?php echo ($site_title); ?></p>
    <a class="header_right" href="javascript:void(0);">编辑</a>
</header>
<!-- 头部 结束-->
<!-- 可视区域 开始 -->
<section class="company_collect_wrapper no_footer">
    <!-- 收藏公司列表 -->
    <!-- 列表页 -->
    <?php if(!empty($list['ordered'])): if(is_array($list['ordered'])): $i = 0; $__LIST__ = $list['ordered'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input id="classify" type="hidden" value="<?php echo ($vo['id']); ?>"/>
            <div class="company_collect_list">
                <!-- 筛选图标 -->
                <div class="unselected_icon"></div>
                <!--收藏列表-->
                <div class="list_detail">
                    <h4><?php echo ($vo['name']); ?></h4>
                    <p class="weui-media-box__desc">联系人：<span><?php echo ($vo['contact_user']); ?></span></p>
                    <p class="weui-media-box__desc">
                        <span class="phone">电话：<?php echo ($vo['telephone']); ?><a href="tel:<?php echo ($vo['telephone']); ?>"><img
                                src="./Public/App/images/dh.png"></a></span>
                        <span class="phone">手机：<?php echo ($vo['subphone']); ?><a href="tel:<?php echo ($vo['subphone']); ?>"><img
                                src="./Public/App/images/dh.png"></a></span>
                    </p>
                    <p class="weui-media-box__desc"><?php echo ($vo['address']); ?></p>
                </div>
                <!--收藏列表结束-->
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div><?php endif; ?>
    <!-- 公司列表 -->
    <?php if(empty($list['ordered'])): ?><!-- 收藏为空时 -->
        <div class="collect_empty_wrap" style="display: none;">
            <div class="common_empty">
                <img src="./Public/App/images/collect_empty.png" width="120" height="120"/>
                <p>您的<?php echo ($site_title); ?>还是空的哦</p>
                <a href="?g=app&m=company">去收藏</a>
            </div>
        </div><?php endif; ?>
</section>
<!-- 可视区域 结束 -->
<!-- 底部 开始 -->
<footer>
    <div class="unselected_icon allselected"></div>
    <span>全选</span>
    <button class="news_delete" href="javascript:void(0);">删除</button>
</footer>
<!-- 底部 结束-->
<script type="text/javascript" src="./Public/static/js/delay.js"></script>
<script type="text/javascript" src="./Public/App/js/company_collect.js"></script>
</body>
</html>