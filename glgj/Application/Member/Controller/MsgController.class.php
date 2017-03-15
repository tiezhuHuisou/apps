<?php
namespace Member\Controller;
use Think\Controller;
class MsgController extends MemberController {
	public function _initialize(){
	    parent::_initialize();
		$this->assign('site', 'msg');
	}

	/**
	 * 系统消息
	 * @return [type] [description]
	 */
	public function index(){
	    $order = 'addtime desc';
	    $condition['to_user'] = UID;
	    $condition['status'] = 1;
	    $list = $this->lists('UserMessage',$condition,$order);
	    $this->assign('list',$list);
		$this->site_title = '系统消息';
		$this->assign('header', 'index');
		$this->display();
	}
	/**
	 * 系统消息删除
	 * @author 83961014@qq.com
	 */
	public function message_del(){
	    $id = I('id');
	    $group = M('UserMessage');
	    $condition['id'] = $id;
	    $return = $group->where($condition)->save(array('status'=>-1));
	    if($return != false){
	        $this->success('删除成功','?g=member&m=msg');
	    }else{
	        $this->error('删除失败','?g=member&m=msg');
	    }
	}
	/**
	 * 客户询盘
	 * @return [type] [description]
	 */
	public function customer(){
		$this->site_title = '系统消息';
		$this->assign('header', 'customer');
		$this->display();
	}

	/**
	 * 系统消息详情
	 * @return [type] [description]
	 */
	public function detail(){
		$this->site_title = '系统消息详情';
		$this->assign('header', 'index');
		$this->display();
	}

	/**
	 * 客户询盘详情
	 * @return [type] [description]
	 */
	public function customer_detail(){
		$this->site_title = '客户询盘详情';
		$this->assign('header', 'customer');
		$this->display();
	}
}