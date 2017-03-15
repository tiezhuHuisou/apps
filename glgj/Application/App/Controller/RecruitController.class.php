<?php
namespace App\Controller;
use Think\Controller;
class RecruitController extends AppController {
    /**
     * 架构函数
     */
    public function _initialize() {
        parent::_initialize();  
        $this->state = array(0 => '无', 1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士', 7 => '博士');
        $this->assign('site', 'index');
    }

    /**
     * 招聘信息列表
     */
    public function index() {
        $title = I('request.title');
        !empty($title) && $map['r.name|l.title'] = array('like', '%' . $title . '%');
        $map['l.status'] = array('eq', 1);
        $list = M('Post')
              ->alias('l')
              ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
              ->field('l.*,r.name,r.logo')
              ->where($map)
              ->limit(10)
              ->select();
        foreach ($list as $key => $value) {
            $list[$key]['degree'] = $this->state[$value['degree']];
        }
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title       = '招聘中心';
        $this->site_keywords    = '招聘中心';
        $this->site_description = '招聘中心';

        $this->display();
    }

    /**
     * 招聘信息列表ajax
     */
    public function ajaxList() {
        $title = I('request.title');
        $num   = I('request.num', 0, 'intval');
        !empty($title) && $map['r.name|l.title'] = array('like', '%' . $title . '%');
        $map['l.status'] = array('eq', 1);
        $list = M('Post')
              ->alias('l')
              ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
              ->field('l.*,r.name,r.logo')
              ->where($map)
              ->limit($num,10)
              ->select();
        foreach ($list as $key => $value) {
            $list[$key]['degree'] = $this->state[$value['degree']];
        }
        $this->ajaxReturn($list);
    }

    /**
     * 招聘详情
     */
    public function detail() {
        $id = I('get.id', 0, 'intval');
        $id == 0 && $this->error('参数错误');
        $map['l.id']     = array('eq', $id);
        $map['l.status'] = array('eq', 1);
        $detail = M('Post')
              ->alias('l')
              ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
              ->join(C('DB_PREFIX').'company_link c ON r.id = c.company_id', 'LEFT')
              ->field('l.*,r.name,c.contact_user,c.subphone')
              ->where($map)
              ->find();
        $count = M('PostApply')->where(array('post_id' => $id, 'apply_id' => $this->user_id))->count();
        $flag = $count ? 1 : 0;
        $this->assign('detail', $detail);
        $this->assign('flag', $flag);

        /* 页面基本设置 */
        $this->site_title       = '招聘详情';
        $this->site_keywords    = '招聘详情';
        $this->site_description = '招聘详情';

        $this->display();
    }

    /**
     * 投递求职信息
     */
    public function delivery() {
        empty($this->user_id) && $this->error('请先登录');
        $id = I('get.id');
        $count = M('ApplyJob')->find($this->user_id);
        if (!$count) {
            $this->error('请填写简历');
        }
        $data['post_id']  = $id;
        $data['apply_id'] = $this->user_id;
        $data['ctime']    = time();
        $status = M('PostApply')->add($data);
        if (!$status) {
            $this->error('已投递');
        }
        $this->success('投递成功');
    }

    /**
     * 添加编辑个人求职信息
     */
    public function addapply() {
        $id = $this->user_id;
        empty($id) && $this->error('请先登陆');
        $this->assign('user_id', $this->user_id);
        $map['uid']    = array('eq', $id);
        $map['status'] = array('eq', 1);
        $detail = M('applyJob')->where($map)->find();
        if ( $detail ) {
            $detail['work_exp'] = M('workExp')
                ->field('id,begin_time,end_time,company_name,post')
                ->where(array('apply_id' => $id))
                ->select(); //工作经历列表
        }
        
        if (IS_POST) {
            $res = D('ApplyJob')->update();
            if (!$res) {
                $msg['info'] = D('ApplyJob')->getError();
                // $this->error(D('applyJob')->getError());
            } else {
                $msg['info'] = $res['uid'] ? '修改成功' : '填写成功';
                //redirect('?g=app&m=recruit');
                //$this->success($res['uid'] ? '修改成功' : '新增成功');
            }
            $this->ajaxReturn($msg);
        }
        $this->site_title = "添加简历";
        $this->site_keywords = "添加简历";
        $this->site_description = "添加简历";
        $this->assign('detail', $detail);
        $this->display();
    }

    /*工作经历列表(企业用户个人用户公用)*/
    public function workExp() {
        $id = I('get.uid', 0, 'intval'); //企业用户UID
        !empty($id) && $map['apply_id'] = array('eq', $id); //企业用户获取个人工作经历列表
        empty($id) && $map['apply_id'] = array('eq', $this->user_id); //个人用户获取个人工作经历列表
        $list = M('WorkExp')->field('id,begin_time,end_time,company_name,post')->where($map)->select();
        $this->site_title = "工作经历";
        $this->site_keywords = "工作经历";
        $this->site_description = "工作经历";
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 企业用户 查看工作经历
     */
    public function workexpdetail() {
        // 工作经历id
        $id = I('get.id', 0, 'intval');

        // 查询工作经历
        if (!empty($id)) {
            $map['id'] = array('eq', $id);
            $detail = M('WorkExp')->where($map)->find();
            $this->assign('detail', $detail);
        } else {
            $this->error('参数错误');
        }

        // 页面基本设置
        $this->site_title = "工作经历详情";
        $this->site_keywords = "工作经历详情";
        $this->site_description = "工作经历详情";

        $this->display();
    }

    /*个人用户*/
    //添加编辑工作经历
    public function addworkexp() {
        empty($this->user_id) && $this->error('请先登陆');
        $id = I('get.id'); //工作经历ID
        if (!empty($id)) {
            $map['id'] = array('eq', $id);
            $detail = M('WorkExp')->where($map)->find();
        }
        if (IS_POST) {
            $res = D('workExp')->update();
            if (!$res) {
                $this->error(D('workExp')->getError());
            } else {
                $msg = $res['id'] ? '修改成功' : '新增成功';
                $this->success($msg, '?g=app&m=recruit&a=addapply');
            }
        }
        $this->assign('uid', $this->user_id);
        $this->site_title = "添加工作经历";
        $this->site_keywords = "添加工作经历";
        $this->site_description = "添加工作经历";
        $this->assign('detail', $detail);
        $this->display();
    }

    /*企业用户*/
    //查看求职者详情
    public function applyDetail() {
        $sex = array(1 => '男', 2 => '女');
        $id = I('get.id'); //个人信息ID
        $map['uid'] = array('eq', $id);
        $map['status'] = array('eq', 1);
        $field = 'name,age,sex,degree,expect_wages,phone,address';
        $detail = M('applyJob')->field($field)->where($map)->find(); //个人信息
        $detail['mphone'] = M('member')->where('uid=' . $detail['pid'])->getField('mphone');
        $detail['degree'] = $this->state[$detail['degree']];
        $detail['sex'] = $sex[$detail['sex']];
        $detail['re_name'] = M('Member')->where("uid = '" . $id . "'")->getField('name'); //推荐人
        $detail['work_exp'] = M('workExp')
            ->field('id,begin_time,end_time,company_name,post')
            ->where(array('apply_id' => $id))
            ->select(); //工作经历列表
        $this->site_title = "求职详情";
        $this->site_keywords = "求职详情";
        $this->site_description = "求职详情";
        $this->assign('detail', $detail);
        $this->display();
    }

    /*退回求职*/
    public function cancelApply() {

    }
}