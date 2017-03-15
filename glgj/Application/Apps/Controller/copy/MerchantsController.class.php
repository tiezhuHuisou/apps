<?php
namespace Apps\Controller;
use Think\Controller;

class MerchantsController extends ApiController {
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
     * 商家管理主页
     */
    public function index() {
        if ( IS_GET ) {
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            $list['company_info']['company_id']     = $memberInfo['company_id'];
            $list['company_info']['logo']           = $memberInfo['logo'];
            $list['company_info']['company_name']   = $memberInfo['company_name'];
            $list['company_info']['bgimg']          = $memberInfo['bgimg'];

            /* 九宫格 */
            $appPath = C('HTTP_APPS_IMG');
            if ( C('FLASHFLAG') == 1 ) {
                $list['section'] = array(
                    array( 'icon' => $appPath . 'merchants_product.png', 'title' => '商品管理' , 'href' => 'merchants_product'),
                    array( 'icon' => $appPath . 'merchants_order.png', 'title' => '订单管理' , 'href' => 'merchants_order'),
                    array( 'icon' => $appPath . 'merchants_assets.png', 'title' => '资产管理' , 'href' => 'merchants_assets'),
                    array( 'icon' => $appPath . 'merchants_group.png', 'title' => '分组设置' , 'href' => 'merchants_group'),
                    array( 'icon' => $appPath . 'merchants_freight.png', 'title' => '运费模版' , 'href' => 'merchants_freight'),
                    array( 'icon' => $appPath . 'merchants_show.png', 'title' => '查看店铺' , 'href' => 'merchants_show'),
                    array( 'icon' => $appPath . 'merchants_need.png', 'title' => '求购管理' , 'href' => 'merchants_need'),
                    array( 'icon' => $appPath . 'merchants_marketing.png', 'title' => '营销活动' , 'href' => 'merchants_marketing'),
                    array( 'icon' => $appPath . 'merchants_setting.png', 'title' => '通用设置', 'href' => 'merchants_setting' )
                );
            } else {
                $list['section'] = array(
                    array( 'icon' => $appPath . 'merchants_product.png', 'title' => '商品管理' , 'href' => 'merchants_product'),
                    array( 'icon' => $appPath . 'merchants_group.png', 'title' => '分组设置' , 'href' => 'merchants_group'),
                    array( 'icon' => $appPath . 'merchants_show.png', 'title' => '查看店铺' , 'href' => 'merchants_show'),
                    array( 'icon' => $appPath . 'merchants_need.png', 'title' => '求购管理' , 'href' => 'merchants_need'),
                    array( 'icon' => $appPath . 'merchants_setting.png', 'title' => '通用设置', 'href' => 'merchants_setting' )
                );
            }
            
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家资产管理
     */
    public function assets() {
        if ( IS_GET ) {
            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询销售利润明细 */
            $withdrawalsList = M('Withdrawals')->field('withdrawals,status')->where(array('cid'=>$memberInfo['company_id']))->select();
            /* 可提现 = -2可提现 - 0申请提现 - 1打款中 -2已打款 - 4佣金发放 */
            $withdrawals = 0.00;
            foreach ($withdrawalsList as $value) {
                if ( in_array($value['status'], array(-2)) ) {
                    $withdrawals += $value['withdrawals'];
                } elseif ( in_array($value['status'], array(0,1,2,4)) ) {
                    $withdrawals -= $value['withdrawals'];
                }
            }
            /* 转为字符串返回客户端 */
            $withdrawals = sprintf('%.2f', $withdrawals);

            $list['withdrawals'] = $withdrawals;
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家提现记录
     * 状态：
     * -4订单退款中，
     * -3待确认(待买家确认收货),
     * -2可提现，
     * -1删除,
     * 0申请提现，
     * 1通过申请，正在打款中，
     * 2已打款，
     * 3申请被拒，
     * 4佣金发放，
     * 5订单退款
     */
    public function withdrawalsLogs() {
        if ( IS_GET ) {
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询销售利润明细 */
            $withdrawalsList = M('Withdrawals')->field('withdrawals,status')->where(array('cid'=>$memberInfo['company_id']))->select();
            if ( !$withdrawalsList ) {
                $list['commission_info'] = array(
                    array('money' => '￥0.00', 'text' => '总收入'),
                    array('money' => '￥0.00', 'text' => '已提现')
                );
                $list['withdrawals_list'] = array();
                $this->returnJson($list);
            }

            /* 可提现 = -2可提现 - 0申请提现 - 1打款中 -2已打款 - 4佣金发放 */
            $withdrawals_all = 0.00;
            /* 已提现 */
            $withdrawals     = 0.00;
            foreach ($withdrawalsList as $value) {
                if ( in_array($value['status'], array(-2)) ) {
                    $withdrawals_all += $value['withdrawals'];
                } elseif ( in_array($value['status'], array(0,1,2,4)) ) {
                    $withdrawals_all -= $value['withdrawals'];
                    $withdrawals     += $value['withdrawals'];
                }
            }
            /* 将金额转换为字符串并保留2位小数 */
            $list['commission_info'] = array(
                array('money' => '￥' . sprintf('%.2f', $withdrawals_all), 'text' => '总收入'),
                array('money' => '￥' . sprintf('%.2f', $withdrawals), 'text' => '已提现')
            );

            /* 查询销售利润明细 */
            $where['cid']    = array('eq', $memberInfo['company_id']);
            $where['status'] = array('in', '0,1,2,3');
            $list['withdrawals_list'] = M('Withdrawals')->field('withdrawals commission,etime ctime,status,"" remark')->where($where)->limit($page,10)->select();
            if ( $list['withdrawals_list'] ) {
                /* 提现状态 */
                $statusList = array('申请提现中', '打款中', '已打款', '申请被拒', '');
                foreach ($list['withdrawals_list'] as $key => $value) {
                    /* 提现状态 */
                    $list['withdrawals_list'][$key]['status'] = $statusList[$value['status']];
                    /* 时间处理 */
                    $list['withdrawals_list'][$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                }
            } else {
                $list['withdrawals_list'] = array();
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家提现
     */
    public function withdrawals() {
        $id = I('request.id', 0, 'intval');
        /* 商家信息 */
        $memberInfo = $this->memberInfo;
        /* 查询销售利润明细 */
        $withdrawalsList = M('Withdrawals')->field('withdrawals,status')->where(array('cid'=>$memberInfo['company_id']))->select();
        /* 可提现 = -2可提现 - 0申请提现 - 1打款中 -2已打款 - 4佣金发放 */
        $withdrawals_all = 0.00;
        foreach ($withdrawalsList as $value) {
            if ( in_array($value['status'], array(-2)) ) {
                $withdrawals_all += $value['withdrawals'];
            } elseif ( in_array($value['status'], array(0,1,2,4)) ) {
                $withdrawals_all -= $value['withdrawals'];
            }
        }

        /* 商家提现提交数据 */
        if (IS_POST) {
            $model = D('Withdrawals');
            $result = $model->update($withdrawals_all);
            if ($result) {
                $this->ajaxJson('40000', '提现申请成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 商家提现请求数据 */
        if ($id) {
            $where['uid']    = array('eq', $memberInfo['uid']);
            $where['id']     = array('eq', $id);
            $where['status'] = array('gt', 0);
            $list['account_info'] = M('WithdrawalsAccount')->field('id account_id,account,img')->where($where)->find();
            !$list['account_info'] && $this->ajaxJson('70000', '账户不存在');
        } else {
            $where['uid']    = array('eq', $memberInfo['uid']);
            $where['status'] = array('eq', 2);
            $list['account_info'] = M('WithdrawalsAccount')->field('id account_id,account,img')->where($where)->find();
            if (!$list['account_info']) {
                $list['account_info']['account_id'] = '';
                $list['account_info']['account']    = '转入余额';
                $list['account_info']['img']        = C('HTTP_APPS_IMG') . 'balancepay_icon.png';
            } else {
                $list['account_info']['img'] = $this->getAbsolutePath($list['account_info']['img']);
            }
        }

        /* 获取佣金信息 */
        $list['account_info']['withdrawals_money'] = sprintf('%.2f', $withdrawals_all);

        /* 获取云通讯配置参数 */
        $smsConfig = C('SMS_CONFIG');
        /* 短信功能标识 */
        // $list['detail']['sms_flag'] = strval($smsConfig['isopen']);
        /* 用户手机号 */
        $list['mobile'] = $smsConfig['isopen'] ? $memberInfo['mphone'] : '';

        $this->returnJson($list);
    }

    /**
     * 商家优惠券列表
     */
    public function couponList() {
        if ( IS_GET ) {
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            $type = I('get.type', '', 'strval');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询优惠券信息 */
            switch ( $type ) {
                case '1':
                    /* 未开始 */
                    $where['l.receive_start'] = array('gt', time());
                    $where['l.status']        = array('eq', 1);
                    break;
                case '2':
                    /* 进行中 */
                    $where['l.receive_start'] = array('elt', time());
                    $where['l.receive_end']   = array('egt', time());
                    $where['l.status']        = array('eq', 1);
                    break;
                case '3':
                    /* 已失效 */
                    // $where['l.status'] = array('eq', -1);
                    $where['_string'] = 'l.status = -1 OR l.receive_end < ' . time();
                    break;
                default:
                    /* 未开始 */
                    $where['l.receive_start'] = array('gt', time());
                    $where['l.status']        = array('eq', 1);
                    break;
            }
            $where['l.company_id']  = array('eq', $memberInfo['company_id']);
            $where['l.coupon_type'] = array('eq', 2);
            $list['coupon_list'] = M('Coupon')
                                 ->alias('l')
                                 ->field('l.id coupon_id,l.title,l.condition,COUNT(r.id) receive_num,GROUP_CONCAT(r.status) status,"0" use_num,l.money')
                                 ->join(C('DB_PREFIX') . 'coupon_member r ON l.id = r.coupon_id', 'LEFT')
                                 ->where($where)
                                 ->order('l.id DESC')
                                 ->group('l.id')
                                 ->limit($page,10)
                                 ->select();
            /* 数据处理 */
            if ( $list['coupon_list'] ) {
                foreach ($list['coupon_list'] as $key => $value) {
                    /* 使用限制 */
                    if ( $value['condition'] == 0 ) {
                        $list['coupon_list'][$key]['condition'] = '无消费条件限制';
                    } else {
                        $list['coupon_list'][$key]['condition'] = '满' . $value['condition'] . '可用';
                    }
                    /* 优惠券使用情况 */
                    if ( $value['status'] ) {
                        $value['status'] = explode(',', $value['status']);
                        foreach ($value['status'] as $v) {
                            /* 优惠券已使用数量 */
                            $v == -1 && $list['coupon_list'][$key]['use_num'] = strval( $list['coupon_list'][$key]['use_num'] + 1 );
                        }
                    }
                    unset($list['coupon_list'][$key]['status']);
                }
            } else {
                $list['coupon_list'] = array();
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家优惠券添加、编辑
     */
    public function couponAdd() {
        $id    = I('request.id', 0, 'intval');
        $token = I('request.token');

        /* 发布、修改 */
        $model = D('Coupon');
        if( IS_POST ) {
            $postDatas = $this->analyticalDatas($_POST);
            $postDatas && $_POST = $postDatas;
            $opt = $_POST['id'] > 0 ? '修改' : '发布';
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', $opt.'成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 商家信息 */
        $memberInfo = $this->memberInfo;

        /* 查询优惠券详情 */
        if ( $id ) {
            $detail = $model->getOneInfo(array('id'=>$id), 'title,money,issue_num,condition,start_time,end_time,receive_start,receive_end,company_id');
            $detail['company_id'] != $memberInfo['company_id'] && $this->ajaxJson('70000', '企业异常');
        } else {
            $detail['title']         = '';
            $detail['money']         = '';
            $detail['issue_num']     = '';
            $detail['condition']     = '';
            $detail['start_time']    = strval(time());
            $detail['end_time']      = strval(time() + 86400 * 7);
            $detail['receive_start'] = strval(time());
            $detail['receive_end']   = strval(time() + 86400 * 7);
            $detail['company_id']    = $memberInfo['company_id'];
        }
        $list['detail'] = $detail;

        /* 第一块 */
        $section_first = array(
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'id',
                'value'       => array(strval($id)),
                'child'       => array()
            ),
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'token',
                'value'       => array($token),
                'child'       => array()
            ),
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'company_id',
                'value'       => array($memberInfo['company_id']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '优惠券名称',
                'placeholder' => '请填写优惠券名称',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'title',
                'value'       => array($detail['title']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '优惠券面值',
                'placeholder' => '请填写优惠券面值',
                'num'         => '12',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'money',
                'value'       => array($detail['money']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '0',
                'title'       => '发放张数',
                'placeholder' => '填0代表无限张',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'issue_num',
                'value'       => array($detail['issue_num']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '0',
                'title'       => '使用条件',
                'placeholder' => '即满多少可用，填0代表无门槛使用',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'condition',
                'value'       => array($detail['condition']),
                'child'       => array()
            ),
            array(
                'type'        => 'date_time',
                'required'    => '0',
                'title'       => '生效时间',
                'placeholder' => '请填写生效时间',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'start_time',
                'value'       => array($detail['start_time']),
                'child'       => array()
            ),
            array(
                'type'        => 'date_time',
                'required'    => '0',
                'title'       => '失效时间',
                'placeholder' => '请填写失效时间',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'end_time',
                'value'       => array($detail['end_time']),
                'child'       => array()
            ),
            array(
                'type'        => 'date_time',
                'required'    => '0',
                'title'       => '开始领取时间',
                'placeholder' => '请填写开始领取时间',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'receive_start',
                'value'       => array($detail['receive_start']),
                'child'       => array()
            ),
            array(
                'type'        => 'date_time',
                'required'    => '0',
                'title'       => '结束领取时间',
                'placeholder' => '请填写结束领取时间',
                'num'         => '10',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'receive_end',
                'value'       => array($detail['receive_end']),
                'child'       => array()
            )
        );

        /* 区信息 */
        $list['section'] = array(
            $section_first
        );

        $this->returnJson($list);
    }

    /**
     * 商家优惠券删除(支持批量操作)
     */
    public function couponDel() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 删除优惠券(改变状态) */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id'] = array('in', $id);
            $save = M('Coupon')->where($where)->setField('status', -1);
            $save && $this->ajaxJson('40000', '操作成功');
            $this->ajaxJson('70000', '操作错误');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商家运费模版列表
     * @author 406764368@qq.com
     * @version 2016年11月14日 23:21:39
     */
    public function freightList() {
        if ( IS_GET ) {
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询运费模版信息 */
            $where['company_id']  = array('eq', $memberInfo['company_id']);
            $list['freight_list'] = M('Freight')->field('id freight_id,title')->where($where)->order('sort DESC,id DESC')->limit($page,10)->select();
            !$list['freight_list'] && $list['freight_list'] = array();

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商家运费模版列表
     * @author 406764368@qq.com
     * @version 2016年11月14日 23:21:39
     */
    public function freightAdd() {
        $id    = I('request.id', 0, 'intval');
        $token = I('request.token');

        /* 发布、修改 */
        $model = D('Freight');
        if( IS_POST ) {
            $postDatas = $this->analyticalDatas($_POST);
            $postDatas && $_POST = $postDatas;
            $opt = $_POST['id'] > 0 ? '修改' : '发布';
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', $opt.'成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 商家信息 */
        $memberInfo = $this->memberInfo;

        /* 查询运费模版详情 */
        if ( $id ) {
            // $where['l.id'] = array('eq', $id);
            // $detail = M('Freight')
            //         ->alias('l')
            //         ->field('l.company_id,l.title,l.piece,l.postage,l.sort,l.status,l.delivery_id')
            //         ->join(C('DB_PREFIX') . 'express r ON l.delivery_id = r.id', 'LEFT')
            //         ->where($where)
            //         ->find();
            $detail = M('Freight')->field('company_id,title,piece,postage,sort,status,delivery_id')->where(array('id'=>$id))->find();
            !$detail && $this->ajaxJson('70000', '运费模版异常');
            $detail['company_id']    != $memberInfo['company_id'] && $this->ajaxJson('70000', '企业异常');
            $detail['postage']       = json_decode($detail['postage'], true);
            $detail['placeallid']    = strval($detail['postage'][0]['placeallid']);
            $detail['package_first'] = strval($detail['postage'][0]['package_first']);
            $detail['freight_first'] = strval($detail['postage'][0]['freight_first']);
            $detail['package_other'] = strval($detail['postage'][0]['package_other']);
            $detail['freight_other'] = strval($detail['postage'][0]['freight_other']);
            unset($detail['postage']);
        } else {
            $detail['company_id']    = $memberInfo['company_id'];
            $detail['title']         = '';
            $detail['piece']         = '1';
            $detail['sort']          = '50';
            $detail['status']        = '1';
            $detail['delivery_id']   = '';
            $detail['placeallid']    = 'moren';
            $detail['package_first'] = '';
            $detail['freight_first'] = '';
            $detail['package_other'] = '';
            $detail['freight_other'] = '';
        }
        $list['detail'] = $detail;

        /* 物流公司 */
        $deliveryList = M('Express')->field('id,title')->where(array('status'=>1))->order('sort DESC,id ASC')->select();
        foreach ($deliveryList as $dlv) {
            $selected = $dlv['id'] == $detail['delivery_id'] ? '1' : '0';
            $deliveryArray[] = array(
                'type'        => 'radio',
                'required'    => '0',
                'title'       => $dlv['title'],
                'placeholder' => '',
                'num'         => '1',
                'width'       => '',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'delivery_id|array',
                'value'       => array($dlv['id'] . ',' . $selected),
                'child'       => array()
            );
            unset($selected);
        }

        /* 第一块 */
        $section_first = array(
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'id',
                'value'       => array(strval($id)),
                'child'       => array()
            ),
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'token',
                'value'       => array($token),
                'child'       => array()
            ),
            array(
                'type'        => 'hidden',
                'required'    => '0',
                'title'       => '',
                'placeholder' => '',
                'num'         => '',
                'width'       => '',
                'height'      => '',
                'top'         => '',
                'name'        => 'company_id',
                'value'       => array($memberInfo['company_id']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '运费模版名称',
                'placeholder' => '请填运费模版名称',
                'num'         => '15',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'title',
                'value'       => array($detail['title']),
                'child'       => array()
            )
        );

        /* 第二块 */
        $section_second = array(
            array(
                'type'        => 'title',
                'required'    => '1',
                'title'       => '选择计费方式',
                'placeholder' => '',
                'num'         => '',
                'width'       => '90',
                'height'      => '44',
                'top'         => '1',
                'name'        => '',
                'value'       => array(),
                'child'       => array()
            ),
            array(
                'type'        => 'radio',
                'required'    => '0',
                'title'       => '按件计费',
                'placeholder' => '',
                'num'         => '1',
                'width'       => '',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'piece|array',
                'value'       => array('1,' . strval($detail['piece'] == 1 ? 1 : 0)),
                'child'       => array()
            ),
            array(
                'type'        => 'radio',
                'required'    => '0',
                'title'       => '按重量计费',
                'placeholder' => '',
                'num'         => '1',
                'width'       => '',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'piece|array',
                'value'       => array('2,' . strval($detail['piece'] == 2 ? 1 : 0)),
                'child'       => array()
            )
        );

        /* 第三块 */
        $section_third[] = array(
            'type'        => 'href',
            'required'    => '1',
            'title'       => '选择快递公司',
            'placeholder' => '',
            'num'         => '1',
            'width'       => '90',
            'height'      => '44',
            'top'         => '1',
            'name'        => '',
            'value'       => array(),
            'child'       => $deliveryArray
        );

        /* 第四块 */
        $section_fourth = array(
            array(
                'type'        => 'title',
                'required'    => '0',
                'title'       => '全国',
                'placeholder' => '(设置指定区域规则请到PC端操作)',
                'num'         => '',
                'width'       => '30',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'placeallid',
                'value'       => array($detail['placeallid']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '首件/首重',
                'placeholder' => '',
                'num'         => '12',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'package_first',
                'value'       => array($detail['package_first']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '首件运费/首重运费',
                'placeholder' => '',
                'num'         => '12',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'freight_first',
                'value'       => array($detail['freight_first']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '续件/续重',
                'placeholder' => '',
                'num'         => '12',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'package_other',
                'value'       => array($detail['package_other']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '续件运费/续重运费',
                'placeholder' => '',
                'num'         => '12',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'freight_other',
                'value'       => array($detail['freight_other']),
                'child'       => array()
            ),
            array(
                'type'        => 'edit_text',
                'required'    => '1',
                'title'       => '排序',
                'placeholder' => '',
                'num'         => '12',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'sort',
                'value'       => array($detail['sort']),
                'child'       => array()
            ),
            array(
                'type'        => 'switch',
                'required'    => '1',
                'title'       => '是否启用',
                'placeholder' => '',
                'num'         => '',
                'width'       => '120',
                'height'      => '44',
                'top'         => '1',
                'name'        => 'status',
                'value'       => array($detail['status']),
                'child'       => array()
            ),
        );

        /* 区信息 */
        $list['section'] = array(
            $section_first,
            $section_second,
            $section_third,
            $section_fourth
        );

        $this->returnJson($list);
    }

    /**
     * 商家运费模版删除(支持批量操作)
     * @author 406764368@qq.com
     * @version 2016年11月15日 11:51:03
     */
    public function freightDel() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);
            
            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 删除优惠券(改变状态) */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $del = M('Freight')->where($where)->delete();
            $del && $this->ajaxJson('40000', '操作成功');
            $this->ajaxJson('70000', '操作错误');
        }
        $this->ajaxJson('70001');
    }
}