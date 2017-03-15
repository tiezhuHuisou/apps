<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-04
 * Time: 15:42
 */

namespace Member\Controller;

use Think\Controller;

class RecruitController extends MemberController {

	protected function _initialize() {
		parent::_initialize();
		/*获取公司ID*/
		$map['user_id']=array('eq',UID);
		$company_info=M('Company')->where($map)->find();
		
		$this->company_info=$company_info;
		$this->$state = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士');
		$this->site_title = '企业招聘';
		$this->assign('site', 'recruit');
	}
	/*招聘列表*/
	//针对对个人用户
	public function index() {
		//cookie('__recruit__', $_SERVER['REQUEST_URI']);
		$title = I('post.title');
		!empty($title) && $map['company_name|contact'] = array('like', '%' . $title . '%');
		$map['status'] = array('eq', 1);
		$list = $this->lists('recruit', $map);

		$this->assign('title', $title);
		$this->assign('list', $list);
		$this->assign('_page', $_page);
		$this->display();
	}

	/*添加招聘信息*/
	//针对企业用户
	public function add() {
		$map['uid'] = array('eq', UID);
		$map['status'] = array('eq', 1);
		$detail = M('recruit')->where($map)->find();
		if (IS_POST) {
			$res = D('recruit')->update();
			if (!$res) {
				$this->error(D('recruit')->getError());
			} else {
				$this->success($res['uid'] ? '修改成功' : '新增成功');
			}
		}
		$this->assign('header', 'add');
		$this->assign('detail', $detail);
		$this->display();
	}

	//岗位列表
	public function post() {
		//$id = I('get.uid');
		$state = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士', 7 => '博士');
		$company_id = M('Company')->where(array('user_id'=>UID))->getField('id');
		$map['status'] = array('eq', 1);
		$map['company_id'] = array('eq', $company_id);
		$list = M('post')->where($map)->select();
		foreach ($list as $key => $value) {
			$list[$key]['degree'] = $state[$value['degree']];
		}
		$this->assign('header', 'post');
		$this->assign('list', $list);
		$this->display();
	}

	//添加岗位
	public function addPost() {
		/* 判断权限 */
		$company_info=$this->company_info;
		if(!$company_info){
		    $this->error('请先填写公司信息', '?g=member&m=sale&a=company');
		}

		$id = I('get.id');
		if (!empty($id)) {
			$map['id'] = array('eq', $id);
			$map['status'] = array('eq', 1);
			$detail = M('post')->where($map)->find();
		}

		if (IS_POST) {
			$res = D('post')->update();
			if (!$res) {
				$this->error(D('post')->getError());
			} else {
				$this->success($res['id'] ? '修改成功' : '新增成功', '?g=member&m=recruit&a=post');
			}
		}
		$this->assign('header', 'addPost');
		$this->assign('detail', $detail);
		$this->display();
	}

	// /*删除招聘*/
	// public function recruit_del() {
	// 	$map['uid'] = array('eq', I('get.id'));
	// 	$map['status'] = array('eq', 1);
	// 	$this->delete('recruit', $map);
	// }

	/*删除选中招聘*/
/*	public function recruit_delall() {
$ids = I('ids');
$map['uid'] = array('in', $ids);
$map['status'] = array('eq', 1);
$status = M('recruit')->where($map)->setField('status', -1);
if (false == $status) {
$return['errno'] = 0;
$return['error'] = "删除失败";
}
$return['errno'] = 1;
$return['error'] = "删除成功";
$this->ajaxReturn($return);
}*/

	/*求职信息列表*/
	//企业用户
	public function apply() {
		$state = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士', 7 => '博士');
		cookie('__apply__', $_SERVER['REQUEST_URI']);
		$company_id = M('Company')->where(array('user_id'=>UID))->getField('id');
		$map['aj.status'] = array('eq', 1);
		$map['company_id'] = array('eq', $company_id);
		$list = M('applyJob')
			->alias('aj')
			->join(C('DB_PREFIX') . 'post_apply	pa on aj.uid = pa.apply_id')
			->join(C('DB_PREFIX') . 'post p on pa.post_id = p.id')
			->field('pa.id as id,aj.name as name,title,phone,expect_wages,aj.degree as degree,pa.ctime as ctime')
			->where($map)
			->select();
		foreach ($list as $key => $value) {
			$list[$key]['degree'] = $state[$value['degree']];
		}
		$this->assign('header', 'apply');
		$this->assign('list', $list);
		$this->assign('_page', $_page);
		$this->display();
	}

