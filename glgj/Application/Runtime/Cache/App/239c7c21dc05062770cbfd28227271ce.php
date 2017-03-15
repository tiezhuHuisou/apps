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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/release_lib.css"/>
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
    <!-- 发布表单 -->
    <form>
        <input type="hidden" name="id" value="<?php echo ($list[unordered]['id']); ?>">
        <input type="hidden" name="uid" value="<?php echo UID;?>">
        <!-- 地址 -->
        <div class="address_wrap">
            <input type="hidden" name="province_id" value="<?php echo ($list[unordered]['province_id']); ?>">
            <input type="hidden" name="city_id" value="<?php echo ($list[unordered]['city_id']); ?>">

            <input id='lib_address' class="city_select" type="text" name=""
                   value="<?php echo ($list['unordered']['provincename']); echo ($list['unordered']['cityname']); ?>"
                   placeholder="请选择创库地址"/>
        </div>
        <!-- 联系信息 -->
        <!--<div class="weui-cells__title">联系信息</div>-->
        <!--<div class="weui-cells">-->
        <!--&lt;!&ndash; 姓名 &ndash;&gt;-->
        <!--&lt;!&ndash;id&ndash;&gt;-->
        <!--<div class="weui-cell">-->
        <!--<div class="weui-cell__hd"><label class="weui-label">联系人</label></div>-->
        <!--<div class="weui-cell__bd">-->
        <!--<input class="weui-input" type="text" name="" value="">-->
        <!--</div>-->
        <!--</div>-->
        <!--&lt;!&ndash; 电话 &ndash;&gt;-->
        <!--<div class="weui-cell">-->
        <!--<div class="weui-cell__hd">-->
        <!--<label class="weui-label">联系电话</label>-->
        <!--</div>-->
        <!--<div class="weui-cell__bd">-->
        <!--<input class="weui-input" type="number" name="" value="" />-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <div class="weui-cells__title">仓库属性</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">仓储类型</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="category_id">
                        <?php if(is_array($list['depotCategory'])): foreach($list['depotCategory'] as $key=>$vo): ?><option <?php if(($list['unordered']['category_id']) == $key): ?>selected<?php endif; ?> value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">建筑标准</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="standard">
                        <?php if(is_array($list['depotStandard'])): foreach($list['depotStandard'] as $key=>$vo): ?><option <?php if(($list['unordered']['standard']) == $vo): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">仓内运营</label>
                </div>
                <div class="weui-cell__bd">
                    <label><input class="weight_select" type="radio" name="run" value="有代运营" />有代运营</label>
                    <label><input class="weight_select" type="radio" name="run" value="无代运营" checked/>无代运营</label>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">库内面积</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">主体结构</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="structure">
                        <?php if(is_array($list['depotStructure'])): foreach($list['depotStructure'] as $key=>$vo): ?><option <?php if(($list['unordered']['structure']) == $vo): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">可做分仓</label>
                </div>
                <div class="weui-cell__bd">
                    <label><input class="weight_select" type="radio" name="issub" value="可做分仓" />可做分仓</label>
                    <label><input class="weight_select" type="radio" name="issub" value="不可做分仓" checked/>不可做分仓</label>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
        </div>
        <div class="weui-cells__title">仓库信息</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">价格</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="price" value="<?php echo ($list['unorderod']['price']); ?>" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <p>元/m&sup2;/天</p>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">舱内高度</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="height" value="<?php echo ($list['unordered']['height']); ?>" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <p>米</p>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">总面积</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <p>m&sup2;</p>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">可租面积</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="area" value="<?php echo ($list['unordered']['area']); ?>" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <p>m&sup2;</p>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">详细地址</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="address" value="<?php echo ($list['unordered']['address']); ?>"/>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">图片上传</label></div>
                <div class="weui-cell__bd">
                    <div class="weui-uploader__input-box">
                        <input id="uploaderInput" class="weui-uploader__input" type="file"
                               accept="image/gif,image/jpg,image/png" multiple="" name="image">
                        <!-- 图片上传 -->
                        <?php if(is_array($list['unordered']['image'])): foreach($list['unordered']['image'] as $key=>$vo): ?><img class="upload_img" src="<?php echo ($vo); ?>"/><?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
            <div class="weui-cell">
                <button type="button" class="weui-btn weui-btn_primary">提交</button>
            </div>
        </div>
    </form>
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
    var city_name = "<?php echo ($city_name); ?>";
    var defaultImg = './Public/static/images/default_icon.jpg';
    var imgUrl = './Public/App/images';
</script>
<!-- 地址选择器 -->
<script type="text/javascript" src="./Public/App/js/city-picker.min.js" charset="utf-8"></script>
<script type="text/javascript" src="./Public/App/js/jquery-weui.min.js" charset="utf-8"></script>
<script type="text/javascript" src="./Public/App/js/release_lib.js"></script>
</body>
</html>