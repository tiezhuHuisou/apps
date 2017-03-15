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
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2"></script>
    <link rel="stylesheet" type="text/css" href="./Public/static/kindeditor/themes/default/default.css" />
    <script type="text/javascript" src="./Public/static/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" src="./Public/static/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="./Public/Admin/js/alldel.js"></script>
</head>
<body>
    <!-- 顶部 开始 -->
    <div class="top">
	<table>
		<tr>
			<td width="200" rowspan="2" align="right" valign="middle">
				<div class="logo">
					<img src="./Public/Admin/images/logo.png" onerror="javascript:this.src='http://fe.huisouimg.com/appmanage/applogo/default/logo2.png'" width="160" height="auto" />
				</div>
			</td>
			<td class="system">
				<span class="yellow">[<span><?php echo (session('webname')); ?></span>管理平台]</span>
				<span class="welcome">欢迎：<strong class="username"><?php echo (session('name')); ?></strong>登陆</span>
				<a class="exit ajax-get" href="javascript:void(0);">[退出]</a>
				<a href="?g=admin&m=index">网站首页</a>
				<span class="spe">|</span>
				<a href="http://unionapp.org/" target="_blank">技术支持</a>
				<span class="spe">|</span>
				<a href="?g=admin&m=index&a=downfile">下载说明文档</a>
			</td>
		</tr>
		<tr>
			<td class="nav">
				<a <?php if(($site) == "home"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index">核心管理</a>
				<a <?php if(($site) == "news"): ?>class="hover"<?php endif; ?> href="?g=admin&m=news">资讯管理</a>
				<!--<a <?php if(($site) == "news"): ?>class="hover"<?php endif; ?> href="?g=admin&m=news">发布</a>-->
				<!--<a <?php if(($site) == "product"): ?>class="hover"<?php endif; ?> href="?g=admin&m=product">产品管理</a>-->
				<a <?php if(($site) == "product"): ?>class="hover"<?php endif; ?> href="?g=admin&m=product&a=truck">网站产品</a>
				<?php if((C("FLASHFLAG")) == "1"): ?><a <?php if(($site) == "order"): ?>class="hover"<?php endif; ?> href="?g=admin&m=order">订单管理</a>
					<a <?php if(($site) == "activity"): ?>class="hover"<?php endif; ?> href="?g=admin&m=activity">活动管理</a><?php endif; ?>
				<?php if(($distribution_flag) == "1"): ?><a <?php if(($site) == "distribution"): ?>class="hover"<?php endif; ?> href="?g=admin&m=distribution">分销管理</a><?php endif; ?>
				<a <?php if(($site) == "more"): ?>class="hover"<?php endif; ?> href="?g=admin&m=more">更多管理</a>
				<a <?php if(($site) == "member"): ?>class="hover"<?php endif; ?> href="?g=admin&m=member">会员管理</a>
				<a <?php if(($site) == "user"): ?>class="hover"<?php endif; ?> href="?g=admin&m=user">管理员管理</a>
				<div class="clear"></div>
			</td>
		</tr>
	</table>
</div>
    <!-- 顶部 结束 -->
    <!-- 左侧 开始 -->
    <div class="left">
	<ul>
		<?php if(($site) == "home"): ?><!-- 核心管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index">管理首页</a></li>
			<li <?php if(($left) == "core"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=core">核心设置</a></li>
			<?php if((C("FLASHFLAG")) == "1"): ?><li <?php if(($left) == "freight"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=freight">运费模板管理</a></li><?php endif; ?>
			<li <?php if(($left) == "regions"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=regions">省市区管理</a></li>
			<li><a  class="exit ajax-get" href="javascript:void(0);">退出登陆</a></li><?php endif; ?>
		<?php if(($site) == "news"): ?><!-- 资讯管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news">资讯列表</a></li>
			<li <?php if(($left) == "classify"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=classify">资讯分类</a></li>
			<!--<li <?php if(($left) == "circle"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=circle">行业圈管理</a></li>-->
			<!--<li <?php if(($left) == "circlecategory"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=circlecategory">行业圈分类</a></li>-->
			<li <?php if(($left) == "notice"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=notice">公告</a></li><?php endif; ?>
		<?php if(($site) == "product"): ?><!-- 产品管理-左侧 -->
			<!--<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product">产品管理</a></li>-->
			<!--<li <?php if(($left) == "classify"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=classify">产品分类</a></li>-->
			<!--<li <?php if(($left) == "need"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=need">求购管理</a></li>-->
			<li <?php if(($left) == "truck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=truck">货车管理</a></li>
			<li <?php if(($left) == "depot"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=depot">仓储管理</a></li>
			<li <?php if(($left) == "goods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=goods">货物管理</a></li><?php endif; ?>
		<?php if(($site) == "activity"): if((C("FLASHFLAG")) == "1"): ?><!-- 活动管理-左侧 -->
				<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity">优惠券设置</a></li>
				<li <?php if(($left) == "coupon"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=coupon">优惠券管理</a></li>
				<li <?php if(($left) == "recharge"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=recharge">充值活动设置</a></li>
				<li <?php if(($left) == "rechargerule"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=rechargerule">充值规则设置</a></li>
				<li <?php if(($left) == "flash"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=flash">限时抢购设置</a></li><?php endif; endif; ?>
		<?php if(($distribution_flag) == "1"): if(($site) == "distribution"): ?><!-- 产品管理-左侧 -->
				<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=distribution">分销设置</a></li>
				<li <?php if(($left) == "financialmanagement"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=distribution&a=financialmanagement">佣金提现管理</a></li><?php endif; endif; ?>
		<?php if(($site) == "order"): ?><!-- 订单管理-左侧 -->
			<li <?php if(($left) == "order"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order">订单列表</a></li>
			<li <?php if(($left) == "recharge"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order&a=recharge">充值订单</a></li>
			<?php if((C("FLASHFLAG")) == "1"): ?><li <?php if(($left) == "withdrawals"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order&a=withdrawals">商家提款申请</a></li><?php endif; endif; ?>
		<?php if(($site) == "more"): ?><!-- 更多管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=more">更多管理</a></li>
			<li <?php if(($left) == "comment"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=comment">留言管理</a></li>
			<li <?php if(($left) == "banner"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=banner">焦点图管理</a></li>
			<li <?php if(($left) == "push"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=push">推送消息管理</a></li><?php endif; ?>
		<?php if(($site) == "member"): ?><!-- 会员管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member">会员列表</a></li>
			<li <?php if(($left) == "company"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=company">企业列表</a></li>
			<li <?php if(($left) == "company_group"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=company_group">企业分组</a></li>
			<li <?php if(($left) == "apply"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=apply">企业申请</a></li>
			<li <?php if(($left) == "applyDepot"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyDepot">仓储主</a></li>
			<li <?php if(($left) == "applyPerTruck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyPerTruck">个体车主</a></li>
			<li <?php if(($left) == "applyComTruck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyComTruck">公司车主</a></li>
			<li <?php if(($left) == "applyPerGoods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyPerGoods">个体货主</a></li>
			<li <?php if(($left) == "applyComGoods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyComGoods">公司货主</a></li>
			<li <?php if(($left) == "message"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=message">系统消息</a></li><?php endif; ?>
		<?php if(($site) == "user"): ?><!-- 管理员管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=user">管理员列表</a></li>
			<li <?php if(($left) == "group"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=user&a=group">管理员分组</a></li><?php endif; ?>
	</ul>
</div>
    <!-- 左侧 结束 -->
    <!-- 主体 开始 -->
    <div class="main">
        <input id="url" type="hidden" value="?g=admin&m=member&a=company_delall">
        <div class="wrapper">
            <!-- 主体头部 -->
            <div class="header">
                <h2>网点管理中心</h2>
                <a href="?g=admin&m=member&a=point&id=<?php echo ($_GET['id']); ?>">网点管理</a>
                <a class="hover" href="?g=admin&m=member&a=pointAdd&id=<?php echo ($_GET['id']); ?>">添加网点</a>
                <a href="javascript:void(0);" class="batch_upload">
                  <u>批量上传</u>
                </a>
            </div>
            <form class="form-horizontal chkform" name="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="p_id" value="<?php echo ($detail["p_id"]); ?>" />
                <input type="hidden" name="c_id" value="<?php echo ($detail["c_id"]); ?>" />

                <input type="hidden" id="lng" value="<?php echo ($detail["lng"]); ?>" />
                <input type="hidden" id="lat" value="<?php echo ($detail["lat"]); ?>" />
                <table class="add_table">
                    <div>
                        <div style="width:520px;height:340px;border:1px solid gray;margin-left:40px;" id="container"></div>
                        <tr id="r-result">
                            <td class="w120">地址搜索：</td>
                            <td>
                                <input type="text" id="suggestId" size="20" value="输入地址搜素" onclick="if(this.value==this.defaultValue){this.value=''};" style="height:22px;line-height:22px;border:1px solid #3cf;width:180px;" />
                            </td>
                        </tr>
                        <div id="searchResultPanel" style="display:none;border:1px solid #C0C0C0;width:150px;height:auto;"></div>
                        <tr>
                            <td class="w120">标注参数：</td>
                            <td>
                                <input name="p_area" style="height:22px;line-height:22px;border:1px solid #A9BCD6;"  type="text" id="area"  <?php if($detail["lng"] != ''): ?>value="<?php echo ($detail["lng"]); ?>,<?php echo ($detail["lat"]); ?>"<?php else: ?>value="120.265094,30.315726"<?php endif; ?> size="42" />
                                <a href="javascript:;" onclick="document.getElementById('area').value='120.265094,30.315726';">初始化</a> 
                            </td>
                        </tr>
                    </div>
                    <tr>
                        <td class="w120">所属公司：</td>
                        <td>
                            <input class="i400 company_add_name" type="text" value="<?php echo ($detail["companyName"]); ?>" readonly/>(不可更改)
                        </td>
                    </tr>

                    <tr>
                        <td class="w120">店名：</td>
                        <td>
                            <input class="i400 company_add_name" type="text" name="p_title" value="<?php echo ($detail["p_title"]); ?>" datatype="*" nullmsg="请填写企业名称" errormsg="请填写企业名称"/>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写店名</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="w120">联系人：</td>
                        <td>
                            <input class="i400" type="text" name="p_name" value="<?php echo ($detail["p_name"]); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">电话号码：</td>
                        <td>
                            <input class="i400" type="text" name="p_phone" value="<?php echo ($detail["p_phone"]); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">手机号码：</td>
                        <td>
                            <input class="i400" type="text" name="p_mobile" value="<?php echo ($detail["p_mobile"]); ?>">
                        </td>
                    </tr>

                    <!--<tr>-->
                        <!--<td class="w120">标注参数：</td>-->
                        <!--<td>-->
                            <!--<input name="companylink[p_area]" style="height:22px;line-height:22px;border:1px solid #A9BCD6;" type="text" id="area" value="" size="42"> -->
                            <!--<a href="javascript:;" onclick="document.getElementById('area').value='120.265094,30.315726';">初始化</a> -->
                        <!--</td>-->
                    <!--</tr>-->
                    <tr>
                        <td class="w120">所在地：</td>
                        <td>
                            <select class="province" name="province_id">
                                <option value="">请选择所在省</option>
                                <?php if(is_array($region_arr[0])): foreach($region_arr[0] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>" <?php if(($detail['province_id']) == $val['id']): ?>selected<?php endif; ?>><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>

                            </select>
                            <select class="city" name="city_id">
                                <option value="">请选择所在市</option>
                                <?php if(is_array($region_arr[$detail['province_id']])): foreach($region_arr[$detail['province_id']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>" <?php if(($detail['city_id']) == $val['id']): ?>selected<?php endif; ?>><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                            </select>
                            <select class="towns" name="countyn">
                                <option value="">请选择所在区/县</option>
                                <?php if(is_array($region_arr[$detail['city_id']])): foreach($region_arr[$detail['city_id']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>" <?php if(($detail['countyn']) == $val['id']): ?>selected<?php endif; ?>><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">地址：</td>
                        <td>
                            <input class="i400" type="text" name="address" value="<?php echo ($detail["address"]); ?>"/>
                        </td>
                    </tr>
                    <!-- 提交按钮 -->
                    <tr class="add_opt">
                        <td></td>
                        <td>
                            <input class="opt_btn ajax-post mgl0 chksubmit" target-form="form-horizontal" type="submit" value="确定提交">
                            <input class="opt_btn" type="reset" value="重置表单">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!-- 主体 结束 -->
    <script type="text/javascript">
        var data = <?php echo ($region_arr_json); ?>;//地区变量
    </script>
    <script type="text/javascript">
        // 百度地图API功能
        function G(id) {
            return document.getElementById(id);
        }
        var map = new BMap.Map("container");
        map.centerAndZoom("北京",12);                   // 初始化地图,设置城市和地图级别。
        var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
            {"input" : "suggestId"
            ,"location" : map
        });
        ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
            var str = "";
            G("searchResultPanel").innerHTML = str;
        });
        var myValue;
        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
            var _value = e.item.value;
            myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            G("searchResultPanel").innerHTML = myValue;
            setPlace();
        });
        function setPlace(){
            map.clearOverlays();    //清除地图上所有覆盖物
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                document.getElementById("area").value = pp.lng + "," + pp.lat;
                map.centerAndZoom(pp, 18);
                map.addOverlay(new BMap.Marker(pp));    //添加标注
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(myValue);
        }
    </script>                    
    <script type="text/javascript">
        lng = $("#lng").val();
        lat = $("#lat").val();
        if(lng != ""){
            var point = new BMap.Point(lng,lat);  // 创建点坐标
        }else{
            var point = new BMap.Point(120.265094,30.315726);  // 创建点坐标
        }
        var map = new BMap.Map("container");
        map.centerAndZoom(point, 16);
        map.enableScrollWheelZoom();
        var marker = new BMap.Marker(point);
        map.addOverlay(marker);
        map.addEventListener("click", function(e){
            document.getElementById("area").value=e.point.lng + "," + e.point.lat;
            var newpoint=new BMap.Point(e.point.lng,e.point.lat);
            map.clearOverlays(marker);
            marker = new BMap.Marker(newpoint);
            map.addOverlay(marker);
        });
    </script>
    <script type="text/javascript">
        KindEditor.ready(function(K) {
            var editor = K.editor({
                allowFileManager : true
            });
            K('.batch_upload').click(function() {
                var _this=$(this);
                editor.loadPlugin('insertfile', function() {
                    editor.plugin.fileDialog({
                        clickFn : function(url, title) {
                            editor.hideDialog();
                            var data={url:url};
                            $.post('?g=admin&m=Point_company&a=batchupload',data,function(data){
                                // location.reload();
                            });
                        }
                    });
                });
            });

        });
    </script>

</body>
</html>