	/*求职详情*/
	public function applydetail() {
		$state = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科', 6 => '硕士');
		$id = I('get.id');
		!empty($id) && $map['pa.id'] = array('eq', $id);
		//$map['status'] = array('eq', 1);
		$detail = M('apply_job')
			->alias('aj')
			->field('uid,apply_id,name,age,phone,sex,expect_wages,aj.degree as degree,email,title,aj.address')
			->join(C('DB_PREFIX') . 'post_apply	pa on aj.uid = pa.apply_id')
			->join(C('DB_PREFIX') . 'post p on pa.post_id = p.id')
			->where($map)
			->find();
		$detail['degree'] = $state[$detail['degree']];
		$detail['work_exp'] = M('WorkExp')->where(array('apply_id' => $detail['apply_id']))->select();
		$this->assign('header', 'apply');
		$this->assign('detail', $detail);
		$this->display();
	}

	/*删除求职*/
	public function apply_del() {
		$status = M('post_apply')->delete(I('get.id'));
		if ($status) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

	/*删除选中*/
	public function apply_delall() {
		$ids = I('ids');
		$map['uid'] = array('in', $ids);
		$map['status'] = array('eq', 1);
		$status = M('applyJob')->where($map)->setField('status', '-1');
		if (false == $status) {
			$return['errno'] = 0;
			$return['error'] = "删除失败";
		}
		$return['errno'] = 1;
		$return['error'] = "删除成功";
		$this->ajaxReturn($return);
	}

	/*简历管理*/
	public function resume() {
		$id = I('get.id');
		$name = I('post.name');
		!empty($id) && $map['apply_id'] = array('eq', $id);
		!empty($name) && $map['name'] = array('like', '%' . $name . '%');
		$map['status'] = array('eq', 1);
		$list = $this->lists('resume', $map, 'ctime desc');
		foreach ($list as $key => $value) {
			$apply_name = M('applyJob')->where(array('apply_id' => $value['uid']))->getField('name');
			$list[$key]['username'] = $apply_name;
		}
		$this->assign('list', $list);
		$this->assign('_page', $page);
		$this->display();
	}

	/*删除简历*/
	public function resume_del() {
		$id = I('get.id');
		$map['id'] = array('eq', $id);
		$map['status'] = array('eq', 1);
		$this->delete('resume', $map);
	}

	/*删除所选简历*/
	public function resume_delall() {
		$ids = I('ids');
		$map['id'] = array('in', $ids);
		$map['status'] = array('eq', 1);
		$status = M('resume')->where($map)->setField('status', -1);
		if (false == $status) {
			$return['errno'] = 0;
			$return['error'] = "删除失败";
		}
		$return['errno'] = 1;
		$return['error'] = "删除成功";
		$this->ajaxReturn($return);
	}

	/*职位管理*/
/*	public function post() {
$state = array(1 => '小学', 2 => '初中', 3 => '高中', 4 => '专科', 5 => '本科');
$apply_id = I('get.apply_id'); //求职者ID
$company_id = I('get.company_id'); //企业ID
$title = I('post.title');
!empty($apply_id) && $map['apply_id'] = array('eq', $apply_id);
!empty($company_id) && $map['apply_id'] = array('eq', $company_id);
!empty($title) && $map['title'] = array('like', '%' . $title . '%');
$map['status'] = array('eq', 1);
$list = $this->lists('post', $map);
foreach ($list as $key => $value) {
$apply_name = M('applyJob')->where(array('apply_id' => $value['uid']))->getField('name');
$company_name = M('recruit')->where(array('company_id' => $value['uid']))->getField('company_name');
$list[$key]['degree'] = $state[$value['degree']];
$list[$key]['apply_name'] = (String) $apply_name;
$list[$key]['company_name'] = (String) $company_name;
}
$this->assign('list', $list);
$this->assign('_page', $page);
$this->display();
}*/

	/*删除职位*/
	public function post_del() {
		$id = I('get.id', 0, 'intval');
		!$id && $this->error('参数错误');
		$map['id'] = array('eq', $id);
		$map['status'] = array('eq', 1);
		$map['company_id'] = array('eq', UID);
		$del = M('Post')->where($map)->delete();
		if ( $del !== false ) {
			$this->success('删除成功');
		}
		$this->error('删除失败');
	}

	/*职位选中删除*/
/*	public function post_delall() {
$ids = I('ids');
$map['id'] = array('in', $ids);
$map['status'] = array('eq', 1);
$status = M('post')->where($map)->setField('status', -1);
if (false == $status) {
$return['errno'] = 0;
$return['error'] = "删除失败";
}
$return['errno'] = 1;
$return['error'] = "删除成功";
$this->ajaxReturn($return);
}*/
}