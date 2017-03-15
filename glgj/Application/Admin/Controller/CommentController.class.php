<?php
namespace Admin\Controller;
use Think\Controller;
class CommentController extends AdminController{
	
    protected function _initialize() {
    	parent::_initialize();
        $this->assign('site','more');
        $this->assign('left','comment');
    }
	/**
	 * 留言管理首页
	 * @return [type] [description]
	 */
	public function index(){
	    $order = 'addtime desc';
	    $list = $this->lists('suggestion','',$order,'');
	    $member = M('Member');
	    foreach ($list as &$val){
	        $tmp = $member->where(array('uid'=>$val['user_id']))->find();
	        $val['sex'] = $tmp['sex'];
	        $val['email'] = $tmp['email'];
	        $val['name'] = $tmp['name'];
	        $val['mphone'] = $tmp['mphone'];
	    }
	    $this->assign('list',$list);
	    /*页面基本设置*/
	    $this->site_title="留言管理-留言列表";
		$this->display();
	}
	/**
	 * 留言删除
	 * @author 83961014@qq.com
	 */
	public function del(){
	    $id = I('id');
	    $review = M('Suggestion');
	    $condition['id'] = $id;
	    $return = $review->where($condition)->delete();
	    if($return != false){
	        $this->success('删除成功');
	    }else{
	        $this->error('删除失败');
	    }
	}
	/**
	 * 留言批量删除
	 * @author 83961014@qq.com
	 */
	public function delall(){
	    if (IS_POST){
	        $ids 		= I('ids');
	        $ids 		= implode(',', $ids);
	        $review = M('Suggestion');
	        $condition['id'] = array('in',$ids);
	        $tem = $review->where($condition)->delete();
	        if($tem != false){
	            $return['errno'] 		= 0;
	            $return['error'] 		= "删除成功";
	            $this->ajaxReturn($return);
	        }else{
	            $return['errno'] 		= 1;
	            $return['error'] 		= "删除失败";
	            $this->ajaxReturn($return);
	        }
	    }
	}
}