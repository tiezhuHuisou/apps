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
                <h2>企业管理中心</h2>
                <a class="hover" href="?g=admin&m=member&a=company">企业列表</a>
                <a href="?g=admin&m=member&a=company_add">添加企业</a>
            </div>
            <!-- 主体搜索 -->
            <div class="search">
                <form class="form-horizontal" name="" action="?g=admin&m=member&a=company" method="post">
                    <p>筛选：</p>
                    <select name="flags">
                        <option value="">全部</option>
                        <?php if(is_array($flags)): $i = 0; $__LIST__ = $flags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($fl["att"]); ?>" <?php if(($_GET['flags']) == $fl["att"]): ?>selected<?php endif; ?>><?php echo ($fl["attname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <p>搜索：</p>
                    <input class="search_input" type="text" name="search" value="<?php echo ($search); ?>"/>
                    <input class="search_btn" type="submit" value="搜索" />
                </form>
            </div>
            <!-- 主体数据 -->
            <table class="data">
                <!-- 数据头部 -->
                <tr class="data_header">
                    <th><input id="check_all" type="checkbox" name="" value="" /></th>
                    <th>ID</th>
                    <!--<th>二维码</th>-->
                    <th>企业名称</th>
                    <th>主要线路</th>
                    <th>状态</th>
                    <th>注册时间</th>
                    <th>操作</th>
                </tr>
                <!-- 一条数据开始 -->
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><tr>
                        <td><input class="check_box" type="checkbox" name="items" value="<?php echo ($li["id"]); ?>" /></td>
                        <td><?php echo ($li["id"]); ?></td>
                        <!--<td><?php if(empty($li["qrcode"])): ?>暂无<?php else: ?><a href="<?php echo ($li["qrcode"]); ?>" target="_blank"><img class="qrcode" src="<?php echo ($li["qrcode"]); ?>" width="60" height="60" /></a><?php endif; ?></td>-->
                        <td><?php echo ($li["name"]); ?></td>
                        <td class="max"><?php echo ($li["business"]); ?></td>
                        <!-- <td><?php echo ($li["site"]); ?></td> -->
                        <!-- <td><?php echo ($li["email"]); ?></td> -->
                        <td><?php if($li["status"] == 1): ?>显示<?php else: ?>不显示<?php endif; if(!empty($li["flags"])): ?>&nbsp<span class="red">(<?php echo ($li["flags"]); ?>)<?php endif; if(($li['proprietary']) == "1"): ?>&nbsp<span class="red">(自营)<?php endif; ?></span></td>
                        <td><?php echo (date("Y-m-d H:i",$li["issue_time"])); ?></td>
                        <td class="data_opt">
                            <a href="?g=admin&m=member&a=company_add&id=<?php echo ($li["id"]); ?>">修改</a>
                            <!-- <a href="?g=admin&m=member&a=comment&id=<?php echo ($li["id"]); ?>">评价管理</a> -->
                            <!--<a href="?g=admin&m=member&a=companyalbumadd&companyid=<?php echo ($li["id"]); ?>">相册设置</a>-->
                            <a href="?g=admin&m=member&a=point&id=<?php echo ($li["id"]); ?>">网点</a>
                            <a class="confirm ajax-get" href="index.php?g=admin&m=member&a=company_del&id=<?php echo ($li["id"]); ?>">删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <!-- 操作 -->
            <div class="opt">
                <a class="opt_btn" href="?g=admin&m=member&a=company_add">添加</a>
                <a id="qrcode" class="opt_btn" href="javascript:void(0);">生成二维码</a>
                <a class="opt_btn" attr="BatchDel" href="javascript:void(0);">删除所选</a>
            </div>
            <!-- 分页 -->
            <div class="page"><?php echo ($_page); ?></div>
        </div>
    </div>
    <!-- 主体 结束 -->
    <script type="text/javascript">
        $(function() {
            $('#qrcode').click(function() {
                var check = $("input[name='items']:checked");
                if (check.length < 1) {
                    layer.open({
                        content : '请选择生成项',
                        time    : 2000
                    });
                    return false;
                }
                layer.confirm('确定要生成二维码吗？', function(luxury) {
                    var id = new Array();
                    check.each(function(i) {
                        id[i] = $(this).val();
                    });

                    $.post('?g=admin&m=member&a=qrcode', {id: id}, function( data ) {
                        var icon = 2;
                        if ( data.status == 1 ) {
                            icon = 1;
                        }
                        layer.msg(data.info, {
                            time : 2000,
                            icon : icon
                        }, function() {
                            window.location.reload();
                        });
                    });

                    layer.close(luxury);
                });  
            });
        });
    </script>
</body>
</html>