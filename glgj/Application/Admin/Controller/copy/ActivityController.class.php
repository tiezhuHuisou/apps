<?php
namespace Admin\Controller;
use Think\Controller;
class ActivityController extends AdminController{
    /**
     * 架构函数
     * @return [type] [description]
     */
    protected function _initialize() {
        parent::_initialize();
        $this->assign('site','activity');
        /* 查询限时抢购活动开关 */
        $this->assign('flashFlag', C('FLASHFLAG'));
    }

    /**
     * 优惠券活动设置
     */
    public function index() {
        if ( IS_POST ) {
            $model = D('Activity');
            $result = $model->update();
            if ( $result !== false ) {
                $this->success('设置成功');
            }
            $this->error($model->getError());
        }
        $detail = M('Activity')->where(array('activity_type'=>3))->find();
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="优惠券活动设置";
        $this->assign('left','index');
        $this->display();
    }

    /**
     * 优惠券活动列表
     */
    public function coupon() {
        $list = $this->lists('Coupon');
        /* 查询可指定商家 */
        $companyList = M('Company')->getField('id,name', true);
        /* 查询可指定分类 */
        $categoryList = M('ProductSaleCategory')->getField('id,name', true);
        /* 数据处理 */
        foreach ($list as $key => $value) {
            /* 优惠券类型 */
            if ( $value['coupon_type'] == 2 ) {
                $list[$key]['coupon_type'] = '指定商家【'.$companyList[$value['company_id']].'】';
            } elseif ( $value['coupon_type'] == 3 ) {
                $list[$key]['coupon_type'] = '指定分类【'.$categoryList[$value['product_category_id']].'】';
            } else {
                $list[$key]['coupon_type'] = '通用类型';
            }
            /* 使用条件 */
            if ( $value['condition'] == 0 ) {
                $list[$key]['condition'] = '无消费条件限制';
            } else {
                $list[$key]['condition'] = '满' . $value['condition'] . '可用';
            }
            /* 有效期 */
            // if ( $value['effect_time'] == 0 ) {
            //     $list[$key]['effect_time'] = '无限期';
            // } else {
            //     $list[$key]['effect_time'] = $value['effect_time'] . '天';
            // }
            /* 使用时间期限 */
            if ( $value['start_time'] > 0 && $value['end_time'] > 0 ) {
                if ( date('Ymd', $value['start_time']) == date('Ymd', $value['end_time']) ) {
                    $list[$key]['limit_time'] = date('Y-m-d', $value['end_time']) . '当日可用';
                } else {
                    $list[$key]['limit_time'] = date('Y-m-d', $value['start_time']) . ' 至 ' . date('Y-m-d', $value['end_time']);
                }
            } elseif ( $value['start_time'] > 0 && $value['end_time'] == 0 ) {
                $list[$key]['limit_time'] = date('Y-m-d', $value['start_time']) . '之后可用';
            } elseif ( $value['start_time'] == 0 && $value['end_time'] > 0 ) {
                $list[$key]['limit_time'] = date('Y-m-d', $value['end_time']) . '之前可用';
            } else {
                $list[$key]['limit_time'] = '无使用时间限制';
            }
        }
        $this->assign('list', $list);

        /*页面基本设置*/
        $this->site_title="优惠券活动列表";
        $this->assign('left','coupon');
        $this->display();
    }

    /**
     * 优惠券活动添加、编辑
     */
    public function couponAdd() {
        $id = I('request.id', 0, 'intval');
        $model = D('Coupon');
        $opt = $id > 0 ? '修改' : '添加';
        if( IS_POST ) {
            $result = $model->update();
            if ( $result ) {
                $this->success($opt.'成功', '?g=admin&m=activity&a=coupon');
            } else {
                $this->error($model->getError());
            }
        }

        /* 修改 */
        if ( $id ) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail',$detail);
        }

        /* 查询可指定商家 */
        $companyList = M('Company')->field('id,name')->select();
        $this->assign('companyList', $companyList);

        /* 查询所有分类 */
        $categoryList = M('ProductSaleCategory')->where(array('status'=>1))->select();
        $categoryList = $this->dumpTreeList($categoryList);
        $this->assign('categoryList', $categoryList);

