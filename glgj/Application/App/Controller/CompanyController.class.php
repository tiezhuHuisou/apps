<?php
namespace App\Controller;
use Think\Controller;
class CompanyController extends AppController {
	/**
	 * 页面基本设置
	 * @return [type] [description]
	 */
	public function _initialize() {
		parent::_initialize();
	    
	    $this->assign('site', 'company');
	}
	/**
	 * 企业列表页
	 * @return [type] [description]
     * @param   cid  分类
     * @param   title   搜索
	 */
	public function index() {
        $title = '';        //搜索
        $cid = '';          //分类id
        $where = array();       //查询条件
        $list = array();        //返回数据

        $title = I('get.title','','strval');
        if($title){
            $where['l.title'] = array('like','%'.$title.'%');
        }
        $cid = I('get.cid',0,'intval');
        if($cid){
            $where['l.company_category_id'] = array('eq',$cid);
        }
        $where['l.status']=array('eq',1);

        $list['list']=M('Company')
            ->alias('l')
            ->join(C('DB_PREFIX').'company_link r ON l.id=r.company_id','INNER')
            ->where($where)
            ->field("l.id,l.name,r.contact_user,r.telephone,r.subphone,r.address")
            ->order('l.flags desc,l.sort desc,l.id desc')
            ->limit(0,10)
            ->select();

	    /* 公司分类 */
	    $list['category']=M('CompanyCategory')->field("id,name")->order("sort desc")->select();
	    $this->assign('list',$list);

	    /* 页面基本设置 */
	    $this->site_title 		= "企业中心";
	    $this->site_keywords 	= "企业中心";
	    $this->site_description = "企业中心";
	    
		$this->display();
	}

	/**
	 * ajax请求企业列表
	 * @return [type] [description]
	 */
	public function ajaxlist() {
        $title = '';        //搜索
        $cid = '';          //分类id
        $where = array();       //查询条件
        $list = array();        //返回数据
        $page = '';

        $page = I('get.page',1,'intval');
        $page = $page*10;
        $title = I('get.title','','strval');
        if($title){
            $where['l.title'] = array('like','%'.$title.'%');
        }
        $cid = I('get.cid',0,'intval');
        if($cid){
            $where['l.company_category_id'] = array('eq',$cid);
        }
        $where['l.status']=array('eq',1);

        $list=M('Company')
            ->alias('l')
            ->join(C('DB_PREFIX').'company_link r ON l.id=r.company_id','INNER')
            ->where($where)
            ->field("l.id,l.name,r.contact_user,r.telephone,r.subphone,r.address")
            ->order('l.flags desc,l.sort desc,l.id desc')
            ->limit($page,10)
            ->select();
            foreach ($list as &$val){
                $val['address'] = $val['address']?$val['address']:'';
            }
	    $this->ajaxReturn($list);
	}
	
	/**
	 * 企业详情页
	 * @return [type] [description]
	 */
	public function detail() {
		// $id=I('get.id',0,'intval');
		// empty($id) && $this->error('参数错误');
		// $this->utoken=I('request.uuid');
		// $prefix=C("DB_PREFIX");		
		// $l_table=$prefix."company";
		// $r_table=$prefix."company_link";
		// $map_company['l.id']=array('eq',$id);
		// $company_info=M()->table($l_table." l")
		// 				 ->join($r_table." r on l.id=r.company_id")
		// 				 ->field("l.id,l.name,l.logo,r.subphone,r.telephone,r.contact_user,r.qq")
		// 				 ->where($map_company)
		// 				 ->find();
		// $this->assign('company_info',$company_info);

		// if(!$company_info){
		// 	$this->error('公司不存在');
		// }
		// /*公司产品*/
		// $map_product['company_id']=array('eq',$id);
		// $product_list=M('ProductSale')->where($map_product)->order("id desc")->select();
		// $this->assign('product_list',$product_list);
        
		// if($this->user_id >0 ){//判断当前用户有没有收藏该内容
		//     $data['aid'] = $id;
		//     $data['uid'] = $this->user_id;
		//     $data['favorite_category'] = 3;
		//     $favorite = M("UserFavorite")->where($data)->find();
		//     if($favorite != "" || $favorite != false){
		//         $this->assign('sign',1);
		//     }
		// }
		
		/* 页面基本设置 */
        $this->site_title 		= "企业详情";
        $this->site_keywords 	= "企业详情";
        $this->site_description = "企业详情";

		$this->display();
	}

	/**
	 * 我的企业
	 * @return [type] [description]
	 */
	public function mycompany() {
		/* 页面基本设置 */
        $this->site_title 		= "我的企业";
        $this->site_keywords 	= "我的企业";
        $this->site_description = "我的企业";

		$this->display();
	}

	/**
	 * 企业信息
	 * @return [type] [description]
	 */
	public function info() {
		$id=I('get.id',0,'intval');
		empty($id) && $this->error('参数错误');
		$prefix=C("DB_PREFIX");		
		$l_table=$prefix."company";
		$r_table=$prefix."company_link";
		$map_company['l.id']=array('eq',$id);
		$company_info=M()->table($l_table." l")
						 ->join($r_table." r on l.id=r.company_id")
						 ->field("l.id,l.name,l.logo,l.business,l.summary,r.subphone,r.telephone,r.lat,r.lng")
						 ->where($map_company)
						 ->find();
		$this->assign('company_info',$company_info);

		if(!$company_info){
			$this->error('公司不存在');
		}
		/* 页面基本设置 */
        $this->site_title 		= "企业简介";
        $this->site_keywords 	= "企业简介";
        $this->site_description = "企业简介";

		$this->display();
	}
	
	/**
	 * 企业收藏
	 * @return [type] [description]
	 */
	public function favorite_add() {
	    $nid   = I('post.nid', 0, 'intval');
        $title = I('post.title');
	    if ( !$nid || !$title ) {
	    	$this->error('参数错误');
	    }
	    !$this->user_id && $this->error('请先登陆');
	    $data['aid'] = $nid;
	    $data['uid'] = $this->user_id;
	    $data['favorite_category'] = 3;
	    $favorite = M("UserFavorite")->where($data)->find();
	    if($favorite == ""){
            $data['title']   = $title;
            $data['addtime'] = time();
            $add = M("UserFavorite")->add($data);
            if ( $add !== false ) {
                $this->success('收藏成功');
            }
            $this->error('收藏失败');
        }else{
            $this->error('已收藏');
        }
	}

	/**
	 * 取消企业收藏
	 * @return [type] [description]
	 */
	public function favorite_del() {
	    $nid   = I('post.nid', 0, 'intval');
	    !$nid && $this->error('参数错误');
	    !$this->user_id && $this->error('请先登陆');
	    $where['aid'] = array('eq', $nid);
	    $where['uid'] = array('eq', $this->user_id);
	    $where['favorite_category'] = array('eq', 3);
	    $del = M("UserFavorite")->where($where)->delete();
        if ( $del !== false ) {
            $this->success('取消收藏成功');
        }
        $this->error('取消收藏失败');
	}
}