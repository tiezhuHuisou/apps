<?php
namespace Member\Controller;

use Think\Controller;

class MemberController extends Controller {
    /**
     * APP控制器初始化
     */
    protected function _initialize() {
        // $this->error('用户后台已关闭，所有操作都可以在手机上进行');
        $user = session("user_auth");
        define(UID, $user['user_id']);
        if (empty($_COOKIE['username']) || empty($_COOKIE['password'])) {
            if (!UID) {
                redirect("?g=member&m=login");
            }
        }

        $map['uid'] = array('eq', UID);
        $user_info = M('Member')->where($map)->find();
        $user_info['birthday'] = $user_info['birthday'] ? date('Y-m-d', $user_info['birthday']) : '';
        if ($user_info['gid'] == 2) {
            $companyInfo = M('Company')->field('id,name')->where(array('user_id' => UID))->find();
            $user_info['name'] = $companyInfo['name'];
            $user_info['company_id'] = $companyInfo['id'];
        }
        $this->user_info = $user_info;

//        /* 查询分销功能是否开启 */
//        $this->distribution_flag = M('Conf')->where(array('name'=>'distribution'))->getField('value');
        $this->assign('gid', $user_info['gid']);
        /* 订单自动确认收货 */
        //$this->orderAutoConfirmReceive();
    }

    /**
     *
     * 订单自动确认收货
     *
     * @author 406764368@qq.com 黄东
     * @version 2017年2月4日 13:38:14
     *
     */
//    public function orderAutoConfirmReceive() {
//        $nowTime    = time();
//        $orderModel = M('Order');
//        $autoDays   = C('AUTO_CONFIRM_RECEIVE_DAYS');
//
//        /* 查询到期的订单 */
//        $where['state']     = array('eq', 2);
//        $where['send_time'] = array('elt', $nowTime - ($autoDays * 86400));
//        $orderList = M('Order')->field('id,uid,company_id,send_time')->where($where)->select();
//
//        /* 更新数据 */
//        if ( $orderList ) {
//            $orderClogModel   = M('OrderClog');
//            $withdrawalsModel = M('Withdrawals');
//            $commissionModel  = M('Commission');
//            $companyModel     = M('Company');
//
//            foreach ($orderList as $value) {
//                /* 更新订单数据 */
//                $orderModel->where(array('id'=>$value['id']))->setField('state', 3);
//
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $value['id'];
//                $clogDatas['action']   = 15;
//                $clogDatas['uid']      = $value['uid'];
//                $clogDatas['remark']   = '系统自动确认收货';
//                $clogDatas['addtime']  = $value['send_time'] + ( $autoDays * 86400 );
//                $orderClogModel->add($clogDatas);
//
//                /* 更新商家利润数据 */
//                $withdrawalsDatas['status'] = -2;
//                $withdrawalsDatas['etime']  = $nowTime;
//                $withdrawalsModel->where(array('order_id' => $value['id']))->save($withdrawalsDatas);
//
//                /* 扣除商家销售利润（发放佣金） */
//                $withdrawals = $commissionModel->where(array('order_id' => $value['id'], 'status' => 0))->sum('commission');
//                if ($withdrawals) {
//                    $deductCommissionDatas['order_id']    = $value['id'];
//                    $deductCommissionDatas['withdrawals'] = $withdrawals;
//                    $deductCommissionDatas['cname']       = $companyModel->where(array('id' => $value['company_id']))->getField('name');
//                    $deductCommissionDatas['cid']         = $value['company_id'];
//                    $deductCommissionDatas['etime']       = $nowTime;
//                    $deductCommissionDatas['ctime']       = $nowTime;
//                    $deductCommissionDatas['status']      = 4;
//                    M('Withdrawals')->add($deductCommissionDatas);
//
//                    /* 更新佣金数据 */
//                    $commissionModel->where(array('order_id' => $value['id']))->setField('status', 1);
//                }
//            }
//        }
//    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model $model 模型名或模型实例
     * @param array $where where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order 排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean $field 单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 122837594@qq.com
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists($model, $where = array(), $order = '', $field = true) {
        $options = array();
        $REQUEST = (array)I('request.');
        unset($REQUEST['aliyungf_tc'], $REQUEST['PHPSESSID'], $REQUEST['app_cityid']);

        if (is_string($model)) {
            $model = M($model);
        }

        $OPT = new \ReflectionProperty($model, 'options');
        $OPT->setAccessible(true);

        $pk = $model->getPk();
        if ($order === null) {
            //order置空
        } else if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
            $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
        } elseif ($order === '' && empty($options['order']) && !empty($pk)) {
            $options['order'] = $pk . ' desc';
        } elseif ($order) {
            $options['order'] = $order;
        }
        unset($REQUEST['_order'], $REQUEST['_field']);

        if (empty($where)) {
            $where = array('status' => array('egt', 0));
        }
        if (!empty($where)) {
            $options['where'] = $where;
        }
        $options = array_merge((array)$OPT->getValue($model), $options);
        $total = $model->where($options['where'])->count();

        if (isset($REQUEST['r'])) {
            $listRows = (int)$REQUEST['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if ($total > $listRows) {
            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p = $page->show();
        $this->assign('_page', $p ? $p : '');
        $this->assign('_total', $total);
        $options['limit'] = $page->firstRow . ',' . $page->listRows;

        $model->setProperty('options', $options);

        return $model->field($field)->select();
    }
}