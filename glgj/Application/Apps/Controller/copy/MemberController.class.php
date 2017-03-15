<?php
namespace Apps\Controller;

use Org\Util\Date;
use Think\Controller;

class MemberController extends ApiController {
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
            ->field('l.uuid token,r.uid,r.gid,r.mphone,r.name username,r.head_pic,c.id company_id,c.name company_name,c.status company_status,r.balance,r.invite_code,r.pid')
            ->join($prefix . 'member r on l.id = r.token_id', 'LEFT')
            ->join($prefix . 'company c on r.uid = c.user_id', 'LEFT')
            ->where(array('l.uuid' => $token))
            ->find();
        /* 处理数据 */
        !$memberInfo && $this->ajaxJson('40004', '登陆信息已过期');
        $memberInfo['head_pic'] = $this->getAbsolutePath($memberInfo['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
        !$memberInfo['company_id'] && $memberInfo['company_id'] = '';
        !$memberInfo['company_name'] && $memberInfo['company_name'] = '';
        $this->memberInfo = $memberInfo;
    }

    /**
     * 我的页面
     */
    public function index() {
        if ( IS_GET ) {
            /* 用户信息 */
            $memberInfo = $this->memberInfo;
            $list['member_info']['head_pic']     = $memberInfo['head_pic'];
            $list['member_info']['username']     = $memberInfo['username'];
            $list['member_info']['mobile']       = $memberInfo['mphone'];
            $list['member_info']['company_id']   = $memberInfo['company_id'];
            $list['member_info']['company_name'] = $memberInfo['company_name'];
            $list['member_info']['balance']      = '￥' . $memberInfo['balance'];
            $list['member_info']['invite_code']  = $memberInfo['invite_code'];
            /* 消息数量 */
            $list['member_info']['message_count'] = $this->message(1);
            /* 购物车数量 */
            $where_cart['userid'] = array('eq', $memberInfo['uid']);
            $where_cart['status'] = array('eq', 1);
            $list['member_info']['cart_num'] = M('OrderShopcart')->where($where_cart)->count('subid');

            /**
             * 订单状态统计
             * '-1'=>'已删除'，'0' => '待付款', '1' => '待发货', '2' => '待收货', '3' => '待评价'（买家确认收货后）, '4' => '退款中'（商家同意退款申请后）, '5' => '交易关闭'（买家确认收款后）, '6' => '申请退款', '7' => '退款被拒', '8' => '交易完成'（买家评价后），'9'=>'已退款（商家确认打款后），'10'=>'交易完成后被删除的订单'
             */
            $statusList = M('Order')->where(array('uid' => $memberInfo['uid']))->getField('state', true);
            $statusList = array_count_values($statusList);
            /* 退款/售后 数量 */
            $refundNums = $statusList[4] + $statusList[6] + $statusList[7] + $statusList[9];
            $refundNums = $refundNums > 0 ? strval($refundNums) : '';

            /* 是否开启企业功能 1->开启 0->关闭 */
            $companyFlag = 1;

            /* 客户端图片路径 */
            $appPath = C('HTTP_APPS_IMG');

            /* app版本 1->电商；2->资讯 */
            $appVersion = C('FLASHFLAG');
            if ( $appVersion == 1 ) {
                /* 常用工具数组 */
                $distributionFlag = M('Conf')->where(array('name'=>'distribution'))->getField('value');
                if ( $distributionFlag == 1 ) {
                    $toolsArray = array(
                        array('title' => '我的钱包', 'icon' => $appPath . 'mine_wallet.png', 'href' => 'wallet', 'num' => ''),
                        array('title' => '我的优惠券', 'icon' => $appPath . 'mine_coupon.png', 'href' => 'coupon', 'num' => ''),
                        array('title' => '商品收藏', 'icon' => $appPath . 'mine_product_collect.png', 'href' => 'product_collect', 'num' => ''),
                        array('title' => '商家收藏', 'icon' => $appPath . 'mine_company_collect.png', 'href' => 'company_collect', 'num' => ''),
                        array('title' => '圈子收藏', 'icon' => $appPath . 'mine_circle_collect.png', 'href' => 'circle_collect', 'num' => ''),
                        array('title' => '求购收藏', 'icon' => $appPath . 'mine_need_collect.png', 'href' => 'need_collect', 'num' => ''),
                        // array('title' => '供应收藏', 'icon' => $appPath . 'mine_supply_collect.png', 'href' => 'supply_collect', 'num' => ''),
                        array('title' => '资讯收藏', 'icon' => $appPath . 'mine_new_collect.png', 'href' => 'new_collect', 'num' => ''),
                        array('title' => '收货地址', 'icon' => $appPath . 'mine_address.png', 'href' => 'address', 'num' => ''),
                        array('title' => '我的邀请码', 'icon' => $appPath . 'mine_invite_code.png', 'href' => 'invite_code', 'num' => $memberInfo['uid']),
                        array('title' => '填写邀请码', 'icon' => $appPath . 'mine_fill_invite_code.png', 'href' => 'fill_invite_code', 'num' => ''),
                        array('title' => '我的佣金', 'icon' => $appPath . 'mine_commission.png', 'href' => 'commission', 'num' => ''),
                        array('title' => '', 'icon' => '', 'href' => '', 'num' => '')
                    );
                } else {
                    $toolsArray = array(
                        array('title' => '我的钱包', 'icon' => $appPath . 'mine_wallet.png', 'href' => 'wallet', 'num' => ''),
                        array('title' => '我的优惠券', 'icon' => $appPath . 'mine_coupon.png', 'href' => 'coupon', 'num' => ''),
                        array('title' => '商品收藏', 'icon' => $appPath . 'mine_product_collect.png', 'href' => 'product_collect', 'num' => ''),
                        array('title' => '商家收藏', 'icon' => $appPath . 'mine_company_collect.png', 'href' => 'company_collect', 'num' => ''),
                        array('title' => '圈子收藏', 'icon' => $appPath . 'mine_circle_collect.png', 'href' => 'circle_collect', 'num' => ''),
                        array('title' => '求购收藏', 'icon' => $appPath . 'mine_need_collect.png', 'href' => 'need_collect', 'num' => ''),
                        // array('title' => '供应收藏', 'icon' => $appPath . 'mine_supply_collect.png', 'href' => 'supply_collect', 'num' => ''),
                        array('title' => '资讯收藏', 'icon' => $appPath . 'mine_new_collect.png', 'href' => 'new_collect', 'num' => ''),
                        array('title' => '收货地址', 'icon' => $appPath . 'mine_address.png', 'href' => 'address', 'num' => '')
                    );
                }
                /* 区信息 */
                $list['section'] = array(
                    array(
                        'section_type'   => 'member_info',
                        'section_title'  => $memberInfo['username'],
                        'section_icon'   => $memberInfo['head_pic'],
                        'section_sub'    => '',
                        'section_href'   => 'mine_info',
                        'section_bottom' => '0',
                        'section_items'  => array(
                            array('title' => '', 'icon' => $appPath . 'mine_message.png', 'href' => 'mine_message', 'num' => $list['member_info']['message_count']),
                            array('title' => '', 'icon' => $appPath . 'mine_cart.png', 'href' => 'mine_cart', 'num' => strval($list['member_info']['cart_num']))
                        )
                    ),
                    // array(
                    //     'section_type'   => 'row_two',
                    //     'section_title'  => '',
                    //     'section_icon'   => '',
                    //     'section_sub'    => '',
                    //     'section_href'   => '',
                    //     'section_bottom' => '10',
                    //     'section_items' => array(
                    //         array('title' => '我的主页', 'icon' => $appPath . 'mine_mine_home.png', 'href' => 'mine_home', 'num' => ''),
                    //         array('title' => '关注的人', 'icon' => $appPath . 'mine_focus.png', 'href' => 'mine_focus', 'num' => '')
                    //     )
                    // ),
                    array(
                        'section_type'   => 'row_five',
                        'section_title'  => '我的订单',
                        'section_icon'   => '',
                        'section_sub'    => '查看全部订单',
                        'section_href'   => 'all_order',
                        'section_bottom' => '10',
                        'section_items'  => array(
                            array('title' => '待付款', 'icon' => $appPath . 'mine_wait_pay.png', 'href' => 'wait_pay', 'num' => strval($statusList[0])),
                            array('title' => '待发货', 'icon' => $appPath . 'mine_wait_delivery.png', 'href' => 'wait_delivery', 'num' => strval($statusList[1])),
                            array('title' => '待收货', 'icon' => $appPath . 'mine_wait_receive.png', 'href' => 'wait_receive', 'num' => strval($statusList[2])),
                            array('title' => '待评价', 'icon' => $appPath . 'mine_wait_comment.png', 'href' => 'wait_comment', 'num' => strval($statusList[3])),
                            array('title' => '退款/售后', 'icon' => $appPath . 'mine_refund.png', 'href' => 'refund', 'num' => $refundNums)
                        )
                    ),
                    array(
                        'section_type'   => 'row_four',
                        'section_title'  => '常用工具',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => $toolsArray
                    ),
                    array(
                        'section_type'   => 'row_three',
                        'section_title'  => '我的店铺',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => array()
                    ),
                    array(
                        'section_type'   => 'row_four',
                        'section_title'  => '更多服务',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => array(
                            // array('title' => '欢迎页', 'icon' => $appPath . 'mine_welcome.png', 'href' => 'welcome', 'num' => ''),
                            array('title' => '关于APP', 'icon' => $appPath . 'mine_about.png', 'href' => 'about', 'num' => ''),
                            // array('title' => '清除缓存', 'icon' => $appPath . 'mine_clear_cache.png', 'href' => 'clear_cache', 'num' => ''),
                            array('title' => '', 'icon' => '', 'href' => '', 'num' => ''),
                            array('title' => '', 'icon' => '', 'href' => '', 'num' => ''),
                            array('title' => '', 'icon' => '', 'href' => '', 'num' => '')
                        )
                    )
                );
            } else {
                /* 区信息 */
                $list['section'] = array(
                    array(
                        'section_type'   => 'member_info',
                        'section_title'  => $memberInfo['username'],
                        'section_icon'   => $memberInfo['head_pic'],
                        'section_sub'    => '',
                        'section_href'   => 'mine_info',
                        'section_bottom' => '0',
                        'section_items'  => array(
                            array('title' => '', 'icon' => $appPath . 'mine_message.png', 'href' => 'mine_message', 'num' => $list['member_info']['message_count']),
                            // array('title' => '', 'icon' => $appPath . 'mine_cart.png', 'href' => 'mine_cart', 'num' => strval($list['member_info']['cart_num']))
                        )
                    ),
                    array(
                        'section_type'   => 'column',
                        'section_title'  => '我的收藏',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => array(
                            array('title' => '商品收藏', 'icon' => $appPath . 'mine_product_collect.png', 'href' => 'product_collect', 'num' => ''),
                            array('title' => '商家收藏', 'icon' => $appPath . 'mine_company_collect.png', 'href' => 'company_collect', 'num' => ''),
                            array('title' => '圈子收藏', 'icon' => $appPath . 'mine_circle_collect.png', 'href' => 'circle_collect', 'num' => ''),
                            array('title' => '求购收藏', 'icon' => $appPath . 'mine_need_collect.png', 'href' => 'need_collect', 'num' => ''),
                            array('title' => '资讯收藏', 'icon' => $appPath . 'mine_new_collect.png', 'href' => 'new_collect', 'num' => '')
                        )
                    ),
                    array(
                        'section_type'   => 'row_three',
                        'section_title'  => '我的店铺',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => array()
                    ),
                    array(
                        'section_type'   => 'column',
                        'section_title'  => '更多服务',
                        'section_icon'   => '',
                        'section_sub'    => '',
                        'section_href'   => '',
                        'section_bottom' => '10',
                        'section_items'  => array(
                            // array('title' => '欢迎页', 'icon' => $appPath . 'mine_welcome.png', 'href' => 'welcome', 'num' => ''),
                            array('title' => '关于APP', 'icon' => $appPath . 'mine_about.png', 'href' => 'about', 'num' => '')
                        )
                    )
                );
            }

            /* 是否开启企业功能 1->开启 0->关闭 */
            if ($companyFlag == 1) {
                /* 判断是否成为企业会员 */
                if ($memberInfo['company_id']) {
                    /* 不同状态跳转不同的页面 */
                    if ($memberInfo['company_status'] == 2 || $memberInfo['company_status'] == 3) {
                        /* 企业会员审核中状态 */
                        $mineCompanyList = array(
                            array('title' => '我的商铺', 'icon' => $appPath . 'mine_company_home.png', 'href' => 'company_status', 'num' => ''),
                            array('title' => '发布产品', 'icon' => $appPath . 'mine_company_product_add.png', 'href' => 'company_status', 'num' => '')
                        );
                    } else {
                        /* 企业会员正常状态 */
                        $mineCompanyList = array(
                            array('title' => '我的商铺', 'icon' => $appPath . 'mine_company_home.png', 'href' => 'company_home', 'num' => $memberInfo['company_id']),
                            array('title' => '发布产品', 'icon' => $appPath . 'mine_company_product_add.png', 'href' => 'company_product_add', 'num' => '')
                        );
                    }
                } else {
                    /* 非企业会员 */
                    $mineCompanyList = array(
                        array('title' => '我的商铺', 'icon' => $appPath . 'mine_company_home.png', 'href' => 'company_apply', 'num' => ''),
                        array('title' => '发布产品', 'icon' => $appPath . 'mine_company_product_add.png', 'href' => 'company_apply', 'num' => '')
                    );
                }
                $mineCompanyList[] = array('title' => 'PC后台登陆', 'icon' => $appPath . 'mine_backstage.png', 'href' => 'backstage', 'num' => '');
                if ( $appVersion == 1 ) {
                    $list['section'][3]['section_items'] = $mineCompanyList;
                } else {
                    $list['section'][2]['section_items'] = $mineCompanyList;
                }
            } else {
                /* 不开启企业功能时把我的商铺整块隐藏 */
                if ( $appVersion == 1 ) {
                    unset($list['section'][3]);
                } else {
                    unset($list['section'][2]);
                }
                $list['section'] = array_values($list['section']);
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 我的个人资料编辑
     */
    public function memberInfoEdit() {
        if (IS_POST) {
            $model = D('MemberInfo');
            $result = $model->update();
            if ($result) {
                $this->ajaxJson('40000', '编辑成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 我的通用收藏列表
     */
    public function collect() {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $favorite_category = I('get.favorite_category', '', 'strval');
            empty($favorite_category) && $this->ajaxJson('70000', '参数错误');

            /* 用户信息 */
            $memberInfo = $this->memberInfo;

            /* 查询收藏列表数据 */
            $collectModel = M('UserFavorite');
            $prefix = C('DB_PREFIX');
            $where['l.favorite_category'] = array('eq', $favorite_category);
            $where['l.uid'] = array('eq', $memberInfo['uid']);
            switch ($favorite_category) {
                case '1':
                    /* 资讯收藏 */
                    $modelName = '资讯';
                    $modelStr = 'news';
                    $list['collect'] = $collectModel
                        ->alias('l')
                        ->join($prefix . 'article r ON l.aid = r.id', 'LEFT')
                        ->join($prefix . 'news_comment c ON l.aid = c.pid', 'LEFT')
                        ->field('l.id collect_id,l.aid,r.id data_id,l.title,r.short_title,r.image img,r.addtime ctime,count(c.id) comment_count,"" price')
                        ->where($where)
                        ->group('l.id')
                        ->order('l.id DESC')
                        ->limit($page, 10)
                        ->select();
                    break;
                case '2':
                    /* 产品收藏 */
                    $modelName = '产品';
                    $modelStr = 'product';
                    $list['collect'] = $collectModel
                        ->alias('l')
                        ->join($prefix . 'product_sale r ON l.aid = r.id', 'LEFT')
                        ->field('l.id collect_id,l.aid,r.id data_id,l.title,r.short_title,r.img,"" ctime,"" comment_count,r.price')
                        ->where($where)
                        ->order('l.id DESC')
                        ->limit($page, 10)
                        ->select();
                    break;
                case '3':
                    /* 企业收藏 */
                    $modelName = '企业';
                    $modelStr = 'company';
                    $list['collect'] = $collectModel
                        ->alias('l')
                        ->join($prefix . 'company r ON l.aid = r.id', 'LEFT')
                        ->field('l.id collect_id,l.aid,r.id data_id,l.title,r.business short_title,r.logo img,"" ctime,"" comment_count,"" price')
                        ->where($where)
                        ->order('l.id DESC')
                        ->limit($page, 10)
                        ->select();
                    break;
                case '4':
                    /* 求购收藏 */
                    $modelName = '求购';
                    $modelStr = 'need';
                    $list['collect'] = $collectModel
                        ->alias('l')
                        ->join($prefix . 'product_buy r ON l.aid = r.id', 'LEFT')
                        ->field('l.id collect_id,l.aid,r.id data_id,l.title,r.short_title,r.img,"" ctime,"" comment_count,r.price')
                        ->where($where)
                        ->order('l.id DESC')
                        ->limit($page, 10)
                        ->select();
                    break;
                case '5':
                    /* 行业圈收藏 */
                    $modelName = '行业圈';
                    $modelStr = 'circle';
                    $list['collect'] = $collectModel
                        ->alias('l')
                        ->join($prefix . 'circle r ON l.aid = r.id', 'LEFT')
                        ->join($prefix . 'member m ON r.uid = m.uid', 'LEFT')
                        ->field('l.id collect_id,l.aid,r.id data_id,l.title,concat("来自",m.name) short_title,r.img,"" ctime,"" comment_count,"" price')
                        ->where($where)
                        ->order('l.id DESC')
                        ->limit($page, 10)
                        ->select();
                    break;
                default:
                    $this->ajaxJson('70000', '参数错误');
                    break;
            }
            /* 数据处理 */
            $list['collect'] = $this->getAbsolutePath($list['collect'], 'img', C('HTTP_APPS_IMG') . $modelStr . '_default.png');
            foreach ($list['collect'] as $key => $value) {
                !$list['collect'][$key]['data_id'] && $list['collect'][$key]['data_id'] = '';
                !$list['collect'][$key]['title'] && $list['collect'][$key]['title'] = '';
                !$list['collect'][$key]['short_title'] && $list['collect'][$key]['short_title'] = '';
                !$list['collect'][$key]['price'] && $list['collect'][$key]['price'] = '';
                $list['collect'][$key]['ctime'] && $list['collect'][$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                $list['collect'][$key]['comment_count'] && $list['collect'][$key]['comment_count'] = $this->dealNumber($value['comment_count']);
                !$list['collect'][$key]['data_id'] && $list['collect'][$key]['short_title'] = '该' . $modelName . '已被删除';
                unset($list['collect'][$key]['aid']);
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 我的通用收藏删除
     */
    public function collectDel()
    {
        if (IS_POST) {
            $id = I('post.id');
            $id = explode(',', $id);
            if (!is_array($id) || !$id[0]) {
                $this->ajaxJson('70000', '参数错误');
            }
            $model = D('UserFavorite');
            $condition['id'] = array('in', $id);
            $return = $model->del($condition);
            if ($return) {
                $this->ajaxJson('40000', '删除成功');
            } else {
                $this->ajaxJson('70000', '删除失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 我的钱包
     */
    public function wallet()
    {
        if (IS_GET) {
            $list['balance'] = $this->memberInfo['balance'];

            /* 查询充值活动信息 */
            $where['activity_type'] = array('eq', 2);
            $where['status'] = array('eq', 1);
            $where['start_time'] = array('lt', time());
            $where['end_time'] = array('gt', time());
            $list['activity_info'] = M('Activity')->field('img,end_time')->where($where)->find();

            /* 判断充值活动是否存在 */
            if ($list['activity_info']) {
                /* 活动图片 */
                $list['activity_info']['img'] = $this->getAbsolutePath($list['activity_info']['img']);
                /* 充值活动截止时间 */
                $list['activity_info']['end_time'] = date('Y-m-d H:i:s', $list['activity_info']['end_time']) . '前截止';
                /* 充值活动规则 */
                $ruleList = M('Recharge')->field('reach,give')->order('sort DESC,id ASC')->select();
                if ($ruleList) {
                    $list['activity_info']['rule'] = '活动期间';
                    foreach ($ruleList as $key => $value) {
                        $list['activity_info']['rule'] .= '充值' . $value['reach'] . '元送' . $value['give'] . '元，';
                    }
                    $list['activity_info']['rule'] .= '赶紧行动吧';
                } else {
                    $list['activity_info']['rule'] = '平台未设置任何规则';
                }
                /* 版权 */
                $list['activity_info']['copyright'] = '本活动最终解释权归举办方所有';
            } else {
                $list['activity_info']['img'] = '';
                $list['activity_info']['end_time'] = '';
                $list['activity_info']['rule'] = '';
                $list['activity_info']['copyright'] = '';
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 钱包明细
     */
    public function walletDetail()
    {
        if (IS_GET) {
            $uid = $this->memberInfo['uid'];

            /* 订单支付和退款 */
            $orderList = M('OrderPayLog')
                ->alias('l')
                ->field('GROUP_CONCAT(r.goodname SEPARATOR "、") title,l.add_time time,l.pay_price money,l.trade_no')
                ->join(C('DB_PREFIX') . 'order_sub r ON l.order_id = r.orderid', 'LEFT')
                ->select();
            /* 数据处理 */
            if ($orderList) {
                foreach ($orderList as $ok => $ov) {
                    /* 标题处理 */
                    if ($ov['trade_no']) {
                        $orderList[$ok]['title'] = '[支付]' . $ov['title'];
                        $orderList[$ok]['money'] = '-' . $ov['money'];
                    } else {
                        $orderList[$ok]['title'] = '[退款]' . $ov['title'];
                        $orderList[$ok]['money'] = '+' . $ov['money'];
                    }
                    /* 销毁客户端不需要的数据 */
                    unset($orderList[$ok]['trade_no']);
                    /* 存储时间，排序用 */
                    $timeList[] = $ov['time'];
                }
            } else {
                $orderList = array();
            }

            /* 充值记录 */
            $where_recharge['uid'] = array('eq', $uid);
            $where_recharge['status'] = array('eq', 1);
            $rechargeList = M('RechargeLogs')->field('payment_id title,pay_time time,total_price money')->where($where_recharge)->select();
            /* 数据处理 */
            if ($rechargeList) {
                /* 充值方式数组 */
                $paymentList = array('1' => '微信', '2' => '支付宝');
                foreach ($rechargeList as $rck => $rcv) {
                    /* 标题处理 */
                    $rechargeList[$rck]['title'] = '[充值]来源：' . $paymentList[$rcv['title']];
                    /* 金额处理 */
                    $rechargeList[$rck]['money'] = '+' . $rcv['money'];
                    /* 存储时间，排序用 */
                    $timeList[] = $rcv['time'];
                }
            } else {
                $rechargeList = array();
            }

            /* 支付红包 */
            $where_red['uid'] = array('eq', $uid);
            $where_red['status'] = array('eq', 0);
            $redEnvelopeList = M('RedEnvelope')->field('"" title,ctime time,money')->where($where_red)->select();
            /* 数据处理 */
            if ($redEnvelopeList) {
                /* 充值方式数组 */
                $paymentList = array('1' => '微信', '2' => '支付宝');
                foreach ($redEnvelopeList as $rek => $rev) {
                    /* 标题处理 */
                    $redEnvelopeList[$rek]['title'] = '[收入]来源：支付红包';
                    /* 金额处理 */
                    $redEnvelopeList[$rek]['money'] = '+' . $rev['money'];
                    /* 存储时间，排序用 */
                    $timeList[] = $rev['time'];
                }
            } else {
                $redEnvelopeList = array();
            }

            $list['wallet_detail'] = array_merge($orderList, $rechargeList, $redEnvelopeList);
            if ($list['wallet_detail']) {
                /* 按时间排序 */
                array_multisort($timeList, SORT_DESC, $list['wallet_detail']);
                /* 时间处理 */
                foreach ($list['wallet_detail'] as $key => $value) {
                    $list['wallet_detail'][$key]['time'] = $this->dateTimeDeal($value['time']);
                }
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 会员充值
     */
    public function recharge()
    {
        /* 用户信息 */
        $memberInfo = $this->memberInfo;
        if (IS_GET) {
            /* 获取支付方式 */
            $list['payment_list'] = R('Payment/getPayment', array($memberInfo['balance'], 0));
            $this->returnJson($list);
        }
        if (IS_POST) {
            /* 验证字段 */
            $money = I('post.money');
            !$money && $this->ajaxJson('70000', '请填写充值金额');
            !preg_match('/^\d+(\.\d{1,2})?$/', $money) && $this->ajaxJson('70000', '充值金额格式不正确');
            $payment_id = I('post.payment_id', 0, 'intval');
            !$payment_id && $this->ajaxJson('70000', '请选择支付方式');
            !in_array($payment_id, array(1, 2)) && $this->ajaxJson('70000', '支付方式异常');

            /* 查询充值活动信息 */
            $where['activity_type'] = array('eq', 2);
            $where['status']        = array('eq', 1);
            $where['start_time']    = array('lt', time());
            $where['end_time']      = array('gt', time());
            $activityInfo = M('Activity')->field('id,img')->where($where)->find();
            /* 充、送 */
            $ruleInfo['reach'] = 0.00;
            $ruleInfo['give']  = 0.00;
            if ($activityInfo) {
                /* 读取活动规则 */
                $where_rule['reach'] = array('elt', $money);
                $ruleInfo = M('Recharge')->field('reach,give')->where($where_rule)->order('reach DESC,give DESC')->find();
            }

            /* 生成充值记录 */
            $logsDatas['id']          = 'R' . $uid . date('Ymd') . time() . mt_rand(100000, 999999);
            $logsDatas['uid']         = intval($memberInfo['uid']);
            $logsDatas['pay_price']   = floatval($money);
            $logsDatas['reach']       = floatval($ruleInfo['reach']);
            $logsDatas['give']        = floatval($ruleInfo['give']);
            $logsDatas['total_price'] = floatval($money + $logsDatas['give']);
            $logsDatas['payment_id']  = intval($payment_id);
            $logsDatas['trade_no']    = '';
            $logsDatas['ctime']       = time();
            $logsDatas['pay_time']    = 0;
            $logsDatas['status']      = 0;
            $add = M('RechargeLogs')->add($logsDatas);
            !$add && $this->ajaxJson('70000', '充值失败');

            /* 获取客户端所需支付参数 */
            $rechargeDatas['title'] = '余额充值';
            $rechargeDatas['order_total_price'] = $logsDatas['pay_price'];
            $rechargeDatas['order_trade'] = $logsDatas['id'];
            switch ($payment_id) {
                case '1':
                    /* 微信支付 */
                    $rechargeDatas['notify_url'] = C('HTTP_ORIGIN') . '/apps/payment/wechatRechargeNotify';
                    $list['pay_param'] = R('Payment/wechatpay', array($rechargeDatas));
                    $list['pay_param']['timestamp'] = strval($list['pay_param']['timestamp']);
                    break;
                case '2':
                    /* 支付宝支付 */
                    $list['pay_param'] = R('Payment/alipay', array($rechargeDatas));
                    $list['pay_param']['notify_url'] = C('HTTP_ORIGIN') . '/apps/payment/alipayRechargeNotify';
                    $list['pay_param']['product_title'] = $rechargeDatas['title'];
                    $list['pay_param']['order_id'] = $logsDatas['id'];
                    $list['pay_param']['order_total_price'] = sprintf('%.2f', $rechargeDatas['order_total_price']);
                    break;
                default:
                    $this->ajaxJson('70000', '支付方式不存在');
                    break;
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70000', '非法操作');
    }

    /**
     * 关于APP
     */
    public function about()
    {
        if (IS_GET) {
            /* logo图片 */
            $list['logo'] = C('HTTP_ORIGIN') . '/Public/App/images/logo.png';

            /* 查询配置信息 */
            $confList = M('Conf')->select();
            foreach ($confList as $key => $value) {
                $conf[$value['name']] = $value['value'];
            }
            $list['telephone'] = $conf['companphone'];
            $list['copyright'] = $conf['other'];

            /* 关于我们 */
            $list['about_weburl'] = C('HTTP_ORIGIN') . '?g=app&m=apps&a=about';

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 意见反馈
     */
    public function opinion()
    {
        if (IS_POST) {
            $result = D('Suggestion')->update();
            if ($result) {
                $this->ajaxJson('40000', '发送成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 融云消息列表
     */
    public function rongCloud()
    {
        if (IS_GET) {
            $id = I('get.id');
            $id = explode(',', $id);
            foreach ($id as $key => $value) {
                $temp = explode('_', $value);
                if ($temp[0] == 'm') {
                    /* 个人会员 */
                    $memberIds[] = $temp[1];
                } elseif ($temp[0] == 'c') {
                    /* 企业 */
                    $companyIds[] = $temp[1];
                } else {
                    $this->ajaxJson('70000', '参数错误');
                }
            }

            if (is_array($memberIds)) {
                // $where_member['status'] = array('eq', 1);
                $where_member['uid'] = array('in', $memberIds);
                $memberList = M('Member')->where($where_member)->getField("concat('m_', uid) id,name,head_pic", true);
            }

            if (is_array($companyIds)) {
                // $where_company['status'] = array('eq', 1);
                $where_company['id'] = array('in', $companyIds);
                $companyList = M('Company')->where($where_company)->getField("concat('c_', id) id,name,logo head_pic", true);
            }

            !$memberList && $memberList = array();
            !$companyList && $companyList = array();

            $rongCloudList = array_merge($memberList, $companyList);
            $rongCloudList = $this->getAbsolutePath($rongCloudList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');

            if ( $rongCloudList ) {
                foreach ($id as $ik => $iv) {
                    $rongCloudList[$iv] && $list['rongcloud'][$ik] = $rongCloudList[$iv];
                }
            }
            if ( $list['rongcloud'] ) {
                $list['rongcloud'] = array_values($list['rongcloud']);
            } else {
                $list['rongcloud'] = array();
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 我的消息
     */
    public function message($type = 0)
    {
        if (!IS_GET && $type != 1) {
            $this->ajaxJson('70002');
        }
        $memberInfo = $this->memberInfo;
        $prefix = C('DB_PREFIX');
        /* 统计行业圈未读消息总数 */
        $where_circle['r.is_delete'] = array('eq', 0);
        $where_circle['r.is_read'] = array('eq', 0);
        $where_circle['r.status'] = array('eq', 1);
        $where_circle['l.status'] = array('eq', 1);
        $where_circle['l.uid'] = array('eq', $memberInfo['uid']);
        $list['circle_count'] = M('Circle')
            ->alias('l')
            ->join($prefix . 'circle_comment r ON l.id = r.pid', 'LEFT')
            ->where($where_circle)
            ->count('r.id');
        /* 统计资讯未读消息总数 */
        $where_news['r.is_delete'] = array('eq', 0);
        $where_news['r.is_read'] = array('eq', 0);
        $where_news['l.uid'] = array('eq', $memberInfo['uid']);
        $list['news_count'] = M('NewsComment')
            ->alias('l')
            ->join($prefix . 'news_reply r ON l.id = r.pid', 'LEFT')
            ->where($where_news)
            ->count('r.id');
        /* 统计站内信未读消息总数 */
        $where_site['to_type'] = array('eq', 1);
        $where_site['to_user'] = array('eq', $memberInfo['uid']);
        $where_site['_logic'] = 'OR';
        $siteMessageList = M('UserMessage')->field('id,is_read,is_delete')->where($where_site)->select();
        if ($siteMessageList) {
            $list['site_count'] = 0;
            foreach ($siteMessageList as $key => $value) {
                $value['is_read'] = explode(',', $value['is_read']);
                if (!in_array($memberInfo['uid'], $value['is_read'])) {
                    $list['site_count']++;
                }
            }
        } else {
            $list['site_count'] = 0;
        }
        $list['count'] = $list['circle_count'] + $list['news_count'] + $list['site_count'];
        $list['site_count'] = strval($list['site_count']);
        $list['circle_count'] = strval($list['circle_count']);
        $list['news_count'] = strval($list['news_count']);
        $list['count'] = strval($list['count']);
        if ($type == 1) {
            return $list['count'];
        } else {
            $this->returnJson($list);
        }
    }

    /**
     * 行业圈消息
     */
    public function circleMessage()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $memberInfo = $this->memberInfo;

            /* 查询行业圈消息 */
            $prefix = C('DB_PREFIX');
            $model = M('Circle');
            $where['r.is_delete'] = array('eq', 0);
            $where['r.status'] = array('eq', 1);
            $where['l.status'] = array('eq', 1);
            $where['m.status'] = array('eq', 1);
            $where['l.uid'] = array('eq', $memberInfo['uid']);
            $commentList = $model
                ->alias('l')
                ->join($prefix . 'circle_comment r ON l.id = r.pid', 'LEFT')
                ->join($prefix . 'member m ON r.uid = m.uid', 'LEFT')
                ->field('l.id circle_id,r.id comment_id,m.head_pic,m.name,r.content,r.ctime,r.is_read')
                ->where($where)
                ->order('r.id DESC')
                ->limit($page, 10)
                ->select();
            !$commentList && $this->returnJson(array('circle_message' => array()));
            /* 数据处理 */
            $commentList = $this->getAbsolutePath($commentList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            foreach ($commentList as $key => $value) {
                $commentList[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                $commentList[$key]['is_read'] = $value['is_read'] ? '40016' : '40015';
                $commentIdArr[] = $value['comment_id'];
            }
            $list['circle_message'] = $commentList;

            /* 将消息置为已读 */
            $map['is_read'] = array('eq', 0);
            $map['id'] = array('in', $commentIdArr);
            M('CircleComment')->where($map)->setField('is_read', 1);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 行业圈消息删除
     */
    public function circleMessageDel()
    {
        if (IS_POST) {
            $id = I('post.id');
            $id = explode(',', $id);
            if (!is_array($id) || !count($id)) {
                $this->ajaxJson('70000', '参数错误');
            }

            $where['id'] = array('in', $id);
            $delete = M('CircleComment')->where($where)->setField('is_delete', 1);
            if (!$delete) {
                $this->ajaxJson('70000', '删除失败');
            }
            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯消息
     */
    public function newsMessage()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $memberInfo = $this->memberInfo;

            /* 查询行业圈消息 */
            $prefix = C('DB_PREFIX');
            $where['r.is_delete'] = array('eq', 0);
            $where['m.status'] = array('eq', 1);
            $where['l.uid'] = array('eq', $memberInfo['uid']);
            $replyList = M('NewsComment')
                ->alias('l')
                ->join($prefix . 'news_reply r ON l.id = r.pid', 'LEFT')
                ->join($prefix . 'member m ON r.uid = m.uid', 'LEFT')
                ->field('l.id comment_id,r.id reply_id,m.head_pic,m.name,r.content,r.ctime,r.is_read')
                ->where($where)
                ->order('r.id DESC')
                ->limit($page, 10)
                ->select();
            !$replyList && $this->returnJson(array('news_message' => array()));
            /* 数据处理 */
            $replyList = $this->getAbsolutePath($replyList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            foreach ($replyList as $key => $value) {
                $replyList[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                $replyList[$key]['is_read'] = $value['is_read'] ? '40016' : '40015';
                $replyIdArr[] = $value['reply_id'];
            }
            $list['news_message'] = $replyList;

            /* 将消息置为已读 */
            $map['is_read'] = array('eq', 0);
            $map['id'] = array('in', $replyIdArr);
            M('NewsReply')->where($map)->setField('is_read', 1);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 资讯消息删除
     */
    public function newsMessageDel()
    {
        if (IS_POST) {
            $id = I('post.id');
            $id = explode(',', $id);
            if (!is_array($id) || !count($id)) {
                $this->ajaxJson('70000', '参数错误');
            }

            $where['id'] = array('in', $id);
            $delete = M('NewsReply')->where($where)->setField('is_delete', 1);
            if (!$delete) {
                $this->ajaxJson('70000', '删除失败');
            }
            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 站内信消息
     */
    public function siteMessage()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $memberInfo = $this->memberInfo;
            $model = M('UserMessage');

            /* 获取已删除的站内信id数组 */
            $isDeleteIdList = $model->where('FIND_IN_SET(' . $memberInfo['uid'] . ',is_delete)')->getField('id', true);

            /* 所有的站内信 */
            $where = '( `to_user` = ' . $memberInfo['uid'] . ' OR `to_type` = 1 )';
            if ($isDeleteIdList) {
                $isDeleteIdList = implode(',', $isDeleteIdList);
                $where .= ' AND ( `id` NOT IN(' . $isDeleteIdList . ') )';
            }
            $siteMessageList = $model->field('id message_id,title,message,addtime,is_read')->where($where)->order('id DESC')->limit($page, 10)->select();
            !$siteMessageList && $this->returnJson(array('site_message' => array()));
            foreach ($siteMessageList as $key => $value) {
                $value['is_read'] = explode(',', $value['is_read']);
                if (in_array($memberInfo['uid'], $value['is_read'])) {
                    $siteMessageList[$key]['is_read'] = '40016';
                } else {
                    $siteMessageList[$key]['is_read'] = '40015';
                    /* 获取未读消息id */
                    $siteMessageIdArr[] = $value['message_id'];
                }
                $siteMessageList[$key]['addtime'] = $this->dateTimeDeal($siteMessageList[$key]['addtime']);
            }

            /* 将消息置为已读 */
            $siteMessageIdArr = implode(',', $siteMessageIdArr);
            if ($siteMessageIdArr) {
                $sql = 'UPDATE `' . C('DB_PREFIX') . "user_message` SET `is_read` = CONCAT(is_read,'," . $memberInfo['uid'] . "') WHERE `id` IN (" . $siteMessageIdArr . ')';
                M()->execute($sql);
            }

            $list['site_message'] = $siteMessageList;
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 站内信消息删除
     */
    public function siteMessageDel()
    {
        if (IS_POST) {
            $id = I('request.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $memberInfo = $this->memberInfo;

            $sql = 'UPDATE `' . C('DB_PREFIX') . "user_message` SET `is_delete` = CONCAT(is_delete,'," . $memberInfo['uid'] . "') WHERE `id` IN (" . $id . ')';
            $delete = M()->execute($sql);
            if (!$delete) {
                $this->ajaxJson('70000', '删除失败');
            }
            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 我的优惠券列表
     */
    public function couponList()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            /* 导航标识，1代表指定优惠券，2代表店铺优惠券 */
            $type = I('get.type', 1, 'intval');
            $memberInfo = $this->memberInfo;

            /* 即将过期的优惠券数量 */
            $list['will_expire'] = 0;

            /* 查询我的优惠券 */
            if ($type == 1) {
                $where['r.company_id'] = array('eq', 0);
            } else {
                $where['r.company_id'] = array('gt', 0);
            }
            $where['l.status'] = array('eq', 1);
            $where['_string'] = '(l.start_time = 0 AND l.end_time = 0) OR (l.start_time = 0 AND l.end_time > ' . time() . ') OR (l.start_time < ' . time() . ' AND l.end_time = 0) OR (l.start_time < ' . time() . ' AND l.end_time > ' . time() . ')';
            $where['l.uid'] = array('eq', $memberInfo['uid']);
            $list['coupon_list'] = M('CouponMember')
                ->alias('l')
                ->field('l.id coupon_id,CONCAT("￥",r.money) money,r.title,r.condition,l.start_time,l.end_time,r.company_id')
                ->join(C('DB_PREFIX') . 'coupon r ON l.coupon_id = r.id', 'LEFT')
                ->where($where)
                ->limit($page, 10)
                ->order('l.id DESC')
                ->select();
            !$list['coupon_list'] && $this->returnJson(array('coupon_list' => array()));
            /* 数据处理 */
            foreach ($list['coupon_list'] as $key => $value) {
                /* 使用限制 */
                if ($value['condition'] == 0) {
                    $list['coupon_list'][$key]['condition'] = '无消费条件限制';
                } else {
                    $list['coupon_list'][$key]['condition'] = '满' . $value['condition'] . '可用';
                }
                /* 优惠券使用期限 */
                if ($value['start_time'] > 0 && $value['end_time'] > 0) {
                    if ($value['start_time'] == $value['end_time']) {
                        $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '当日可用';
                    } else {
                        $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['start_time']) . ' 至 ' . date('Y-m-d', $value['end_time']);
                    }
                } elseif ($value['start_time'] > 0 && $value['end_time'] == 0) {
                    $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['start_time']) . '之后可用';
                } elseif ($value['start_time'] == 0 && $value['end_time'] > 0) {
                    $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '之前可用';
                } else {
                    $list['coupon_list'][$key]['effect_time'] = '无使用时间限制';
                }
                /* 判断优惠券是否即将过期 使用期限剩余不到1天的就算是即将过期的 */
                if ($value['end_time'] > 0 && $value['end_time'] - time() <= 86400) {
                    $list['will_expire']++;
                }
                /* 销毁客户端不需要的变量 */
                unset($list['coupon_list'][$key]['start_time'], $list['coupon_list'][$key]['end_time']);
            }

            /* 将即将过期的优惠券数量转为字符串返回客户端 */
            $list['will_expire'] = strval($list['will_expire']);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 我的优惠券历史记录
     */
    public function couponHistroy()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            /* 导航标识，1代表指定优惠券，2代表店铺优惠券 */
            $type = I('get.type', 1, 'intval');
            $memberInfo = $this->memberInfo;

            /* 即将过期的优惠券数量 */
            $list['will_expire'] = '0';

            /* 查询我的优惠券 */
            if ($type == 1) {
                $where['r.company_id'] = array('eq', 0);
            } else {
                $where['r.company_id'] = array('gt', 0);
            }
            $where['_string'] = 'l.uid = ' . $memberInfo['uid'] . ' AND ( ( l.status = 1 AND l.end_time != 0 AND l.end_time < ' . time() . ' ) OR l.status != 1 )';
            // $where['l.status']   = array('eq', 1);
            // $where['_string']    = 'l.end_time != 0 AND l.end_time < '.time();
            // $where['l.uid']      = array('eq', $memberInfo['uid']);
            $list['coupon_list'] = M('CouponMember')
                ->alias('l')
                ->field('l.id coupon_id,CONCAT("￥",r.money) money,r.title,r.condition,l.start_time,l.end_time,l.status')
                ->join(C('DB_PREFIX') . 'coupon r ON l.coupon_id = r.id', 'LEFT')
                ->where($where)
                ->limit($page, 10)
                ->order('l.id DESC')
                ->select();
            !$list['coupon_list'] && $this->returnJson(array('coupon_list' => array()));
            /* 数据处理 */
            foreach ($list['coupon_list'] as $key => $value) {
                /* 使用限制 */
                if ($value['condition'] == 0) {
                    $list['coupon_list'][$key]['condition'] = '无消费条件限制';
                } else {
                    $list['coupon_list'][$key]['condition'] = '满' . $value['condition'] . '可用';
                }
                /* 优惠券使用期限 */
                if ($value['start_time'] > 0 && $value['end_time'] > 0) {
                    if ($value['start_time'] == $value['end_time']) {
                        $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '当日可用';
                    } else {
                        $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['start_time']) . ' 至 ' . date('Y-m-d', $value['end_time']);
                    }
                } elseif ($value['start_time'] > 0 && $value['end_time'] == 0) {
                    $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['start_time']) . '之后可用';
                } elseif ($value['start_time'] == 0 && $value['end_time'] > 0) {
                    $list['coupon_list'][$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '之前可用';
                } else {
                    $list['coupon_list'][$key]['effect_time'] = '无使用时间限制';
                }
                /* 优惠券名称 追加 优惠券状态 */
                switch ($value['status']) {
                    case '1':
                        $list['coupon_list'][$key]['title'] .= '【已过期】';
                        break;
                    case '2':
                        $list['coupon_list'][$key]['title'] .= '【锁定中】';
                        break;
                    case '-1':
                        $list['coupon_list'][$key]['title'] .= '【已使用】';
                        break;
                    default:
                        $list['coupon_list'][$key]['title'] .= '【已删除】';
                        break;
                }
                /* 销毁客户端不需要的变量 */
                unset($list['coupon_list'][$key]['start_time'], $list['coupon_list'][$key]['end_time'], $list['coupon_list'][$key]['status']);
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 我的佣金
     */
    public function commission()
    {
        if (IS_GET) {
            $memberInfo = $this->memberInfo;

            $list = $this->calcCommission($memberInfo['uid']);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 佣金收支明细
     */
    public function commissionDetail()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            /* 用户信息 */
            $memberInfo = $this->memberInfo;
            /* 提现方式 */
            $withdrawalType = array(
                '1' => '微信',
                '2' => '支付宝',
                '3' => '账户余额',
                '4' => '银行卡'
            );
            /* 查询佣金 */
            $commissionList = M('Commission')
                ->alias('l')
                ->field('l.id commission_id,l.order_id,l.commission,l.ctime,l.status,l.withdrawal_type,r.name')
                ->join(C('DB_PREFIX') . 'member r ON l.source_uid = r.uid', 'LEFT')
                ->where(array('l.uid' => $memberInfo['uid']))
                ->order('l.id DESC')
                ->limit($page, 10)
                ->select();
            foreach ($commissionList as $key => $value) {
                /* 佣金标题 */
                if ($value['status'] == 0 || $value['status'] == 1) {
                    $commissionList[$key]['title'] = '订单佣金';
                    $commissionList[$key]['commission'] = '+' . $value['commission'];
                    $commissionList[$key]['name'] = '来源：' . $value['name'];
                } elseif ($value['status'] == 2 || $value['status'] == 3) {
                    $commissionList[$key]['title'] = '提现中';
                    $commissionList[$key]['commission'] = '-' . $value['commission'];
                    $commissionList[$key]['name'] = '提现到：' . $withdrawalType[$value['withdrawal_type']];
                } elseif ($value['status'] == 4) {
                    $commissionList[$key]['title'] = '提现被拒';
                    $commissionList[$key]['name'] = '提现到：' . $withdrawalType[$value['withdrawal_type']];
                } elseif ($value['status'] == 5) {
                    $commissionList[$key]['title'] = '提现成功';
                    $commissionList[$key]['commission'] = '-' . $value['commission'];
                    $commissionList[$key]['name'] = '提现到：' . $withdrawalType[$value['withdrawal_type']];
                } elseif ($value['status'] == -1) {
                    $commissionList[$key]['title'] = '流失佣金';
                    $commissionList[$key]['commission'] = '-' . $value['commission'];
                    $commissionList[$key]['name'] = '来源：' . $value['name'];
                } else {
                    $commissionList[$key]['title'] = '';
                }
                /* 时间处理 */
                $commissionList[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                /* 销毁客户端不需要的数据 */
                unset($commissionList[$key]['status'], $commissionList[$key]['withdrawal_type']);
            }

            $list['commissionList'] = $commissionList;
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 佣金提现
     */
    public function withdrawals()
    {
        $id = I('request.id', 0, 'intval');
        /* 用户信息 */
        $memberInfo = $this->memberInfo;
        /* 佣金信息 */
        $commissionInfo = $this->calcCommission($memberInfo['uid']);

        /* 佣金提现提交数据 */
        if (IS_POST) {
            $model = D('Commission');
            $result = $model->update($commissionInfo['commission_info']['commission_rest']);
            if ($result) {
                $this->ajaxJson('40000', '提现申请成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 佣金提现请求数据 */
        if ($id) {
            $where['uid'] = array('eq', $memberInfo['uid']);
            $where['id'] = array('eq', $id);
            $where['status'] = array('gt', 0);
            $list['account_info'] = M('WithdrawalsAccount')->field('id account_id,account,img')->where($where)->find();
            !$list['account_info'] && $this->ajaxJson('70000', '账户不存在');
        } else {
            $where['uid'] = array('eq', $memberInfo['uid']);
            $where['status'] = array('eq', 2);
            $list['account_info'] = M('WithdrawalsAccount')->field('id account_id,account,img')->where($where)->find();
            if (!$list['account_info']) {
                $list['account_info']['account_id'] = '';
                $list['account_info']['account'] = '转入余额';
                $list['account_info']['img'] = C('HTTP_APPS_IMG') . 'balancepay_icon.png';
            } else {
                $list['account_info']['img'] = $this->getAbsolutePath($list['account_info']['img']);
            }
        }

        /* 获取佣金信息 */
        $list['account_info']['withdrawals_money'] = $commissionInfo['commission_info']['commission_rest'];

        /* 获取云通讯配置参数 */
        $smsConfig = C('SMS_CONFIG');
        /* 短信功能标识 */
        // $list['detail']['sms_flag'] = strval($smsConfig['isopen']);
        /* 用户手机号 */
        $list['mobile'] = $smsConfig['isopen'] ? $memberInfo['mphone'] : '';

        $this->returnJson($list);
    }

    /**
     * 提现记录
     */
    public function withdrawalsLosgs()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            /* 用户信息 */
            $memberInfo = $this->memberInfo;

            /* 查询佣金 */
            $where['uid'] = array('eq', $memberInfo['uid']);
            $commissionList = M('Commission')->field('commission,status')->where($where)->select();
            if (!$commissionList) {
                $list['commission_info'] = array(
                    array('money' => '￥0.00', 'text' => '总收入'),
                    array('money' => '￥0.00', 'text' => '已提现')
                );
                $list['withdrawals_list'] = array();
                $this->returnJson($list);
            }
            /* 佣金总额 */
            $commission_all = 0.00;
            /* 已提现 */
            $commission_withdrawals = 0.00;
            /* 佣金状态：0待提现（等待买家确认收货），1可提现，2申请提现中，3待打款（申请通过），4申请被拒，5已打款，-1流失佣金 */
            foreach ($commissionList as $v) {
                $commission_all += $v['status'] <= 1 ? $v['commission'] : 0;
                $commission_withdrawals += $v['status'] == 5 ? $v['commission'] : 0;
            }
            /* 将金额转换为字符串并保留2位小数 */
            $list['commission_info'] = array(
                array('money' => '￥' . sprintf('%.2f', $commission_all), 'text' => '总收入'),
                array('money' => '￥' . sprintf('%.2f', $commission_withdrawals), 'text' => '已提现')
            );

            /* 状态 2申请提现中，3待打款（申请通过），4申请被拒，5已打款 */
            $statusList = array(
                '2' => '申请提现',
                '3' => '待打款',
                '4' => '申请被拒',
                '5' => '已打款'
            );
            /* 查询提现记录 */
            $where['status'] = array('in', array(2, 3, 4, 5));
            $commissionList = M('Commission')->field('commission,ctime,status,remark')->where($where)->order('id DESC')->limit($page, 10)->select();
            foreach ($commissionList as $key => $value) {
                /* 申请被拒才显示理由 */
                $value['status'] != 4 && $commissionList[$key]['remark'] = '';
                /* 时间处理 */
                $commissionList[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                /* 状态处理 */
                $commissionList[$key]['status'] = $statusList[$value['status']];
            }
            $list['withdrawals_list'] = $commissionList;

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 提现账户列表
     */
    public function withdrawalsAccount()
    {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $memberInfo = $this->memberInfo;

            /* 查询提现账户列表 */
            $where['uid']    = array('eq', $memberInfo['uid']);
            $where['status'] = array('gt', 0);
            $accountList = M('WithdrawalsAccount')->field('id account_id,account,truename,type,bank_card,img')->where($where)->order('id DESC')->limit($page,10)->select();
            /* 查不到数据或数据条数少于10条则将转入余额添加进去 */
            if (!$accountList || count($accountList) < 10) {
                /* 转入余额 */
                $accountList[] = array(
                    'account_id' => '',
                    'account'    => $memberInfo['mphone'],
                    'truename'   => $memberInfo['username'],
                    'type'       => '3',
                    'bank_card'  => '',
                    'img'        => C('HTTP_APPS_IMG') . 'balancepay_icon.png'
                );
            }

            $list['account_list'] = $accountList;

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 添加提现账户
     */
    public function withdrawalsAccountAdd()
    {
        $id = I('request.id', 0, 'intval');
        $model = D('WithdrawalsAccount');

        /* 用户信息 */
        $memberInfo = $this->memberInfo;

        /* 提交数据 */
        if (IS_POST) {
            $_POST['mobile'] = $memberInfo['mphone'];
            $opt = $id > 0 ? '修改' : '添加';
            $result = $model->update();
            if ($result) {
                $this->ajaxJson('40000', $opt . '成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        if (IS_GET) {
            /* 修改、发布时返回详情数据 */
            if ($id) {
                $list['detail'] = $model->getOneInfo(array('id' => $id), 'id account_id,account,truename,bank_card,bank_address,img');
            } else {
                $list['detail']['account_id'] = '';
                $list['detail']['account'] = '';
                $list['detail']['truename'] = '';
                $list['detail']['bank_card'] = '';
                $list['detail']['bank_address'] = '';
                $list['detail']['img'] = '';
            }
            /* 所有可选择银行数据 */
            $list['detail']['bank_list'] = array('中国银行', '工商银行', '农业银行', '交通银行', '招商银行', '建设银行', '邮政储蓄', '浦发银行', '广发银行', '民生银行', '平安银行', '光大银行', '兴业银行', '中信银行', '上海银行', '宁波银行', '江苏银行', '浙江农信', '浙商银行');

            /* 获取云通讯配置参数 */
            $smsConfig = C('SMS_CONFIG');
            /* 短信功能标识 */
            $list['detail']['sms_flag'] = strval($smsConfig['isopen']);
            /* 用户手机号 */
            $list['detail']['mobile'] = $smsConfig['isopen'] ? $memberInfo['mphone'] : '';

            $this->returnJson($list);
        }
        $this->ajaxJson('70000', '非法操作');
    }

    /**
     * 提现账户删除（支持批量删除）
     */
    public function withdrawalsAccountDel()
    {
        if (IS_POST) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);
            /* 用户信息 */
            $memberInfo = $this->memberInfo;
            /* 软删除 */
            $where['uid'] = array('eq', $memberInfo['uid']);
            $where['id'] = array('in', $id);
            $save = M('WithdrawalsAccount')->where($where)->setField('status', 0);
            if ($save) {
                $this->ajaxJson('40000', '删除成功');
            }
            $this->ajaxJson('70000', '删除失败');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 我的人脉
     */
    public function connection()
    {
        if (IS_GET) {
            /* $type 1代表一级人脉 2代表二级人脉 */
            $type = I('get.type', 0, 'intval');
            !$type && $this->ajaxJson('70000', '参数错误');
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;

            /* 用户信息 */
            $memberInfo = $this->memberInfo;

            /* 我的一级人脉 */
            $oneList = M('Member')->where(array('pid' => $memberInfo['uid']))->getField('uid,name,head_pic,"已贡献：0.00元" contribute,"0" sub_title');
            /* 一级人脉为空则二级人脉必定为空，故直接返回空数据 */
            if (!$oneList) {
                $list['member_list'] = array();
                $list['contribute_total'] = '￥0.00';
                $this->returnJson($list);
            }
            $oneList = $this->getAbsolutePath($oneList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            /* 一级人脉uid数组 */
            foreach ($oneList as $v) {
                $oneUids[] = $v['uid'];
            }

            /* 我的一级人脉贡献数据 */
            $where_one_commission['l.pid'] = array('eq', $memberInfo['uid']);
            $where_one_commission['r.uid'] = array('eq', $memberInfo['uid']);
            $oneContributeList = M('Member')
                ->alias('l')
                ->join(C('DB_PREFIX') . 'commission r ON l.uid = r.source_uid', 'LEFT')
                ->where($where_one_commission)
                ->group('l.uid')
                ->getField('l.uid,GROUP_CONCAT(r.commission) contribute', true);
            /* 数据处理 */
            $oneTotalContribute = 0.00;
            if ($oneContributeList) {
                foreach ($oneContributeList as $ok => $ov) {
                    /* 统计个人贡献 */
                    $ov = explode(',', $ov);
                    $ov = array_sum($ov);
                    /* 一级人脉总贡献 */
                    $oneTotalContribute += $ov;
                    /* 个人贡献拼接 */
                    $oneList[$ok]['contribute'] = '已贡献' . sprintf('%.2f', $ov) . '元';
                }
            }
            /* 一级人脉总贡献拼接 */
            $oneTotalContribute = '￥' . sprintf('%.2f', $oneTotalContribute);

            /* 我的二级人脉 */
            $where_two['pid'] = array('in', $oneUids);
            $twoList = M('Member')->where($where_two)->getField('uid,name,head_pic,"已贡献：0.00元" contribute,pid sub_title');
            if (!$twoList && $type == 2) {
                $list['member_list'] = array();
                $this->returnJson($list);
            }
            $twoList = $this->getAbsolutePath($twoList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');

            /* 我的二级人脉贡献数据 */
            $where_two_commission['l.pid'] = array('in', $oneUids);
            $where_two_commission['r.uid'] = array('eq', $memberInfo['uid']);
            $twoContributeList = M('Member')
                ->alias('l')
                ->join(C('DB_PREFIX') . 'commission r ON l.uid = r.source_uid', 'LEFT')
                ->where($where_two_commission)
                ->group('l.uid')
                ->getField('l.uid,GROUP_CONCAT(r.commission) contribute', true);
            /* 数据处理 */
            $twoTotalContribute = 0.00;
            if ($twoContributeList) {
                foreach ($twoContributeList as $tk => $tv) {
                    /* 统计个人贡献 */
                    $tv = explode(',', $tv);
                    $tv = array_sum($tv);
                    /* 一级人脉总贡献 */
                    $twoTotalContribute += $tv;
                    /* 个人贡献拼接 */
                    $twoList[$tk]['contribute'] = '已贡献' . sprintf('%.2f', $tv) . '元';
                }
            }
            /* 二级人脉总贡献拼接 */
            $twoTotalContribute = '￥' . sprintf('%.2f', $twoTotalContribute);
            foreach ($twoList as $tlk => $tlv) {
                /* 父级名称 */
                $twoList[$tlk]['sub_title'] = '(来自' . $oneList[$tlv['sub_title']]['name'] . ')';
                $oneList[$tlv['sub_title']]['sub_title']++;
            }

            /* 一级人脉下级数据处理 */
            foreach ($oneList as $olk => $olv) {
                $oneList[$olk]['sub_title'] = $olv['sub_title'] > 0 ? '(' . $olv['sub_title'] . '位二级人脉)' : '';
            }

            /* $type 1代表一级人脉 2代表二级人脉 */
            if ($type == 1) {
                $list['member_list'] = array_values($oneList);
                $list['contribute_total'] = $oneTotalContribute;
            } else {
                $list['member_list'] = array_values($twoList);
                $list['contribute_total'] = $twoTotalContribute;
            }

            /* 虚拟分页 */
            foreach ($list['member_list'] as $mlk => $mlv) {
                if ($mlk < $page || $mlk > $page + 9) {
                    unset($list['member_list'][$mlk]);
                }
            }
            $list['member_list'] = array_values($list['member_list']);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 计算各个状态的佣金
     * @param  [Integer] $uid [用户id]
     * 佣金状态：0待提现（等待买家确认收货），1可提现，2申请提现中，3待打款（申请通过），4申请被拒，5已打款，-1流失佣金（订单退款完成）
     */
    private function calcCommission($uid)
    {
        /* 查询佣金 */
        $commissionList = M('Commission')->field('id commission_id,order_id,commission,ctime,status,remark')->where(array('uid' => $uid))->select();
        if (!$commissionList) {
            $list['commission_info'] = array(
                'commission_all' => '0.00',
                'commission_unconfirm' => '0.00',
                'commission_withdraw' => '0.00',
                'commission_rest' => '0.00',
                'commission_transfer' => '0.00'
            );
        }
        /* 佣金总额 */
        $commission_all = 0.00;
        /* 待提现 */
        $commission_unconfirm = 0.00;
        /* 已提现 */
        $commission_withdrawals = 0.00;
        /* 可提现 */
        $commission_rest = 0.00;
        /* 待打款（进入提现流程，未成功的金额） */
        $commission_transfer = 0.00;
        /* 已流失（订单退款） */
        $commission_loss = 0.00;
        /* 佣金状态：0待提现（等待买家确认收货），1可提现，2申请提现中，3待打款（申请通过），4申请被拒，5已打款，-1流失佣金 */
        foreach ($commissionList as $v) {
            $commission_all         += $v['status'] <= 1 ? $v['commission'] : 0;
            $commission_unconfirm   += $v['status'] == 0 ? $v['commission'] : 0;
            $commission_withdrawals += $v['status'] == 5 ? $v['commission'] : 0;
            $commission_transfer    += ($v['status'] == 2 || $v['status'] == 3) ? $v['commission'] : 0;
            $commission_loss        += $v['status'] == -1 ? $v['commission'] : 0;
        }
        /* 计算可提现佣金 */
        $commission_rest = $commission_all - $commission_unconfirm - $commission_transfer - $commission_withdrawals - $commission_loss;
        $list['commission_info'] = array(
            'commission_all'       => $commission_all,
            'commission_unconfirm' => $commission_unconfirm,
            'commission_withdraw'  => $commission_withdrawals,
            'commission_rest'      => $commission_rest,
            'commission_transfer'  => $commission_transfer,
            'commission_loss'      => $commission_loss
        );
        /* 将金额转换为字符串并保留2位小数 */
        foreach ($list['commission_info'] as $key => $value) {
            $list['commission_info'][$key] = sprintf('%.2f', $value);
        }

        return $list;
    }

    /**
     * 填写邀请码
     * @author 406764368@qq.com
     * @version 2016年12月6日 08:57:22
     */
    public function fillInviteCode() {
        if ( IS_POST ) {
            /* 用户信息 */
            $memberInfo = $this->memberInfo;
            /* 验证是否有上级 */
            $memberInfo['pid'] && $this->ajaxJson('70000', '您已绑定过邀请码');
            /* 验证参数 */
            $invite_code = I('post.invite_code');
            empty($invite_code) && $this->ajaxJson('70000', '请填写邀请码');
            /* 查询邀请码对应用户id */
            $pid = M('Member')->where(array('invite_code'=>$invite_code))->getField('uid');
            !$pid && $this->ajaxJson('70000', '邀请码不存在');
            $pid == $memberInfo['uid'] && $this->ajaxJson('70000', '不能绑定自己的邀请码');
            /* 锁定上下级关系 */
            $save = M('Member')->where(array('uid'=>$memberInfo['uid']))->setField('pid', $pid);
            $save && $this->ajaxJson('40000', '操作成功');
            $this->ajaxJson('70000', '操作失败');
        }
        $this->ajaxJson('70002');
    }
}
?>