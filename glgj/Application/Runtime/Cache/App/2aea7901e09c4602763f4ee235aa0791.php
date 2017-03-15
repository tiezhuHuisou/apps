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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/list_lib.css"/>
</head>
<body>
<?php if(($appsign) != "1"): ?><header>
    <a class="header_left" href="javascript:history.go(-1);">
        <div class="lt"><div></div></div>
    </a>
    <p class="header_title"><?php echo ($site_title); ?></p>
    <a class="editor_btn header_right -mob-editor-open" href="javascript:void(0);" style="color: white;">编辑</a>
</header><?php endif; ?>
<!-- 可视区域 开始 -->
<section class="viewport">
    <?php if(!empty($list)): ?><!-- 列表 -->
        <div class="list_wrap">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="?g=app&m=member&a=publishDepot&id=<?php echo ($vo["id"]); ?>">
                    <div class="mgt10 weui-form-preview">
                        <div class="weui-form-preview__hd">
                            <em class="weui-form-preview__value"><?php echo ($vo['location']); ?></em>
                        </div>
                        <div class="weui-form-preview__bd">
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">详细地址</label>
                                <span class="weui-form-preview__value"><?php echo ($vo['address']); ?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">面积</label>
                                <span class="weui-form-preview__value"><?php echo ($vo['area']); ?>平方米</span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">建筑标准</label>
                                <span class="weui-form-preview__value"><?php echo ($vo['standard']); ?></span>
                            </div>
                        </div>
                    </div>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="weui-loadmore">
            <i class="weui-loading"></i>
            <span class="weui-loadmore__tips">正在加载</span>
        </div>
        <?php else: ?>

        <!-- 为空 -->
        <div class="weui-loadmore weui-loadmore_line">
            <span class="weui-loadmore__tips">暂无数据</span>
        </div><?php endif; ?>
</section>
<!-- 可视区域 结束 -->
<!-- 底部 开始 -->
<a class="editor_footer" href="?g=app&m=member&a=publishdepot">新增仓储</a>
<!-- 底部 结束 -->
<script type="text/javascript">
    var city_name = "<?php echo ($city_name); ?>";
    var defaultImg = './Public/static/images/default_icon.jpg';
    var imgUrl = './Public/App/images';
</script>
<!-- 地址选择器 -->
<script type="text/javascript" src="./Public/App/js/list_depot.js"></script>
</body>
</html>