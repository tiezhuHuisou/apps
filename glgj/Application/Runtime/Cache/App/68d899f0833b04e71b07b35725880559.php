<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
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
	<link rel="stylesheet" type="text/css" href="./Public/App/css/login.css" />
</head>
<body>
    <!-- 头部 开始 -->
    <?php if(($appsign) != "1"): ?><header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title mgr50"><?php echo ($site_title); ?></p>
    </header><?php endif; ?>

    <!-- 头部 结束 -->
    <!-- 主体内容 开始 -->
    <section class="login_wrapper no_footer">
        <form>
            <!-- 账号、密码输入框 -->
            <div class="login_content">
                <!-- 账号 -->
                <p class="login_item login_content_phone">
                    <i class="login_content_phone_icon"></i>
                    <input class="login_content_phone_input" type="tel" name="mphone"  placeholder="请输入手机号码" />
                </p>
                <!-- 密码 -->
                <p class="login_item login_content_password">
                    <i class="login_content_password_icon"></i>
                    <input class="login_content_password_input" type="password" name="password" placeholder="请输入密码" />
                    <span class="login_content_password_see nosee"></span>
                </p>
            </div>
            <!-- 登陆按钮 -->
            <div class="login_btn_wrap">
                <a class="login_btn " href="javascript:void(0);">登陆</a>
            </div>
        </form>
        <!-- 忘记密码按钮 -->
        <div class="forget_btn_wrap clearfix">
            <a class="register" href="?g=app&m=register">注册账号</a>
            <a class="forget_btn" href="?g=app&m=register&a=forgotten">忘记密码？</a>
        </div>
       
    </section>
    <!-- 第三方登录 -->
    <div class="login_three">
        <div class="login_three_info">
            <p>第三方账号登录</p>
        </div>
        <div class="login_three_type">
            <a class="qq" href="javascript:void(0);">
                <img src="./Public/App/images/qq.png">
            </a>
            <a class="weixin" href="javascript:void(0);">
                <img src="./Public/App/images/weixin.png">
            </a>
        </div>
    </div>
    <!-- 主体内容 结束 -->
    <script type="text/javascript" src="./Public/App/js/login.js"></script>
</body>
</html>