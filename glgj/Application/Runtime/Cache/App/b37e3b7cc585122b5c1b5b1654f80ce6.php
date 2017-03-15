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
		<link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css" />
		<link rel="stylesheet" type="text/css" href="./Public/App/css/opinion.css" />
	</head>

	<body>
		<!-- 头部 -->
		<header>
			<a class="header_left" href="javascript:history.go(-1);">
				<div class="lt">
					<div></div>
				</div>
			</a>
			<p class="header_title"><?php echo ($site_title); ?></p>
			<a class="header_right f15252" href="javascript:void(0);">发送</a>
		</header>
		
		<div class="content">
			<p class="hint">您想咨询或留言的内容</p>
			<div class="weui-cells weui-cells_form">
				<div class="weui-cell">
					<div class="weui-cell__bd">
						<textarea class="weui-textarea" placeholder="请输入您想告诉我们的信息" rows="8"></textarea>
					</div>
				</div>
			</div>
			<p class="hint">您的手机号、邮箱或QQ</p>
			<div class="weui-cells">
				<div class="weui-cell">
					<div class="weui-cell__bd">
						<input class="weui-input" type="text" placeholder="方便我们给您回复">
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="./Public/static/js/delay.js"></script>
		<script type="text/javascript" src="./Public/static/js/layer.js"></script>
		<script type="text/javascript" src="./Public/App/js/opinion.js"></script>
	</body>

</html>