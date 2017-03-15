<?php
namespace App\Controller;
use Think\Controller;
class MoreController extends AppController {
	/**
	 * 页面基本设置
	 * @return [type] [description]
	 */
	public function _initialize() {
		parent::_initialize();		
	    $this->assign('site','member');
	}

	/**
	 * 关于APP首页
	 * @return [type] [description]
	 */
	public function index() {
	    $conf = M('Conf');
	    $url = $conf->where(array('name'=>'url'))->find();
	    $this->assign('url',$url['value'].'/?g=member');//网站
	    $phone = $conf->where(array('name'=>'companphone'))->find();
	    $this->assign('phone',$phone['value']);//电话
	    //版权信息
	    $other = M('Conf')->where(array('name'=>'other'))->getField('value');
	    $this->assign('other',$other);
		/* 页面基本设置 */
        $this->site_title 		= "关于APP";
        $this->site_keywords 	= "关于APP";
        $this->site_description = "关于APP";

		$this->display();
	}

	/**
	 * 评价
	 * @return [type] [description]
	 */
	public function comment() {
	    $id = I('id','','intval');
	    if(!$id){
	        $this->error('参数错误');
	    }
	    if(IS_POST){
	        if(!$this->user_id){
	            $return['errno'] 		= 1;
	            $return['error'] 		= "请先登录";
	            $this->ajaxReturn($return);
	            exit;
	        }
	        $content = I('content');
	        if(empty($content)){
	            $return['errno'] 		= 1;
	            $return['error'] 		= "请输入反馈内容";
	            $this->ajaxReturn($return);
	            exit;
	        }
	        $data['uid'] = $this->user_id;
	        $data['goodid'] = $id;
	        $data['pid'] = 0;
	        $data['type'] = 2;
	        $tmp = M('Review')->where($data)->find();
	        if($tmp){
	            $data['pid'] = $tmp['id'];
	        }else{
	            unset($data['pid']);
	        }
	        $data['content'] = $content;
	        $data['addtime'] = time();
	         
	        $sug = M('Review')->add($data);
	        if($sug){
	            $return['errno'] 		= 0;
	            $return['error'] 		= "评价成功";
	            $this->ajaxReturn($return);
	        }else{
	            $return['errno'] 		= 1;
	            $return['error'] 		= "评价失败";
	            $this->ajaxReturn($return);
	        }
	    }
	    $order = 'addtime desc';
	    $condition['goodid'] = $id;
	    $condition['pid'] = 0;
	    $condition['type'] = 2;
	    $condition['state'] = 1;
	    $list = $this->lists('review',$condition,$order);
	    $review = M('Review');
	    $member = M('Member');
	    foreach ($list as $key=>$val){
	        $user = $member->where(array('uid'=>$val['uid']))->find();
	        $list[$key]['uname'] = $user['nickname'];
	        $list[$key]['reply'] = $review->where(('pid = '.$val['id']))->select();
	        foreach ($list[$key]['reply'] as $key1=>$val1){
	            $user = $member->where(array('uid'=>$val1['uid']))->find();
	            $list[$key]['reply'][$key1]['uname'] = $user['nickname'];
	        }
	    }
	    $this->assign('list',$list);
		/* 页面基本设置 */
        $this->site_title 		= "评价";
        $this->site_keywords 	= "评价";
        $this->site_description = "评价";

		$this->display();
	}

	/**
	 * 意见反馈
	 * @return [type] [description]
	 */
	public function opinion() {
	    if(IS_AJAX){
	        $data['content'] = I('content');
	        if(empty($data['content'])){
	            $return['errno'] 		= 1;
	            $return['error'] 		= "请输入反馈内容";
	            $this->ajaxReturn($return);
	            exit;
	        }
	        $data['phone'] = I('phone');
	        if(!preg_match("/1[3458]{1}\d{9}$/",$data['phone'])){
	            $return['errno'] 		= 1;
	            $return['error'] 		= "请输入正确的手机号码";
	            $this->ajaxReturn($return);
	            exit;
	        }
	        $data['addtime'] = time();
	        $data['user_id'] = $this->user_id;
	        $sug = M('Suggestion')->add($data);
	        if($sug){
	            $return['errno'] 		= 0;
	            $return['error'] 		= "发送成功";
	            $this->ajaxReturn($return);
	            exit;
	        }else{
	            $return['errno'] 		= 1;
	            $return['error'] 		= "发送失败";
	            $this->ajaxReturn($return);
	            exit;
	        }
	    }
		/* 页面基本设置 */
        $this->site_title 		= "意见反馈";
        $this->site_keywords 	= "意见反馈";
        $this->site_description = "意见反馈";

		$this->display();
	}

	/**
	 * 关于我们
	 * @return [type] [description]
	 */
	public function copyright() {
		$map['linkid']=array('eq','link-aboutus');
		$info=M('Page')->where($map)->find();
		$info['content'] = stripslashes($info['content']);
		$count = preg_match_all('/\{\#([\w]+)\#\}/isU', $info['content'], $match);
		$content = $info['content'];
		if($count>0) {
		    $where_sql = implode(",", $match[1]);
		    $where['name'] = array('in',$where_sql);
		    $sql 	= M('Conf')->where($where)->select();
		    foreach ($sql as $key=>$val){
		        $content = preg_replace('/\{\#'.$val['name'].'\#\}/isU',$val['value'], $content);
		    }
		}
		$content = str_replace('&gt;','>',$content);
		$content = str_replace('&lt;','<',$content);
		$content = str_replace('&amp;','&',$content);
		$info['content'] = $content;
		$this->assign('info',$info);
		/* 页面基本设置 */
        $this->site_title 		= "关于我们";
        $this->site_keywords 	= "关于我们";
        $this->site_description = "关于我们";

		$this->display();
	}

	/**
	 * 商家管理说明
	 * @return [type] [description]
	 */
	public function merchant() {
		$conf = M('Conf');
	    $url = $conf->where(array('name'=>'url'))->find();
	    $this->assign('url',$url['value'].'/member');//网站
		/* 页面基本设置 */
        $this->site_title 		= "商家管理说明";
        $this->site_keywords 	= "商家管理说明";
        $this->site_description = "商家管理说明";

		$this->display();
	}

	/**
     * 商务合作
     */
	public function cooperation(){
        $info = M('Page')->where(array('linkid'=>'link-cooperation'))->getField('content');
        $this->assign('info',$info);
        /* 页面基本设置 */
        $this->site_title 		= "商务合作";
        $this->site_keywords 	= "商务合作";
        $this->site_description = "商务合作";
        $this->display();
    }
}