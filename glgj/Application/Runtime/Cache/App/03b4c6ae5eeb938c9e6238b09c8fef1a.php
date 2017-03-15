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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/release_truck.css"/>
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
    <form action="#" method="post">
        <input type="hidden" name="id" value="<?php echo ($list['info']['id']); ?>">
        <input type="hidden" name="uid" value="<?php echo UID;?>">
        <!-- 地址 -->
        <!--起始地省市-->
        <input type="hidden" name="provincen" value="<?php echo ($list['info']['provincen']); ?>">
        <input type="hidden" name="cityn" value="<?php echo ($list['info']['city']); ?>">
        <!--目的地省市-->
        <input type="hidden" name="provincen2" value="<?php echo ($list['info']['provincen2']); ?>">
        <input type="hidden" name="cityn2" value="<?php echo ($list['info']['city2']); ?>">

        <div class="address_wrap">
            <input id='start_city' class="city_select" type="text" name="after" value="<?php echo ($list['info']['provincename']); echo ($list['info']['cityname']); ?>"
                   placeholder="请选择起始地"/> <span class="rotate_icon"></span>
            <input id="end_city" class="city_select" type="text" name="end" value="<?php echo ($list['info']['provincename2']); echo ($list['info']['cityname2']); ?>" placeholder="请选择目的地">
        </div>
        <!-- 联系信息 -->
        <div class="weui-cells__title">联系信息</div>
        <div class="weui-cells">
            <!-- 姓名 -->
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">随车司机姓名</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="driver" value="<?php echo ($list['info']['driver']); ?>">
                </div>
            </div>
            <!-- 电话 -->
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">联系电话</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="driver_phone" value="<?php echo ($list['info']['driver_phone']); ?>"/>
                </div>
            </div>
        </div>
        <div class="weui-cells__title">车辆信息</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">车源类型</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="source_type">
                        <?php if(is_array($list['source_type'])): foreach($list['source_type'] as $key=>$vo): ?><option <?php if(($list['info']['source_type']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>

                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">车辆类型</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="truck_type">
                        <?php if(is_array($list['truck_type'])): foreach($list['truck_type'] as $key=>$vo): ?><option <?php if(($list['info']['truck_type']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>

                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">车辆数量</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="'truck_num'">
                        <?php if(is_array($list['truck_num'])): foreach($list['truck_num'] as $key=>$vo): ?><option <?php if(($list['info']['truck_num']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">车身长度</label>
                </div>
                <div class="weui-cell__bd">
                    <select class="weui-select" name="truck_length">
                        <?php if(is_array($list['truck_length'])): foreach($list['truck_length'] as $key=>$vo): ?><option <?php if(($list['info']['truck_length']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">车牌号码</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" name="truck_no" value="<?php echo ($list['info']['truck_no']); ?>">
                </div>
            </div>
        </div>
        <div class="weui-cells__title">载货信息</div>
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">可载轻货</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="light" value="<?php echo ($list['info']['light']); ?>" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <p>方</p>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">可载重货</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="heavy" value="<?php echo ($list['info']['heavy']); ?>" placeholder="请填写数字">
                </div>
                <div class="weui-cell__ft">
                    <label><input class="weight_select" type="radio" name="heavy_unit" value="吨" checked/>吨</label>
                    <label><input class="weight_select" type="radio" name="heavy_unit" value="公斤"/>公斤</label>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">途经地区</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" name="approach" value="<?php echo ($list['info']['approach']); ?>">
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">发车日期</label>
                </div>
                <div class="weui-cell__bd">
                    <input type="text" id='start_time' name="departure_time" class="time_select"/>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd">
                    <label class="weui-label">有效日期</label>
                </div>
                <div class="weui-cell__bd">
                    <input type="text" id='end_time' name="end_time" class="time_select"/>
                </div>
                <div class="weui-cell__ft">
                    <div class="select_icon"></div>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">备注</label></div>
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" name="content" placeholder="请输入备注" rows="3"><?php echo ($list['info']['content']); ?></textarea>
                    <div class="weui-textarea-counter"><span>0</span>/200</div>
                </div>
            </div>
            <div class="weui-cell">
                <button class="weui-btn weui-btn_primary">提交</button>
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
<script type="text/javascript" src="./Public/App/js/release_truck.js"></script>
</body>
</html>