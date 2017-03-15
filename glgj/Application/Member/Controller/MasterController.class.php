<?php
namespace Member\Controller;

use Think\Controller;

class MasterController extends MemberController {
    public function _initialize() {
        parent::_initialize();
        $this->assign('site', 'master');
    }

    /**
     * 个人仓库主
     * 1->仓储主,2->个人货主,3->企业货主,4->个人车主,5->企业车主表,
     */
    public function index() {
        $type = I('get.type', 1, 'intval');
        $masterStatus = M('Master')->where(array('uid' => UID, 'type' => $type))->getField('status');

        /*头信息*/
        $list['url'] = 'index.php?g=member&m=master&a=apply&type=' . $type;
        if (empty($masterStatus)) {
            $list['info'] = '您还没有发布本项目权限，去申请';
        } elseif ($masterStatus == 0) {
            $list['info'] = '商家正在审核中，去完善';
        } elseif ($masterStatus == 1) {
            $list['info'] = '商家已通过审核，去发布';
            $list['url'] = 'index.php?g=member&m=master&a=add&type=' . $type;
        } elseif ($masterStatus == 2) {
            $list['info'] = '商家审核被拒，重新提交';
        }

        /*数据检索*/
        $where['uid'] = array('eq', 1026);
//        $where['uid'] = array('eq', UID);
        $where['private'] = array('eq', 1);
        if ($type == 1) {
            $table = 'Depot';
        } elseif ($type == 2) {
            $table = 'Goods';
        } elseif ($type == 3) {
            $table = 'Goods';
            $where['private'] = array('eq', 2);
        } elseif ($type == 4) {
            $table = 'Truck';
        } elseif ($type == 5) {
            $table = 'Truck';
            $where['private'] = array('eq', 2);
        }

        $list['list'] = $this->lists($table, $where, 'id desc');
        /*省市区*/
        $list['regions'] = M('Regions')->getField('id,name',true);
        $this->assign('list', $list);

        $this->site_title = '个人权限列表';
        $this->assign('header', $type);
        $this->display();
    }









//    /* 订单状态 */
//    private $statusList = array(
//        '0' => '待付款',
//        '1' => '待发货',
//        '2' => '待收货',
//        '3' => '待评价',
//        '4' => '退款中',
//        '5' => '交易关闭',
//        '6' => '申请退款',
//        '7' => '退款被拒',
//        '8' => '交易完成',
//        '9' => '已退款'
//    );
//    /* 订单状态 */
//    private $stateList = array(
//        '0' => '待付款',
//        '1' => '待发货',
//        '2' => '待收货',
//        '3' => '待评价',
//        '4' => '退款中',
//        '5' => '交易关闭',
//        '6' => '申请退款',
//        '7' => '退款被拒',
//        '8' => '交易完成',
//        '9' => '已退款'
//    );
//
//    public function _initialize() {
//        parent::_initialize();
//        /*获取公司ID*/
//        $field = 'l.*,subphone,province_id,r.city_id,countyN,address,contact_user,contact_office,sex,site,qq,telephone,subphone,fax,email,r.modify_time,lng,lat,wechat';
//        $map['user_id'] = array('eq', UID);
//        $company_info = M('Company')
//            ->alias('l')
//            ->join(C('DB_PREFIX') . 'company_link r ON l.id=r.company_id', 'LEFT')
//            ->where($map)
//            ->field($field)
//            ->select();
//
//        $this->company_info = $company_info[0];
//
//        $this->assign('site', 'sale');
//    }
//
//    /**
//     * 发布产品信息
//     * @return [type] [description]
//     */
//    public function indexCopy() {
//        /* 判断权限 */
//        $company_info = $this->company_info;
//
//        if (!$company_info) {
//            $this->error('请先填写公司信息', '?g=member&m=sale&a=company');
//        }
//
//        $productid = I('id');
//        $product = D('ProductSale');
//        $pic = M('ProdcutPicture');
//        if (IS_POST) {
//            $company_info = $this->company_info;
//            if (!$company_info) {
//                $this->error('请先填写公司信息');
//            }
//            $imgall = I('allpic');
//            $status = $product->update();
//            if ($status) {
//                $time = time();
//                $num = is_array($imgall) ? count($imgall) : 0;
//                if ($productid) {
//                    $pic->where(array('product_id' => $productid))->delete();
//                    for ($i = 0; $i < $num; $i++) {
//                        $data['product_id'] = $productid;
//                        $data['pic_url'] = $imgall[$i];
//                        $data['addtime'] = $time;
//                        $pic->add($data);
//                    }
//                    $this->success('修改成功', '?g=member&m=sale&a=product');
//                } else {
//                    for ($i = 0; $i < $num; $i++) {
//                        $data['product_id'] = $status;
//                        $data['pic_url'] = $imgall[$i];
//                        $data['addtime'] = $time;
//                        $pic->add($data);
//                    }
//                    $this->success('添加成功', '?g=member&m=sale&a=product');
//                }
//            } else {
//                $errorInfo = $product->getError();
//                $this->error($errorInfo);
//            }
//        }
//        $detail = $product->getProductSaleInfo(array('id' => $productid));
//        $this->assign('detail', $detail);//商品详情
//        $allpic = $pic->where(array('product_id' => $productid))->select();
//        $this->assign('allpic', $allpic);//商品轮播图
//        $classify = M('ProductSaleCategory')->select();
//        $classify = lowest($classify);
//        $this->assign('classify', $classify);//分类
//        $flags = M('Flags')->select();
//        $this->assign('flags', $flags);//属性
//
//        $this->site_title = '发布产品信息';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'index');
//        $this->display();
//    }
//
//    public function index() {
//        $id = I('request.id', 0, 'intval');
//        $model = D('ProductSale');
//        $opt = $id > 0 ? '修改' : '添加';
//        if (IS_POST) {
//            //dump(I('post.'));exit;
//            $company_info = $this->company_info;
//            if (!$company_info) {
//                $this->error('完善公司信息后才能上传商品', '?g=member&m=sale&a=company', 1);
//            }
//            $result = $model->update();
//            if ($result) {
//                $this->success($opt . '成功', 'index.php?g=member&m=sale&a=product');
//            } else {
//                $errorInfo = $model->getError();
//                $this->error($errorInfo);
//            }
//        }
//        /*修改*/
//        if ($id) {
//            /* 商品详细信息 */
//            $condition['id'] = $id;
//            $detail = $model->getProductSaleInfo($condition);
//            $this->assign('detail', $detail);
//            /* 轮播图 */
//            $allpic = M('ProdcutPicture')->field('pic_url')->where(array('product_id' => $id))->order('id ASC')->select();
//            $this->assign('allpic', $allpic);
//            /* 商品规格 */
//            if ($detail['is_spec'] == 1) {
//                $specList = M('ProductSpec')->field('title1,title2,spec1,spec2,price,oprice,activity_price,weight,stock,buymin,sort,img')->where(array('product_id' => $id))->order('sort DESC,id ASC')->select();
//                $specTitle1 = $specList[0]['title1'];
//                $specTitle2 = $specList[0]['title2'];
//                $this->assign('specList', $specList);
//                $this->assign('specTitle1', $specTitle1);
//                $this->assign('specTitle2', $specTitle2);
//            }
//        }
//
//        /* 分类 */
//        $category = M('ProductSaleCategory')->field('id,name,parent_id')->select();
//        $category = $this->dumpTreeList($category);
//        $this->assign('category', $category);
//
//        $distribution = M('Conf')->where(array('name' => 'distribution'))->getField('value');
//        $this->assign('distribution', $distribution);
//
//        /* 查询限时抢购活动开关 */
//        $this->assign('flashFlag', C('FLASHFLAG'));
//
//        $this->site_title = '产品管理';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'index');
//        $this->display();
//    }
//
//    /**
//     * 产品管理
//     * @return [type] [description]
//     */
//    public function product() {
//        /* 判断权限 */
//        $company_info = $this->company_info;
//        $order = 'issue_time desc';
//        $company_info = $this->company_info;
//        $where['company_id'] = $company_info['id'];
//        $list = $this->lists('ProductSale', $where, $order);
////        $classify = M('ProductSaleCategory')->field('id,name')->select();
////        $classify = array_column($classify, 'name', 'id');
////        foreach ($list as $key => $value) {
////            $list[$key]['classify'] = $classify[$value['sale_category_id']];
////        }
//        $this->assign('list', $list);
//        $this->site_title = '产品管理';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'product');
//        $this->display();
//    }
//
//    /**
//     * 产品删除
//     * @author 83961014@qq.com
//     */
//    public function product_del() {
//        $id = I('id');
//        $product = D('ProductSale');
//        $condition['id'] = $id;
//        $condition['company_id'] = array('eq', $this->company_info['id']);
//        $return = $product->delProductSale($condition);
//        if ($return != false) {
//            $this->success('删除成功', '?g=member&m=sale&a=product');
//        } else {
//            $this->error('删除失败', '?g=member&m=sale&a=product');
//        }
//    }
//
//    /*
//     *评价管理
//     * */
//    public function comment() {
//        $id = I('id');
//        $order = 'ctime desc';
//        $condition['pid'] = $id;
//        // $condition['type'] = $type;
//        $list = $this->lists('ProductComment', $condition, $order);
//        $review = M('ProductComment');
//        $member = M('Member');
//        foreach ($list as $key => $val) {
//            $user = $member->where(array('uid' => $val['uid']))->find();
//            $list[$key]['uname'] = $user['name'];
//        }
//
//        $this->assign('list', $list);
//
//        /*基本资料*/
//        $this->site_title = '评价管理';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'product');
//        $this->display();
//    }
//
//    /*
//     *回复评论
//     * */
//    public function comment_reply() {
//
//
//    }
//
//    /**
//     * 订单管理
//     * @return [type] [description]
//     */
//    public function order() {
//        // $status = I('get.state');
//        /* 订单状态筛选 */
//        $status = I('state');
//        if ($status) {
//            if ($status - 1 == 8) {
//                /* 将交易完成后被删除的订单也展示出来 */
//                $where['state'] = array('in', '8,10');
//            } else {
//                $where['state'] = array('eq', $status - 1);
//            }
//        } else {
//            /* 用户付款之前删除的订单 */
//            $where['state'] = array('gt', 0);
//        }
//        $where['company_id'] = $this->company_info['id'];
//        // $where['state'] = $status;
//        $order = 'etime desc';
//        $list = $this->lists('order', $where, $order);
//        $order_sub = M('Order_sub');
//        $field = "title,img,price";
//        foreach ($list as &$val) {
//            $condition['orderid'] = $val['id'];
//            $val['productList'] = $order_sub->where($condition)->field('goodname,gpic,nums,unitprice,totalprice,spec_info')->select();
//        }
//        $this->assign('list', $list);
//
//        /*基本设置*/
//        $this->assign('statusList', $this->statusList);
//        $this->site_title = '订单管理';
//        $this->assign('state', $status);
//        $this->assign('header', 'order');
//        $this->assign('nav', 'order');
//        $this->display();
//    }
//
//    /**
//     * 订单详情
//     * @return [type] [description]
//     */
//    public function detail() {
//        $orderid = I('id');
//        empty($orderid) && $this->error('参数错误');
//        $order = M('Order');
//        $ordersub = M('OrderSub');
//        $address = M('Address');
//        $regions = M('Regions');
//        $order = $order->where(array('id' => $orderid))->find();
//        $address = $address->where(array('id' => $order['address_id']))->find();
//        $tmp = trim($address['region'], ',');
//        $where['id'] = array('in', $tmp);
//        $regions = $regions->where($where)->select();
//        $ordersub = $ordersub->where(array('orderid' => $orderid))->field('goodid,goodname,gpic,nums,unitprice,totalprice,specid,spec_info')->select();
//        foreach ($ordersub as $key => $val) {
//            if ($val['specid']) {
//                $ordersub[$key]['spec'] = M('Product_spec')->where(array('id' => $val['specid']))->find();
//            }
//        }
//
////        $product = D('ProductSale');
////        foreach ($ordersub as $key => &$val) {//检查该商品是否还存在
////            $tmp = $product->getProductSaleInfo(array('id' => $val['goodid']));
////            if ($tmp != '' && $tmp != false) {
////                $val['tmp'] = 1;
////            }
////        }
//
//        /*写出地址*/
//        $region = '';
//        foreach ($regions as $key => $val) {
//            $region .= $val['name'] . "-";
//        }
//        $region = substr($region, 0, -1);
//        $address['region'] = $region;
//
//        $this->assign('order', $order);
//        $this->assign('order_info', $order);
//        $this->assign('address', $address);
//        $this->assign('ordersub', $ordersub);
//        $this->assign('statusList', $this->statusList);
//        $this->site_title = '订单详情';
//        $this->assign('header', 'order');
//        $this->assign('nav', 'detail');
//        $this->display();
//    }
//
//    /**
//     * 发货页面
//     * @return [type] [description]
//     */
//    public function send() {
//        $orderid = I('id');
//        empty($orderid) && $this->error('参数错误');
//        $order = M('Order')->where(array('id' => $orderid))->find();
//        if (IS_POST) {
//            if ($order['express_no']) {
//                $this->error('您已发货，不可编辑');
//            } else {
//                $express_no = I('express_no');
//                $express_id = I('express_id');
//                if (!is_numeric($express_no)) {
//                    $this->error('请输入正确的运单号');
//                } else {
//                    if ($order['freight_id'] == 1 && !$express_id) {
//                        $this->error('请选择快递公司');
//                    }
//                    $data['express_no'] = $express_no;
//                    $data['express_id'] = $express_id;
//                    $data['send_time'] = time();
//                    $data['state'] = 2;
//                    $state = M('Order')->where(array('id' => $orderid))->save($data);
//                    if ($state) {
//                        $data_order_log['order_id'] = $orderid;
//                        $data_order_log['remark'] = '商家发货';
//                        $data_order_log['action'] = 3;
//                        $data_order_log['uid'] = UID;
//                        $data_order_log['addtime'] = time();
//                        $execute = M('OrderClog')->add($data_order_log);
//                        $this->success('保存成功', '?g=member&m=sale&a=order&state=3');
//                    } else {
//                        $this->error('物流信息保存失败');
//                    }
//                }
//            }
//        }
//
//        /* 运费模版名称 */
//        $freight = M('Freight')->where(array('id' => $order['freight_id']))->getField('title');
//        $this->assign('freight', $freight);
//        $this->assign('order', $order);
//        $this->assign('order_info', $order);
//
//        /* 快递公司 */
//        $expressList = M('Express')->where(array('status' => 1))->select();
//        $this->assign('expressList', $expressList);
//
//        /* 页面基本信息 */
//        $this->site_title = '发货';
//        $this->assign('header', 'order');
//        $this->assign('nav', 'send');
//        $this->display();
//    }
//
//    /**
//     * 物流详情
//     * @return [type] [description]
//     */
//    public function logistics() {
//        $orderid = I('get.id');
//        empty($orderid) && $this->error('参数错误');
//        $map['id'] = array('eq', $orderid);
//        $order_info = M('Order')->where(array('id' => $orderid))->find();
//        if (!$order_info) {
//            $this->error('订单信息不存在');
//        }
//        $delivery_id = M('Freight')->where(array('id' => $order_info['freight_id']))->getField('delivery_id');
//        $title = M('Express')->where(array('id' => $delivery_id))->getField('title');
//        if ($title == null) {
//            $title = '未知的物流公司';
//        }
//        $url = "http://www.kuaidi100.com/query?&type=" . $title . "&postid=" . $order_info['express_no'];
//        $detail = json_decode($this->http_get($url), true);
////    dump($detail);exit;
////        $exress = M('Exress')->select();
////        foreach ($exress as $val) {
////            $exress_arr[$val['id']] = $val['title'];
////        }
//
//        $this->assign('detail', $detail);
//        $this->assign('title', $title);
//        $this->assign('order_info', $order_info);
//        $this->site_title = '物流详情';
//        $this->assign('header', 'order');
//        $this->assign('nav', 'logistics');
//        $this->display();
//    }
//
//    /**
//     * 订单操作日志
//     */
//    public function orderLog() {
//        $orderid = I('request.id');
//        empty($orderid) && $this->error('参数错误');
//        $order_info = M('Order')->where(array('id' => $orderid))->find();
//        $this->assign('order_info', $order_info);
//        /* 操作日志 */
//        $list = $this->lists('OrderClog', array('order_id' => $orderid), 'addtime DESC');
//        $this->assign('list', $list);
//        /* 支付记录 */
//        $payLog = M('OrderPayLog')->field('trade_no,pay_price,payment_id,add_time')->where(array('order_id' => $orderid))->select();
//        $this->assign('payLog', $payLog);
//        $paymentList = array('1' => '微信支付', '2' => '支付宝支付', '3' => '账户余额支付');
//        $this->assign('paymentList', $paymentList);
//
//        $this->assign('header', 'order');
//        $this->assign('nav', 'orderlog');
//        $this->display();
//    }
//
//    /**
//     * 订单退款
//     */
//    public function refund() {
//        $orderid = I('request.id');
//        empty($orderid) && $this->error('参数错误');
//        $order_info = M('Order')->where(array('id' => $orderid))->find();
//        $this->assign('order_info', $order_info);
//
//        $this->assign('header', 'order');
//        $this->assign('nav', 'refund');
//        $this->display();
//    }
//
//    /**
//     * 同意退款
//     */
//    public function refundAgree() {
//        if (IS_POST) {
//            $id = I('post.id');
//            !$id && $this->error('参数错误');
//            $where['state'] = array('eq', 6);
//            $where['id'] = array('eq', $id);
//            /* 查询订单信息 */
//            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
//            !$orderInfo && $this->error('订单不存在或状态异常');
//            /* 更新订单状态 */
//            $datas['state'] = 4;
//            $datas['etime'] = time();
//            $result = M('Order')->where($where)->save($datas);
//            if ($result) {
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $orderInfo['id'];
//                $clogDatas['action'] = 9;
//                $clogDatas['uid'] = $orderInfo['uid'];
//                $clogDatas['remark'] = '商家同意退款';
//                $clogDatas['addtime'] = time();
//                M('OrderClog')->add($clogDatas);
//
//                /* 查询订单销售利润 */
//                $where_withdrawals['status'] = array('eq', -3);
//                $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
//                $where_withdrawals['cid'] = array('eq', $orderInfo['company_id']);
//                $withdrawals = M('Withdrawals')->where($where_withdrawals)->getField('withdrawals');
//                /* 构建数据数组 并生成订单退款的商家销售利润记录 一旦同意退款买家不能取消退款，故在此生成 */
//                $withdrawalsDatas['order_id'] = $orderInfo['id'];
//                $withdrawalsDatas['withdrawals'] = $withdrawals;
//                $withdrawalsDatas['task'] = '';
//                $withdrawalsDatas['cname'] = $orderInfo['company_name'];
//                $withdrawalsDatas['payee'] = $orderInfo['address_name'];
//                $withdrawalsDatas['type'] = 0;
//                $withdrawalsDatas['cid'] = $orderInfo['company_id'];
//                $withdrawalsDatas['etime'] = time();
//                $withdrawalsDatas['ctime'] = time();
//                $withdrawalsDatas['status'] = -4;
//                M('Withdrawals')->add($withdrawalsDatas);
//
//                $this->success('操作成功');
//            }
//            $this->error('操作失败');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * 拒绝退款
//     */
//    public function refundRefuse() {
//        if (IS_POST) {
//            $id = I('post.id');
//            !$id && $this->error('参数错误');
//            $remark = I('post.remark');
//            !$remark && $this->error('请填写拒绝理由');
//            strlen($remark) > 255 && $this->error('拒绝理由不能超过255个字符');
//            /* 查询订单信息 */
//            $where['state'] = array('eq', 6);
//            $where['id'] = array('eq', $id);
//            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
//            !$orderInfo && $this->error('订单不存在或状态异常');
//            /* 更新订单状态 */
//            $datas['state'] = 7;
//            $datas['etime'] = time();
//            $result = M('Order')->where($where)->save($datas);
//            if ($result) {
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $orderInfo['id'];
//                $clogDatas['action'] = 10;
//                $clogDatas['uid'] = $orderInfo['uid'];
//                $clogDatas['remark'] = '商家拒绝退款 拒绝理由：' . $remark;
//                $clogDatas['addtime'] = time();
//                M('OrderClog')->add($clogDatas);
//
//                /* 更新商家利润数据 */
//                // $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
//                // $where_withdrawals['status']   = array('eq', -4);
//                // M('Withdrawals')->where($where_withdrawals)->setField('status', -3);
//
//                $this->success('操作成功');
//            }
//            $this->error('操作失败');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * 打款完成
//     */
//    public function refundComplete() {
//        if (IS_POST) {
//            $id = I('post.id');
//            !$id && $this->error('参数错误');
//            $money = I('post.money');
//            !$money && $this->error('请填写退款金额');
//            $where['state'] = array('eq', 4);
//            $where['id'] = array('eq', $id);
//            /* 查询订单信息 */
//            $orderInfo = M('Order')->field('id,uid,pay_price,payment_id,company_id,company_name,address_name')->where($where)->find();
//            !$orderInfo && $this->error('订单不存在或状态异常');
//            /* 数据验证 */
//            if (!preg_match('/^\d+(\.\d{1,2})?$/', $money) || $money == 0) {
//                $this->error('退款金额格式不正确');
//            }
//            if ($money > $orderInfo['pay_price']) {
//                $this->error('退款金额不能大于订单的支付金额');
//            }
//            /* 更新订单状态 */
//            $datas['state'] = 9;
//            $datas['etime'] = time();
//            $result = M('Order')->where($where)->save($datas);
//            if ($result) {
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $orderInfo['id'];
//                $clogDatas['action'] = 8;
//                $clogDatas['uid'] = $orderInfo['uid'];
//                $clogDatas['remark'] = '商家退款完成，退款金额：￥' . $money;
//                $clogDatas['addtime'] = time();
//                M('OrderClog')->add($clogDatas);
//
//                /* 生成订单支付记录 */
//                $payLogDatas['order_id'] = $orderInfo['id'];
//                $payLogDatas['trade_no'] = '';
//                $payLogDatas['pay_price'] = $money;
//                $payLogDatas['payment_id'] = $orderInfo['payment_id'];
//                $payLogDatas['add_time'] = time();
//                M('OrderPayLog')->add($payLogDatas);
//
//                /* 余额支付的订单增加用户余额 */
//                if ($orderInfo['payment_id'] == 3) {
//                    M('Member')->where(array('uid' => $orderInfo['uid']))->setInc('balance', $money);
//                }
//
//                /* 将待提现佣金标记为流失佣金记录 */
//                $where_commission['order_id'] = array('eq', $orderInfo['id']);
//                $where_commission['status'] = array('eq', 0);
//                $commissionDatas['status'] = -1;
//                $commissionDatas['remark'] = '订单退款成功，佣金流失';
//                M('Commission')->where($where_commission)->save($commissionDatas);
//
//                /* 更新商家销售利润记录状态 */
//                $where_withdrawals['order_id'] = array('eq', $orderInfo['id']);
//                $where_withdrawals['status'] = array('eq', -4);
//                $withdrawalsDatas['status'] = 5;
//                $withdrawalsDatas['etime'] = time();
//                M('Withdrawals')->where($where_withdrawals)->save($withdrawalsDatas);
//
//                $this->success('操作成功');
//            }
//            $this->error('操作失败');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * curl get 方法
//     * @param $url 请求地址
//     */
//    private function http_get($url) {
//        $oCurl = curl_init();
//        if (stripos($url, "https://") !== FALSE) {
//            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
//        }
//        curl_setopt($oCurl, CURLOPT_URL, $url);
//        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
//        $sContent = curl_exec($oCurl);
//        $aStatus = curl_getinfo($oCurl);
//        curl_close($oCurl);
//        if (intval($aStatus["http_code"]) == 200) {
//            return $sContent;
//        } else {
//            return false;
//        }
//    }
//
//    /**
//     * 发布求购
//     * @return [type] [description]
//     */
//    public function needadd() {
//        $id = I('request.id', 0, 'intval');
//        $opt = $id > 0 ? '编辑' : '发布';
//
//        $model = D('ProductBuy');
//        if (IS_POST) {
//
//            $result = $model->update();
//            if ($result) {
//                $this->success($opt . '成功', '?g=member&m=sale&a=need');
//            } else {
//                $this->error($model->getError());
//            }
//        }
//
//        /* 修改 */
//        if ($id > 0) {
//            $condition['id'] = $id;
//            $detail = $model->getProductBuyInfo($condition);
//            $allpic = M('NeedPicture')->where(array('need_id' => $id))->select();
//            $this->assign('allpic', $allpic);//求购轮播图
//            $this->assign('detail', $detail);
//        }
//        /* 分类 */
//        $where['status'] = array('eq', 1);
//        $classify = M('ProductSaleCategory')->field('id,name,parent_id')->where($where)->order('sort DESC,id DESC')->select();
//        $classify = lowest($classify);
//        $this->assign('classify', $classify);
//
//        /* 页面基本设置 */
//        $this->assign('header', 'index');
//        $this->assign('nav', 'need');
//        $this->assign('site_title', $opt . '求购信息');
//        $this->display();
//    }
//
//    /**
//     * 求购管理
//     * @return [type] [description]
//     */
//    public function need() {
//        $where['company_id'] = $this->company_info['id'];
//        $order = 'issue_time desc';
//        $list = $this->lists('product_buy', $where, $order);
//        $classify = M('ProductSaleCategory')->where(array('status' => 1))->getField('id,name');
//        foreach ($list as &$val) {
//            $val['buy_category_id'] = $classify[$val['buy_category_id']];
//        }
//        $this->assign('list', $list);
//        $this->site_title = '求购管理';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'need');
//        $this->display();
//    }
//
//    /**
//     * 求购删除
//     * @author 83961014@qq.com
//     */
//    public function needDel() {
//        $id = I('request.id', 0, 'intval');
//        !$id && $this->error('参数错误');
//        $where['id'] = array('eq', $id);
//        $delete = M('ProductBuy')->where($where)->delete();
//        if ($delete !== false) {
//            $this->success('删除成功');
//        }
//        $this->error('删除失败');
//    }
//
//    /**
//     * 发布供应
//     * @return [type] [description]
//     */
//    public function supplyAdd() {
//        $id = I('request.id', 0, 'intval');
//        $opt = $id > 0 ? '编辑' : '发布';
//        $model = D('ProductSupply');
//        if (IS_POST) {
//            $result = $model->update();
//            if ($result) {
//                $this->success($opt . '成功', '?g=member&m=sale&a=supply');
//            } else {
//                $this->error($model->getError());
//            }
//        }
//
//        /* 修改 */
//        if ($id > 0) {
//            $condition['id'] = $id;
//            $detail = $model->getOneInfo($condition);
//            $this->assign('detail', $detail);
//        }
//
//        /* 分类 */
//        $where['status'] = array('eq', 1);
//        $classify = M('ProductBuyCategory')->field('id,name,parent_id')->where($where)->order('sort DESC,id DESC')->select();
//        $classify = lowest($classify);
//        $this->assign('classify', $classify);
//
//        /* 页面基本设置 */
//        $this->assign('header', 'index');
//        $this->assign('site_title', $opt . '供应信息');
//
//        $this->display();
//    }
//
//    /**
//     * 添加运费模版
//     * @return [type] [description]
//     */
//    public function template() {
//        $company_info = $this->company_info;
//        if (IS_POST) {
//            if (!$company_info) {
//                $this->error('请先填写公司信息');
//            }
//            $status = D('Exp')->update($company_info['id']);
//            if ($status) {
//                $this->success('添加成功');
//            } else {
//                $this->error(D('Exp')->getError());
//            }
//        } else {
//            /*快递*/
//            $delivery = M('Exress')->select();
//            $this->assign('delivery', $delivery);
//
//            /*页面公用设置*/
//            $this->site_title = '添加运费模版';
//            $this->assign('header', 'index');
//            $this->display();
//        }
//
//    }
//
//    /**
//     * 运费管理
//     * @return [type] [description]
//     */
//    public function freight() {
//        $content = I('request.search');
//        $where['title'] = array('like', '%' . $content . '%');
//        $this->assign('search', $content);
//        /* 查询自营商家 */
//        $companyIds = M('Company')->where(array('user_id' => UID))->getField('id');
//        //dump($companyIds);exit;
//        if ($companyIds) {
//            $where['company_id'] = array('in', $companyIds);
//            $where['status'] = array('eq', 1);
//
//            $list = $this->lists('Freight', $where);
//
//        } else {
//            $list = array();
//        }
//        $this->assign('list', $list);
//        /*页面公用设置*/
//        $this->site_title = '运费管理';
//        $this->assign('header', 'index');
//        $this->assign('nav', 'freight');
//        $this->display();
//    }
//
//    /**
//     * 删除运费模板
//     * @author 83961014@qq.com
//     */
//    public function freight_del() {
//        $id = I('id');
//        $condition['id'] = $id;
//        $condition['company_id'] = array('eq', $this->company_info['id']);
//        $return = M('Freight')->where($condition)->delete();
//        if ($return != false) {
//            $this->success('删除成功', '?g=member&m=sale&a=freight');
//        } else {
//            $this->error('删除失败', '?g=member&m=sale&a=freight');
//        }
//    }
//
//    /**
//     * 修改运费管理
//     * @return [type] [description]
//     */
//    /**
//     * 运费模版添加、编辑
//     */
//    public function freightAdd() {
//        $id = I('id', 0, 'intval');
//        // $model = D('Regions');
//        $opt = $id > 0 ? '修改' : '添加';
//        $ModelDB = M('Freight');
//        $detail = $ModelDB->where(array('id' => $id))->find();
//        if ($id && empty($detail)) {
//            $this->error('运费模板不存在');
//        }
//
//        if (IS_POST) {
//            $post = $_POST;
//            $data['title'] = $post['title'];
//            $data['delivery_id'] = $post['delivery_id'];
//            $data['piece'] = $post['piece'];
//            $data['delivery_id'] = $post['delivery_id'];
//            if (!$data['delivery_id']) {
//                $this->error('请选择默认物流公司');
//            }
//            foreach ($post['placeAllId'] as $key => $val) {
//                $arr[$key]['placeallid'] = $val;
//                $arr[$key]['package_first'] = $post['package_first'][$key] ? $post['package_first'][$key] : 0;
//                $arr[$key]['freight_first'] = $post['freight_first'][$key] ? $post['freight_first'][$key] : 0;
//                $arr[$key]['package_other'] = $post['package_other'][$key] ? $post['package_other'][$key] : 0;
//                $arr[$key]['freight_other'] = $post['freight_other'][$key] ? $post['freight_other'][$key] : 0;
//            }
//            $data['postage'] = json_encode($arr);
//            $data['sort'] = $post['sort'] ? $post['sort'] : 0;
//            $data['etime'] = time();
//            $data['company_id'] = $this->company_info['id'];
//            if (!$post['id']) {
//                // $data['wid'] = $wid;
//                $data['ctime'] = time();
//
//                $save = $ModelDB->data($data)->add();
//
//                $this->success('添加成功', '/index.php?g=member&m=sale&a=freight');
//                // $this->assign('error', array('url' => ''));
//                // $this->assign('info', $post);
//                // $this->display();
//                // exit;
//            } else {
//                // if ($detail['wid'] != $wid) {
//                //     echo "<script>alert('非法操作！');history.go(-1);</script>";
//                //     exit;
//                // }
//                $data['id'] = $post['id'];
//                $save = $ModelDB->data($data)->save();
//                // $this->assign('error', array('url' => '/index.php?g=admin&m=index&a=freight'));
//                // $this->assign('info', $data);
//                // $this->display();
//                // exit;
//                $this->success('添加成功', '/index.php?g=member&m=sale&a=freight');
//            }
//        } else {
//            $delivery = M('Express')->select();
//            //获取所有省市区信息
//            $city_arr = M('Regions')->cache(true, 3600)->select();
//            foreach ($city_arr as $val) {
//                $city_arr_name[$val['id']] = $val['name'];
//                $city_arr_new[$val['id']] = $val;
//
//                if ($val['parent']) {
//                    $all_arr[$val['parent']][] = $val['id'];
//                } else {
//                    $all_arr['sheng'][] = $val['id'];
//                }
//
//                // if($val['grade']==2){
//                //  $city_id_arr[]=$val['id'];
//                // }
//                // if($val['grade']==1){
//                //  $province_id_arr[]=$val['id'];
//                // }
//            }
//            // foreach($city_arr)
//
//            $this->assign('city_arr_name', $city_arr_name);
//
//            $detail['postage'] = json_decode($detail['postage']);
//            foreach ($detail['postage'] as $val) {
//                $val = get_object_vars($val);
//                $post_address = explode(",", $val['placeallid']);
//
//                if ($val['placeallid'] == 'moren') {
//                    $val['placename'] = '默认';
//                } else {
//                    $val['placename'] = $this->detailtree($post_address, $city_arr_new, $city_arr_name);
//                }
//
//                $postage_arr[] = $val;
//            }
//
//            $detail['postage'] = $postage_arr;
//
//            unset($postage_arr[0]);
//            foreach ($postage_arr as $val) {
//                $area_str .= $val['placeallid'];
//            }
//            $area_arr = explode(",", $area_str);
//            //当前已选市ID
//            foreach ($area_arr as $val) {
//                if ($city_arr_new[$val]['parent']) {
//                    $shi_arr[$city_arr_new[$val]['parent']][] = $val;
//                    $shi_arr2[] = $city_arr_new[$val]['parent'];
//                }
//                if (!$all_arr[$val] && ($city_arr_new[$val]['grade'] == 2)) {
//
//                    $shi_arr2[] = $val;
//                    $use_city_id[] = $val;
//                }
//            }
//            //当前已选省ID
//            foreach (array_unique($shi_arr2) as $val) {
//                if ($city_arr_new[$val]['parent']) {
//                    $sheng_arr[$city_arr_new[$val]['parent']][] = $val;
//                    $sheng_arr2[] = $city_arr_new[$val]['parent'];
//                }
//            }
//
//            //获取隐藏市ID
//            foreach ($all_arr as $key => $value) {
//                foreach ($shi_arr as $k => $val) {
//                    if (($key == $k)) {
//                        if (!array_diff($value, $val)) {
//                            $use_city_id[] = $key;
//                        }
//
//                    }
//                }
//            }
//            //获取隐藏省ID
//            foreach ($all_arr as $keynew => $valuenew) {
//                foreach ($sheng_arr as $ks => $vals) {
//                    if (($keynew == $ks)) {
//                        if (!array_diff($valuenew, $vals)) {
//                            // dump($valuenew);
//
//                            // dump($vals);
//                            $use_sheng_id[] = $keynew;
//                        }
//
//                    }
//                }
//            }
//            // dump($all_arr[423]);
//            // dump($sheng_arr);
//            // dump(array_diff($all_arr[423],$sheng_arr[423]));
//
//            $this->assign('use_city_id', implode(",", $use_city_id));
//            $this->assign('use_sheng_id', implode(",", $use_sheng_id));
//
//            $this->assign('info', $detail);
//
//            //页面显示省市区
//            $all_area = list_to_tree($city_arr, $pk = 'id', $pid = 'parent', $child = '_child', $root = 0);
//            $areastr = $this->detailareastr($all_area, $id, $detail['postage']);
//            $this->assign('areastr', $areastr);
//
//            // echo($areastr);exit;
//
//            $right = $this->detailrightstr($all_area, $id);
//            $this->assign('right', $right);
//            $this->assign('delivery', $delivery);
//            //dump($detail);exit;
//            // $this->display();
//
//            /* 页面基本设置 */
//            $this->site_title = $opt . '运费模版';
//            $this->assign('left', 'freight');
//            $this->display();
//        }
//    }
//
//    /**
//     * 商家销售利润提现
//     *
//     * 状态：
//     * -4订单退款中，
//     * -3待确认(待买家确认收货),
//     * -2可提现，
//     * -1删除,
//     * 0申请提现，
//     * 1通过申请，正在打款中，
//     * 2已打款，
//     * 3申请被拒，
//     * 4佣金发放，
//     * 5订单退款
//     */
//    public function withdrawals() {
//        if (IS_POST) {
//            $model = D('Withdrawals');
//            $result = $model->update();
//            if ($result) {
//                $this->success('操作成功', '?g=member&m=sale&a=withdrawalslist');
//            } else {
//                $this->error($model->getError());
//            }
//        }
//        /* 用户信息 */
//        $user_info = $this->user_info;
//        /* 查询销售利润明细 */
//        $withdrawalsList = M('Withdrawals')->field('withdrawals,status')->where(array('cid' => $user_info['company_id']))->select();
//        /* 可提现 = -2可提现 - 0申请提现 - 1打款中 -2已打款 - 4佣金发放 */
//        // $withdrawals = M('Withdrawals')->where(array('cid'=>$user_info['company_id'], 'status'=>-2))->sum('withdrawals');
//        $withdrawals = 0.00;
//        foreach ($withdrawalsList as $value) {
//            if (in_array($value['status'], array(-2))) {
//                $withdrawals += $value['withdrawals'];
//            } elseif (in_array($value['status'], array(0, 1, 2, 4))) {
//                $withdrawals -= $value['withdrawals'];
//            }
//        }
//
//        $this->assign('withdrawals', $withdrawals);
//        $this->site_title = '销售利润提现';
//        $this->assign('header', 'withdrawals');
//        $this->assign('nav', 'index');
//        $this->display();
//    }
//
//    /**
//     * 商家销售利润提现记录
//     *
//     * 状态：
//     * -4订单退款中，
//     * -3待确认(待买家确认收货),
//     * -2可提现，
//     * -1删除,
//     * 0申请提现，
//     * 1通过申请，正在打款中，
//     * 2已打款，
//     * 3申请被拒，
//     * 4佣金发放，
//     * 5订单退款
//     */
//    public function withdrawalsList() {
//        /* 用户信息 */
//        $user_info = $this->user_info;
//        /* 查询销售利润明细 */
//        $where['cid'] = array('eq', $user_info['company_id']);
//        $where['status'] = array('in', '0,1,2,3');
//        $list = $this->lists('Withdrawals', $where, 'etime DESC');
//        $this->assign('statusList', array('申请提现中', '打款中', '已打款', '申请被拒'));
//        $this->assign('typeList', array('', '支付宝', '银联转账', '转入余额'));
//        $this->assign('list', $list);
//        $this->site_title = '商家销售利润提现记录';
//        $this->assign('header', 'withdrawals');
//        $this->assign('nav', 'withdrawalslist');
//        $this->display();
//    }
//
//    /**
//     * 商家销售利润明细
//     *
//     * 状态：
//     * -4订单退款中，
//     * -3待确认(待买家确认收货),
//     * -2可提现，
//     * -1删除,
//     * 0申请提现，
//     * 1通过申请，正在打款中，
//     * 2已打款，
//     * 3申请被拒，
//     * 4佣金发放，
//     * 5订单退款
//     */
//    public function withdrawalsDetail() {
//        /* 用户信息 */
//        $user_info = $this->user_info;
//        /* 查询销售利润明细 */
//        $where['cid'] = array('eq', $user_info['company_id']);
//        $list = $this->lists('Withdrawals', $where, 'id DESC');
//
//        /* 数据处理 */
//        foreach ($list as $key => $value) {
//            switch ($value['status']) {
//                case '-4':
//                    $list[$key]['status'] = '订单退款中';
//                    $list[$key]['remark'] = '该笔利润来源订单（' . $value['order_id'] . '）正在退款中';
//                    break;
//                case '-3':
//                    $list[$key]['status'] = '待确认';
//                    $list[$key]['remark'] = '该笔利润来源订单（' . $value['order_id'] . '），待买家确认收货后可提现';
//                    break;
//                case '-2':
//                    $list[$key]['status'] = '可提现';
//                    $list[$key]['remark'] = '该笔利润来源订单（' . $value['order_id'] . '）';
//                    break;
//                case '-1':
//                    $list[$key]['status'] = '已删除';
//                    $list[$key]['remark'] = '';
//                    break;
//                case '0':
//                    $list[$key]['status'] = '申请提现';
//                    $list[$key]['remark'] = '请耐心等待平台处理';
//                    break;
//                case '1':
//                    $list[$key]['status'] = '打款中';
//                    $list[$key]['remark'] = '平台正在给您的帐号打款';
//                    break;
//                case '2':
//                    $list[$key]['status'] = '已打款';
//                    $list[$key]['remark'] = '平台已完成打款，请及时查收';
//                    break;
//                case '3':
//                    $list[$key]['status'] = '申请被拒';
//                    $list[$key]['remark'] = '平台拒绝了您的提现申请';
//                    break;
//                case '4':
//                    $list[$key]['status'] = '佣金发放';
//                    $list[$key]['remark'] = '该笔利润来源订单（' . $value['order_id'] . '）有参与分销的商品，故需要发放佣金';
//                    break;
//                case '5':
//                    $list[$key]['status'] = '订单退款完成';
//                    $list[$key]['remark'] = '该笔利润来源订单（' . $value['order_id'] . '）退款完成，扣除相应销售利润';
//                    break;
//                default:
//                    # code...
//                    break;
//            }
//        }
//
//        $this->assign('list', $list);
//        $this->site_title = '商家销售利润明细';
//        $this->assign('header', 'withdrawals');
//        $this->assign('nav', 'detail');
//        $this->display();
//    }
//
//    /**
//     * 提现账户管理
//     * @author 406764368@qq.com
//     * @version 2016年11月21日 14:09:11
//     */
//    public function account() {
//        /* 用户信息 */
//        $user_info = $this->user_info;
//        /* 查询提现账户 */
//        $where['uid'] = array('eq', UID);
//        $where['status'] = array('gt', 0);
//        $list = $this->lists('WithdrawalsAccount', $where, 'id DESC');
//        /* 查不到数据或数据条数少于10条则将转入余额添加进去 */
//        if (!$list || count($list) < 10) {
//            /* 转入余额 */
//            $list[] = array(
//                'account_id' => '-1',
//                'account' => $user_info['mphone'],
//                'truename' => $user_info['name'],
//                'type' => '3',
//                'bank_card' => '',
//                'img' => C('HTTP_APPS_IMG') . 'balancepay_icon.png'
//            );
//        }
//
//        $this->assign('list', $list);
//        $this->site_title = '提现账户管理';
//        $this->assign('header', 'withdrawals');
//        $this->assign('nav', 'account');
//        $this->display();
//    }
//
//
//    /**
//     * 申请成为企业
//     * @return [type] [description]
//     */
//    public function contact() {
//        if (IS_POST) {
//            /*验证companyLink*/
//            $companyModel = D('Company');
//            $companyLinkModel = D('CompanyLink');
//            $linkStatus = $companyLinkModel->test(I('post.companyLink'));
//            if (!$linkStatus) {
//                $errorInfo = $companyLinkModel->getError();
//                $this->error($errorInfo);
//            }
//            /*向companyLink表插入数据*/
//            $status = $companyModel->update();
//            if ($status) {
//                $companyLinkModel->update($status['id']);
//                $company_type = M('Conf')->where(array('name' => 'company_type'))->getField('value');
//                if ($company_type != 1) {
//                    $status = M('member')->where(array('uid' => UID))->setField('gid', 2);
//                    $this->success('保存企业资料成功', 'index.php?g=member&m=sale');
//                }
//                $this->success('保存企业资料成功请等待审核', 'index.php?g=member&m=sale');
//            } else {
//                $errorInfo = $companyModel->getError();
//                $this->error($errorInfo);
//            }
//        }
//
//        /*省市*/
//        $region = M('Regions')->cache(true)->select();
//        foreach ($region as $val) {
//            if (!$val['parent']) {
//                $region_arr[0][] = $val;
//            } else {
//                $region_arr[$val['parent']][] = $val;
//            }
//        }
//        $this->assign('region_arr', $region_arr);
//        $this->assign('region_arr_json', json_encode($region_arr));
//
//        /* 企业分类 */
//        $list = M('CompanyCategory')->field('id,name')->where(array('status' => 1))->order('sort DESC')->select();
//        $this->assign('list', $list);
//
//        /*企业联系信息*/
//        $company_info = $this->company_info;
//        $this->assign('company_info', $company_info);
//        $this->site_title = '联系方式';
//        $this->assign('header', 'company');
//        $this->display();
//    }
//
//
//    public function contactCopy() {
//        $company_info = $this->company_info;
//        if (IS_POST) {
//            $status = D('CompanyLink')->update();
//            if ($status) {
//                //修改会员为企业会员
//                M('Member')->where(array('uid' => UID))->save(array('gid' => 2));
//                $this->success('保存企业联系方式成功');
//            } else {
//                $errorInfo = D("CompanyLink")->getError();
//                $this->error($errorInfo);
//            }
//        } else {
//            /*省市*/
//            $region = M('Regions')->cache(true)->select();
//            foreach ($region as $val) {
//                if (!$val['parent']) {
//                    $region_arr[0][] = $val;
//                } else {
//                    $region_arr[$val['parent']][] = $val;
//                }
//            }
//            $this->assign('region_arr', $region_arr);
//            $this->assign('region_arr_json', json_encode($region_arr));
//            /*企业联系信息*/
//            $map['company_id'] = array('eq', $company_info['id']);
//            $link_info = M('CompanyLink')->where($map)->find();
//            $this->assign('link_info', $link_info);
//            $this->site_title = '联系方式';
//            $this->assign('header', 'company');
//            $this->display();
//        }
//    }
//
//    /**
//     * @param  array $city_arr 所有省市区信息
//     * @return string           左侧地区信息
//     * @author 122837594@qq.com
//     */
//    private function detailareastr($city_arr, $id) {
//        $this->assign('id', $id);
//        $this->assign('city_arr', $city_arr);
//        return $this->fetch('detailareastr');
//    }
//
//    /**
//     * @param  array $city_arr 所有省市区信息
//     * @return string           右侧地区信息
//     * @author 122837594@qq.com
//     */
//    public function detailrightstr($city_arr, $id) {
//        $this->assign('id', $id);
//        $this->assign('city_arr', $city_arr);
//        return $this->fetch('detailrightstr');
//    }
//
//    /**
//     * 把当前邮费地址处理成树
//     * @param  array $arr 当前邮费地址
//     * @param  array $city_arr 所有省市区信息
//     * @return string           地区信息
//     * @author 122837594@qq.com
//     */
//    protected function detailtree($arr, $city_arr, $city_arr_name) {
//
//        foreach ($arr as $val) {
//            if ($city_arr[$val]['parent']) {
//                $new_arr[$city_arr[$val]['parent']][] = $val;
//            }
//        }
//        foreach ($new_arr as $key => $val) {
//            $tmp_arr[$key] = $val;
//            //if ($city_arr[$key]['parent']) {
//            $new_arr2[$city_arr[$key]['parent']][] = $tmp_arr;
//            //}
//            $tmp_arr = array();
//        }
//        $str = "";
//        foreach ($new_arr2 as $provincek => $provincevo) {
//
//            $str .= $city_arr_name[$provincek] . "(";
//            foreach ($provincevo as $allcity) {
//                foreach ($allcity as $citykey => $cityvo) {
//                    $str .= $city_arr_name[$citykey] . ":";
//                    foreach ($cityvo as $areavo) {
//                        $str .= $city_arr_name[$areavo] . ",";
//                    }
//                }
//            }
//            $str = rtrim($str, ',');
//            $str .= ")<br /><br />";
//        }
//
//        return $str;
//    }
//
//    private function &dumpTreeList($arr, $parentId = 0, $lv = 0) {
//        $lv++;
//        $tree = array();
//        foreach ((array)$arr as $row) {
//            if ($row['parent'] == $parentId) {
//                $row['level'] = $lv;
//                if ($row['parent'] != 0) {
//                    $row['sty'] = "|";
//                }
//
//                for ($i = 1; $i < $row['level']; $i++) {
//                    $row['sty'] .= "——";
//                    $row['sty2'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//                }
//                $row['sty2'] = $row['sty2'] . $row['sty'];
//                $tree[] = $row;
//                if ($children = $this->dumpTreeList($arr, $row['id'], $lv)) {
//                    $tree = array_merge($tree, $children);
//                }
//            }
//        }
//        return $tree;
//    }

}