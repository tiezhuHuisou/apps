<?php
namespace App\Controller;

use Think\Controller;

class ProductController extends AppController {
    /**
     * 页面基本设置
     * @return [type] [description]
     */
    public function _initialize() {
        parent::_initialize();
        $this->assign('site', 'product');
    }

    /**
     * 产品列表页
     * @return [type] [description]
     */
    public function index() {
        $list = array();
        /*公告*/
        $list = M('Announce')->where(array('flags' => 'c'))->order('sort desc')->limit(8)->field('id,desc')->select();
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = "产品中心";
        $this->site_keywords = "产品中心";
        $this->site_description = "产品中心";
        $this->display();
    }

    /**
     * 公告列表
     */
    public function announce() {
        $title = '';    //搜索
        $where = array();   //筛选条件
        $list = array();    //返回数据

        $title = I('get.title', '', 'strval');
        if ($title) {
            $where['title|desc'] = array('like', '%$title%');
        }
        $where['is_display'] = array('eq', 1);
        $list = M('Announce')->where($where)->field('id,title,addtime')->order('flags desc,sort desc')->limit(10)->select();

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "公告列表";
        $this->site_keywords = "公告列表";
        $this->site_description = "公告列表";
        $this->display();
    }

    /**
     * ajax请求产品列表页
     * @return [type] [description]
     */
    public function ajaxAnnounce() {
        $title = '';    //搜索
        $where = array();   //筛选条件
        $list = array();    //返回数据
        $page = '';         //翻页
        $page = $page * 10;

        $title = I('get.title', '', 'strval');
        if ($title) {
            $where['title|desc'] = array('like', '%$title%');
        }
        $where['is_display'] = array('eq', 1);
        $list = M('Announce')->where($where)->field('id,title,addtime')->order('flags desc,sort desc')->limit($page, 10)->select();

        foreach ($list as &$val) {
            $val['addtime'] = date('Y-m-d', $val['addtime']);
        }
        $this->ajaxReturn($list);
    }


    /**
     * 公告详情
     * @param id
     */
    public function announceDetail() {
        $id = '';   //公告id
        $list = array();
        $id = I('get.id', '0', 'intval');
        if (!$id) {
            $this->error('参数错误');
        }
        $list = M('Announce')->where(array('id' => $id))->find();
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "公告详情";
        $this->site_keywords = "公告详情";
        $this->site_description = "公告详情";
        $this->display();
    }

    /**
     * 物流信息列表（不知道对应哪个页面）
     */

    /**
     * 物流信息列表下拉加载（不知道对应哪个页面）
     */

