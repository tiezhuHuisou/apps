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
    <link rel="stylesheet" type="text/css" href="./Public/static/kindeditor/themes/default/default.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/Admin/css/product_add.css"/>
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
    <div class="wrapper">
        <!-- 主体头部 -->
        <div class="header">
            <h2>添加货车</h2>
            <a href="?g=admin&m=product&a=truck">货车列表</a>
            <a class="hover" href="?g=admin&m=product&a=truckAdd">添加货车</a>
        </div>
        <h3>添加货车</h3>
        <!-- 主体数据 -->
        <form class="form-horizontal chkform" name="" action="" method="post">
            <input type="hidden" name="id" value="<?php echo ($detail["id"]); ?>"/>
            <table class="add_table">
                <!--始发地-->
                <tr>
                    <td class="w120">始发地：</td>
                    <td>
                        <select class="province" name="provincen">
                            <option value="">请选择所在省</option>
                            <?php if(is_array($region_arr[0])): foreach($region_arr[0] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['provincen']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>

                        <select class="city" name="cityn">
                            <option value="">请选择所在市</option>
                            <?php if(is_array($region_arr[$detail['provincen']])): foreach($region_arr[$detail['provincen']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['cityn']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>
                        <select class="towns" name="countryn">
                            <option value="">请选择所在区/县</option>
                            <?php if(is_array($region_arr[$detail['cityn']])): foreach($region_arr[$detail['cityn']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['countryn']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--目的地-->
                <tr>
                    <td class="w120">目的地：</td>
                    <td>
                        <select class="province2" name="provincen2">
                            <option value="">请选择所在省</option>
                            <?php if(is_array($region_arr[0])): foreach($region_arr[0] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['provincen2']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>

                        <select class="city2" name="cityn2">
                            <option value="">请选择所在市</option>
                            <?php if(is_array($region_arr[$detail['provincen2']])): foreach($region_arr[$detail['provincen2']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['cityn2']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>

                        <select class="towns2" name="countryn2">
                            <option value="">请选择所在区/县</option>
                            <?php if(is_array($region_arr[$detail['cityn2']])): foreach($region_arr[$detail['cityn2']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['countryn2']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!-- 会员名称* -->
                <tr>
                    <td class="w120">发布者帐号：</td>
                    <td>
                        <input class="i200" type="text" name="mphone" value="<?php echo ($detail["mphone"]); ?>" datatype="mphone"
                               nullmsg="请填写发布者帐号" errormsg="请填写2-50位字符"/>&nbsp;&nbsp;*
                    </td>
                    <!-- 表单验证提示信息 -->
                    <td>
                        <div class="info">
                            <span class="Validform_checktip">请填写发布者帐号</span>
                            <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                        </div>
                    </td>
                </tr>
                <!--车辆类型*-->
                <tr>
                    <td class="w120">车辆类型：</td>
                    <td class="lh22">
                        <select class="" name="truck_type">
                            <option value="">请选择车辆类型</option>
                            <?php if(is_array($list['truck_type'])): foreach($list['truck_type'] as $key=>$val): ?><option value="<?php echo ($val); ?>"
                                <?php if(($detail['truck_type']) == $val): ?>selected<?php endif; ?>
                                ><?php echo ($val); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--车辆数量*-->
                <tr>
                    <td class="w120">车辆数量：</td>
                    <td class="lh22">
                        <select class="" name="truck_num">
                            <option value="">请选择车辆数量</option>
                            <?php if(is_array($list['truck_num'])): foreach($list['truck_num'] as $key=>$val): ?><option value="<?php echo ($val); ?>"
                                <?php if(($detail['truck_num']) == $val): ?>selected<?php endif; ?>
                                ><?php echo ($val); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--车辆长度*-->
                <tr>
                    <td class="w120">车辆长度：</td>
                    <td class="lh22">
                        <select class="" name="truck_length">
                            <option value="">请选择车辆长度</option>
                            <?php if(is_array($list['truck_length'])): foreach($list['truck_length'] as $key=>$val): ?><option value="<?php echo ($val); ?>"
                                <?php if(($detail['truck_length']) == $val): ?>selected<?php endif; ?>
                                ><?php echo ($val); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--车源类型*-->
                <tr>
                    <td class="w120">车源类型：</td>
                    <td class="lh22">
                        <select class="" name="source_type">
                            <option value="">请选择车源类型</option>
                            <?php if(is_array($list['source_type'])): foreach($list['source_type'] as $key=>$val): ?><option value="<?php echo ($val); ?>"
                                <?php if(($detail['source_type']) == $val): ?>selected<?php endif; ?>
                                ><?php echo ($val); ?></option><?php endforeach; endif; ?>
                        </select>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--可载重货-->
                <tr>
                    <td class="w120">可载重货：</td>
                    <td>
                        <input class="i200" type="text" name="heavy" value="<?php echo ($detail["heavy"]); ?>"/>
                    </td>
                </tr>
                <!--重货单位-->
                <tr>
                    <td class="w120">重货单位：</td>
                    <td class="lh22">

                        <label class="mgr4">
                            <input class="pt3" name="heavy_unit" checked type="radio" value="吨" <?php if(($detail['heavy_unit']) == "吨"): ?>checked<?php endif; ?>/>吨
                        </label>
                        <label class="mgr4">
                            <input class="pt3" name="heavy_unit" type="radio" value="公斤" <?php if(($detail['heavy_unit']) == "公斤"): ?>checked<?php endif; ?>/>公斤
                        </label>

                    </td>
                </tr>

                <!--可载轻货-->
                <tr>
                    <td class="w120">可载轻货：</td>
                    <td>
                        <input class="i200" type="text" name="light" value="<?php echo ($detail["light"]); ?>"/>
                    </td>
                </tr>
                <!--轻货单位-->
                <tr>
                    <td class="w120">轻货单位：</td>
                    <td class="lh22">
                        <input class="i200" type="hidden" name="light_unit" value="<?php echo ($detail["light_unit"]); ?>"/>
                        方
                        <!--<select class="" name="light_unit">-->
                            <!--<option value="">请选择轻货单位</option>-->
                            <!--<?php if(is_array($list['light_unit'])): foreach($list['light_unit'] as $key=>$val): ?>-->
                                <!--<option value="<?php echo ($val); ?>"-->
                                <!--<?php if(($detail['light_unit']) == $val): ?>selected<?php endif; ?>-->
                                <!--&gt;<?php echo ($val); ?></option>-->
                            <!--<?php endforeach; endif; ?>-->
                        <!--</select>-->
                    </td>
                </tr>
                <!--随车司机*-->
                <tr>
                    <td class="w120">随车司机：</td>
                    <td>
                        <input class="i200" type="text" name="driver" value="<?php echo ($detail["driver"]); ?>"/>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--随车号码*-->
                <tr>
                    <td class="w120">随车号码：</td>
                    <td>
                        <input class="i200" type="text" name="driver_phone" value="<?php echo ($detail["driver_phone"]); ?>"/>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--发车时间*-->
                <tr>
                    <td class="w120">发车时间：</td>
                    <td>
                        <input class="i200" type="text" name="departure_time" value="<?php echo ($detail["departure_time"]); ?>"/>&nbsp;&nbsp;*
                    </td>

                </tr>
                <!--有效日期-->
                <tr>
                    <td class="w120">有效日期：</td>
                    <td>
                        <input class="i200" type="text" name="end_date" value="<?php echo ($detail["end_date"]); ?>"/>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--途经地-->
                <tr>
                    <td class="w120">途经地：</td>
                    <td>
                        <textarea name="approach" cols=40 rows=4> <?php echo ($detail["approach"]); ?> </textarea>
                    </td>
                </tr>
                <!--车牌号码*-->
                <tr>
                    <td class="w120">车牌号码：</td>
                    <td>
                        <input class="i200" type="text" name="truck_no" value="<?php echo ($detail["truck_no"]); ?>"/>&nbsp;&nbsp;*
                    </td>
                </tr>
                <!--备注-->
                <tr>
                    <td class="w120">备注：</td>
                    <td>
                        <textarea name="remark" cols=40 rows=4> <?php echo ($detail["remark"]); ?> </textarea>
                    </td>
                </tr>
                <!-- 操作 -->
                <tr class="add_opt">
                    <td></td>
                    <td>
                        <input class="opt_btn ajax-post mgl0 chksubmit" target-form="form-horizontal" type="submit"
                               value="确认提交"/>
                        <input class="opt_btn" type="reset" value="重置表单"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!-- 主体 结束 -->
<script type="text/javascript">
    var data = <?php echo ($region_arr_json); ?>;
</script>
<script type="text/javascript" src="./Public/static/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="./Public/static/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
    var imgUrl = "./Public/Admin/images";
    var staticPath = "./Public/static";
    var flashFlag = "<?php echo ((isset($flashFlag) && ($flashFlag !== ""))?($flashFlag):0); ?>";
</script>
<script type="text/javascript" src="./Public/static/layer/layer.js"></script>
<script type="text/javascript" src="./Public/Admin/js/truckAdd.js"></script>
</body>
</html>