<?php
namespace Admin\Controller;
use Think\Controller;
class OrderController extends AdminController {
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
        $this->assign('site', 'order');
    }

    /**
     * 订单列表
     */
    public function index() {
        /* 订单状态筛选 */
        $status = I('state');
        if ( $status ) {
            if ( $status - 1 == 8 ) {
                /* 将交易完成后被删除的订单也展示出来 */
                $where['state'] = array('in', '8,10');
            } else {
                $where['state'] = array('eq', $status - 1);
            }
        } else {
            /* 用户付款之前删除的订单 */
            $where['state'] = array('egt', 0);
        }
        /* 订单搜索 */
        if(IS_POST){
            $orderid    = I('orderid');
            $time_start = I('time_start');
            $time_end   = I('time_end');
            $sellername = I('sellername');
            if(!empty($orderid)){
                $where['id'] = array('like','%'.$orderid.'%');
                $this->assign('orderid',$orderid);
            }
            if(!empty($time_start) || !empty($time_end)){
                $this->assign('time_start',$time_start);
                $this->assign('time_end',$time_end);
                $time_start = strtotime($time_start);
                $time_end   = strtotime($time_end);
                $where['ctime'] = array('between',array($time_start,$time_end));
            }
            if(!empty($sellername)){
                $where['company_name'] = array('like','%'.$sellername.'%');
                $this->assign('sellername',$sellername);
            }
        }
        /* 订单查询 */
        $order = 'ctime desc';
        $list = $this->lists('order',$where,$order);

        $this->assign('list',$list);
        $this->assign('state',$status);
        $this->assign('statusList',$this->statusList);
        /*页面基本设置*/
        $this->site_title="订单管理";
        $this->assign('left','order');
        $this->display();
    }

    /**
     * 订单列表
     */
    public function recharge() {
        $status = I('request.status', 0, 'intval');
        if(IS_POST){
            $orderid = I('orderid');
            $time_start = I('time_start');
            $time_end = I('time_end');
            if(!empty($orderid)){
                $where['id'] = array('like','%'.$orderid.'%');
                $this->assign('orderid',$orderid);
            }
            if(!empty($time_start) || !empty($time_end)){
                $this->assign('time_start',$time_start);
                $this->assign('time_end',$time_end);
                $time_start = strtotime($time_start);
                $time_end   = strtotime($time_end);
                $where['ctime'] = array('between',array($time_start,$time_end));
            }
        }
        $status && $where['status'] = array('eq', $status-1);
        $order = 'ctime desc';
        $list = $this->lists('RechargeLogs',$where,$order);

        if ( $list ) {
            foreach ($list as $key => $value) {
                $ids[] = $value['uid'];
            }
            $where_member['uid'] = array('in', $ids);
            $memberList = M('Member')->where($where_member)->getField('uid,name', true);
            $this->assign('memberList',$memberList);
        }

        $this->assign('list',$list);
        // $this->assign('state',$status);
        // $this->assign('statusList',$this->statusList);
        /*页面基本设置*/
        $this->site_title="充值订单";
        $this->assign('left','recharge');
        $this->display();
    }

    /**
     * 订单详情
     * @author 83961014@qq.com
     */
    public function detail(){
        if(IS_POST){
            $orderid = I('orderid');
            $expressid = I('expressid');
            $express_id = I('express_id');
            $where_order['id'] = $orderid;
            $order = M('Order')->where($where_order)->find();
            if($expressid <= 0){
                $this->error('请选择运费模版');
            }
            if ( $expressid == 1 && !$express_id ) {
                $this->error('请选择快递公司');
            }
            $express_number = I('express_number');
            if(!is_numeric($express_number)){
                $this->error('请输入正确的运单号');
            }else{
                if ( !$order['express_no'] ) {
                    $data['etime']      = time();
                    $data['send_time']  = time();
                    $data['state']      = 2;
                    $data['freight_id'] = $expressid;
                    $express_id && $data['express_id'] = $express_id;
                    $data['express_no'] = $express_number;
                    $state = M('Order')->where(array('id'=>$orderid))->save($data);
                    if ( $state ) {
                        /* 生成订单操作记录 */
                        $clogDatas['order_id'] = $orderid;
                        $clogDatas['action']   = 3;
                        $clogDatas['uid']      = $order['uid'];
                        $clogDatas['remark']   = '商家发货';
                        $clogDatas['addtime']  = time();
                        M('OrderClog')->add($clogDatas);

                        $this->success('保存成功');
                    } else {
                        $this->error('物流信息保存失败');
                    }
                } else {
                    $this->error('您已发货，不可编辑');
                }
                // $data['express_company'] = M('Freight')->where(array('id'=>$expressid))->getField('word');
            }
        }
        $orderid = I('id');
        $order = M('Order');
        $ordersub = M('OrderSub');
        $address = M('Address');
        $regions = M('Regions');
        $where_order['id'] = $orderid;
        $order = $order->where($where_order)->find();
        $address = $address->where('id = '.$order['address_id'])->find();

        $tmp = trim($address['region'],',');
        $where['id'] = array('in',$tmp);
        $regions = $regions->where($where)->select();
        $where_sub['orderid'] = array('eq', $orderid);
        $ordersub = $ordersub->where($where_sub)->select();
        $product = D('ProductSale');
        foreach ($ordersub as $key=>&$val){//检查该商品是否还存在
            $tmp = $product->getProductSaleInfo(array('id'=>$val['goodid']));
            if($tmp !='' && $tmp != false){
                $val['tmp'] = 1;
            }
        }
        $order['state_name'] = $this->statusList[$order['state']];
        $this->assign('order',$order);
        $this->assign('address',$address);
        $this->assign('regions',$regions);
        $this->assign('ordersub',$ordersub);
        /*快递公司*/
        // $exress = M('Freight')->where(array('status'=>1))->select();
        $expressList = M('Express')->where(array('status'=>1))->select();
        $this->assign('expressList',$expressList);

        /* 操作日志 */
        $clogList = M('OrderClog')->field('addtime,remark')->where(array('order_id'=>$orderid))->select();
        $this->assign('clogList', $clogList);
        /* 支付记录 */
        $payLog = M('OrderPayLog')->field('trade_no,pay_price,payment_id,add_time')->where(array('order_id'=>$orderid))->select();
        $this->assign('payLog', $payLog);
        $paymentList = array('1'=>'微信支付', '2'=>'支付宝支付', '3'=>'账户余额支付');
        $actionList = array('1'=>'买家创建订单', '2'=>'买家支付订单', '3'=>'商家发货', '4'=>'买家确认收货', '5'=>'买家评价订单', '6'=>'买家删除订单', '7'=>'买家申请退款', '8'=>'商家退款完成', '9'=>'商家同意退款', '10'=>'商家拒绝退款', '11'=>'买家取消退款', '12'=>'买家确认收款');
        $this->assign('paymentList', $paymentList);
        $this->assign('actionList', $actionList);
        /*页面基本设置*/
        $this->site_title="订单详情";
        $this->assign('left','order');
        $this->display();
    }

    /**
     * 同意退款
     */
    public function refundAgree() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->error('参数错误');
            $where['state'] = array('eq', 6);
            $where['id']    = array('eq', $id);
            /* 查询订单信息 */
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->error('订单不存在或状态异常');
            /* 更新订单状态 */
            $datas['state'] = 4;
            $datas['etime'] = time();
            $result = M('Order')->where($where)->save($datas);
            if ( $result ) {
                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderInfo['id'];
                $clogDatas['action']   = 9;
                $clogDatas['uid']      = $orderInfo['uid'];
                $clogDatas['remark']   = '平台同意退款';
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

                $this->success('操作成功');
            }
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 拒绝退款
     */
    public function refundRefuse() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->error('参数错误');
            $remark = I('post.remark');
            !$remark && $this->error('请填写拒绝理由');
            strlen($remark) > 255 && $this->error('拒绝理由不能超过255个字符');
            /* 查询订单信息 */
            $where['state']  = array('eq', 6);
            $where['id']     = array('eq', $id);
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->error('订单不存在或状态异常');
            /* 更新订单状态 */
            $datas['state']  = 7;
            $datas['etime']  = time();
            $result = M('Order')->where($where)->save($datas);
            if ( $result ) {
                /* 生成订单操作记录 */
                $clogDatas['order_id'] = $orderInfo['id'];
                $clogDatas['action']   = 10;
                $clogDatas['uid']      = $orderInfo['uid'];
                $clogDatas['remark']   = '平台拒绝退款 拒绝理由：' . $remark;
                $clogDatas['addtime']  = time();
                M('OrderClog')->add($clogDatas);

                /* 更新商家利润数据 */
                // $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
                // $where_withdrawals['status']   = array('eq', -4);
                // M('Withdrawals')->where($where_withdrawals)->setField('status', -3);

                $this->success('操作成功');
            }
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 打款完成
     */
    public function refundComplete() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->error('参数错误');
            $money = I('post.money');
            !$money && $this->error('请填写退款金额');
            $where['state'] = array('eq', 4);
            $where['id']    = array('eq', $id);
            /* 查询订单信息 */
            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
            !$orderInfo && $this->error('订单不存在或状态异常');
            /* 数据验证 */
            if ( !preg_match('/^\d+(\.\d{1,2})?$/', $money) || $money == 0 ) {
                $this->error('退款金额格式不正确');
            }
            if ( $money > $orderInfo['pay_price'] ) {
                $this->error('退款金额不能大于订单的支付金额');
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
                $clogDatas['remark']   = '平台退款完成，退款金额：￥' . $money;
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
     * 商家提现列表
     */
    public function withdrawals() {
        $status = I('request.status', 0, 'intval');
        if ( $status ) {
            $where['status'] = array('eq', $status - 1);
        } else {
            $where['status'] = array('in', array(0,1,2,3));
        }
        $list = $this->lists('Withdrawals', $where, 'etime DESC');

        /* 数据处理 */
        if ( $list ) {
            /* 数据处理 */
            foreach ($list as $value) {
                /* 企业id */
                $companyIds[] = $value['cid'];
                /* 提现账户id */
                $accountIds[] = $value['account_id'];
            }

            /* 查询用户信息 */
            $where_member['l.id'] = array('in', $companyIds);
            $memberList = M('Company')
                        ->alias('l')
                        ->join(C('DB_PREFIX') . 'member r ON l.user_id = r.uid', 'LEFT')
                        ->where($where_member)
                        ->getField('l.id,r.name,r.mphone', true);
            $this->assign('memberList', $memberList);

            /* 提现方式 */
            $typeList = array(1=>'支付宝',2=>'银行卡',3=>'转余额');
            $this->assign('typeList', $typeList);

            /* 查询帐号信息 */
            $where_account['id'] = array('in', $accountIds);
            $accountList = M('WithdrawalsAccount')->where($where_account)->getField('id,account,truename,bank_card,bank_address,img', true);
            $this->assign('accountList', $accountList);

            /* 状态 */
            $statusList = array('申请提现中', '待打款', '已打款', '申请被拒');
            $this->assign('statusList', $statusList);

            /* 收款信息 */
            foreach ($list as $k => $v) {
                if ( $v['type'] == 3 ) {
                    $list[$k]['html'] = '<div>';
                    $list[$k]['html'] .= '<p>收款人：'.$memberList[$v['cid']]['name'].'</p>';
                    $list[$k]['html'] .= '<p>收款帐号：'.$memberList[$v['cid']]['mphone'].'</p>';
                    $list[$k]['html'] .= '</div>';
                } else {
                    $list[$k]['html'] = '<div>';
                    $list[$k]['html'] .= '<p>收款人：'.$accountList[$v['account_id']]['truename'].'</p>';
                    $list[$k]['html'] .= '<p>收款帐号：'.$accountList[$v['account_id']]['account'].'</p>';
                    $accountList[$v['account_id']]['bank_card'] && $list[$k]['html'] .= '<p>银行类型：'.$accountList[$v['account_id']]['bank_card'].'</p>';
                    $accountList[$v['account_id']]['bank_address'] && $list[$k]['html'] .= '<p>开户行地址：'.$accountList[$v['account_id']]['bank_address'].'</p>';
                    $list[$k]['html'] .= '</div>';
                }
            }
            $this->assign('list', $list);
        }

        /* 筛选状态 */
        $stateList = array(
            array('id'=>0, 'title'=>'全部'),
            array('id'=>1, 'title'=>'申请提现中'),
            array('id'=>2, 'title'=>'待打款'),
            array('id'=>3, 'title'=>'已打款'),
            array('id'=>4, 'title'=>'申请被拒')
        );
        $this->assign('stateList', $stateList);

        $this->site_title = '商家提现列表';
        $this->assign('left', 'withdrawals');
        $this->display();
    }

    /**
     * 同意商家提现
     */
    public function withdrawalsAgree() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->error('参数错误');
            /* 更新商家利润表 */
            $where['status'] = array('eq', 0);
            $where['id']     = array('eq', $id);
            $datas['status'] = 1;
            $datas['etime']  = time();
            $result = M('Withdrawals')->where($where)->save($datas);
            $result && $this->success('操作成功');
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 拒绝商家提现
     */
    public function withdrawalsRefuse() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->error('参数错误');
            /* 更新商家利润表 */
            $where['status'] = array('eq', 0);
            $where['id']     = array('eq', $id);
            $datas['status'] = 3;
            $datas['etime']  = time();
            $result = M('Withdrawals')->where($where)->save($datas);
            $result && $this->success('操作成功');
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 商家提现打款完成
     */
    public function withdrawalsComplete() {
        if ( IS_GET ) {
            $id = I('get.id');
            !$id && $this->error('参数错误');
            /* 更新商家利润表 */
            $where['status'] = array('eq', 1);
            $where['id']     = array('eq', $id);
            $datas['status'] = 2;
            $datas['etime']  = time();
            $result = M('Withdrawals')->where($where)->save($datas);
            if ( $result ) {
                /* 转入余额的提现 增加用户余额 */
                $where_info['l.id'] = array('eq', $id);
                $info = M('Withdrawals')
                      ->alias('l')
                      ->field('l.type,r.user_id,l.withdrawals')
                      ->join(C('DB_PREFIX') . 'company r ON l.cid = r.id', 'LEFT')
                      ->where($where_info)
                      ->find();
                if ( $info['type'] == 3 ) {
                    M('Member')->where(array('uid'=>$info['user_id']))->setInc('balance', $info['withdrawals']);
                }

                $this->success('操作成功');
            }
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }
}