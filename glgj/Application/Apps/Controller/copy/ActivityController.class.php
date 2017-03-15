<?php
namespace Apps\Controller;
use Think\Controller;

class ActivityController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
        if ( !in_array(ACTION_NAME, array('index')) ) {
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
            !$this->uid && $this->ajaxJson('40004');
        }
    }

    /**
     * 活动列表
     */
    public function index() {
        if ( IS_GET ) {
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;

            /* 查询活动信息 */
            $list['activity'] = M('Activity')->field('id activity_id,title,img,start_time,end_time,activity_type')->where(array('status'=>1))->order('start_time DESC')->limit($page,10)->select();
            !$list['activity'] && $this->returnJson(array('activity'=>array()));
            /* 数据处理 */
            foreach ($list['activity'] as $key => $value) {
                $list['activity'][$key]['activity_time'] = date('Y-m-d H:i:s', $value['start_time']) . ' 至 ' . date('Y-m-d H:i:s', $value['end_time']);
                unset($list['activity'][$key]['start_time'], $list['activity'][$key]['end_time']);
            }
            /* 图片处理 */
            $list['activity'] = $this->getAbsolutePath($list['activity'], 'img');

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 限时抢购商品列表
     */
    public function flash() {
        if ( IS_GET ) {
            $title = I('get.title');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;

            /* 查询活动信息 */
            $where['activity_type'] = array('eq', 1);
            $where['status']        = array('eq', 1);
            $where['start_time']    = array('lt', time());
            $where['end_time']      = array('gt', time());
            $list['activity_info']  = M('Activity')->field('title,img,end_time')->where($where)->find();
            !$list['activity_info'] && $this->returnJson(array('activity_info'=>'', 'product_list'=>array()));
            /* 数据处理 */
            $list['activity_info']['residue_time'] = $list['activity_info']['end_time'] - time();
            $list['activity_info']['img'] = $this->getAbsolutePath($list['activity_info']['img']);
            unset($list['activity_info']['end_time']);

            /* 查询活动商品 */
            !empty($title) && $map['title'] = array('like', '%'.$title.'%');
            $map['activity_type'] = array('eq', 1);
            $map['status']        = array('eq', 1);
            $list['product_list'] = M('ProductSale')->field('id product_id,title,img,activity_price,oprice')->where($map)->order('id DESC')->limit($page,10)->select();
            /* 数据处理 */
            $list['product_list'] = $this->getAbsolutePath($list['product_list'], 'img', C('HTTP_APPS_IMG') . 'product_default.png');

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 优惠券列表
     * 活动未开启是进不到这个控制器的，如果未开启也能来，就是客户端的问题，故不需要判断活动是否开启
     */
    public function coupon() {
        if ( IS_GET ) {
            // $page  = I('get.page', 1, 'intval');
            // $page  = ( $page - 1 ) * 10;
            $company_id = I('get.company_id', 0, 'intval');

            /* 查询活动信息 */
            // $where['activity_type'] = array('eq', 3);
            // $where['status']        = array('eq', 1);
            // $where['start_time']    = array('lt', time());
            // $where['end_time']      = array('gt', time());
            // $list['activity_info']  = M('Activity')->field('title,img,end_time')->where($where)->find();
            // !$list['activity_info'] && $this->returnJson(array('activity_info'=>array(), 'coupon_list'=>array()));
            /* 数据处理 */
            // $list['activity_info']['residue_time'] = $list['activity_info']['end_time'] - time();
            // $list['activity_info']['img'] = $this->getAbsolutePath($list['activity_info']['img']);
            // unset($list['activity_info']['end_time']);
            $list['activity_info'] = array();

            /* 查询可领取优惠券 */
            $map['l.status']   = array('eq', 1);
            $map['_string'] = '(l.start_time = 0 AND l.end_time = 0) OR (l.start_time = 0 AND l.end_time > '.time().') OR (l.start_time < '.time().' AND l.end_time = 0) OR (l.start_time < '.time().' AND l.end_time > '.time().')';
            $company_id && $map['l.company_id'] = array('eq', $company_id);
            $couponList = M('Coupon')
                        ->alias('l')
                        ->field('l.id coupon_id,CONCAT("￥",l.money) money,l.title,l.condition,GROUP_CONCAT(r.uid) receive,l.start_time,l.end_time')
                        ->join(C('DB_PREFIX') . 'coupon_member r ON l.id = r.coupon_id', 'LEFT')
                        // ->join(C('DB_PREFIX') . 'company c ON l.company_id = c.id', 'LEFT')
                        ->where($map)
                        ->order('l.id DESC')
                        ->group('l.id')
                        // ->limit($page,10)
                        ->select();
            !$couponList && $this->returnJson(array('activity_info'=>$list['activity_info'], 'coupon_list'=>array()));
            /* 数据处理 */
            $uid = $this->uid;
            $list['coupon_receive'] = array();
            $list['coupon_has']     = array();
            foreach ($couponList as $key => $value) {
                /* 优惠券使用条件限制 */
                if ( $value['condition'] == 0 ) {
                    $couponList[$key]['condition'] = '无消费条件限制';
                } else {
                    $couponList[$key]['condition'] = '满' . $value['condition'] . '可用';
                }
                /* 优惠券使用时间期限 */
                if ( $value['start_time'] > 0 && $value['end_time'] > 0 ) {
                    if ( date('Ymd', $value['start_time']) == date('Ymd', $value['end_time']) ) {
                        $couponList[$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '当日可用';
                    } else {
                        $couponList[$key]['effect_time'] = date('Y-m-d', $value['start_time']) . ' 至 ' . date('Y-m-d', $value['end_time']);
                    }
                } elseif ( $value['start_time'] > 0 && $value['end_time'] == 0 ) {
                    $couponList[$key]['effect_time'] = date('Y-m-d', $value['start_time']) . '之后可用';
                } elseif ( $value['start_time'] == 0 && $value['end_time'] > 0 ) {
                    $couponList[$key]['effect_time'] = date('Y-m-d', $value['end_time']) . '之前可用';
                } else {
                    $couponList[$key]['effect_time'] = '无使用时间限制';
                }
                /* 判断用户是否领取优惠券 */
                $value['receive'] = explode(',', $value['receive']);
                /* 销毁不需要的数据 */
                unset($couponList[$key]['receive'], $couponList[$key]['start_time'], $couponList[$key]['end_time']);
                if ( in_array($uid, $value['receive']) ) {
                    /* 已领取 */
                    $list['coupon_has'][] = $couponList[$key];
                } else {
                    /* 可领取 */
                    $list['coupon_receive'][] = $couponList[$key];
                }
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 优惠券领取
     */
    public function couponReceive() {
        if ( IS_POST ) {
            $id  = I('post.id', 0, 'intval');
            !$id && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            /* 判断用户是否领取过该优惠券 */
            $model = M('CouponMember');
            $where['coupon_id'] = array('eq', $id);
            $where['uid']       = array('eq', $uid);
            $record = $model->where($where)->getField('id');
            $record && $this->ajaxJson('70000', '您已领取过该优惠券');

            /* 查询活动信息 */
            $where_activity['status']        = array('eq', 1);
            $where_activity['start_time']    = array('lt', time());
            $where_activity['end_time']      = array('gt', time());
            $where_activity['activity_type'] = array('eq', 3);
            $activityInfo = M('Activity')->where($where_activity)->getField('id');
            !$activityInfo && $this->ajaxJson('70000', '不在活动时间内不能领取');

            /* 获取优惠券限制条件字段 */
            $couponInfo = M('Coupon')->field('start_time,end_time,company_id,product_category_id,issue_num')->where(array('id'=>$id))->find();

            /* 判断是否超过发放张数 */
            $where_coupon['coupon_id'] = array('eq', $id);
            $issue_num = $model->where($where_coupon)->count('id');
            $issue_num >= $couponInfo['issue_num'] && $this->ajaxJson('70000', '该优惠券已发放完毕');

            /* 领取优惠券 */
            $datas['uid']                 = $uid;
            $datas['coupon_id']           = $id;
            $datas['start_time']          = $couponInfo['start_time'];
            $datas['end_time']            = $couponInfo['end_time'];
            $datas['create_time']         = time();
            $datas['status']              = 1;
            $datas['company_id']          = $couponInfo['company_id'];
            $datas['product_category_id'] = $couponInfo['product_category_id'];
            $add = $model->add($datas);
            $add && $this->ajaxJson('40000', '领取成功');
            $this->ajaxJson('70000', '领取失败');
        }
        $this->ajaxJson('70001');
    }
}