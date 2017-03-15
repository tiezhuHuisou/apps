<?php
namespace App\Controller;

use Think\Controller;

class NewsController extends AppController {
    /**
     * 页面基本设置
     * @return [type] [description]
     */
    public function _initialize() {
        parent::_initialize();
        $this->assign('site', 'news');
    }

    /**
     * 资讯列表页
     * @return [type] [description]
     * @param cid  分类id
     * @param title 搜索
     */
    public function index() {
        $categoryId = '';      //分类id
        $title = '';   //搜索
        $list = array();        //返回数据
        $map_banner = array();   //轮播图筛选条件
        $map_article = array();     //筛选条件
        /*首页轮播图*/
        $map_banner['tid'] = array('eq', 1);
        $list['banner'] = M('Jdpic')->where($map_banner)->field('id,url,thumbnail')->order('listorder,addtime desc')->select();

        /*文章分类*/
        $list['category'] = M('Artcategory')->field("cid,pid,cname")->order("cid desc")->select();

        /*文章列表*/
        if (IS_GET) {
            $title = I('title', '', 'strval');
            if ($title) {
                $map_article['title'] = array('like', '%' . $title . '%');
            }
            $categoryId = I('get.cid', '0', 'intval');
            if ($categoryId) {
                $map_article['categoryid'] = array('eq', $categoryId);
            }
        }

        $map_article['is_display'] = array('eq', 1);
        $list['article'] = M('Article')->where($map_article)->field("id,title,short_title,image,FROM_UNIXTIME(addtime,'%Y-%m-%d') addtime")->order('flags desc,sort desc,id desc')->limit(0, 10)->select();
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->activity_type = $categoryId;
        $this->site_title = "资讯中心";
        $this->site_keywords = "资讯中心";
        $this->site_description = "资讯中心";
        $this->display();
    }

    /**
     * ajax请求资讯列表页
     * @param cid   分类id
     * @param  title 搜索值
     */
    public function ajaxlist() {
        $title = '';
        $categoryId = '';
        $where = array();
        $list = array();
        $page = '';

        $page = I('get.page', '1', 'intval');
        $page = $page * 10;

        $title = I('title', '', 'strval');
        if ($title) {
            $where['title'] = array('like', '%' . $title . '%');
        }
        $categoryId = I('get.cid', 0, 'intval');
        if ($categoryId) {
            $where['categoryid'] = array('eq', $categoryId);
        }
        $where['is_display'] = array('eq', 1);
        $list = M('Article')->where($where)->field("id,title,short_title,image,FROM_UNIXTIME(addtime,'%Y-%m-%d') addtime")->order('flags desc,sort desc,id desc')->limit($page, 10)->select();
        $this->ajaxReturn($list);
    }

    /**
     *
     * 资讯详情页
     * @return [type] [description]
     */
    public function detail() {
        $id = I('get.id', 0, 'intval');
        $this->utoken = I('request.uuid');
        empty($id) && $this->error('参数错误');
        $article = M('Article');
        $map['id'] = array('eq', $id);
        $info = $article->where($map)->find();
        if (!$info) {
            $this->error('文章不存在');
        }

        if ($this->user_id) {//判断当前用户有没有收藏该内容
            $data['aid'] = $id;
            $data['uid'] = $this->user_id;
            $data['favorite_category'] = 1;
            $favorite = M("UserFavorite")->where($data)->find();
            if ($favorite != "" || $favorite != false) {
                $this->assign('sign', 1);
            }
        }

        /*上一篇*/
        $map_prev['id'] = array('gt', $id);
        $prev_info = $article->field('id,title')->where($map_prev)->order("id")->find();

        /*下一篇*/
        $map_next['id'] = array('lt', $id);
        $next_info = $article->field('id,title')->where($map_next)->order("id desc")->find();

        $this->assign('info', $info);
        $this->assign('prev_info', $prev_info);
        $this->assign('next_info', $next_info);

        /* 页面基本设置 */
        $this->site_title = "资讯详情";
        $this->site_keywords = $info['seo_keywords'];
        $this->site_description = $info['seo_description'];

        $this->display();
    }

    /**
     * 资讯收藏
     * @return [type] [description]
     */
    public function favorite_add() {
        $nid = I('post.nid', 0, 'intval');
        $title = I('post.title');
        if (!$nid || !$title) {
            $this->error('参数错误');
        }
        !$this->user_id && $this->error('请先登陆');
        $data['aid'] = $nid;
        $data['uid'] = $this->user_id;
        $data['favorite_category'] = 1;
        $favorite = M("UserFavorite")->where($data)->find();
        if ($favorite == "") {
            $data['title'] = $title;
            $data['addtime'] = time();
            $add = M("UserFavorite")->add($data);
            if ($add !== false) {
                $this->success('收藏成功');
            }
            $this->error('收藏失败');
        } else {
            $this->error('已收藏');
        }
    }

    /**
     * 取消资讯收藏
     * @return [type] [description]
     */
    public function favorite_del() {
        $nid = I('post.nid', 0, 'intval');
        !$nid && $this->error('参数错误');
        !$this->user_id && $this->error('请先登陆');
        $where['aid'] = array('eq', $nid);
        $where['uid'] = array('eq', $this->user_id);
        $where['favorite_category'] = array('eq', 1);
        $del = M("UserFavorite")->where($where)->delete();
        if ($del !== false) {
            $this->success('取消收藏成功');
        }
        $this->error('取消收藏失败');
    }
}