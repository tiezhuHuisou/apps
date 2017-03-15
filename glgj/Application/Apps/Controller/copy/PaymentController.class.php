<?php
namespace Apps\Controller;
use Think\Controller;

class PaymentController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 获取微信配置参数
     */
    public function getWechatConf() {
        /* 读取微信支付配置信息 */
        $where['name'] = array('in', array('weixin_name', 'weixin_appsecret', 'weixin_mchid', 'weixin_key', 'weixin_appid'));
        $conf = M('Conf')->where($where)->getField('name,value', true);
        return $conf;
    }

    /**
     * 微信支付
     * @param  array   $orderDatas  [订单数据数组]
     * @return array                [客户端所需的数据]
     */
    public function wechatpay( $orderDatas ) {
        $conf = $this->getWechatConf();

        /* 设置微信支付参数 */
        vendor('Wxpay.WxPay','','.Api.php');
        /* 统一下单 */
        $input = new \WxPayUnifiedOrder();
        /* 设置商户分配的唯一32位key */
        \WxPayConfig::$KEY = $conf['weixin_key'];
        /* 微信分配的公众账号ID */
        $input->SetAppid($conf['weixin_appid']);
        /* 微信支付分配的商户号 */
        $input->SetMch_id($conf['weixin_mchid']);
        /* 设置商户分配的唯一32位key (已废弃) */
        // $input->SetKey($conf['weixin_key']);
        /* 商品描述 */
        $input->SetBody($orderDatas['title']);
        /* 商品名称明细列表 */
        // $input->SetDetail($orderDatas['detail']);
        /* 附加数据，异步通知中原样返回 */
        // $input->SetAttach('test');
        /* 支付单号 */
        $input->SetOut_trade_no($orderDatas['order_trade']);
        /* 设置订单总金额，单位为分，只能为整数 */
        $input->SetTotal_fee(intval($orderDatas['order_total_price'] * 100));
        /* 订单生成时间 */
        // $input->SetTime_start(date('YmdHis'));
        /* 订单失效时间 */
        // $input->SetTime_expire(date('YmdHis', time() + 600));
        /* 接收微信支付异步通知回调地址 */
        $input->SetNotify_url($orderDatas['notify_url']);
        /* 交易类型 */
        $input->SetTrade_type('APP');
        /* 接收xml数据解析为array数组 */
        $result = \WxPayApi::unifiedOrder($input);
        /* 判断错误信息 */
        $result['return_code'] !== 'SUCCESS' && $this->ajaxJson('70000', $result['return_msg']);
        $result['result_code'] !== 'SUCCESS' && $this->ajaxJson('70000', $result['err_code_des']);

        /* 生成签名 */
        vendor('WxpayAPI.WxPay','','.Data.php');
        $obj = new \WxPayDataBase();
        $obj->setData('appid', $conf['weixin_appid']);
        $obj->setData('partnerid', $conf['weixin_mchid']);
        $obj->setData('prepayid', $result['prepay_id']);
        $obj->setData('package', 'Sign=WXPay');
        $obj->setData('noncestr', $result['nonce_str']);
        $obj->setData('timestamp', time());
        $obj->SetSign();
        $sign = $obj->GetValues();

        $list = array_merge($sign, $conf);

        return $list;
    }

    /**
     * 微信支付异步通知处理函数
     */
    public function wechatNotify() {
        /* 获取通知数据 */
        $data = $GLOBALS['HTTP_RAW_POST_DATA'];
        if ( !$data ) {
            $msg['return_code'] = 'FAIL';
            $msg['return_msg']  = '通知数据不正确';
            echo $this->arrayToXml($msg);
            exit();
        }

        /* xml转化为array 禁止引用外部xml实体<xml></xml> */
        libxml_disable_entity_loader(true); 

        /* CDATA设置为文本节点 生成xml节点对象 */
        $data = $this->xmlToArray($data);
        /* 签名验证 */
        if ( $data['return_code'] == 'SUCCESS' ) {
            /* 支付单号 */
            $order_trade = $data['out_trade_no'];
            /* 支付金额 */
            $total_fee   = $data['total_fee'] / 100;

            /* 查询订单信息 */
            $orderList = M('Order')->where(array('order_trade'=>$order_trade))->select();
            /* 订单总额 */
            $orderTotalPrice = 0.00;
            foreach ($orderList as $key => $value) {
                if ( $value['state'] != 0 ) {
                    $msg['return_code'] = 'FAIL';
                    $msg['return_msg']  = '订单状态异常';
                    echo $this->arrayToXml($msg);
                    exit;
                }
                $orderTotalPrice += $value['pay_price'];
                $orderList[$key]['order_trade'] = $data['transaction_id'];
            }

            /* 数据库里的订单支付总额和微信返回的支付金额对比 */
            if ( $total_fee != $orderTotalPrice ) {
                $msg['return_code'] = 'FAIL';
                $msg['return_msg']  = '实际支付金额不等于订单金额,请重新支付';
                echo $this->arrayToXml($msg);
                exit;
            }

            /* 支付成功后续操作 */
            $orderDatas['order_list'] = $orderList;
            $this->paySuccessAction($orderDatas);

            $msg['return_code'] = 'SUCCESS';
            $msg['return_msg']  = 'OK';
            echo $this->arrayToXml($msg);
            exit;
        } else {
            $msg['return_code'] = 'FAIL';
            $msg['return_msg']  = '支付失败,微信返回支付失败';
            echo $this->arrayToXml($msg);
            exit;
        }
    }

    /**
     * 微信充值异步通知处理函数
     */
    public function wechatRechargeNotify() {
        /* 获取通知数据 */
        $data = $GLOBALS['HTTP_RAW_POST_DATA'];
        if ( !$data ) {
            $msg['return_code'] = 'FAIL';
            $msg['return_msg']  = '通知数据不正确';
            echo $this->arrayToXml($msg);
            exit();
        }

        /* xml转化为array 禁止引用外部xml实体<xml></xml> */
        libxml_disable_entity_loader(true); 

        /* CDATA设置为文本节点 生成xml节点对象 */
        $data = $this->xmlToArray($data);
        /* 签名验证 */
        if ( $data['return_code'] == 'SUCCESS' ) {
            /* 查询充值记录 */
            $orderInfo = M('RechargeLogs')->where(array('id'=>$data['out_trade_no'], 'status'=>0))->find();
            if ( !$orderInfo ) {
                $msg['return_code'] = 'FAIL';
                $msg['return_msg']  = '订单不存在或已支付';
                echo $this->arrayToXml($msg);
                exit;
            }
            
            /* 交易流水号 */
            $orderInfo['order_trade'] = $data['trade_no'];

            /* 数据库里的订单支付总额和微信返回的支付金额对比 */
            if ( ($data['total_fee'] / 100) != $orderInfo['pay_price'] ) {
                $msg['return_code'] = 'FAIL';
                $msg['return_msg']  = '实际支付金额不等于订单金额,请重新支付';
                echo $this->arrayToXml($msg);
                exit;
            }

            /* 支付成功后续操作 */
            $this->rechargeSuccessAcion($orderInfo);

            $msg['return_code'] = 'SUCCESS';
            $msg['return_msg']  = 'OK';
            echo $this->arrayToXml($msg);
            exit;
        } else {
            $msg['return_code'] = 'FAIL';
            $msg['return_msg']  = '支付失败,微信返回支付失败';
            echo $this->arrayToXml($msg);
            exit;
        }
    }

    /**
     * 获取支付宝支付配置信息
     */
    public function getAlipayConf() {
        /* 读取微信支付配置信息 */
        $where['name'] = array('in', array('alipay_id', 'alipay_code', 'alipay_account', 'alipay_name'));
        $conf = M('Conf')->where($where)->getField('name,value', true);

        return $conf;
    }

    /**
     * 支付宝支付
     * @param  array   $orderDatas [订单数据数组]
     * @return array               [客户端所需的数据]
     */
    public function alipay( $orderDatas ) {
        /* 读取微信支付配置信息 */
        $conf = $this->getAlipayConf();

        /* 服务端返回签名等操作暂时没做，现在是放到客户端去操作... */

        return $conf;
    }

    /**
     * 支付宝支付异步通知处理函数
     */
    public function alipayNotify() {
        $notify_data = I('request.');
        if ( $notify_data['trade_status'] == 'TRADE_SUCCESS' ) {
            /* 支付单号 */
            $order_trade = $notify_data['out_trade_no'];

            /* 查询订单信息 */
            $where_order['order_trade'] = array('eq', $order_trade);
            $where_order['state']       = array('eq',0);
            $orderList = M('Order')->where($where_order)->select();
            if ( $orderList ) {
                /* 支付金额 */
                $total_fee = $notify_data['price'];

                /* 订单总额 */
                $orderTotalPrice = 0.00;
                foreach ($orderList as $key => $value) {
                    $orderTotalPrice += $value['pay_price'];
                    $orderList[$key]['order_trade'] = $notify_data['trade_no'];
                }

                /* 数据库里的订单支付总额和支付宝返回的支付金额对比 */
                if ( $total_fee != $orderTotalPrice ) {
                    echo "FAIL";
                    exit;
                }

                /* 支付成功后续操作 */
                $orderDatas['order_list'] = $orderList;
                $this->paySuccessAction($orderDatas);
            } else {
                echo "FAIL";
                exit;
            }
            echo "SUCCESS";
            exit; 
        } else {
            echo "FAIL";
            exit;
        }
    }

    /**
     * 支付宝充值异步通知处理函数
     */
    public function alipayRechargeNotify() {
        $notify_data = I('request.');
        if ( $notify_data['trade_status'] == 'TRADE_SUCCESS' ) {
            /* 查询订单信息 */
            $where_order['id']     = array('eq', $notify_data['out_trade_no']);
            $where_order['status'] = array('eq', 0);
            $orderInfo = M('RechargeLogs')->where($where_order)->find();
            if ( $orderInfo ) {
                /* 交易流水号 */
                $orderInfo['order_trade'] = $notify_data['trade_no'];

                /* 数据库里的订单支付总额和支付宝返回的支付金额对比 */
                if ( $notify_data['price'] != $orderInfo['pay_price'] ) {
                    echo "FAIL";
                    exit;
                }

                /* 充值成功后续操作 */
                $this->rechargeSuccessAcion($orderInfo);
            } else {
                echo "FAIL";
                exit;
            }
            echo "SUCCESS";
            exit; 
        } else {
            echo "FAIL";
            exit;
        }
    }

    /**
     * 账户余额支付
     * @param  array $orderDatas [订单数据数组]
     */
    public function balancepay( $orderDatas ) {
        $uid = $orderDatas['order_list'][0]['uid'];
        /* 扣除用户余额 */
        $result = M('Member')->where(array('uid'=>$uid))->setDec('balance', $orderDatas['order_total_price']);

        if ( $result ) {
            /* 支付成功后续操作 */
            $this->paySuccessAction($orderDatas);

            $this->ajaxJson('40000', '支付成功');
        }
        $this->ajaxJson('70000', '支付失败');
    }

    /**
     * 获取支付方式
     * @param  float   $memberBalance [会员账户余额]
     * @param  integer $balanceFlag   [是否返回账户余额支付选项]
     * @return array                  [支付方式数据数组]
     */
    public function getPayment( $memberBalance = 0.00, $balanceFlag = 1 ) {
        /* 默认支付方式 */
        $list = array(
            array('payment_id' => '3', 'payment_icon' => C('HTTP_APPS_IMG') . 'balancepay_icon.png', 'payment_title' => '账户余额支付(￥' . $memberBalance . ')')
        );
        /* 微信支付配置检测 */
        $wechatConf = $this->getWechatConf();
        if ( $wechatConf['weixin_name'] && $wechatConf['weixin_appsecret'] && $wechatConf['weixin_mchid'] && $wechatConf['weixin_key'] && $wechatConf['weixin_appid'] ) {
            $list[] = array('payment_id' => '1', 'payment_icon' => C('HTTP_APPS_IMG') . 'wechatpay_icon.png', 'payment_title' => '微信支付');
        }
        /* 支付宝支付配置检测 */
        $alipayConf = $this->getAlipayConf();
        if ( $alipayConf['alipay_id'] && $alipayConf['alipay_code'] && $alipayConf['alipay_account'] && $alipayConf['alipay_name'] ) {
            $list[] = array('payment_id' => '2', 'payment_icon' => C('HTTP_APPS_IMG') . 'alipay_icon.png', 'payment_title' => '支付宝支付');
        }
        /* 账户余额支付配置检测 */
        $balanceFlag != 1 && array_shift($list);
        return $list;
    }

    /**
     * 充值成功后续操作
     * @param  Array $orderInfo [description]
     * 更新订单部分数据
     * 用户余额增加
     */
    public function rechargeSuccessAcion( $orderInfo ) {
        /* 更新订单部分数据 */
        $datas['trade_no'] = $orderInfo['order_trade'];
        $datas['status']   = 1;
        $datas['pay_time'] = time();
        $save = M('RechargeLogs')->where(array('id'=>$orderInfo['id']))->save($datas);
        if ( !$save ) {
            return false;
        }

        /* 用户余额增加 */
        $save = M('Member')->where(array('uid'=>$orderInfo['uid']))->setInc('balance', $orderInfo['total_price']);
        if ( !$save ) {
            return false;
        }
        return true;
    }

    /**
     * 支付成功后续操作
     * @param  Array   $orderInfo  [订单数据数组]
     * 更新订单部分数据
     * 生成订单操作记录
     * 生成订单支付记录
     * 商家销售利润记录
     * 计算佣金
     */
    public function paySuccessAction( $orderDatas ) {
        $model            = M('Order');
        $subModel         = M('OrderSub');
        $orderClogModel   = M('OrderClog');
        $orderPayLogModel = M('OrderPayLog');
        $withdrawalsModel = M('Withdrawals');
        $productModel     = M('ProductSale');
        $specModel        = M('ProductSpec');
        $memberModel      = M('Member');

        /* 使用优惠券id数组 */
        $couponList = array();

        /* 获取分销功能开关 */
        $distributionFlag = M('Conf')->where(array('name'=>'distribution'))->getField('value');

        /* 支付成功后续操作 */
        foreach ($orderDatas['order_list'] as $key => $value) {
            /* 改变订单状态和使用余额字段 */
            $datas['state']       = 1;
            $value['payment_id'] == 3 && $datas['use_balance'] = $value['pay_price'];
            $datas['pay_time']    = time();
            $model->where(array('id'=>$value['id']))->save($datas);

            /* 站内信提醒  */
            R('Apps/General/sendSiteMessage', array('您有新订单', '订单编号：' . $value['id'] . '，请及时处理。', $value['company_id']));

            /* 获取商家手机号 */
            $mobile = M('CompanyLink')->where(array('company_id'=>$value['company_id']))->getField('subphone');
            /* 发送数据 */
            $sendDatas = array($value['id']);
            /* 发送短信模版 */
            $result = $this->smsSend( $mobile, $sendDatas, 'orderPayTemplateId' );

            /* 生成订单操作记录 */
            $clogDatas['order_id'] = $value['id'];
            $clogDatas['action']   = 2;
            $clogDatas['uid']      = $value['uid'];
            $clogDatas['remark']   = '买家支付成功';
            $clogDatas['addtime']  = time();
            $orderClogModel->add($clogDatas);

            /* 生成订单支付记录 */
            $payLogDatas['order_id']   = $value['id'];
            $payLogDatas['trade_no']   = $value['order_trade'];
            $payLogDatas['pay_price']  = $value['pay_price'];
            $payLogDatas['payment_id'] = $value['payment_id'];
            $payLogDatas['add_time']   = time();
            $orderPayLogModel->add($payLogDatas);

            /* 商家销售利润记录 */
            $withdrawalsDatas['order_id']    = $value['id'];
            $withdrawalsDatas['withdrawals'] = $value['pay_price'];
            $withdrawalsDatas['task']        = '';
            $withdrawalsDatas['cname']       = $value['company_name'];
            $withdrawalsDatas['payee']       = '';
            $withdrawalsDatas['type']        = 0;
            $withdrawalsDatas['cid']         = $value['company_id'];
            $withdrawalsDatas['etime']       = time();
            $withdrawalsDatas['ctime']       = time();
            $withdrawalsDatas['status']      = -3;
            $withdrawalsModel->add($withdrawalsDatas);

            /* 更新商品库存、销量，有规格的要同时更新商品和规格的库存、销量 */
            $orderSubList = $subModel->where(array('orderid'=>$value['id']))->select();
            foreach ($orderSubList as $osv) {
                $productModel->where(array('id'=>$osv['goodid']))->setInc('sale_num', $osv['nums']);
                $productModel->where(array('id'=>$osv['goodid']))->setDec('num', $osv['nums']);
                if ( $osv['specid'] > 0 ) {
                    $specModel->where(array('id'=>$osv['specid']))->setInc('sale_num', $osv['nums']);
                    $specModel->where(array('id'=>$osv['specid']))->setDec('stock', $osv['nums']);
                }
            }

            /* 判断是否开启分销功能 */
            if ( $distributionFlag == 1 ) {
                /* 计算佣金 */
                $this->calculateCommission($value['id'], $value['uid'], $value['company_id'] , floatval($value['use_coupon']));
            }

            /* 记录使用优惠券id */
            $value['coupon_id'] && $couponList[] = $value['coupon_id'];
        }
        /* 锁定优惠券 */
        if ( $couponList ) {
            $where_coupon['id'] = array('in', $couponList);
            M('CouponMember')->where($where_coupon)->setField('status', -1);
        }

        /* 销毁变量 */
        unset($clogDatas, $payLogDatas, $withdrawalsDatas, $orderSubList, $couponList);
    }

    /**
     * 计算佣金
     * @param  string  $orderId     [订单id]
     * @param  string  $uid         [用户id]
     * @param  float   $use_coupon  [订单使用优惠券金额]
     * 
     * 理论上循环内再查询表做这么多操作是很不科学的
     * 但考虑到实际情况一次性购买N个商家的商品情况不多
     * 并且便于维护与逻辑性等因素
     * 将所有佣金相关操作都提取到该方法中执行
     * 
     * 佣金规则：
     * 使用了优惠券或参与活动的订单不计算佣金计算
     * 只计算三级佣金：本级+上级+上上级 相关字段：lp_member.pid
     * 被冻结状态下不计算佣金 相关字段：lp_member.status
     */
    public function calculateCommission( $orderId = '', $uid = '', $company_id = 0, $use_coupon = 0.00 ) {
        /* 验证参数 使用优惠券不参与佣金计算 */
        if ( !$orderId || !$uid || !$company_id || $use_coupon > 0 ) {
            return false;
        }

        /* 查询本级用户信息 */
        $memberModel = M('Member');
        $myself = $memberModel->field('pid,name,mphone,status')->where(array('uid'=>$uid))->find();
        $myself['name'] = $myself['name'] ? $myself['name'] : $myself['mphone'];
        /* 冻结状态不计算佣金 */
        if ( $myself['status'] != 1 ) {
            return true;
        }
        
        /* 查询订单商品信息 */
        $productList = M('OrderSub')->field('goodid,goodname,spec_info,distribution,commission1,commission2,commission3,activity_name,nums')->where(array('orderid'=>$orderId))->select();

        $where_distribution['name'] = array('in', array('commission1', 'commission2', 'commission3'));
        $distributionConf = M('Conf')->where($where_distribution)->getField('name,value', true);

        /* 计算各等级佣金 并构建备注信息(佣金日志) */
        $commission1 = 0.00;
        $commission2 = 0.00;
        $commission3 = 0.00;
        $remark = array('订单佣金');
        foreach ($productList as $key => $value) {
            if ( $value['distribution'] == 1 ) {
                /* 参与了活动的订单不参与佣金计算 */
                if ( $value['activity_name'] ) {
                    $remark[] = '商品：'.$value['goodname'].' '.$value['spec_info'].' 为【'.$value['activity_name'].'】活动商品，故不计算佣金';
                } else {
                    /* 商品参与分销 并且 3级分销利润都为0 则 读取全局分销利润设置 */
                    if ( $value['commission1'] == 0 && $value['commission2'] == 0 && $value['commission3'] == 0 ) {
                        $commission1 += $distributionConf['commission1'] * $value['nums'];
                        $commission2 += $distributionConf['commission2'] * $value['nums'];
                        $commission3 += $distributionConf['commission3'] * $value['nums'];
                    } else {
                        $commission1 += $value['commission1'] * $value['nums'];
                        $commission2 += $value['commission2'] * $value['nums'];
                        $commission3 += $value['commission3'] * $value['nums'];
                    }
                }
            } else {
                /* 不参与分销的商品不计算佣金 */
                $remark[] = '商品：'.$value['goodname'].' '.$value['spec_info'].' 设置为不参与分销，故不计算佣金';
            }
        }
        $remark = implode('；', $remark);

        /* 计算本级佣金 */
        if ( $commission1 > 0 ) {
            $model = M('Commission');
            /* 生成佣金 */
            $datas['uid']             = $uid;
            $datas['order_id']        = $orderId;
            $datas['commission']      = $commission1;
            $datas['source_uid']      = $uid;
            $datas['withdrawal_type'] = 0;
            $datas['ctime']           = time();
            $datas['status']          = 0;
            $datas['remark']          = $remark;
            $model->add($datas);
            unset($datas);
            /* 发送收益消息：站内信 */
            $this->sendCommissionMessage($uid, '您成功付款一笔订单( ' . $orderId . ' )，您获得待提现佣金￥' . $commission1 . '，待您确认收货后该笔佣金将变为可提现状态，方可进行提现操作。');
            /* 发送收益消息：极光 暂时未做 */
            /* 发送收益消息：短信 暂时未做 */
            /* 扣除商家销售利润（发放佣金）并生成操作记录 确认收货后才扣除！！！ */
            // $this->deductCommission($orderId, $company_id, $commission1);
        }
        /* 没有上级用户则终止佣金计算 */
        if ( !$myself['pid'] ) {
            return true;
        }

        /* 查询上级用户信息 */
        $superior = $memberModel->field('pid,status')->where(array('uid'=>$myself['pid']))->find();
        /* 冻结状态不计算佣金 */
        if ( $superior['status'] != 1 ) {
            return true;
        }
        /* 计算上级佣金 */
        if ( $commission2 > 0 ) {
            /* 生成佣金 */
            $datas['uid']             = $myself['pid'];
            $datas['order_id']        = $orderId;
            $datas['commission']      = $commission2;
            $datas['source_uid']      = $uid;
            $datas['withdrawal_type'] = 0;
            $datas['ctime']           = time();
            $datas['status']          = 0;
            $datas['remark']          = $remark;
            $model->add($datas);
            unset($datas);
            /* 发送收益消息：站内信 */
            $this->sendCommissionMessage($myself['pid'], $myself['name'] . '成功付款一笔订单( ' . $orderId . ' )，您获得待提现佣金￥' . $commission2 . '，待其确认收货后该笔佣金将变为可提现状态，方可进行提现操作。');
            /* 发送收益消息：极光 暂时未做 */
            /* 发送收益消息：短信 暂时未做 */
            /* 扣除商家销售利润（发放佣金）并生成操作记录 确认收货后才扣除！！！ */
            // $this->deductCommission($orderId, $company_id, $commission2);
        }
        /* 没有上上级用户则终止佣金计算 */
        if ( !$superior['pid'] ) {
            return true;
        }

        /* 查询上上级用户状态 */
        $topStatus = $memberModel->where(array('uid'=>$superior['pid']))->getField('status');
        /* 冻结状态不计算佣金 */
        if ( $topStatus != 1 ) {
            return true;
        }
        /* 计算上级佣金 */
        if ( $commission3 > 0 ) {
            /* 生成佣金 */
            $datas['uid']             = $superior['pid'];
            $datas['order_id']        = $orderId;
            $datas['commission']      = $commission3;
            $datas['source_uid']      = $uid;
            $datas['withdrawal_type'] = 0;
            $datas['ctime']           = time();
            $datas['status']          = 0;
            $datas['remark']          = $remark;
            $model->add($datas);
            unset($datas);
            /* 发送收益消息：站内信 */
            $this->sendCommissionMessage($superior['pid'], $myself['name'] . '成功付款一笔订单( ' . $orderId . ' )，您获得待提现佣金￥' . $commission3 . '，待其确认收货后该笔佣金将变为可提现状态，方可进行提现操作。');
            /* 发送收益消息：极光 暂时未做 */
            /* 发送收益消息：短信 暂时未做 */
            /* 扣除商家销售利润（发放佣金）并生成操作记录 确认收货后才扣除！！！ */
            // $this->deductCommission($orderId, $company_id, $commission3);
        }

        /* 销毁变量 */
        unset($myself, $commission1, $commission2, $commission3, $commissionBase, $model, $status, $remark, $productList, $company_id);

        return true;
    }

    /**
     * 分销佣金利润发送站内信模版
     */
    private function sendCommissionMessage( $uid, $message ) {
        $messageDatas['to_user']   = $uid;
        $messageDatas['title']     = '您有一笔新佣金';
        $messageDatas['message']   = $message;
        $messageDatas['addtime']   = time();
        $messageDatas['mobile']    = M('Member')->where(array('uid'=>$uid))->getField('mphone');
        $messageDatas['is_read']   = '';
        $messageDatas['is_delete'] = '';
        M('UserMessage')->add($messageDatas);
    }

    /**
     * 扣除商家销售利润（发放佣金）并生成操作记录
     * @param  [Integer] $orderId    [订单id]
     * @param  [Integer] $company_id [企业id]
     * @param  [Float]   $commission [发放佣金金额]
     */
    private function deductCommission($orderId, $company_id, $commission) {
        /* 扣除商家销售利润（发放佣金） */
        $datas['order_id'] = $orderId;
        $datas['withdrawals'] = $commission;
        $datas['cname'] = M('Company')->where(array('id'=>$company_id))->getField('name');
        $datas['cid'] = $company_id;
        $datas['etime'] = time();
        $datas['ctime'] = time();
        $datas['status'] = 4;
        M('Withdrawals')->add($datas);
    }

    /**
     * xml转array
     * @author 406764368@qq.com
     * @version 2016年11月18日 08:30:08
     * @param  [Xml]   $xml [要转成数组的xml]
     * @return [Array]      [转化后的数组]
     */
    public function xmlToArray( $xml ) {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * array转xml
     * @author 406764368@qq.com
     * @version 2016年11月18日 08:30:12
     * @param  [Array] $arr [要转成xml的数组]
     * @return [Xml]        [转化后的xml]
     */
    public function arrayToXml( $arr ) {
        $xml = "<xml>";
        foreach ($arr as $key=>$val) {
            if ( is_numeric($val) ) {
                $xml .= "<".$key.">".$val."</".$key.">";
            } else {
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}