        /* 页面基本设置 */
        $this->site_title = $opt.'优惠券';
        $this->assign('left', 'coupon');
        $this->display();   
    }

    /**
     * 优惠券删除
     */
    public function couponDel() {
        $id = I('get.id', 0, 'intval');
        $del = M('Coupon')->where(array('id'=>$id))->setField('status', -1);
        !$del && $this->error('删除失败');
        $this->success('删除成功');
    }

    /**
     * 限时抢购
     */
    public function flash() {
        if ( IS_POST ) {
            $model = D('Activity');
            $result = $model->update();
            if ( $result !== false ) {
                $this->success('设置成功');
            }
            $this->error($model->getError());
        }
        $detail = M('Activity')->where(array('activity_type'=>1))->find();
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="限时抢购活动设置";
        $this->assign('left','flash');
        $this->display();
    }

    /**
     * 充值活动
     */
    public function recharge() {
        if ( IS_POST ) {
            $model = D('Activity');
            $result = $model->update();
            if ( $result !== false ) {
                $this->success('设置成功');
            }
            $this->error($model->getError());
        }
        $detail = M('Activity')->where(array('activity_type'=>2))->find();
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="充值优惠活动设置";
        $this->assign('left','recharge');
        $this->display();
    }

    /**
     * 充值活动规则
     */
    public function rechargerule() {
        $model = M('Recharge');
        if ( IS_POST ) {
            /* 数据验证 */
            if ( count($_POST['reach']) != count($_POST['give']) || count($_POST['reach']) != count($_POST['sort']) ) {
                $this->error('请完善规则');
            }
            $i = 0;
            for ($i; $i < count($_POST['reach']); $i++) { 
                /* 验证(充多少送多少活动) 达到金额 */
                empty($_POST['reach'][$i]) && $this->error('第'.($i+1).'条充值规则充（元）不能为空');
                !preg_match('/^\d+(\.\d{1,2})?$/', $_POST['reach'][$i]) && $this->error('第'.($i+1).'条充值规则充（元）格式不正确');
                /* 验证(充多少送多少活动) 赠送金额 */
                empty($_POST['give'][$i]) && $this->error('第'.($i+1).'条充值规则送（元）不能为空');
                !preg_match('/^\d+(\.\d{1,2})?$/', $_POST['give'][$i]) && $this->error('第'.($i+1).'条充值规则送（元）格式不正确');
                /* 验证排序字段 */
                empty($_POST['sort'][$i]) && $this->error('第'.($i+1).'条充值规则排序不能为空');
                !is_numeric($_POST['sort'][$i]) && $this->error('第'.($i+1).'条充值规则排序只能为数字');
                if ( $_POST['sort'][$i] < 0 || $_POST['sort'][$i] > 99 ) {
                    $this->error('第'.($i+1).'条充值规则排序范围0-99');
                }
            }
            /* 清空表数据 */
            $del = M()->execute('TRUNCATE `lp_recharge`');
            $del === false && $this->error('操作失败');
            /* 插入数据 */
            $j = 0;
            for ($j; $j < count($_POST['reach']); $j++) { 
                $datas[$j]['reach'] = $_POST['reach'][$j];
                $datas[$j]['give']  = $_POST['give'][$j];
                $datas[$j]['sort']  = $_POST['sort'][$j];
            }
            $add = $model->addAll($datas);
            !$add && $this->error('操作失败');
            $this->success('操作成功');
        }

        /* 查询充值规则数据 */
        $list = $model->field('reach,give,sort')->order('id ASC')->select();
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = '充值规则设置';
        $this->assign('left', 'rechargerule');
        $this->display();
    }

    private function &dumpTreeList($arr, $parentId = 0, $lv = 0) {
        $lv++;
        $tree = array();
        foreach ((array)$arr as $row) {
            if ($row['parent_id'] == $parentId) {
                $row['level'] = $lv;
                if ($row['parent_id'] != 0) {
                    $row['sty'] = "|";
                }

                for ($i = 1; $i < $row['level']; $i++) {
                    $row['sty'] .= "——";
                    $row['sty2'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $row['sty2'] = $row['sty2'] . $row['sty'];
                $tree[] = $row;
                if ($children = $this->dumpTreeList($arr, $row['id'], $lv)) {
                    $tree = array_merge($tree, $children);
                }
            }
        }
        return $tree;
    }
}