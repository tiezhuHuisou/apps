<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo ($site_title); ?></title>
<link rel="stylesheet" type="text/css" href="./Public/Admin/css/base.css" />
<link rel="stylesheet" type="text/css" href="./Public/static/check/check.css">
<script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./Public/Admin/js/base.js"></script>
<script type="text/javascript" src="./Public/static/layer/layer.js"></script>
<script type="text/javascript" src="./Public/static/js/common.js"></script>
<script type="text/javascript" src="./Public/static/check/check.js"></script>
<script type="text/javascript" src="./Public/static/js/check.js"></script>
	<link rel="stylesheet" type="text/css" href="./Public/Admin/css/login.css" />
</head>
<body>
	<!-- 顶部 开始 -->
	<div class="top"></div>
	<!-- 顶部 结束 -->
	<!-- 主体 开始 -->
	<div class="login_main">
		<!-- 左侧 -->
		<div class="left fl">
			<div class="logo">
				<img src="./Public/Admin/images/appmanage.png" width="150" height="50" />
				<h1>中国APP行业应用注册中心</h1>
				<p>授权给</p>
				<img src="./Public/Admin/images/login_logo.png" height="50" />
			</div>
		</div>
		<!-- 右侧 -->
		<div class="right fl">
			<div class="login">
				<!-- 锁图标 -->
				<i class="locks icon"></i>
				<!-- 登陆标题 -->
				<div class="login_title">
					<i class="key icon fl"></i>
					<span class="fl">登陆</span>
					<div class="clear"></div>
				</div>
				<!-- 输入框 -->
				<form class="form-horizontal" name="" action="" method="post">
					<div class="row">
						<p class="row_left">用户名</p>
						<label><input class="text" type="text" name="username" /></label>
						<div class="clear"></div>
					</div>
					<div class="row">
						<p class="row_left">密&nbsp;&nbsp;&nbsp;码</p>
						<label><input class="text" type="password" name="password" /></label>
						<div class="clear"></div>
					</div>
					<div class="row">
						<p class="row_left"></p>
						<label><input class="submit icon ajax-post" target-form="form-horizontal" type="submit" value="登陆" /></label>
						<div class="clear"></div>
					</div>
				</form>
				<!-- 提示信息 -->
				<div class="notice">
					<p class="notice_title">注意：</p>
					<div class="fl">
						<p> 1、不要在公共场合保存登录信息。</p>
						<p>2、尽量避免多人使用同一帐号。</p>
						<p>3、为保证您的帐号安全，退出系统时请注销登录。</p>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- 主体 结束 -->
</body>
</html>