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
	<link rel="stylesheet" type="text/css" href="./Public/static/css/base_apps.css" />
    <link rel="stylesheet" type="text/css" href="./Public/App/css/news_detail.css" />
</head>
<body>
    <!-- 资讯标题、发布人、发布时间 -->
    <div class="detail_top">
        <h3>标题标题</h3>
        <div class="top_info">
            <span class="info_name">发布人</span>
            <span class="info_time">发布时间</span>
        </div>
    </div>
    <!-- 发布内容 -->
    <article><?php echo ($info['content']); ?></article>
    <script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="./Public/App/js/news_detail.js"></script>
</body>
</html>