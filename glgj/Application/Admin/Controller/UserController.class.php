<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends AdminController{
	
    protected function _initialize() {
    	parent::_initialize();
        $this->assign('site','user');
    }
	/**
	 * 管理员管理首页
	 * @return [type] [description]
	 */
	public function index(){
		$map['user_id']=array('gt',1);
	    $order = 'add_time desc';
	    $list = $this->lists('AdminUser',$map,$order,'');
	    $this->assign('list',$list);
	    /*页面基本设置*/
	    $this->site_title="管理员管理-管理员列表";
	    $this->assign('left','index');
		$this->display();
	}

	/**
	 * 添加管理员
	 * @return [type] [description]
	 */
	public function add(){
		$id = I('user_id');
	    $user = D('AdminUser');
	    if(IS_POST){
	        $status = $user->update();
	        if($status){
	            if($id){
	                $this->success('修改成功','?g=admin&m=user');
	            }else{
	            $this->success('添加成功','?g=admin&m=user');
	            }
	        }else{
	            $errorInfo=$user->getError();
	            $this->error($errorInfo);
	        }
	    }
	    $list = M('AdminGroup')->select();
	    $detail = $user->getAdminUserInfo(array('user_id'=>$id));
	    $this->assign('list',$list);
	    $this->assign('detail',$detail);
	    /*页面基本设置*/
	    $this->site_title="管理员管理-编辑管理员";
	    $this->assign('left','index');
		$this->display();
	}
	/**
	 * 删除管理员
	 * @author 83961014@qq.com
	 */
	public function del(){
	    $id = I('id');
	    $user = D('AdminUser');
	    $condition['user_id'] = $id;
	    $return = $user->delAdminUser($condition);
	    if($return != false){
	        $this->success('删除成功');
	    }else{
	        $this->error('删除失败');
	    }
	}
	/**
	 * 管理员批量删除
	 * @author 83961014@qq.com
	 */
	public function delall(){
	    if (IS_POST){
	        $ids 		= I('ids');
	        $ids 		= implode(',', $ids);
	        $user = D('AdminUser');
	        $condition['user_id'] = array('in',$ids);
	        $tem = $user->delAdminUser($condition);
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
	/**
	 * 管理员分组首页
	 * @return [type] [description]
	 */
	public function group(){
	    $order = 'addtime desc';
	    $list = $this->lists('AdminGroup','',$order,'');
	    $this->assign('list',$list);
	    /*页面基本设置*/
	    $this->site_title="管理员管理-管理员分组列表";
	    $this->assign('left','group');
	    $this->display();
	}
	
	/**
	 * 添加管理员分组
	 * @return [type] [description]
	 */
	public function group_add(){
	    $id = I('id');
	    $user = D('AdminGroup');
	    if(IS_POST){
	        $status = $user->update();
	        if($status){
	            if($id){
	                $this->success('修改成功','?g=admin&m=user&a=group');
	            }else{
	            $this->success('添加成功','?g=admin&m=user&a=group');
	            }
	        }else{
	            $errorInfo=$user->getError();
	            $this->error($errorInfo);
	        }
	    }
	    $detail = $user->getAdminGroupInfo(array('gid'=>$id));
	    $this->assign('detail',$detail);

	    /*权限列表*/
	    $rule_list=M('AdminRule')->where(array('status'=>1))->order('sort DESC')->select();
	    $this->assign('rule_list',$rule_list);
	    /*页面基本设置*/
	    $this->site_title="管理员管理-编辑管理员分组";
	    $this->assign('left','group');
	    $this->display();
	}
	/**
	 * 删除管理员分组
	 * @author 83961014@qq.com
	 */
	public function group_del(){
	    $id = I('id');
	    $user = D('AdminGroup');
	    $condition['gid'] = $id;
	    $return = $user->delAdminGroup($condition);
	    if($return != false){
	        $this->success('删除成功');
	    }else{
	        $this->error('删除失败');
	    }
	}
	/**
	 * 管理员分组批量删除
	 * @author 83961014@qq.com
	 */
	public function group_delall(){
	    if (IS_POST){
	        $ids 		= I('ids');
	        $ids 		= implode(',', $ids);
	        $user = D('AdminGroup');
	        $condition['gid'] = array('in',$ids);
	        $tem = $user->delAdminGroup($condition);
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
	
	/**
	 * 权限管理首页
	 * @return [type] [description]
	 */
	public function permissions(){
		if(IS_POST){
			$status=D('AdminRule')->update();
			if($status){
				$this->success('添加规则成功');
			}else{
				$this->error('添加规则失败');
			}
		}else{
			/*页面基本设置*/
			$this->site_title="管理员管理-权限管理";
			$this->assign('left','permissions');
			$this->display();
		}
	    
	}
}