    /**
     * 货车信息列表
     * type 1->仓储主,2->个人货主,3->企业货主,4->个人车主,5->企业车主表,
     */
    public function truckList() {
        $where = array();   //搜索条件
        $list = array();     //数据列表
        $region = array();  //省市区数据
        $uids = array();    //发布者uid
        $master = array();   //各种主的联系方式
        $master4 = array();   //改变键后 各种主的联系方式个人
        $master5 = array();   //改变键后 各种主的联系方式企业

        $where['l.status'] = array('eq', 1);
        $list = M('Truck')->alias('l')
            ->join(C('DB_PREFIX') . 'member r ON l.uid=r.uid', 'LEFT')
            ->join(C('DB_PREFIX') . 'master m ON l.uid=m.uid', 'LEFT')
            ->where($where)
            ->field('r.head_pic,l.truck_no,l.truck_type,l.truck_length,l.source_type,l.provincen,
l.cityn,l.countryn,l.provincen2,l.cityn2,l.countryn2,l.uid,l.master')
            ->limit(10)
            ->select();
        /*获得发布着号码。因为老版问题，有些没有号码*/
        $uids = array_unique(array_column($list, 'uid'));
        $uids = implode(',', $uids);
        unset($where);

        $where['uid'] = array('in', $uids);
        $where['type'] = array('in', '4,5');
        $master = M('Master')->where($where)->field('uid,type,phone,name')->select();
        if ($master) {
            foreach ($master as $key => $val) {
                if ($val['type'] == 4) {
                    $master4[$val['uid']] = $val();
                }
                if ($val['type'] == 5) {
                    $master5[$val['uid']] = $val();
                }
            }
        }

        $region = $this->regions();
        foreach ($list as &$val) {
            $val['after'] = $region[$val['provincen']] . $region[$val['cityn']] . $region[$val['countryn']];
            $val['end'] = $region[$val['provincen2']] . $region[$val['cityn2']] . $region[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
            if ($val['master'] == 4) {
                $val['name'] = $master4[$val['uid']]['name'] ? $master4[$val['uid']]['name'] : '';
                $val['phone'] = $master4[$val['uid']]['phone'] ? $master4[$val['uid']]['phone'] : '';
            }
            if ($val['master'] == 5) {
                $val['name'] = $master5[$val['uid']]['name'] ? $master5[$val['uid']]['name'] : '';
                $val['phone'] = $master5[$val['uid']]['phone'] ? $master5[$val['uid']]['phone'] : '';
            }
        }

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "货车信息";
        $this->site_keywords = "货车信息";
        $this->site_description = "货车信息";
        $this->display();
    }
    /**
     * 货车信息下拉加载
     */

    /**
     * 货物信息列表
     */

    /**
     * 货物信息下拉加载
     */

    /**
     * 仓储信息列表
     */

    /**
     * 仓储信息下拉加载
     */


//
//    /**
//     * 产品详情页
//     * @return [type] [description]
//     */
//    public function detail() {
//        $id = I('get.id', 0, 'intval');
//        empty($id) && exit('参数错误');
//        $this->utoken = I('request.uuid');
//        $prefix = C('DB_PREFIX');
//        $l_table = $prefix . "product_sale";
//        $r_table = $prefix . "company";
//
//        $map['l.id'] = array('eq', $id);
//        $product_info = M()->table($l_table . " l")
//            ->join($r_table . " r on l.company_id=r.id", "left")
//            ->field("l.*,r.name as company_name,r.logo as company_logo")
//            ->where($map)
//            ->find();
//        $this->assign('product_info', $product_info);
//
//        /*商品轮播图*/
//        $map_pic['product_id'] = array('eq', $id);
//        $pic_list = M('ProdcutPicture')->where($map_pic)->select();
//        $this->assign('pic_list', $pic_list);
//        /*获取微信config参数*/
//        /*$appid = '';
//        $appsecret = '';
//        $result = $this->http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
//
//        $json = json_decode($result,true);
//
//        $this->access_token = $json['access_token'];
//        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->access_token;
//        $res = json_decode ( $this->http_get ( $url ) );
//        $ticket = $res->ticket;
//        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        $signPackage = $this->getSignPackage($appid,$ticket,$url,time(),'abc');
//        $this->assign('signPackage',$signPackage);*/
//
//        if ($this->user_id > 0) {//判断当前用户有没有收藏该内容
//            $data['aid'] = $id;
//            $data['uid'] = $this->user_id;
//            $data['favorite_category'] = 2;
//            $favorite = M("UserFavorite")->where($data)->find();
//            if ($favorite != "" || $favorite != false) {
//                $this->assign('sign', 1);
//            }
//        }
//        /* 评论列表 */
//        $order = 'addtime desc';
//        $condition['goodid'] = $id;
//        $condition['pid'] = 0;
//        $list = $this->lists('review', $condition, $order);
//        $review = M('Review');
//        foreach ($list as $key => $val) {
//            $list[$key]['reply'] = $review->where(('pid = ' . $val['id']))->select();
//        }
//        $this->assign('list', $list);
//        cookie('order_sign', null);
//        /* 页面基本设置 */
//        $this->site_title = "产品详情";
//        $this->site_keywords = "产品详情";
//        $this->site_description = "产品详情";
//
//        $this->display();
//    }
//
//    /**
//     * 商品评价
//     * @return [type] [description]
//     */
//    public function comment() {
//        $id = I('id', '', 'intval');
//        if (!$id) {
//            $this->error('参数错误');
//        }
//        if (IS_POST) {
//            if (!$this->user_id) {
//                $this->error('请先登录');
//            }
//            $content = I('content');
//            if (empty($content)) {
//                $this->error('请输入评价内容');
//            }
//            $data['uid'] = $this->user_id;
//            $data['goodid'] = $id;
//            $data['pid'] = 0;
//            $data['type'] = 1;
//            $tmp = M('Review')->where($data)->find();
//            if ($tmp) {
//                $data['pid'] = $tmp['id'];
//            } else {
//                unset($data['pid']);
//            }
//            $data['content'] = $content;
//            $data['addtime'] = time();
//
//            $sug = M('Review')->add($data);
//            if ($sug) {
//                $this->success('评价成功');
//            } else {
//                $this->error('评价失败');
//            }
//        }
//        $order = 'addtime desc';
//        $condition['goodid'] = $id;
//        $condition['pid'] = 0;
//        $condition['type'] = 1;
//        $condition['state'] = 1;
//        $list = M('Review')->where($condition)->order($order)->select();
//        $review = M('Review');
//        $user = M('Member');
//        foreach ($list as $key => $val) {
//            $list[$key]['reply'] = $review->where(('pid = ' . $val['id']))->select();
//            $list[$key]['uname'] = $user->where(('uid = ' . $val['uid']))->getField('nickname');
//        }
//        $this->assign('list', $list);
//        /* 页面基本设置 */
//        $this->site_title = "商品评价";
//        $this->site_keywords = "商品评价";
//        $this->site_description = "商品评价";
//
//        $this->display();
//    }
//
//    /**
//     * 产品收藏
//     * @return [type] [description]
//     */
//    public function favorite_add() {
//        $nid = I('post.nid', 0, 'intval');
//        $title = I('post.title');
//        if (!$nid || !$title) {
//            $this->error('参数错误');
//        }
//        !$this->user_id && $this->error('请先登陆');
//        $data['aid'] = $nid;
//        $data['uid'] = $this->user_id;
//        $data['favorite_category'] = 2;
//        $favorite = M("UserFavorite")->where($data)->find();
//        if ($favorite == "") {
//            $data['title'] = $title;
//            $data['addtime'] = time();
//            $add = M("UserFavorite")->add($data);
//            if ($add !== false) {
//                $this->success('收藏成功');
//            }
//            $this->error('收藏失败');
//        } else {
//            $this->error('已收藏');
//        }
//    }
//
//    /**
//     * 取消产品收藏
//     * @return [type] [description]
//     */
//    public function favorite_del() {
//        $nid = I('post.nid', 0, 'intval');
//        !$nid && $this->error('参数错误');
//        !$this->user_id && $this->error('请先登陆');
//        $where['aid'] = array('eq', $nid);
//        $where['uid'] = array('eq', $this->user_id);
//        $where['favorite_category'] = array('eq', 2);
//        $del = M("UserFavorite")->where($where)->delete();
//        if ($del !== false) {
//            $this->success('取消收藏成功');
//        }
//        $this->error('取消收藏失败');
}
