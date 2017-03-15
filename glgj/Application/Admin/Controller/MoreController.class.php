<?php
namespace Admin\Controller;
use Think\Controller;
class MoreController extends AdminController{
    
    protected function _initialize() {
        parent::_initialize();
        $this->degree = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士', 7 => '博士');
        $this->assign('site','more');
    }
    /**
     * 更多管理首页（单页列表）
     * @return [type] [description]
     */
    public function index(){
        $order = 'inputtime desc';
        if ( !$this->distribution_flag ) {
            $where['id'] = array('in', '2,3,4,5');
        }
        $list = $this->lists('Page',$where,$order,'');
        $this->assign('list',$list);
        /*页面基本设置*/
        $this->site_title="更多管理-单页列表";
        $this->assign('left','index');
        $this->display();
    }

    /**
     * 添加单页
     * @return [type] [description]
     */
    public function add(){
        $id = I('id');
        $page = D('Page');
        if(IS_POST){
            $status = $page->update();
            if($status){
                if($id){
                    $this->success('修改成功','?g=admin&m=more');
                }else{
                $this->success('添加成功','?g=admin&m=more');
                }
            }else{
                $errorInfo=$page->getError();
                $this->error($errorInfo);
            }
        }
        $detail = $page->getPageInfo(array('id'=>$id));
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="更多管理-编辑单页";
        $this->assign('left','index');
        $this->display();
    }
    
    /**
     * 删除页面
     * @author 83961014@qq.com
     */
    public function del(){
        $id = I('id');
        $page = D('Page');
        $condition['id'] = $id;
        $return = $page->delPage($condition);
        if($return != false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 自定义单页
     * @return [type] [description]
     */
    public function custom() {
        /*页面基本设置*/
        $this->site_title="更多管理-自定义单页";
        $this->assign('left','index');
        $this->display();
    }

    /**
     * 优惠券管理
     * @return [type] [description]
     */
    public function coupon() {
        $order = 'end_time desc';
        $list = $this->lists('Coupon','',$order,'');
        $this->assign('list',$list);
        /*页面基本设置*/
        $this->site_title="更多管理-优惠券管理";
        $this->assign('left','coupon');
        $this->display();
    }

    /**
     * 添加优惠券
     * @return [type] [description]
     */
    public function couponAdd() {
        $id = I('id');
        if(IS_POST){
            $start_time = strtotime(I('start_time'));
            $end_time = strtotime(I('end_time'));
            if($start_time >= $end_time){
                $this->error('开始日期不能大于结束日期');
                exit;
            }
            $status=D('Coupon')->update();
            if($status){
                $this->success('添加优惠券成功','?g=admin&m=more&a=coupon');
            }else{
                $this->error(D('Coupon')->getError());
            }
        }
        $coupon = D('Coupon');
        $detail = $coupon->getCouponInfo(array('id'=>$id));
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="更多管理-添加优惠券";
        $this->assign('left','coupon');
        $this->display();
    }
    /**
     * 删除优惠券
     * @author 83961014@qq.com
     */
    public function coupondel(){
        $id = I('id');
        $page = D('Coupon');
        $condition['id'] = $id;
        $return = $page->delCoupon($condition);
        if($return != false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    
    /**
     * 招聘管理-岗位列表
     */
    public function post() {
        $map['status'] = array('eq', 1);
        $list = M('post')->where($map)->select();
        foreach ($list as $key => $value) {
            $list[$key]['degree'] = $this->degree[$value['degree']];
        }
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title="招聘管理-岗位列表";
        $this->assign('left','post');
        $this->display();
    }

    /**
     * 招聘管理-添加岗位
     */
    public function addPost() {
        $id = I('get.id', 0, 'intval');

        if (IS_POST) {
            $res = D('Post')->update();
            if (!$res) {
                $this->error(D('Post')->getError());
            } else {
                $this->success($res['id'] ? '修改成功' : '新增成功', '?g=admin&m=more&a=post');
            }
        }

        if ( $id > 0 ) {
            $map['id'] = array('eq', $id);
            $map['status'] = array('eq', 1);
            $detail = M('Post')->where($map)->find();
            $this->assign('detail', $detail);
        }

        $where['user_id'] = array('gt', 0);
        $company_list = M('Company')->field('id,name')->where($where)->select();
        $this->assign('company_list', $company_list);
        /*页面基本设置*/
        $this->site_title="招聘管理-添加岗位";
        $this->assign('left','post');
        $this->display();
    }

    /**
     * 招聘管理-删除岗位
     */
    public function post_del() {
        $id = I('get.id', 0, 'intval');
        !$id && $this->error('参数错误');
        $map['id'] = array('eq', $id);
        $del = M('Post')->where($map)->delete();
        if ( $del !== false ) {
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }

    /**
     * 批量删除岗位
     */
    public function postDelAll() {
        if (IS_POST){
            $ids = I('ids');
            $ids = implode(',', $ids);
            $condition['id'] = array('in',$ids);
            $tem = M('Post')->where($condition)->delete();
            if($tem != false){
                $return['errno']        = 0;
                $return['error']        = "删除成功";
                $this->ajaxReturn($return);
            }else{
                $return['errno']        = 1;
                $return['error']        = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 求职管理
     * @return [type] [description]
     */
    public function apply() {
        $list = $this->lists('PostApply');
        foreach ($list as $key => $value) {
            $apply_info = M('ApplyJob')->field('name,phone,expect_wages,degree')->where(array('uid'=>$value['apply_id']))->find();
            $list[$key]['title'] = M('Post')->where(array('id'=>$value['post_id']))->getField('title');
            $list[$key]['name'] = $apply_info['name'];
            $list[$key]['phone'] = $apply_info['phone'];
            $list[$key]['expect_wages'] = $apply_info['expect_wages'];
            $list[$key]['degree'] = $this->degree[$apply_info['degree']];
        }
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title="求职管理";
        $this->assign('left','apply');
        $this->display();
    }

    /**
     * 求职详情
     */
    public function applydetail() {
        $id = I('get.id', 0, 'intval');
        !$id && $this->error('参数错误');
        $map['pa.id'] = array('eq', $id);
        $detail = M('apply_job')
            ->alias('aj')
            ->field('uid,apply_id,name,age,phone,sex,expect_wages,aj.degree as degree,email,title,aj.address')
            ->join(C('DB_PREFIX') . 'post_apply pa on aj.uid = pa.apply_id')
            ->join(C('DB_PREFIX') . 'post p on pa.post_id = p.id')
            ->where($map)
            ->find();
        $detail['degree'] = $this->degree[$detail['degree']];
        $detail['work_exp'] = M('WorkExp')->where(array('apply_id' => $detail['apply_id']))->select();
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title="求职详情";
        $this->assign('left','apply');
        $this->display();
    }

    /**
     * 删除求职
     */
    public function apply_del() {
        $id = I('get.id', 0, 'intval');
        !$id && $this->error('参数错误');
        $del = M('PostApply')->where(array('id'=>$id))->delete();
        if ( $del !== false ) {
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }

    /**
     * 批量删除求职
     */
    public function applyDelAll() {
        if (IS_POST){
            $ids = I('ids');
            $ids = implode(',', $ids);
            $condition['id'] = array('in',$ids);
            $tem = M('PostApply')->where($condition)->delete();
            if($tem != false){
                $return['errno']        = 0;
                $return['error']        = "删除成功";
                $this->ajaxReturn($return);
            }else{
                $return['errno']        = 1;
                $return['error']        = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }
}