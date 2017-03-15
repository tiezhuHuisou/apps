<?php
namespace Apps\Controller;
use Think\Controller;

class MerchantsOrderController extends ApiController {
    /* 订单状态 */
    private $statusList = array(
        '0' => '待付款',
        '1' => '待发货',
        '2' => '待收货',
        '3' => '待评价',
        '4' => '退款中',
        '5' => '交易关闭',
        '6' => '申请退款',
        '7' => '退款被拒',
        '8' => '交易完成',
        '9' => '已退款'
    );

    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
        $token = I('request.token');
        empty($token) && $this->ajaxJson('70000', '请先登陆');
        /* 根据token获取用户信息 */
        $prefix = C('DB_PREFIX');
        $memberInfo = M('Token')
                    ->alias('l')
                    ->field('r.uid,r.head_pic,c.id company_id,c.name company_name,c.logo,c.bgimg')
                    ->join($prefix.'member r on l.id = r.token_id', 'LEFT')
                    ->join($prefix.'company c on r.uid = c.user_id', 'LEFT')
                    ->where(array('l.uuid'=>$token))
                    ->find();
        /* 处理数据 */
        !$memberInfo && $this->ajaxJson('40004');
        $memberInfo['head_pic'] = $this->getAbsolutePath($memberInfo['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
        $memberInfo['logo'] = $this->getAbsolutePath($memberInfo['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
        !$memberInfo['company_id'] && $this->ajaxJson('70000', '您尚未成为企业会员');
        $this->memberInfo = $memberInfo;
    }

    /**
     * 商家订单列表
     * 订单状态：'-1'=>'已删除'，'0' => '待付款', '1' => '待发货', '2' => '待收货', '3' => '待评价'（买家确认收货后）, '4' => '退款中'（商家同意退款申请后）, '5' => '交易关闭'（商家主动取消订单）, '6' => '申请退款', '7' => '退款被拒', '8' => '交易完成'（买家评价后），'9'=>'已退款（商家确认打款后），'10'=>'交易完成后被删除的订单'
     */
    public function index() {
        if ( IS_GET ) {
            /* 接收参数 */
            $page   = I('get.page', 1, 'intval');
            $page   = ( $page - 1 ) * 10;
            $state  = I('get.state', -2, 'intval');
            $search = I('get.search');
            /* 查询订单信息 */
            if ( $state == -2 ) {
                /* 所有未删除的订单 */
                $where['state'] = array('egt', 0);
            } else {
                if ( $state == 4 ) {
                    /* '4' => '待售后' */
                    $where['state'] = array('in', '4,6,7,9');
                } else {
                    /* '0' => '待付款', '1' => '待发货', '2' => '待收货', '3' => '待评价' */
                    $where['state'] = array('eq', $state);
                }
            }
            /* 搜索订单号、收货人姓名、收货人手机 */
            $search && $where['id|address_name|address_mobile'] = array('like', '%'.$search.'%');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询商家订单 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $orderList = M('Order')->where($where)->order('etime DESC')->limit($page,10)->getField('id order_id,uid company_id,"" company_logo,address_name company_name,pay_price,freight_price,state,payment_id,etime,remark', true);
            !$orderList && $this->returnJson(array('order_list'=>array()));
            /* 取订单id、订单状态处理 */
            foreach ($orderList as $key => $value) {
                $orderIds[] = $value['order_id'];
                $orderList[$key]['status'] = $this->statusList[$value['state']];
                $orderList[$key]['order_bottom'] = '合计：￥' . $value['pay_price'] . '（含运费￥' . $value['freight_price'] . '）';
                $orderList[$key]['etime'] = date('Y-m-d H:i:s', $value['etime']);
                // unset($orderList[$key]['pay_price'], $orderList[$key]['freight_price']);

            }
            /* 查询订单商品信息 */
            $map['orderid'] = array('in', $orderIds);
            $productList = M('OrderSub')->field('orderid,goodid,gpic,goodname,nums,unitprice,spec_info')->where($map)->select();
            /* 订单商品数据处理 */
            foreach ($productList as $pk => $pv) {
                unset($productList[$pk]['orderid']);
                $orderList[$pv['orderid']]['product_all_nums'] += $pv['nums'];
                $orderList[$pv['orderid']]['product_list'][] = $productList[$pk];
            }
            /* 订单数据处理 */
            foreach ($orderList as $ok => $ov) {
                $orderList[$ok]['order_bottom'] = '共计' . $ov['product_all_nums'] . '件商品 ' . $orderList[$ok]['order_bottom'];
                unset($orderList[$ok]['product_all_nums']);
            }
            $orderList = array_values($orderList);
            $list['order_list'] = $orderList;

            /* 关闭订单理由 */
            $list['order_close_reason_list'] = array( '无法联系上买家', '买家误拍/重拍了', '买家无诚意/恶意干扰', '已通过其他方式交易', '缺货暂时无法交易' );

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 订单详情
     */
    public function detail() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $memberInfo = $this->memberInfo;

            /* 查询订单和订单商品信息 */
            $orderInfo = M('Order')
                       ->alias('l')
                       ->field('l.id,l.address_name,l.address_mobile,l.address_detail,l.company_id,l.company_logo,l.company_name,l.ctime,l.pay_price,l.freight_price,l.express_no,l.use_coupon,l.state,r.goodid,r.goodname,r.gpic,r.nums,r.unitprice,r.spec_info,e.word,l.payment_id,l.remark,l.reason,l.etime')
                       ->join(C('DB_PREFIX') . 'order_sub r ON l.id = r.orderid', 'LEFT')
                       ->join(C('DB_PREFIX') . 'freight f ON l.freight_id = f.id', 'LEFT')
                       ->join(C('DB_PREFIX') . 'express e ON f.delivery_id = e.id', 'LEFT')
                       ->where(array('l.company_id'=>$memberInfo['company_id'], 'l.id'=>$id))
                       ->select();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在');
            if ( !$orderInfo[0]['word'] ) {
                $orderInfo = M('Order')
                           ->alias('l')
                           ->field('l.id,l.address_name,l.address_mobile,l.address_detail,l.company_id,l.company_logo,l.company_name,l.ctime,l.pay_price,l.freight_price,l.express_no,l.use_coupon,l.state,r.goodid,r.goodname,r.gpic,r.nums,r.unitprice,r.spec_info,e.word,l.payment_id,l.remark,l.reason,l.etime')
                           ->join(C('DB_PREFIX') . 'order_sub r ON l.id = r.orderid', 'LEFT')
                           ->join(C('DB_PREFIX') . 'express e ON l.express_id = e.id', 'LEFT')
                           ->where(array('l.company_id'=>$memberInfo['company_id'], 'l.id'=>$id))
                           ->select();
            }
            /* 数据处理 */
            foreach ($orderInfo as $key => $value) {
                $orderList['company_id']   = $value['company_id'];
                $orderList['company_logo'] = $value['company_logo'];
                $orderList['company_name'] = $value['company_name'];
                $orderList['remark']       = $value['remark'];
                $orderList['reason']       = $value['reason'];
                $orderList['etime']        = date('Y-m-d H:i:s', $value['etime']);
                $orderList['product_list'][$key]['goodid'] = $value['goodid'];
                $orderList['product_list'][$key]['goodname'] = $value['goodname'];
                $orderList['product_list'][$key]['goodid'] = $value['goodid'];
                $orderList['product_list'][$key]['gpic'] = $value['gpic'];
                $orderList['product_list'][$key]['nums'] = $value['nums'];
                $orderList['product_list'][$key]['unitprice'] = $value['unitprice'];
                $orderList['product_list'][$key]['spec_info'] = $value['spec_info'];
                $orderList['refund_flag'] = in_array($value['state'], array(0,1,2)) ? 1 : 0;
                if ( $value['use_coupon'] > 0 ) {
                    $orderList['other_info'] = array(
                        array('other_left' => '运费', 'other_left_color' => '#666666', 'other_right' => '￥' . $value['freight_price'], 'other_right_color' => '#666666'),
                        array('other_left' => '优惠券', 'other_left_color' => '#666666', 'other_right' => '-￥' . $value['use_coupon'], 'other_right_color' => '#666666'),
                        array('other_left' => '实付款', 'other_left_color' => '#333333', 'other_right' => '￥' . $value['pay_price'], 'other_right_color' => '#fe5722')
                    );
                } else {
                    $orderList['other_info'] = array(
                        array('other_left' => '运费', 'other_left_color' => '#666666', 'other_right' => '￥' . $value['freight_price'], 'other_right_color' => '#666666'),
                        array('other_left' => '实付款', 'other_left_color' => '#333333', 'other_right' => '￥' . $value['pay_price'], 'other_right_color' => '#fe5722')
                    );
                }
            }

            /* 物流信息 */
            if ( in_array($orderInfo[0]['state'], array(3,8)) ) {
                $url = 'http://www.kuaidi100.com/query?&type=' . $orderInfo[0]['word'] . '&postid=' . $orderInfo[0]['express_no'];
                $logistics = json_decode($this->curlGet($url), true);
                switch ( $logistics['state'] ) {
                    case 0:
                        $logistics['state'] = '在途';
                        break;
                    case 1:
                        $logistics['state'] = '揽件';
                        break;
                    case 2:
                        $logistics['state'] = '疑难';
                        break;
                    case 3:
                        $logistics['state'] = '签收';
                        break;
                    case 4:
                        $logistics['state'] = '退签';
                        break;
                    case 5:
                        $logistics['state'] = '派件';
                        break;
                    case 6:
                        $logistics['state'] = '退回';
                        break;
                    default:
                        break;
                }
                $list['logistics_info']['logistics_icon']    = C('HTTP_APPS_IMG') . 'logistics_icon.png';
                $list['logistics_info']['logistics_message'] = $logistics['data'][0]['context'] ? $logistics['data'][0]['context'] : '';
                $list['logistics_info']['logistics_time']    = $logistics['message'] == 'ok' ? $logistics['data'][0]['ftime'] : '';
                // $list['logistics_info']['logistics_time']    = $logistics['message'] == 'ok' ? $this->dateTimeDeal( strtotime($logistics['data'][0]['ftime']) ) : '';
            } else {
                $list['logistics_info']['logistics_icon']    = '';
                $list['logistics_info']['logistics_message'] = '';
                $list['logistics_info']['logistics_time']    = '';
            }

            /* 收货地址信息 */
            $list['address_info']['address_title']  = '收货人：' . $orderInfo[0]['address_name'] . ' ' . $orderInfo[0]['address_mobile'];
            $list['address_info']['address_detail'] = '收货地址：' . $orderInfo[0]['address_detail'];

            /* 订单详情 */
            $list['order_detail'] = $orderList;

            /* 订单信息 操作记录 */
            $clogList = M('OrderClog')->field('remark,addtime')->where(array('order_id'=>$id))->order('id DESC')->select();
            $list['order_info'][] = '订单编号：' . $orderInfo[0]['id'];
            $list['order_info'][] = '订单状态：' . $this->statusList[$orderInfo[0]['state']];
            if ( $clogList ) {
                foreach ($clogList as $key => $value) {
                    $list['order_info'][] = date('Y-m-d H:i:s', $value['addtime']) . ' ' . $value['remark'];
                }
            }

            /* 订单状态 */
            $list['order_status'] = $orderInfo[0]['state'];

            /* 订单支付方式id */
            $list['payment_id'] = $orderInfo[0]['payment_id'];

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家订单关闭
     */
    public function close() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $reason = I('post.reason');
            empty($reason) && $this->ajaxJson('70000', '请选择关闭理由');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('uid,state')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单异常');
            /* 未付款订单才能关闭 */
            $orderInfo['state'] != 0 && $this->ajaxJson('70000', '订单状态异常');

            /* 交易关闭 */
            $save = M('Order')->where($where)->setField('state', 5);
            !$save && $this->ajaxJson('70000', '关闭失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 13;
            $clogDatas['uid']      = $orderInfo['uid'];
            $clogDatas['remark']   = '商家关闭交易，关闭理由：' . $reason;
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            $this->ajaxJson('40000', '关闭成功');            
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单发货
     */
    public function delivery() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            /* 发货单号 */
            $express_no = I('post.express_no');
            empty($express_no) && $this->ajaxJson('70000', '请填写发货单号');
            strlen($express_no) > 50 && $this->ajaxJson('70000', '发货单号最多填写50个字符');
            /* 发货公司id */
            $express_id = I('post.express_id', 0, 'intval');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('uid,state,freight_id')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单异常');
            /* 退款被拒或已付款订单才能发货 */
            !in_array($orderInfo['state'], array(1,7)) && $this->ajaxJson('70000', '订单状态异常');
            /* 通用包邮模版 则需要选择发货公司 */
            if ( $orderInfo['freight_id'] == 1 && !$express_id ) {
                $this->ajaxJson('70000', '请选择发货公司');
            }

            /* 订单发货 */
            $datas['state']      = 2;
            $datas['send_time']  = time();
            $datas['etime']      = time();
            $datas['express_no'] = $express_no;
            $express_id && $datas['express_id'] = $express_id;
            $save = M('Order')->where($where)->save($datas);
            !$save && $this->ajaxJson('70000', '发货失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 3;
            $clogDatas['uid']      = $orderInfo['uid'];
            $clogDatas['remark']   = '商家发货';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            $this->ajaxJson('40000', '发货成功');            
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单同意退款
     */
    public function refundAgree() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 6);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在或状态异常');
            /* 更新订单状态 */
            $datas['state'] = 4;
            $datas['etime'] = time();
            $result = M('Order')->where($where)->save($datas);
            if ( $result ) {
                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderInfo['id'];
                $clogDatas['action']   = 9;
                $clogDatas['uid']      = $orderInfo['uid'];
                $clogDatas['remark']   = '商家同意退款';
                $clogDatas['addtime']  = time();
                M('OrderClog')->add($clogDatas);

                /* 查询订单销售利润 */
                $where_withdrawals['status']   = array('eq', -3);
                $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
                $where_withdrawals['cid']      = array('eq', $orderInfo['company_id']);
                $withdrawals = M('Withdrawals')->where($where_withdrawals)->getField('withdrawals');

                /* 构建数据数组 并生成订单退款的商家销售利润记录 一旦同意退款买家不能取消退款，故在此生成 */
                $withdrawalsDatas['order_id']    = $orderInfo['id'];
                $withdrawalsDatas['withdrawals'] = $withdrawals;
                $withdrawalsDatas['task']        = '';
                $withdrawalsDatas['cname']       = $orderInfo['company_name'];
                $withdrawalsDatas['payee']       = $orderInfo['address_name'];
                $withdrawalsDatas['type']        = 0;
                $withdrawalsDatas['cid']         = $orderInfo['company_id'];
                $withdrawalsDatas['etime']       = time();
                $withdrawalsDatas['ctime']       = time();
                $withdrawalsDatas['status']      = -4;
                M('Withdrawals')->add($withdrawalsDatas);

                $this->ajaxJson('40000', '操作成功');
            }
            $this->ajaxJson('70000', '操作失败');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单拒绝退款
     */
    public function refundRefuse() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $remark = I('post.remark');
            !$remark && $this->ajaxJson('70000', '请填写拒绝理由');
            strlen($remark) > 200 && $this->ajaxJson('70000', '拒绝理由不能超过200个字符');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 6);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在或状态异常');
            /* 更新订单状态 */
            $datas['state']  = 7;
            $datas['etime']  = time();
            $result = M('Order')->where($where)->save($datas);
            if ( $result ) {
                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderInfo['id'];
                $clogDatas['action']   = 10;
                $clogDatas['uid']      = $orderInfo['uid'];
                $clogDatas['remark']   = '商家拒绝退款 拒绝理由：' . $remark;
                $clogDatas['addtime']  = time();
                M('OrderClog')->add($clogDatas);

                $this->ajaxJson('40000', '操作成功');
            } 
            $this->ajaxJson('70000', '操作失败');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单打款完成
     */
    public function refundComplete() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $money = I('post.money');
            !$money && $this->ajaxJson('70000', '请填写退款金额');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 4);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在或状态异常');
            /* 数据验证 */
            if ( !preg_match('/^\d+(\.\d{1,2})?$/', $money) || $money == 0 ) {
                $this->ajaxJson('70000', '退款金额格式不正确');
            }
            if ( $money > $orderInfo['pay_price'] ) {
                $this->ajaxJson('70000', '退款金额不能大于订单的支付金额');
            }
            /* 更新订单状态 */
            $datas['state'] = 9;
            $datas['etime'] = time();
            $result = M('Order')->where($where)->save($datas);
            if ( $result ) {
                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderInfo['id'];
                $clogDatas['action']   = 8;
                $clogDatas['uid']      = $orderInfo['uid'];
                $clogDatas['remark']   = '商家退款完成，退款金额：￥' . $money;
                $clogDatas['addtime']  = time();
                M('OrderClog')->add($clogDatas);

                /* 生成订单支付记录 */
                $payLogDatas['order_id']   = $orderInfo['id'];
                $payLogDatas['trade_no']   = '';
                $payLogDatas['pay_price']  = $money;
                $payLogDatas['payment_id'] = $orderInfo['payment_id'];
                $payLogDatas['add_time']   = time();
                M('OrderPayLog')->add($payLogDatas);

                /* 余额支付的订单增加用户余额 */
                if ( $orderInfo['payment_id'] == 3 ) {
                    M('Member')->where(array('uid'=>$orderInfo['uid']))->setInc('balance', $money);
                }

                /* 将待提现佣金标记为流失佣金记录 */
                $where_commission['order_id'] = array('eq', $orderInfo['id']);
                $where_commission['status']   = array('eq', 0);
                $commissionDatas['status']    = -1;
                $commissionDatas['remark']    = '订单退款成功，佣金流失';
                M('Commission')->where($where_commission)->save($commissionDatas);

                /* 更新商家销售利润记录状态 */
                $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
                $where_withdrawals['status']   = array('eq', -4);
                $withdrawalsDatas['status']    = 5;
                $withdrawalsDatas['etime']     = time();
                M('Withdrawals')->where($where_withdrawals)->save($withdrawalsDatas);

                $this->success('操作成功');
            }
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 商家订单备注
     */
    public function remark() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $remark = I('post.remark');
            strlen($remark) > 200 && $this->ajaxJson('70000', '订单备注不能超过200个字符');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 更新订单状态 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('eq', $id);
            $datas['remark'] = $remark;
            $datas['etime']  = time();
            $result = M('Order')->where($where)->save($datas);
            $result !== false && $this->ajaxJson('40000', '操作成功');
            $this->ajaxJson('70000', '操作失败');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单改价
     */
    public function editPrice() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $pay_price = I('post.pay_price');
            empty($pay_price) && $this->ajaxJson('70000', '请填写现订单价格');
            $freight_price = I('post.freight_price');
            empty($freight_price) && $this->ajaxJson('70000', '请填写现运费');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 0);
            $where['id']         = array('eq', $id);
            $orderInfo = M('Order')->field('id,uid,pay_price,freight_price')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在或状态异常');
            /* 数据验证 */
            $pay_price == 0 && $this->ajaxJson('70000', '现订单价格不能为0');
            if ( !preg_match('/^\d+(\.\d{1,2})?$/', $pay_price) ) {
                $this->ajaxJson('70000', '现订单价格格式不正确');
            }
            // if ( $pay_price > $orderInfo['pay_price'] - $orderInfo['freight_price'] ) {
            //     $this->ajaxJson('70000', '现订单价格不能大于原订单价格');
            // }
            if ( !preg_match('/^\d+(\.\d{1,2})?$/', $freight_price) ) {
                $this->ajaxJson('70000', '现运费格式不正确');
            }
            // if ( $freight_price > $orderInfo['freight_price'] ) {
            //     $this->ajaxJson('70000', '现运费不能大现运费');
            // }
            /* 更新订单信息 */
            $datas['pay_price']     = $pay_price + $freight_price;
            $datas['freight_price'] = $freight_price;
            $datas['etime']         = time();
            $result = M('Order')->where($where)->save($datas);
            $result === false && $this->ajaxJson('70000', '改价失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $orderInfo['id'];
            $clogDatas['action']   = 14;
            $clogDatas['uid']      = $orderInfo['uid'];
            $clogDatas['remark']   = '商家改价，原价：￥' . $orderInfo['pay_price'] . '（含运费：￥' . $orderInfo['freight_price'] . '）';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            $this->ajaxJson('40000', '改价成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家订单发货物流公司
     */
    public function expressList() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            /* 查询订单信息 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 1);
            $where['id']         = array('eq', $id);
            $freight_id = M('Order')->where($where)->getField('freight_id');
            !$freight_id && $this->ajaxJson('70000', '订单不存在或状态异常');
            /* 默认全国包邮则返回所有物流公司 */
            $where_express['status'] = array('eq', 1);
            if ( $freight_id == 1 ) {
                $list['express_list'] = M('Express')->field('id express_id,title')->where($where_express)->select();
            } else {
                $express_id = M('Freight')->where(array('id'=>$freight_id))->getField('delivery_id');
                !$express_id && $this->ajaxJson('70000', '订单异常');
                $where_express['id'] = array('eq', $express_id);
                $list['express_list'] = M('Express')->field('id express_id,title')->where($where_express)->select();
            }
            !$list['express_list'] && $this->ajaxJson('70000', '数据异常');

            $this->returnJson($list);
        }
        $this->ajaxJson('70001');
    }

    /**
     * 提醒买家(提醒买家去评价)
     */
    public function remind() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 一个订单只能提现一次 */
            $model               = M('Order');
            $where['id']         = array('eq', $id);
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['state']      = array('eq', 3);
            $orderInfo = $model->field('id,remind_buyer')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单状态异常');
            $orderInfo['remind_buyer'] > 0 && $this->ajaxJson('70000', '已提醒');
            $save = $model->where($where)->setInc('remind_buyer', 1);
            !$save && $this->ajaxJson('70000', '操作失败');
            /* 提醒功能未实现 站内信提醒  */
            
            /* 提醒功能未实现：短信提醒 极光推送提醒  */

            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * curl get 方法【物流信息专用】
     * @param $url 请求地址
     */
    private function curlGet($url) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
}