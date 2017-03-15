<?php
namespace Apps\Controller;
use Think\Controller;

class OrderController extends ApiController {
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
        $this->uid = M('Token')
                   ->alias('l')
                   ->join($prefix.'member r on l.id = r.token_id', 'LEFT')
                   ->where(array('l.uuid'=>$token))
                   ->getField('r.uid');
        /* 处理数据 */
        !$this->uid && $this->ajaxJson('40004', '登陆信息已过期');
    }

    /**
     * 订单列表
     */
    public function index() {
        if ( IS_GET ) {
            /* 接收参数 */
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            $uid   = $this->uid;
            $state = I('get.state', -2, 'intval');
            /* 查询订单信息 */
            if ( $state == -2 ) {
                /* 所有未删除的订单 */
                $where['state'] = array('egt', 0);
            } else {
                if ( $state == 4 ) {
                    /* 退款/售后 */
                    $where['state'] = array('in', '4,6,7,9');
                } else {
                    /* '0' => '待付款', '1' => '待发货', '2' => '待收货', '3' => '待评价' */
                    $where['state'] = array('eq', $state);
                }
            }
            $where['uid']   = array('eq', $uid);
            $orderList = M('Order')->where($where)->order('etime DESC')->limit($page,10)->getField('id order_id,company_id,company_logo,company_name,pay_price,freight_price,state,payment_id,etime', true);
            !$orderList && $this->returnJson(array('order_list'=>array()));
            /* 取订单id、订单状态处理 */
            foreach ($orderList as $key => $value) {
                $orderIds[] = $value['order_id'];
                $orderList[$key]['status'] = $this->statusList[$value['state']];
                $orderList[$key]['order_bottom'] = '合计：￥' . $value['pay_price'] . '（含运费￥' . $value['freight_price'] . '）';
                $orderList[$key]['company_logo'] = $this->getAbsolutePath($value['company_logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
                $orderList[$key]['etime'] = date('Y-m-d H:i:s', $value['etime']);
                unset($orderList[$key]['pay_price'], $orderList[$key]['freight_price']);
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
            $uid = $this->uid;

            /* 查询订单和订单商品信息 */
            $orderInfo = M('Order')
                       ->alias('l')
                       ->field('l.id,l.address_name,l.address_mobile,l.address_detail,l.company_id,l.company_logo,l.company_name,l.ctime,l.pay_price,l.freight_price,l.express_no,l.use_coupon,l.state,r.goodid,r.goodname,r.gpic,r.nums,r.unitprice,r.spec_info,e.word,l.payment_id')
                       ->join(C('DB_PREFIX') . 'order_sub r ON l.id = r.orderid', 'LEFT')
                       ->join(C('DB_PREFIX') . 'freight f ON l.freight_id = f.id', 'LEFT')
                       ->join(C('DB_PREFIX') . 'express e ON f.delivery_id = e.id', 'LEFT')
                       ->where(array('l.uid'=>$uid, 'l.id'=>$id))
                       ->select();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在');
            if ( !$orderInfo[0]['word'] ) {
                $orderInfo = M('Order')
                           ->alias('l')
                           ->field('l.id,l.address_name,l.address_mobile,l.address_detail,l.company_id,l.company_logo,l.company_name,l.ctime,l.pay_price,l.freight_price,l.express_no,l.use_coupon,l.state,r.goodid,r.goodname,r.gpic,r.nums,r.unitprice,r.spec_info,e.word,l.payment_id')
                           ->join(C('DB_PREFIX') . 'order_sub r ON l.id = r.orderid', 'LEFT')
                           ->join(C('DB_PREFIX') . 'express e ON l.express_id = e.id', 'LEFT')
                           ->where(array('l.uid'=>$uid, 'l.id'=>$id))
                           ->select();
            }
            /* 数据处理 */
            foreach ($orderInfo as $key => $value) {
                $orderList['company_id']   = $value['company_id'];
                $orderList['company_logo'] = $value['company_logo'];
                $orderList['company_name'] = $value['company_name'];
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
     * 订单确认
     */
    public function confirm() {
        if ( IS_GET ) {
            /* 操作类型标识 1购物车结算 2立即购买(需要先加入购物车获取购物车id才能继续) */
            $operate = I('get.operate', '0', 'strval');
            switch ( $operate ) {
                case '1':
                    /* 购物车结算 购物车id */
                    $subid = I('get.subid');
                    empty($subid) && $this->ajaxJson('70000', '参数错误');
                    break;
                case '2':
                    /* 2立即购买 请求加入购物车接口获得购物车id */
                    $curlDatas['operate'] = 2;
                    $curlDatas['id']      = I('get.id', 0, 'intval');
                    $curlDatas['nums']    = I('get.nums', 0, 'intval');
                    $curlDatas['token']   = I('get.token');
                    foreach ($curlDatas as $cv) {
                        !$cv && $this->ajaxJson('70000', '参数错误');
                    }
                    $curlDatas['specid']  = I('get.specid', 0, 'intval');
                    $subid = $this->curlPost(C('HTTP_ORIGIN') . '/apps/cart/add', $curlDatas);
                    !$subid && $this->ajaxJson('70000', '系统繁忙请稍候');
                    break;
                default:
                    $this->ajaxJson('70000', '非法操作');
                    break;
            }
            $subid = explode(',', $subid);

            /* 获取订单信息 */
            $uid       = $this->uid;
            $orderInfo = $this->getOrderInfo( $subid );

            /* 销毁不需要返回客户端的数据 */
            unset($orderInfo['address']['region'], $orderInfo['address']['place_id'], $orderInfo['total_price']);

            $this->returnJson($orderInfo);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 提交订单
     */
    public function submit() {
        if ( IS_POST ) {
            /* 接受参数，买家留言以数组形式传递，其余都是使用英文逗号拼接的字符串 */
            $subid = I('post.subid');
            empty($subid) && $this->ajaxJson('70000', '参数错误');
            $address_id = I('post.address_id', 0, 'intval');
            !$address_id && $this->ajaxJson('70000', '请选择收货地址');
            $freight_id = I('post.freight_id');
            empty($freight_id) && $this->ajaxJson('70000', '请选择物流公司');
            $coupon_id  = I('post.coupon_id');
            $buyer_message  = $_POST['buyer_message'];
            $payment_id = I('post.payment_id', 0, 'intval');
            !$payment_id && $this->ajaxJson('70000', '请选择支付方式');
            !in_array($payment_id, array(1,2,3)) && $this->ajaxJson('70000', '支付方式异常');
            $subid          = explode(',', $subid);
            $freight_id     = explode(',', $freight_id);
            $coupon_id      = explode(',', $coupon_id);
            !is_array($buyer_message) && $buyer_message = json_decode($buyer_message, true);
            if ( (count($freight_id) != count($coupon_id)) || (count($freight_id) != count($buyer_message)) ) {
                $this->ajaxJson('70000', '参数错误');
            }
            /* 将请求数据带到获取订单信息方法中去，支付方式id参数可以不带过去，暂时用不到 */
            $postDatas = array(
                'address_id'    => $address_id,
                'freight_id'    => $freight_id,
                'coupon_id'     => $coupon_id,
                'buyer_message' => $buyer_message,
                'payment_id'    => $payment_id
            );

            /* 获取订单信息 */
            $uid       = $this->uid;
            $orderInfo = $this->getOrderInfo( $subid, 2, $postDatas );

            /* 生成订单支付单号 */
            $order_trade = 'N'.time().$uid.date('Ymd').mt_rand(10000,99999);
            /* 订单支付总额 */
            $orderPaymentDatas['order_total_price'] = 0.00;
            /* 支付时显示的商品名称 */
            $orderPaymentDatas['title'] = array();
            /* 支付时显示的商品名称明细列表 */
            // $orderPaymentDatas['detail'] = array();
            /* 使用优惠券id数组 */
            $couponList = array();
            /* 订单数据组装 顺序和数据库字段顺序保持一致 */
            $model     = M('Order');
            $subModel  = M('OrderSub');
            $clogModel = M('OrderClog');
            foreach ($orderInfo['order_list'] as $key => $value) {
                $orderDatas[$key]['id']             = $uid.date('Ymd').time().mt_rand(100000,999999);
                $orderDatas[$key]['order_trade']    = $order_trade;
                $orderDatas[$key]['uid']            = intval($uid);
                $orderDatas[$key]['address_id']     = intval($orderInfo['address']['address_id']);
                $orderDatas[$key]['address_name']   = $orderInfo['address']['name'];
                $orderDatas[$key]['address_mobile'] = $orderInfo['address']['phone'];
                $orderDatas[$key]['address_detail'] = $orderInfo['address']['address'];
                $orderDatas[$key]['company_id']     = intval($value['company_id']);
                $orderDatas[$key]['company_logo']   = $value['company_logo'];
                $orderDatas[$key]['company_name']   = $value['company_name'];
                $orderDatas[$key]['ctime']          = time();
                $orderDatas[$key]['payment_id']     = $payment_id;
                $orderDatas[$key]['pay_price']      = floatval($value['company_total_price']);
                $orderDatas[$key]['total_price']    = $orderInfo['total_price'][$value['company_id']];
                $orderDatas[$key]['pay_time']       = 0;
                $orderDatas[$key]['freight_id']     = intval($value['freight_list'][0]['freight_id']);
                $orderDatas[$key]['freight_price']  = floatval($value['freight_list'][0]['freight_price']);
                $orderDatas[$key]['buyer_message']  = $buyer_message[$key];
                $orderDatas[$key]['coupon_id']      = $value['coupon_id'] ? intval($value['coupon_id']) : 0;
                $orderDatas[$key]['use_coupon']     = $value['use_coupon'] ? floatval($value['use_coupon']) : 0.00;
                $orderDatas[$key]['use_balance']    = 0.00;
                $orderDatas[$key]['state']          = 0;
                $orderDatas[$key]['send_time']      = 0;
                $orderDatas[$key]['confirm_time']   = 0;
                $orderDatas[$key]['refund_time']    = 0;
                $orderDatas[$key]['etime']          = time();
                $orderDatas[$key]['reason']         = '';
                /* 最后提醒时间设置为昨天  */
                $orderDatas[$key]['remind_time']    = time() - 86400;
                $orderDatas[$key]['comment_time']   = 0;
                /* 插入数据 */
                $model->add($orderDatas[$key]);

                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderDatas[$key]['id'];
                $clogDatas['action']   = 1;
                $clogDatas['uid']      = $uid;
                $clogDatas['remark']   = '买家创建订单';
                $clogDatas['addtime']  = time();
                $clogModel->add($clogDatas);

                /* 订单总额 */
                $orderPaymentDatas['order_total_price'] += $orderDatas[$key]['pay_price'];

                /* 订单商品数据组装 以购物车id为下标 插入之前重置下标 */
                foreach ($value['product_list'] as $pk => $pv) {
                    $subDatas[$pv['subid']]['subid']         = intval($pv['subid']);
                    $subDatas[$pv['subid']]['userid']        = intval($uid);
                    $subDatas[$pv['subid']]['orderid']       = $orderDatas[$key]['id'];
                    $subDatas[$pv['subid']]['goodid']        = $pv['goodid'];
                    $subDatas[$pv['subid']]['goodname']      = $pv['goodname'];
                    $subDatas[$pv['subid']]['gpic']          = $pv['gpic'];
                    $subDatas[$pv['subid']]['nums']          = intval($pv['nums']);
                    $subDatas[$pv['subid']]['unitprice']     = floatval($pv['unitprice']);
                    $subDatas[$pv['subid']]['totalprice']    = $pv['nums'] * $pv['unitprice'];
                    $subDatas[$pv['subid']]['status']        = 1;
                    $subDatas[$pv['subid']]['addtime']       = time();
                    $subDatas[$pv['subid']]['paymentime']    = 0;
                    $subDatas[$pv['subid']]['sellerid']      = intval($value['company_id']);
                    $subDatas[$pv['subid']]['seller']        = $value['company_name'];
                    $subDatas[$pv['subid']]['specid']        = $pv['specid'];
                    $subDatas[$pv['subid']]['spec_1']        = '';
                    $subDatas[$pv['subid']]['spec_2']        = '';
                    $subDatas[$pv['subid']]['stock']         = 0;
                    $subDatas[$pv['subid']]['spec_info']     = $pv['spec_info'];
                    $subDatas[$pv['subid']]['distribution']  = $pv['distribution'];
                    $subDatas[$pv['subid']]['commission1']   = $pv['commission1'];
                    $subDatas[$pv['subid']]['commission2']   = $pv['commission2'];
                    $subDatas[$pv['subid']]['commission3']   = $pv['commission3'];
                    $subDatas[$pv['subid']]['activity_name'] = $pv['activity_name'] ? $pv['activity_name'] : '';
                    /* 插入数据 */
                    $subModel->add($subDatas[$pv['subid']]);

                    /* 支付时显示的商品名称 */
                    $orderPaymentDatas['title'][] = $pv['goodname'];
                    /* 支付时显示的商品名称明细列表 */
                    // $orderPaymentDatas['detail'][] = $pv['spec_info'] ? $pv['goodname'] . '(' . $pv['spec_info'] . ')' : $pv['goodname'];
                    /* 记录使用优惠券id */
                    $value['coupon_id'] && $couponList[] = $value['coupon_id'];
                }
            }
            // $subDatas = array_values($subDatas);
            // M('Order')->addAll($orderDatas);
            // M('OrderSub')->addAll($subDatas);
            unset($subDatas);

            /* 锁定优惠券 */
            if ( $couponList ) {
                $where_coupon['id'] = array('in', $couponList);
                M('CouponMember')->where($where_coupon)->setField('status', 2);
            }

            /* 删除购物车数据 */
            $where_shopcart['userid'] = array('eq', $uid);
            $where_shopcart['subid']  = array('in', $subid);
            M('OrderShopcart')->where($where_shopcart)->setField('status', 0);

            /* 根据不同支付方式进行不同操作返回不同数据 */
            $orderPaymentDatas['order_trade'] = $order_trade;
            $orderPaymentDatas['title'] = implode('、', $orderPaymentDatas['title']);
            if ( mb_strlen($orderPaymentDatas['title'], 'utf-8') > 40 ) {
                $orderPaymentDatas['title'] = mb_substr($orderPaymentDatas['title'], 0, 36, 'utf-8') . '...';
            }
            // $orderPaymentDatas['detail'] = implode(',', $orderPaymentDatas['detail']);
            switch ( $payment_id ) {
                case '1':
                    /* 微信支付 */
                    $orderPaymentDatas['notify_url'] = C('HTTP_ORIGIN') . '/apps/payment/wechatNotify';
                    $list['pay_param'] = R('Payment/wechatpay', array($orderPaymentDatas));
                    $list['pay_param']['timestamp'] = strval($list['pay_param']['timestamp']);
                    break;
                case '2':
                    /* 支付宝支付 */
                    $list['pay_param']                      = R('Payment/alipay', array($orderPaymentDatas));
                    $list['pay_param']['notify_url']        = C('HTTP_ORIGIN') . '/apps/payment/alipayNotify';
                    $list['pay_param']['product_title']     = $orderPaymentDatas['title'];
                    $list['pay_param']['order_id']          = $order_trade;
                    $list['pay_param']['order_total_price'] = sprintf('%.2f', $orderPaymentDatas['order_total_price']);
                    break;
                case '3':
                    /* 账户余额支付 */
                    $orderPaymentDatas['order_list'] = $orderDatas;
                    R('Payment/balancepay', array($orderPaymentDatas));
                    break;
                default:
                    $this->ajaxJson('70000', '支付方式不存在');
                    break;
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70001');
    }

    /**
     * 获取订单信息【优惠券要补上！】
     * @param  string  $subid       [购物车id，结算多个用英文逗号隔开]
     * @param  integer $type        [操作标识：1代表确认订单，2代表提交订单]
     * @param  array   $postDatas   [提交订单时的数据]
     * @return array   $list        [订单相关信息数据数据]
     */
    private function getOrderInfo( $subid = '', $type = 1, $postDatas = array() ) {
        if ( !$subid ) {
            $this->ajaxJson('70000', '参数错误');
        }
        $uid            = $this->uid;
        $cartModel      = M('OrderShopcart');
        $productModel   = M('ProductSale');
        $specModel      = M('ProductSpec');
        $activityModel  = M('Activity');
        $addressModel   = M('Address');
        $freightModel   = M('Freight');
        $companyModel   = M('Company');

        /* 0代表活动商品不能使用优惠券(默认) 1代表活动商品可以使用优惠券 */
        $activityProductAllowUseCoupon = 0;

        /* 省市区 */
        $regions = M('Regions')->cache(true)->getField('id,name');

        /* 确认订单时查询默认收货地址 提交订单时查询所选的收货地址 */
        $address_id = I('request.address_id', 0, 'intval');
        if ( $address_id ) {
            $where_address['id'] = array('eq', $address_id);
        } else {
            $where_address['type'] = array('eq', 1);
        }
        $where_address['owner'] = array('eq', $uid);
        $list['address'] = $addressModel->field('id address_id,name,region,phone,address')->where($where_address)->find();
        if ( $list['address'] ) {
            $list['address']['region']   = explode(',', $list['address']['region']);
            $list['address']['address']  = $regions[$list['address']['region'][0]] . $regions[$list['address']['region'][1]] . $regions[$list['address']['region'][2]] . $list['address']['address'];
            $list['address']['place_id'] = $list['address']['region'][2] ? $list['address']['region'][2] : $list['address']['region'][1];
        } else {
            $type == 2 && $this->ajaxJson('70000', '收货地址异常');
            $list['address']['address_id']  = '';
            $list['address']['name']        = '';
            $list['address']['phone']       = '';
            $list['address']['address']     = '';
            $list['address']['place_id']    = '';
        }
        
        /* 验证订单重新支付 */
        $where_suborder['subid']  = array('in', $subid);
        $where_suborder['userid'] = array('eq', $uid);
        $subOrderResult = M('OrderSub')->where($where_suborder)->getField('subid', true);
        $subOrderResult && $this->ajaxJson('70000', '订单已生成，请到订单列表进行支付');

        /* 查询购物车信息 */
        $where_cart['status'] = array('in', '1,2');
        $where_cart['subid'] = array('in', $subid);
        $where_cart['userid'] = array('eq', $uid);
        $cartList = $cartModel->field('subid,sellerid,logo,seller,goodid,gpic,goodname,specid,title1,title2,spec_1,spec_2,unitprice,nums,stock')->where($where_cart)->select();
        !$cartList && $this->ajaxJson('70000', '参数错误');

        /* 获取产品、规格 */
        foreach ( $cartList as $clv ) {
            $productIds[] = $clv['goodid'];
            $specIds[]    = $clv['specid'];
        }

        /* 查询产品信息和企业信息 */
        $where_product['l.status'] = array('eq', 1);
        $where_product['l.id']     = array('in', $productIds);
        $where_product['r.status'] = array('eq', 1);
        $productList = $productModel
                     ->alias('l')
                     ->join(C('DB_PREFIX') . 'company r ON l.company_id = r.id', 'LEFT')
                     ->where($where_product)
                     ->getField('l.id,l.title,l.img,l.price,l.oprice,l.activity_price,l.activity_type,l.num,l.is_spec,l.buymin,l.weight,r.id company_id,r.name,r.logo,l.distribution,l.commission1,l.commission2,l.commission3,l.preferential,l.sale_category_id');
        /* 图片处理 */
        !$productList && $this->ajaxJson('70000', '商品状态异常');
        $productList = $this->getAbsolutePath($productList, 'logo', C('HTTP_APPS_IMG') . 'category_default.png');
        $productList = $this->getAbsolutePath($productList, 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');

        /* 查询规格信息 */
        $where_spec['status'] = array('eq', 1);
        $where_spec['id']     = array('in', $specIds);
        $specList = $specModel->where($where_spec)->getField('id,product_id,title1,title2,spec1,spec2,price,oprice,activity_price,stock,buymin,img,weight');
        /* 图片处理 */
        $specList && $specList = $this->getAbsolutePath($specList, 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');

        /* 商品总重量 商品总价 */
        // $totalweight  = array();
        // $productprice = array();

        /* 查询活动信息 */
        $where_activity['status']        = array('eq', 1);
        $where_activity['start_time']    = array('lt', time());
        $where_activity['end_time']      = array('gt', time());
        $where_activity['activity_type'] = array('gt', 0);
        $activityList = $activityModel->where($where_activity)->getField('activity_type,title', true);

        /* 数据处理 将购物车表里取出的数据更新成最新数据 需要比对的话在此代码块中进行 */
        foreach ($cartList as $key => $value) {
            !$productList[$value['goodid']]['id'] && $this->ajaxJson('70000', '商品参数错误');
            !$productList[$value['goodid']]['company_id'] && $this->ajaxJson('70000', '商家异常');
            $value['nums'] < 1 && $this->ajaxJson('70000', '购买数量不能小于1');
            /* 判断是否有规格 */
            if ( $productList[$value['goodid']]['is_spec'] ) {
                /* 有规格商品处理 */
                if ( !$value['specid'] || !$specList[$value['specid']] || $specList[$value['specid']]['product_id'] != $productList[$value['goodid']]['id'] ) {
                    $this->ajaxJson('70000', '规格参数有变动，请重新选择规格购买');
                }
                /* 活动价、图片、库存、最小购买数量、商品重量 取规格里的数据 */
                $cartList[$key]['unitprice'] = $specList[$value['specid']]['price'];
                $cartList[$key]['oprice']    = $specList[$value['specid']]['oprice'];
                $cartList[$key]['gpic']      = $specList[$value['specid']]['img'];
                $cartList[$key]['stock']     = $specList[$value['specid']]['stock'];
                $cartList[$key]['buymin']    = $specList[$value['specid']]['buymin'];
                $cartList[$key]['weight']    = $specList[$value['specid']]['weight'];
            } else {
                /* 无规格商品处理 */
                $value['specid'] && $this->ajaxJson('70000', '商品规格不存在');
                /* 活动价、图片、库存、最小购买数量、商品重量 取产品里的数据 */
                $cartList[$key]['unitprice'] = $productList[$value['goodid']]['price'];
                $cartList[$key]['oprice']    = $productList[$value['goodid']]['oprice'];
                $cartList[$key]['gpic']      = $productList[$value['goodid']]['img'];
                $cartList[$key]['stock']     = $productList[$value['goodid']]['num'];
                $cartList[$key]['buymin']    = $productList[$value['goodid']]['buymin'];
                $cartList[$key]['weight']    = $productList[$value['goodid']]['weight'];
            }
            
            /* 更新其余信息 */
            $cartList[$key]['goodname'] = $productList[$value['goodid']]['title'];
            $cartList[$key]['title1']   = $specList[$value['specid']]['title1'] ? $specList[$value['specid']]['title1'] : '';
            $cartList[$key]['title2']   = $specList[$value['specid']]['title2'] ? $specList[$value['specid']]['title2'] : '';
            $cartList[$key]['spec_1']   = $specList[$value['specid']]['spec1'] ? $specList[$value['specid']]['spec1'] : '';
            $cartList[$key]['spec_2']   = $specList[$value['specid']]['spec2'] ? $specList[$value['specid']]['spec2'] : '';
            /* 拼接规格 */
            if ( $value['specid'] ) {
                $cartList[$key]['spec_info'] = $cartList[$key]['title1'] . '：' . $cartList[$key]['spec_1'];
                $cartList[$key]['title2'] && $cartList[$key]['spec_info'] .= ' ' . $cartList[$key]['title2'] . '：' . $cartList[$key]['spec_2'];
            } else {
                $cartList[$key]['spec_info'] = '';
            }
            /* 判断最小购买数量 */
            $value['nums'] < $cartList[$key]['buymin'] && $this->ajaxJson('70000', '商品：' . $productList[$value['goodid']]['title'] . ' ' . $cartList[$key]['spec_info'] . '最少要购买' . $cartList[$key]['buymin'] . '件');
            /* 判断库存 */
            $value['nums'] > $productList[$value['goodid']]['num'] && $this->ajaxJson('70000', '库存不足');
            /* 如果商品参与活动且在活动时间范围内 则将活动价设置为现价 */
            $cartList[$key]['activity_type']   = $productList[$value['goodid']]['activity_type'];
            $cartList[$key]['activity_status'] = '0';
            if ( $activityList && $activityList[$productList[$value['goodid']]['activity_type']] ) {
                $cartList[$key]['unitprice'] = $productList[$value['goodid']]['is_spec'] ? $specList[$value['specid']]['activity_price'] : $productList[$value['goodid']]['activity_price'];
                $cartList[$key]['activity_status'] = '1';
                $cartList[$key]['activity_name']   = $activityList[$productList[$value['goodid']]['activity_type']];
            }
            /* 同一商家下的所有商品重量 */
            $orderList[$productList[$value['goodid']]['company_id']]['total_weight'] += $cartList[$key]['nums'] * $cartList[$key]['weight'];
            /* 同一商家下的优惠券相关信息 */
            if ( $cartList[$key]['activity_status'] == 1 ) {
                /* 活动商品允许使用优惠券开关 */
                if ( $activityProductAllowUseCoupon == 0 ) {
                    /* 一旦有活动商品则该商家订单都不能使用优惠券 优惠券可抵额度置为-1 */
                    $orderList[$productList[$value['goodid']]['company_id']]['preferential'] = '-1';
                    /* 一旦有活动商品则该商家订单都不能使用优惠券 商家订单优惠券产品分类置为-1 */
                    $orderList[$productList[$value['goodid']]['company_id']]['coupon_product_category_id'] = '-1';
                    /* 一旦有活动商品则该商家订单都不能使用优惠券 商家订单优惠券商家id-1 */
                    $orderList[$productList[$value['goodid']]['company_id']]['coupon_company_id'] = '-1';
                } else {
                    /* 累积商家订单优惠券产品分类 */
                    $orderList[$productList[$value['goodid']]['company_id']]['coupon_product_category_id'] .= $productList[$value['goodid']]['sale_category_id'] . ',';
                    /* 商家订单优惠券商家id设为商家id */
                    $orderList[$productList[$value['goodid']]['company_id']]['coupon_company_id'] = $productList[$value['goodid']]['company_id'];
                }
            } else {
                /* 该商家订单没有活动商品 累积优惠券可抵额度 */
                if ( $orderList[$productList[$value['goodid']]['company_id']]['preferential'] != '-1' ) {
                    $orderList[$productList[$value['goodid']]['company_id']]['preferential'] += $productList[$value['goodid']]['preferential'];
                }
                /* 该商家订单没有活动商品 累积商家订单优惠券产品分类 */
                if ( $orderList[$productList[$value['goodid']]['company_id']]['coupon_product_category_id'] != '-1' ) {
                    $orderList[$productList[$value['goodid']]['company_id']]['coupon_product_category_id'] .= $productList[$value['goodid']]['sale_category_id'] . ',';
                }
                /* 该商家订单没有活动商品 商家订单优惠券商家id设为商家id */
                $orderList[$productList[$value['goodid']]['company_id']]['coupon_company_id'] = $productList[$value['goodid']]['company_id'];
            }
            /* 获取分销信息 */
            if ( $type == 2 ) {
                $cartList[$key]['distribution'] = $productList[$value['goodid']]['distribution'];
                $cartList[$key]['commission1']  = $productList[$value['goodid']]['commission1'];
                $cartList[$key]['commission2']  = $productList[$value['goodid']]['commission2'];
                $cartList[$key]['commission3']  = $productList[$value['goodid']]['commission3'];
            } else {
                unset($cartList[$key]['activity_name']);
            }
            /* 销毁不需要返回客户端的字段 */
            unset($cartList[$key]['title1'], $cartList[$key]['title2'], $cartList[$key]['spec_1'], $cartList[$key]['spec_2'], $cartList[$key]['sellerid'], $cartList[$key]['logo'], $cartList[$key]['seller'], $cartList[$key]['buymin'], $cartList[$key]['weight'], $cartList[$key]['activity_type'], $cartList[$key]['activity_status'], $cartList[$key]['oprice'], $cartList[$key]['stock']);
            /* 按商家分组 */
            $orderList[$productList[$value['goodid']]['company_id']]['company_id']     = $productList[$value['goodid']]['company_id'];
            $orderList[$productList[$value['goodid']]['company_id']]['company_logo']   = $productList[$value['goodid']]['logo'];
            $orderList[$productList[$value['goodid']]['company_id']]['company_name']   = $productList[$value['goodid']]['name'];
            $orderList[$productList[$value['goodid']]['company_id']]['product_list'][] = $cartList[$key];
            /* 同一商家下的所有商品总价 */
            $orderList[$productList[$value['goodid']]['company_id']]['company_total_price'] += $cartList[$key]['nums'] * $cartList[$key]['unitprice'];
            $list['total_price'][$productList[$value['goodid']]['company_id']] = $orderList[$productList[$value['goodid']]['company_id']]['company_total_price'];
            /* 同一商家下的所有商品总数 */
            $orderList[$productList[$value['goodid']]['company_id']]['total_nums']    += $cartList[$key]['nums'];
            /* 所有商家id 查询运费模版时用 */
            $companyIds[] = $productList[$value['goodid']]['company_id'];
        }
        $companyIds = array_unique($companyIds);
        $companyIds = array_values($companyIds);

        /* 提交订单时以商家id为下标重组数据 */
        foreach ($companyIds as $cik => $civ) {
            $postDatas['freight_list'][$civ] = $postDatas['freight_id'][$cik] ? $postDatas['freight_id'][$cik] : 0;
            $postDatas['coupon_list'][$civ]  = $postDatas['coupon_id'][$cik] ? $postDatas['coupon_id'][$cik] : 0;
            $postDatas['message_list'][$civ] = trim($postDatas['buyer_message'][$cik]) !== '' ? $postDatas['buyer_message'][$cik] : '';
        }

        /* 查询用户所有可选择的优惠券 */
        $where_coupon['l.status'] = array('eq', 1);
        $where_coupon['_string'] = '(l.start_time = 0 AND l.end_time = 0) OR (l.start_time = 0 AND l.end_time > '.time().') OR (l.start_time < '.time().' AND l.end_time = 0) OR (l.start_time < '.time().' AND l.end_time > '.time().')';
        // $where_coupon['l.start_time'] = array('lt', time());
        // $where_coupon['l.end_time'] = array('gt', time());
        $where_coupon['l.uid'] = array('eq', $uid);
        $couponList = M('CouponMember')
                    ->alias('l')
                    ->field('l.id coupon_id,r.money,r.title,r.condition,company_id,r.start_time,r.end_time,r.product_category_id')
                    ->join(C('DB_PREFIX') . 'coupon r ON l.coupon_id = r.id', 'LEFT')
                    ->where($where_coupon)
                    ->order('r.money DESC,l.id ASC')
                    ->select();
        /* 对订单信息数据进行二次处理 */
        foreach ($orderList as $ock => $ocv) {
            /* 将优惠券最多可抵金额转化为字符串类型返回客户端 */
            $orderList[$ock]['preferential'] = sprintf('%.2f', $ocv['preferential']);
            /* 将优惠券商品分类id转化为数组 */
            $ocv['coupon_product_category_id'] = array_values(array_unique(explode(',', trim($ocv['coupon_product_category_id'], ','))));
            /* 挑选出可用的优惠券 */
            foreach ($couponList as $cok => $cov) {
                /* 可用优惠券规则：(全场通用类型的优惠券 或者 购买了优惠券指定分类商品 或者 购买了优惠券指定分类商品) 并且 (优惠券无消费条件限制 或者 优惠券消费条件限制小于或等于商家订单金额) */
                if ( ( ( $cov['product_category_id'] == 0 && $cov['company_id'] == 0 ) || in_array($cov['product_category_id'], $ocv['coupon_product_category_id']) || $cov['company_id'] == $ocv['coupon_company_id'] ) && ( $cov['condition'] == 0 || $cov['condition'] <= $ocv['company_total_price'] ) ) {
                    /* 优惠券使用条件 */
                    if ( $cov['condition'] == 0 ) {
                        $cov['condition'] = '无消费条件限制';
                    } else {
                        $cov['condition'] = '满' . $cov['condition'] . '可用';
                    }
                    /* 优惠券使用期限 */
                    if ( $cov['start_time'] > 0 && $cov['end_time'] > 0 ) {
                        $cov['effect_time'] = date('Y-m-d', $cov['start_time']) . ' 至 ' . date('Y-m-d', $cov['end_time']);
                    } elseif ( $cov['start_time'] > 0 && $cov['end_time'] == 0 ) {
                        $cov['effect_time'] = date('Y-m-d', $cov['start_time']) . '之后可用';
                    } elseif ( $cov['start_time'] == 0 && $cov['end_time'] > 0 ) {
                        $cov['effect_time'] = date('Y-m-d', $cov['end_time']) . '之前可用';
                    } else {
                        $cov['effect_time'] = '无使用时间限制';
                    }
                    /* 销毁客户端不需要的数据 */
                    unset($cov['company_id'], $cov['product_category_id'], $cov['start_time'], $cov['end_time']);
                    /* 商家订单可用优惠券数组 */
                    $orderList[$ock]['coupon_list'][$cov['coupon_id']] = $cov;
                    /* 记录商家订单可用优惠券id */
                    $couponIds[$ock][] = $cov['coupon_id'];
                }
            }
            /* 无可以用优惠券时 商家订单可用优惠券数组定义为空数组 */
            !$orderList[$ock]['coupon_list'] && $orderList[$ock]['coupon_list'] = array();
            /* 销毁客户端不需要的数据 */
            unset($orderList[$ock]['coupon_product_category_id'], $orderList[$ock]['coupon_company_id']);
        }
        /* 提交订单时 优惠券相关操作 更新商家小计(扣除优惠券金额) */
        if ( $type == 2 ) {
            foreach ($postDatas['coupon_list'] as $pdck => $pdcv) {
                /* 商家订单使用优惠券id */
                $orderList[$pdck]['coupon_id']  = $pdcv;
                /* 商家订单使用优惠券抵扣金额 */
                $orderList[$pdck]['use_coupon'] = 0;
                /* 商家订单未使用优惠券则略过 */
                if ( $pdcv == 0 ) {
                    continue;
                }
                /**
                 * 客户端优惠券id数据取值错误 或者 服务端优惠券可用判断逻辑错误
                 * ( 商家订单不存在可用优惠券 ) 或者 ( 商家订单存在可用优惠券 并且 商家订单优惠券id不存在于商家订单可用优惠券id数组中 )
                 */
                if ( !$couponIds[$pdck] || ( $couponIds[$pdck] && !in_array($pdcv, $couponIds[$pdck]) ) ) {
                    $this->ajaxJson('70000', '优惠券不可用');
                }
                /* 更新商家小计(减掉优惠券金额) 计算商家订单使用优惠券抵扣金额 */
                if ( $orderList[$pdck]['preferential'] == 0 ) {
                    /* 优惠券可抵金额无上限 */
                    $orderList[$pdck]['company_total_price'] -= $orderList[$pdck]['coupon_list'][$pdcv]['money'];
                    $orderList[$pdck]['use_coupon'] += $orderList[$pdck]['coupon_list'][$pdcv]['money'];
                } elseif ( $orderList[$pdck]['preferential'] > $orderList[$pdck]['coupon_list'][$pdcv]['money'] ) {
                    /* 优惠券可抵金额 大于 优惠券面值 */
                    $orderList[$pdck]['company_total_price'] -= $orderList[$pdck]['coupon_list'][$pdcv]['money'];
                    $orderList[$pdck]['use_coupon'] += $orderList[$pdck]['coupon_list'][$pdcv]['money'];
                } else {
                    /* 优惠券可抵金额 小于或等于 优惠券面值 */
                    $orderList[$pdck]['company_total_price'] -= $orderList[$pdck]['preferential'];
                    $orderList[$pdck]['use_coupon'] += $orderList[$pdck]['preferential'];
                }
            }
        }

        /* 查询所有运费模版 */
        $companyIds[] = 0;
        $where_freight['company_id'] = array('in', $companyIds);
        $where_freight['status']     = array('eq', 1);
        $freightAllList = $freightModel->field('id,company_id,title,piece,postage')->where($where_freight)->order('sort DESC,id DESC')->select();
        foreach ($freightAllList as $falk => $falv) {
            $freightList[$falv['company_id']][] = $falv;
        }

        /* 查询满包邮规则 0代表满多少都不包邮 */
        $freightFree = M('Conf')->where(array('name'=>'freight_free'))->getField('value');

        /* 计算运费所有运费模版所需运费 更新商家小计(加上运费) 统计订单总价 */
        $list['order_total_price'] = 0.00;
        foreach ($orderList as $ok => $ov) {
            /* 商家未设置独立运费模版则使用通用默认模版 */
            if ( !$freightList[$ok] ) {
                $freightList[$ok] = $freightList[0];
            }
            // !$freightList[$ok] && $this->ajaxJson('70000', '商家【' . $ov['company_name'] . '】未设置运费模版');
            foreach ($freightList[$ok] as $fk => $fv) {
                /* 判断有收货地址 则计算运费 */
                if ( $list['address']['address_id'] ) {
                    /* 提交订单时判断运费模版 */
                    if ( $type == 2 && $postDatas['freight_list'][$ok] != $fv['id'] ) {
                        continue;
                    }
                    /* 运费模版id */
                    $orderList[$ok]['freight_list'][$fk]['freight_id'] = $fv['id'];
                    /* 运费模版名称 */
                    $orderList[$ok]['freight_list'][$fk]['freight_title'] = $fv['title'];
                    /* 判断全场包邮规则 */
                    if ( $freightFree > 0 && $freightFree <= $orderList[$ok]['company_total_price'] ) {
                        $orderList[$ok]['freight_list'][$fk]['freight_price'] = '0.00';
                        continue;
                    }
                    /* 解析地区运费规则 */
                    $fv['postage'] = json_decode($fv['postage']);
                    foreach ($fv['postage'] as $fpk => $fpv) {
                        $fpv = get_object_vars($fpv);
                        /* 地区id */
                        $fpv['placeallid'] && $fpv['placeallid'] = explode(',', $fpv['placeallid']);
                        /* 判断收货地址是否在区域内 */
                        if ( $list['address']['place_id'] && in_array($list['address']['place_id'], $fpv['placeallid']) ) {
                            $fv['freight'] = $fpv;
                            break;
                        }
                    }
                    /* 都不在区域内则取默认地区的数据 */
                    !$fv['freight'] && $fv['freight'] = get_object_vars($fv['postage'][0]);
                    /* 1按件数计算运费 2按重量计算运费 */
                    if ($fv['piece'] == 1) {
                        if ( intval($fv['freight']['package_first']) < $ov['total_nums'] ) {
                            $orderList[$ok]['freight_list'][$fk]['freight_price'] = $fv['freight']['freight_first'] + ceil(($ov['total_nums'] - $fv['freight']['package_first']) / $fv['freight']['package_other']) * $fv['freight']['freight_other'];
                        } else {
                            $orderList[$ok]['freight_list'][$fk]['freight_price'] = $fv['freight']['freight_first'];
                        }
                    } else {
                        if ($fv['freight']['package_first'] < $ov['total_weight']) {
                            $orderList[$ok]['freight_list'][$fk]['freight_price'] = $fv['freight']['freight_first'] + ceil(($ov['total_weight'] - $fv['freight']['package_first']) / $fv['freight']['package_other']) * $fv['freight']['freight_other'];
                        } else {
                            $orderList[$ok]['freight_list'][$fk]['freight_price'] = $fv['freight']['freight_first'];
                        }
                    }
                    /* 显示包邮客户端切换物流公司时不方便计算故显示0.00 */
                    $orderList[$ok]['freight_list'][$fk]['freight_price'] = sprintf('%.2f', $orderList[$ok]['freight_list'][$fk]['freight_price']);
                }
            }
            if ( !$orderList[$ok]['freight_list'] ) {
                $type == 2 && $this->ajaxJson('70000', '请选择配送方式');
                /* 无收货地址 则不计算运费 添加一个默认运费模版选择作为提示用 */
                $orderList[$ok]['freight_list'][0]['freight_id']    = '';
                $orderList[$ok]['freight_list'][0]['freight_title'] = '请先选择收货地址';
                $orderList[$ok]['freight_list'][0]['freight_price'] = '';
            }
            $orderList[$ok]['freight_list'] = array_values($orderList[$ok]['freight_list']);
            /* 商家小计加上运费 */
            $orderList[$ok]['company_total_price'] += $orderList[$ok]['freight_list'][0]['freight_price'];
            /* 统计订单总价 */
            $list['order_total_price']             += $orderList[$ok]['company_total_price'];
            /* 将商家小计转化为字符串类型返回客户端 */
            $orderList[$ok]['company_total_price']  = sprintf('%.2f', $orderList[$ok]['company_total_price']);
            /* 销毁不需要返回客户端的字段 */
            unset($orderList[$ok]['total_weight'], $orderList[$ok]['total_nums']);
            /* 确认订单时 商家订单可使用优惠券数组重置下标 */
            if ( $type == 1 ) {
                $orderList[$ok]['coupon_list'] = array_values($orderList[$ok]['coupon_list']);
            }
        }
        /* 订单信息数据数组重置下标 */
        $orderList = array_values($orderList);
        $list['order_list'] = $orderList;

        /* 用户余额 */
        $memberBalance = M('Member')->where(array('uid'=>$uid))->getField('balance');

        /* 订单确认时才需要获取支付方式，余额不足时不显示余额支付选项 */
        if ( $type == 1 ) {
            $list['payment_list'] = R('Payment/getPayment', array($memberBalance, $list['order_total_price'] > $memberBalance ? 0 : 1));
        }

        /* 将订单总计转化为字符串类型返回客户端 */
        $list['order_total_price'] = sprintf('%.2f', $list['order_total_price']);

        return $list;
    }

    /**
     * 订单删除
     */
    public function del() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 查询订单信息 */
            $where['id']  = array('eq', $id);
            $where['uid'] = array('eq', $uid);
            $model = M('Order');
            $orderInfo = $model->field('state,coupon_id')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单不存在');
            !in_array($orderInfo['state'], array(0,3,5,8,9)) && $this->ajaxJson('70000', '订单状态异常');

            /* 删除订单 */
            $del = M('Order')->where($where)->setField('state', -1);
            !$del && $this->ajaxJson('70000', '删除失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 6;
            $clogDatas['uid']      = $uid;
            $clogDatas['remark']   = '买家删除订单';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            /* 恢复锁定的优惠券 */
            if ( $orderInfo['coupon_id'] && $orderInfo['state'] == 0 ) {
                $where_coupon['id'] = array('eq', $orderInfo['coupon_id']);
                M('CouponMember')->where($where_coupon)->setField('status', 1);
            }

            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 立即付款
     */
    public function paynow() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 查询订单信息 */
            $model = M('Order');
            $info = $model
                  ->alias('l')
                  ->field('l.id,l.uid,l.order_trade,l.company_id,l.pay_price,l.payment_id,GROUP_CONCAT(r.goodname SEPARATOR "、") title')
                  ->join(C('DB_PREFIX') . 'order_sub r ON l.id = r.orderid', 'LEFT')
                  ->where(array('l.id'=>$id, 'l.uid'=>$uid, 'l.state'=>0))
                  ->group('l.id,r.goodid')
                  ->find();
            !$info && $this->ajaxJson('70000', '订单异常');

            /* 重新生成订单支付单号 */
            $order_trade = 'N'.time().$uid.date('Ymd').mt_rand(10000,99999);
            $model->where(array('id'=>$id))->setField('order_trade', $order_trade);

            /* 支付流程 */
            $orderPaymentDatas['order_trade'] = $order_trade;
            $orderPaymentDatas['order_total_price'] = $info['pay_price'];
            $orderPaymentDatas['title'] = $info['title'];
            switch ( $info['payment_id'] ) {
                case '1':
                    /* 微信支付 */
                    $orderPaymentDatas['notify_url'] = C('HTTP_ORIGIN') . '/apps/payment/wechatNotify';
                    $list['pay_param'] = R('Payment/wechatpay', array($orderPaymentDatas));
                    $list['pay_param']['timestamp'] = strval($list['pay_param']['timestamp']);
                    break;
                case '2':
                    /* 支付宝支付 */
                    $list['pay_param']                      = R('Payment/alipay', array($orderPaymentDatas));
                    $list['pay_param']['notify_url']        = C('HTTP_ORIGIN') . '/apps/payment/alipayNotify';
                    $list['pay_param']['product_title']     = $orderPaymentDatas['title'];
                    $list['pay_param']['order_id']          = $order_trade;
                    $list['pay_param']['order_total_price'] = sprintf('%.2f', $orderPaymentDatas['order_total_price']);
                    break;
                case '3':
                    /* 账户余额支付 */
                    $orderPaymentDatas['order_list'] = $info;
                    R('Payment/balancepay', array($orderPaymentDatas));
                    break;
                default:
                    $this->ajaxJson('70000', '支付方式不存在');
                    break;
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70001');
    }

    /**
     * 订单确认收货
     */
    public function receive() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 更新订单数据 */
            $datas['state']        = 3;
            $datas['confirm_time'] = time();
            $where['id']    = array('eq', $id);
            $where['uid']   = array('eq', $uid);
            $where['state'] = array('eq', 2);
            $company_id = M('Order')->where($where)->getField('company_id');
            $save = M('Order')->where($where)->save($datas);
            !$save && $this->ajaxJson('70000', '操作失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 4;
            $clogDatas['uid']      = $uid;
            $clogDatas['remark']   = '买家确认收货';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            /* 更新商家利润数据 */
            $withdrawalsDatas['status'] = -2;
            $withdrawalsDatas['etime']  = time();
            M('Withdrawals')->where(array('order_id'=>$id))->save($withdrawalsDatas);

            /* 扣除商家销售利润（发放佣金） */
            $withdrawals = M('Commission')->where(array('order_id'=>$id, 'status'=>0))->sum('commission');
            if ( $withdrawals ) {
                $deductCommissionDatas['order_id']    = $id;
                $deductCommissionDatas['withdrawals'] = $withdrawals;
                $deductCommissionDatas['cname']       = M('Company')->where(array('id'=>$company_id))->getField('name');
                $deductCommissionDatas['cid']         = $company_id;
                $deductCommissionDatas['etime']       = time();
                $deductCommissionDatas['ctime']       = time();
                $deductCommissionDatas['status']      = 4;
                M('Withdrawals')->add($deductCommissionDatas);

                /* 更新佣金数据 */
                M('Commission')->where(array('order_id'=>$id))->setField('status', 1);
            }
            
            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 订单查看物流
     */
    public function logistics() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 查询运费模版id */
            $map['id']  = array('eq', $id);
            $map['uid'] = array('eq', $uid);
            $freight_id = M('Order')->where($map)->getField('freight_id');

            /* 查询订单信息 */
            $where['l.id']  = array('eq', $id);
            $where['l.uid'] = array('eq', $uid);
            if ( $freight_id == 1 ) {
                $info = M('Order')
                      ->alias('l')
                      ->field('l.state,e.word,l.express_no,e.title')
                      ->join(C('DB_PREFIX') . 'express e ON l.express_id = e.id', 'LEFT')
                      ->where($where)
                      ->find();
            } else {
                $info = M('Order')
                      ->alias('l')
                      ->field('l.state,e.word,l.express_no,e.title')
                      ->join(C('DB_PREFIX') . 'freight f ON l.freight_id = f.id', 'LEFT')
                      ->join(C('DB_PREFIX') . 'express e ON f.delivery_id = e.id', 'LEFT')
                      ->where($where)
                      ->find();
            }
            !$info && $this->ajaxJson('70000', '订单不存在');
            !in_array($info['state'], array(2,3,8)) && $this->ajaxJson('70000', '订单状态异常');
            if ( !$info['word'] ) {
                $info = M('Order')
                      ->alias('l')
                      ->field('l.state,e.word,l.express_no,e.title')
                      ->join(C('DB_PREFIX') . 'express e ON l.express_id = e.id', 'LEFT')
                      ->where($where)
                      ->find();
            }

            $url = 'http://www.kuaidi100.com/query?type=' . $info['word'] . '&postid=' . $info['express_no'];
            $logistics = json_decode($this->curlGet($url), true);
            switch ( $logistics['state'] ) {
                case 0:
                    $logistics['state'] = '【在途】货物处于运输过程中';
                    break;
                case 1:
                    $logistics['state'] = '【揽件】货物已由快递公司揽收';
                    break;
                case 2:
                    $logistics['state'] = '【疑难】货物寄送过程出了问题';
                    break;
                case 3:
                    $logistics['state'] = '【签收】收件人已签收';
                    break;
                case 4:
                    $logistics['state'] = '【退签】货物由于用户拒签、超区等原因退回';
                    break;
                case 5:
                    $logistics['state'] = '【派件】快递正在进行同城派件';
                    break;
                case 6:
                    $logistics['state'] = '【退回】货物正处于退回发件人的途中';
                    break;
                default:
                    $logistics['state'] = '【异常】';
                    $logistics['data'][0] = array('time'=>'','context'=>$logistics['message'],'ftime'=>'');
                    break;
            }

            /* 组装数据返回客户端 */
            $list['express_info']['express_name'] = $info['title'];
            $list['express_info']['express_no'] = '运单编号：' . $info['express_no'];
            $list['express_info']['express_status'] = '物流状态：' . $logistics['state'];
            $list['express_list'] = $logistics['data'];
            
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 订单申请退款
     */
    public function refundApply() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $reason = I('post.reason');
            !$reason && $this->ajaxJson('70000', '请填写退款理由');
            $uid = $this->uid;

            /* 每天最多申请退款3次 */
            $where_refund['order_id'] = array('eq', $id);
            $where_refund['action']   = array('eq', 7);
            $where_refund['addtime']  = array('gt', date('Y-m-d 00:00:00'));
            $refundNum = M('OrderClog')->where($where_refund)->count('id');
            $refundNum > 2 && $this->ajaxJson('70000', '每天最多申请退款3次');

            /* 更新订单状态 */
            $where['id']     = array('eq', $id);
            $where['uid']    = array('eq', $uid);
            $where['state']  = array('in', '1,2,7');
            $datas['state']  = 6;
            $datas['reason'] = $reason;
            $datas['etime']  = time();
            $save = M('Order')->where($where)->save($datas);
            !$save && $this->ajaxJson('70000', '操作失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 7;
            $clogDatas['uid']      = $uid;
            $clogDatas['remark']   = '买家申请退款';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            /* 查询订单信息 */
            $where_info['id']  = array('eq', $id);
            $where_info['uid'] = array('eq', $uid);
            $orderInfo = M('Order')->field('id,company_id')->where($where_info)->find();

            /* 站内信提醒  */
            R('Apps/General/sendSiteMessage', array('订单退款申请提醒', '您有订单退款申请，订单编号：' . $orderInfo['id'] . '，请及时处理。', $orderInfo['company_id']));

            /* 获取商家手机号 */
            $mobile = M('CompanyLink')->where(array('company_id'=>$orderInfo['company_id']))->getField('subphone');
            /* 发送数据 */
            $sendDatas = array($orderInfo['id']);
            /* 发送短信模版 */
            $result = $this->smsSend( $mobile, $sendDatas, 'orderRefundTemplateId' );

            /* 更新商家利润数据 */
            // M('Withdrawals')->where(array('order_id'=>$id))->setField('status', -4);

            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 订单取消退款
     */
    public function refundCancle() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 查询订单状态，订单物流单号lp_order.express_no字段为空则退回待发货状态，否则退回待收货状态 */
            $where['id']    = array('eq', $id);
            $where['uid']   = array('eq', $uid);
            $where['state'] = array('in', '6,7');
            $express_no = M('Order')->where($where)->getField('express_no');

            /* 更新订单状态 */
            $datas['etime'] = time();
            if ( $express_no ) {
                $datas['state'] = 2;
                $save = M('Order')->where($where)->save($datas);
            } else {
                $datas['state'] = 1;
                $save = M('Order')->where($where)->save($datas);
            }
            !$save && $this->ajaxJson('70000', '操作失败');

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 11;
            $clogDatas['uid']      = $uid;
            $clogDatas['remark']   = '买家取消退款';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            /* 更新商家利润数据 */
            // M('Withdrawals')->where(array('order_id'=>$id))->setField('status', -3);

            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 订单确认收款
     */
    public function refundConfirm() {
        if ( IS_POST ) {
            $this->ajaxJson('70000', '该功能已关闭');
            // $id = I('post.id');
            // !$id && $this->ajaxJson('70000', '参数错误');
            // $uid = $this->uid;

            // $where['id']    = array('eq', $id);
            // $where['uid']   = array('eq', $uid);
            // $where['state'] = array('eq', 9);
            // $datas['state'] = 5;
            // $datas['etime'] = time();
            // $save = M('Order')->where($where)->save($datas);
            // !$save && $this->ajaxJson('70000', '操作失败');

            /* 生成订单操作记录 */
            // $clogDatas['order_id'] = $id;
            // $clogDatas['action']   = 12;
            // $clogDatas['uid']      = $uid;
            // $clogDatas['remark']   = '买家确认收款';
            // $clogDatas['addtime']  = time();
            // M('OrderClog')->add($clogDatas);

            /* 更新商家利润数据 */
            // M('Withdrawals')->where(array('order_id'=>$id))->setField('status', 5);

            /* 更新佣金数据 */
            // M('Commission')->where(array('order_id'=>$id))->setField('status', -1);

            // $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 提醒发货
     */
    public function remind() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $company_id = I('post.company_id', 0, 'intval');
            !$company_id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 每天只能提醒一次 */
            $model          = M('Order');
            $where['id']    = array('eq', $id);
            $where['uid']   = array('eq', $uid);
            $where['state'] = array('eq', 1);
            $orderInfo = $model->field('id,remind_time,company_id')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单状态异常');
            date('Ymd', $orderInfo['remind_time']) == date('Ymd') && $this->ajaxJson('70000', '每天只能提醒一次');
            $save = $model->where($where)->setField('remind_time', time());
            !$save && $this->ajaxJson('70000', '操作失败');
            /* 站内信提醒  */
            R('Apps/General/sendSiteMessage', array('订单发货提醒', '订单(' . $orderInfo['id'] . ')的买家提醒您尽快发货，请及时处理。', $orderInfo['company_id']));
            /* 获取商家手机号 */
            $mobile = M('CompanyLink')->where(array('company_id'=>$orderInfo['company_id']))->getField('subphone');
            /* 发送数据 */
            $sendDatas = array($orderInfo['id']);
            /* 发送短信模版 */
            $result = $this->smsSend( $mobile, $sendDatas, 'orderSendTemplateId' );
            /* 极光推送提醒  */
            

            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 提醒打款
     */
    public function remindRemittance() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $company_id = I('post.company_id', 0, 'intval');
            !$company_id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 每天只能提醒一次 */
            $model          = M('Order');
            $where['id']    = array('eq', $id);
            $where['uid']   = array('eq', $uid);
            $where['state'] = array('eq', 4);
            $orderInfo = $model->field('id,remind_time,company_id')->where($where)->find();
            !$orderInfo && $this->ajaxJson('70000', '订单状态异常');
            date('Ymd', $orderInfo['remind_time']) == date('Ymd') && $this->ajaxJson('70000', '每天只能提醒一次');
            $save = $model->where($where)->setField('remind_time', time());
            !$save && $this->ajaxJson('70000', '操作失败');
            /* 站内信提醒  */
            R('Apps/General/sendSiteMessage', array('订单退款打款提醒', '订单(' . $orderInfo['id'] . ')的买家提醒您尽快打款，请及时处理。', $orderInfo['company_id']));
            /* 获取商家手机号 */
            $mobile = M('CompanyLink')->where(array('company_id'=>$orderInfo['company_id']))->getField('subphone');
            /* 发送数据 */
            $sendDatas = array($orderInfo['id']);
            /* 发送短信模版 */
            $result = $this->smsSend( $mobile, $sendDatas, 'orderRemittanceTemplateId' );
            /* 极光推送提醒  */

            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 订单评价
     */
    public function comment() {
        if ( IS_POST ) {
            /* 订单id */
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '订单id异常');
            /* 企业id */
            $company_id = I('post.company_id', 0, 'intval');
            !$company_id && $this->ajaxJson('70000', '企业id异常');
            /* 商品id数组 */
            $product_id = I('post.product_id');
            !$product_id && $this->ajaxJson('70000', '商品id异常');
            $product_id = is_array($product_id) ? $product_id : json_decode($product_id, true);
            /* 商品评论内容数组 */
            $content = I('post.content');
            !$content && $this->ajaxJson('70000', '请填写商品评论');
            $content = is_array($content) ? $content : json_decode($content, true);
            count($product_id) != count($content) && $this->ajaxJson('70000', '商品评论数量异常');
            /* 商品评论图片数组 */
            $img = I('post.img');
            !$img && $this->ajaxJson('70000', '商品评论图片异常');
            $img = is_array($img) ? $img : json_decode($img, true);
            count($product_id) != count($img) && $this->ajaxJson('70000', '商品评论图片数量异常');
            /* 企业评论图片 */
            $company_img = I('post.company_img');
            /* 企业评论内容 */
            $company_content = I('post.company_content');
            // !$company_content && $this->ajaxJson('70000', '请填写企业评论');
            /* 企业星级 */
            $company_praise = I('post.company_praise', 5, 'intval');
            !in_array($company_praise, array(1,2,3,4,5)) && $this->ajaxJson('70000', '企业星级异常');
            /* 商品星级 */
            $product_praise = I('post.product_praise');
            !$product_praise && $this->ajaxJson('70000', '商品星级异常');
            $product_praise = is_array($product_praise) ? $product_praise : json_decode($product_praise, true);
            count($product_id) != count($product_praise) && $this->ajaxJson('70000', '商品星级异常数量异常');
            /* 用户id */
            $uid = $this->uid;

            /* 生成商品评论数据 */
            foreach ($product_id as $key => $value) {
                !$content[$key] && $this->ajaxJson('70000', '请填写第'.($key+1).'个商品评论');
                $productDatas[$key]['order_id'] = $id;
                $productDatas[$key]['uid'] = $uid;
                $productDatas[$key]['pid'] = $value;
                $productDatas[$key]['content'] = $content[$key] ? $content[$key] : '';
                $productDatas[$key]['img'] = $img[$key] ? implode(',', $img[$key]) : '';
                $productDatas[$key]['praise'] = $product_praise[$key] ? $product_praise[$key] : 1;
                $productDatas[$key]['ctime'] = time();
                $productDatas[$key]['status'] = 1;
            }
            $prodcutAdd = M('ProductComment')->addAll($productDatas);

            /* 生成企业评论数据 */
            $companyDatas['order_id'] = $id;
            $companyDatas['uid'] = $uid;
            $companyDatas['pid'] = implode(',', $product_id);
            $companyDatas['cid'] = $company_id;
            $companyDatas['content'] = $company_content ? $company_content : '';
            $companyDatas['img'] = $company_img ? $company_img : '';
            $companyDatas['praise'] = $company_praise;
            $companyDatas['ctime'] = time();
            $companyDatas['status'] = 1;
            $companyAdd = M('CompanyComment')->add($companyDatas);
            
            if ( !$prodcutAdd || !$companyAdd ) {
                M('ProductComment')->where(array('order_id'=>$id))->delete();
                M('CompanyComment')->where(array('order_id'=>$id))->delete();
                $this->ajaxJson('70000', '评论失败');
            }

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $id;
            $clogDatas['action']   = 5;
            $clogDatas['uid']      = $uid;
            $clogDatas['remark']   = '买家评价订单';
            $clogDatas['addtime']  = time();
            M('OrderClog')->add($clogDatas);

            /* 改变订单状态 */
            $orderDatas['state']        = 8;
            $orderDatas['comment_time'] = time();
            M('Order')->where(array('id'=>$id))->save($orderDatas);

            $this->ajaxJson('40000', '评论成功');
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