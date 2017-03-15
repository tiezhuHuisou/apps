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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/release_source.css" />
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
            <input type="hidden" name="id" value="<?php echo ($list['unordered']['id']); ?>">
            <input type="hidden" name="uid" value="<?php echo UID;?>">
            <!-- 地址 -->
            <!--起始地省市-->
            <input type="hidden" name="provincen" value="<?php echo ($list['unordered']['provincen']); ?>">
            <input type="hidden" name="cityn" value="<?php echo ($list['unordered']['city']); ?>">
            <!--目的地省市-->
            <input type="hidden" name="provincen2" value="<?php echo ($list['unordered']['provincen2']); ?>">
            <input type="hidden" name="cityn2" value="<?php echo ($list['unordered']['city2']); ?>">

            <!-- 地址 -->
            <div class="address_wrap">
                <input id='start_city' class="city_select" type="text" name="" value="<?php echo ($list['unordered']['provincename']); echo ($list['unordered']['cityname']); ?>" placeholder="请选择起始地" /> <span class="rotate_icon"></span>
                <input id="end_city" class="city_select" type="text" name="" value="<?php echo ($list['unordered']['provincename']); echo ($list['unordered']['cityname']); ?>" placeholder="请选择目的地">
            </div>
            <!-- 联系信息 -->
            <div class="weui-cells__title">联系信息</div>
            <div class="weui-cells">
                <!-- 姓名 -->
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">收货人姓名</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" name="receive_name" value="<?php echo ($list['unordered']['receive_name']); ?>">
                    </div>
                </div>
                <!-- 电话 -->
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">联系电话</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" name="receive_phone" value="<?php echo ($list['unordered']['receive_phone']); ?>" />
                    </div>
                </div>
            </div>
            <div class="weui-cells__title">车辆信息</div>
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">车长要求</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="truck_length">
                            <?php if(is_array($list['truck_length'])): foreach($list['truck_length'] as $key=>$vo): ?><option <?php if(($list['unordered']['truck_length']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>

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
                            <?php if(is_array($list['truck_type'])): foreach($list['truck_type'] as $key=>$vo): ?><option <?php if(($list['unordered']['truck_type']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
                <!--<div class="weui-cell">-->
                    <!--<div class="weui-cell__hd">-->
                        <!--<label class="weui-label">车源类型</label>-->
                    <!--</div>-->
                    <!--<div class="weui-cell__bd">-->
                        <!--<select class="weui-select" name="">-->
                            <!--<?php if(is_array($list['source_type'])): foreach($list['source_type'] as $key=>$vo): ?>-->
                                <!--<option <?php if(($list['info']['source_type']) == $val): ?>selected<?php endif; ?> value="<?php echo ($vo); ?>"><?php echo ($vo); ?></option>-->
                            <!--<?php endforeach; endif; ?>-->
                        <!--</select>-->
                    <!--</div>-->
                    <!--<div class="weui-cell__ft">-->
                        <!--<div class="select_icon"></div>-->
                    <!--</div>-->
                <!--</div>-->
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">运输方式</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="transport_id">
                            <?php if(is_array($list['goodsTransport'])): foreach($list['goodsTransport'] as $key=>$vo): ?><option <?php if(($list['unordered']['goodsTransport']) == $val): ?>selected<?php endif; ?> value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
            </div>
            <div class="weui-cells__title">载货信息</div>
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">货物名称</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" name="name" value="<?php echo ($list['unordered']['name']); ?>" />
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">货物类型</label>
                    </div>
                    <div class="weui-cell__bd">
                        <select class="weui-select" name="category_id">
                            <?php if(is_array($list['goodsType'])): foreach($list['goodsType'] as $key=>$vo): ?><option <?php if(($list['unordered']['category_id']) == $val): ?>selected<?php endif; ?> value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">运费意向</label>
                    </div>
                    <div class="weui-cell__bd">
                         <input class="weui-input" type="number" name="freight_price" value="<?php echo ($list['unordered']['freight_price']); ?>" placeholder="请填写数字">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">货物体积</label>
                    </div>
                    <div class="weui-cell__bd">
                    <input class="weui-input" type="number" name="volume" value="<?php echo ($list['unordered']['volume']); ?>" placeholder="请填写数字">
                    </div>
                    <div class="weui-cell__ft">
                        <p>方</p>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">货物重量</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="number" name="weight" value="<?php echo ($list['unordered']['weight']); ?>" placeholder="请填写数字">
                    </div>
                    <div class="weui-cell__ft">
                            <label><input class="weight_select" type="radio" name="weight_unit" value="吨" checked />吨</label>
                        <label><input class="weight_select" type="radio" name="weight_unit" value="公斤" />公斤</label>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">发货日期</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" id='start_time' class="time_select" name="deliver_time" value="<?php echo ($list['unordered']['deliver_time']); ?>"/>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">截止日期</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" id='end_time' class="time_select" name="end_time" value="<?php echo ($list['unorder']['end_time']); ?>"/>
                    </div>
                    <div class="weui-cell__ft">
                        <div class="select_icon"></div>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">图片上传</label></div>
                    <div class="weui-cell__bd">
                        <div class="weui-uploader__input-box">
                            <input id="uploaderInput" class="weui-uploader__input" type="file" accept="image/gif,image/jpg,image/png" multiple="">
                            <!-- 图片上传 -->
                            <img class="upload_img" src="" />
                        </div>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">货物说明</label></div>
                    <div class="weui-cell__bd">
                        <textarea class="weui-textarea" placeholder="请输入货物说明" rows="3" name="remark"></textarea>
                        <div class="weui-textarea-counter"><span><?php echo ($list['unorder']['remark']); ?></span>/200</div>
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
        var city_name  = "<?php echo ($city_name); ?>";
        var defaultImg = './Public/static/images/default_icon.jpg';
        var imgUrl     = './Public/App/images';
    </script>
    <!-- 地址选择器 -->
    <script type="text/javascript" src="./Public/App/js/city-picker.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="./Public/App/js/jquery-weui.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="./Public/App/js/release_source.js"></script>
</body>
</html>