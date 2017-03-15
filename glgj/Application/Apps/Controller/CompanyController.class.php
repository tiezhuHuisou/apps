<?php
namespace Apps\Controller;

use Think\Controller;

class CompanyController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 企业分类
     */
    public function category() {
        if (IS_GET) {
            $list['category'] = M('CompanyCategory')->field('id category_id,name,logo')->where(array('status' => 1))->select();
            array_unshift($list['category'], array('category_id' => '', 'name' => '全部', 'logo' => ''));
            $list['category'] = $this->getAbsolutePath($list['category'], 'logo', C('HTTP_APPS_IMG') . 'category_default.png');
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 企业列表
     * @param page
     * @param title
     * @param cid
     */
    public function index() {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = ($page - 1) * 10;
            $title = I('get.title');
            $cid = I('get.cid', 0, 'intval');
            !empty($title) && $where['l.name'] = array('like', '%' . $title . '%');
            $cid > 0 && $where['l.company_category_id'] = array('eq', $cid);
            $where['l.status'] = array('eq', 1);
            $list['company_list'] = M('Company')->alias('l')
                ->join(C('DB_PREFIX') . 'company_link r ON l.id=r.company_id', 'LEFT')
                ->field('l.id company_id,l.name,l.logo,r.contact_user,r.telephone,r.subphone,r.point,r.address')
                ->where($where)
                ->order('l.flags DESC,l.sort DESC,l.modify_time DESC')
                ->limit($page, 10)
                ->select();
            $list['company_list'] = $this->getAbsolutePath($list['company_list'], 'logo', C('HTTP_APPS_IMG') . 'company_default.png');

            foreach ($list['company_list'] as &$value) {
                if ($value['point']) {
                    $value['lat'] = strstr($value['point'], ',', 'TRUE');
                    $value['lng'] = substr(strstr($value['point'], ','), 1);
                } else {
                    $value['lat'] = '0';
                    $value['lng'] = '0';
                }
                unset($value['point']);
                $value['name'] = $value['name'] ? $value['name'] : '';
                $value['contact_user'] = $value['contact_user'] ? $value['contact_user'] : '';
                $value['telephone'] = $value['telephone'] ? $value['telephone'] : '';
                $value['subphone'] = $value['subphone'] ? $value['subphone'] : '';
                $value['address'] = $value['address'] ? $value['address'] : '';
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }


//    /**
//     * 企业主页
//     */
//    public function detail() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            $page = I('get.page', 1, 'intval');
//            $page = ($page - 1) * 10;
//            $type = I('get.type', '', 'strval');
//            !$id && $this->ajaxJson('70000', '参数错误');
//            $httpOrigin = C('HTTP_ORIGIN');
//
//            /* 查询企业信息 */
//            $where['l.id'] = array('eq', $id);
//            $list['company_info'] = M('Company')
//                ->alias('l')
//                ->join(C('DB_PREFIX') . 'user_favorite r ON l.id = r.aid', 'LEFT')
//                ->field('l.id company_id,l.user_id,l.name,l.logo,l.bgimg,l.business,l.status,COUNT(r.id) collect_count')
//                ->where($where)
//                ->group('l.id')
//                ->find();
//            !$list['company_info'] && $this->ajaxJson('70000', '企业不存在');
//            switch ($list['company_info']['status']) {
//                case '0':
//                    $this->ajaxJson('70000', '企业被停封');
//                    break;
//                case '2':
//                    $this->ajaxJson('70000', '企业审核中');
//                    break;
//                case '3':
//                    $this->ajaxJson('70000', '企业审核被拒');
//                    break;
//                default:
//                    break;
//            }
//            /* 图片处理 */
//            $list['company_info']['logo'] = $this->getAbsolutePath($list['company_info']['logo'], '', $httpOrigin . 'company_default.png');
//            $list['company_info']['bgimg'] = $this->getAbsolutePath($list['company_info']['bgimg'], '', $httpOrigin . 'company_bgimg.png');
//            /* 主营行业处理 */
//            if ($list['company_info']['business']) {
//                $list['company_info']['business'] = '主营行业：' . $list['company_info']['business'];
//            } else {
//                $list['company_info']['business'] = '';
//            }
//            /* 收藏人数处理 */
//            if ($list['company_info']['collect_count'] > 9999) {
//                $list['company_info']['collect_count'] = $list['company_info']['collect_count'] / 10000 . '万';
//            }
//            $list['company_info']['collect_count'] .= '关注';
//
//
//            /* 企业导航 */
//            // $list['company_navigation'] = array(
//            //     array('title' => '公司简介', 'img' => $httpOrigin . '/Public/static/images/company_introduction.png', 'company_flag' => 'company_introduction'),
//            //     array('title' => '产品信息', 'img' => $httpOrigin . '/Public/static/images/company_product.png', 'company_flag' => 'company_product'),
//            //     array('title' => '求购信息', 'img' => $httpOrigin . '/Public/static/images/company_need.png', 'company_flag' => 'company_need'),
//            //     array('title' => '公司相册', 'img' => $httpOrigin . '/Public/static/images/company_album.png', 'company_flag' => 'company_album'),
//            //     array('title' => '地图导航', 'img' => $httpOrigin . '/Public/static/images/company_map.png', 'company_flag' => 'company_map'),
//            //     array('title' => '联系我们', 'img' => $httpOrigin . '/Public/static/images/company_contact.png', 'company_flag' => 'company_contact')
//            // );
//
//            /* 查询活动信息 */
//            $where_activity['status'] = array('eq', 1);
//            $where_activity['start_time'] = array('lt', time());
//            $where_activity['end_time'] = array('gt', time());
//            $where_activity['activity_type'] = array('gt', 0);
//            $activityList = M('Activity')->where($where_activity)->getField('activity_type', true);
//
//            /* 企业优惠券 */
//            $where_coupon['status'] = array('eq', 1);
//            $where_coupon['receive_start'] = array('elt', time());
//            $where_coupon['receive_end'] = array('egt', time());
//            $where_coupon['company_id'] = array('eq', $id);
//            $where_coupon['_string'] = '(start_time = 0 AND end_time = 0) OR (start_time = 0 AND end_time > ' . time() . ') OR (start_time < ' . time() . ' AND end_time = 0) OR (start_time < ' . time() . ' AND end_time > ' . time() . ')';
//            $list['company_coupon'] = M('Coupon')->field('id coupon_id,money,condition,end_time,company_id')->where($where_coupon)->order('id DESC')->select();
//            if ($list['company_coupon']) {
//                /* 优惠券背景颜色 */
//                $couponBgColor = array('#ffaf50', '#ff9d60', '#ff7a51');
//                foreach ($list['company_coupon'] as $ck => $cv) {
//                    /* 使用限制 */
//                    if ($cv['condition'] == 0) {
//                        $list['company_coupon'][$ck]['condition'] = '无消费条件限制';
//                    } else {
//                        $list['company_coupon'][$ck]['condition'] = '本店满' . $cv['condition'] . '可用';
//                    }
//                    /* 优惠券使用期限 */
//                    $list['company_coupon'][$ck]['end_time'] = $cv['end_time'] ? date('Y-m-d', $cv['end_time']) . '前有效' : '无使用时间限制';
//                    /* 优惠券背景颜色 */
//                    $list['company_coupon'][$ck]['bgcolor'] = $couponBgColor[$ck % 3];
//                }
//                $list['company_coupon'] = array_values($list['company_coupon']);
//            } else {
//                $list['company_coupon'] = array();
//            }
//
//            /* 设置分享url */
//            $list['company_info']['share_url'] = $httpOrigin . '/?g=app&m=apps&a=company_home&id=' . $list['company_info']['company_id'];
//
//            /* 判断用户是否收藏 登陆信息过期或未登录-按未登录情况展示 */
//            $list['company_info']['collect_code'] = '40005';
//            $list['company_info']['edit_code'] = '40011';
//            if ($token) {
//                /* 通过token获取用户信息 */
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo) {
//                    /* 判断是否可编辑 */
//                    $memberInfo['uid'] == $list['company_info']['user_id'] && $list['company_info']['edit_code'] = '40012';
//                    /* 判断是否收藏 */
//                    $where_favorite['uid'] = array('eq', $memberInfo['uid']);
//                    $where_favorite['aid'] = array('eq', $list['company_info']['company_id']);
//                    $where_favorite['favorite_category'] = array('eq', 3);
//                    $collectCount = M('UserFavorite')->where($where_favorite)->count('id');
//                    $collectCount > 0 && $list['company_info']['collect_code'] = '40006';
//                }
//            }
//
//            /* 查询企业所有产品 */
//            // $map['status']     = array('eq', 1);
//            // $map['company_id'] = array('eq', $id);
//            // $list['company_product'] = M('ProductSale')->field('id product_id,title,img')->where($map)->order('flags DESC,sort DESC,id DESC')->limit(9)->select();
//            // $list['company_product'] = $this->getAbsolutePath($list['company_product'], 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');
//
//            /* 企业简介处理 */
//            // if ( empty($list['company_info']['summary']) ) {
//            //     $list['company_introduction'] = '';
//            // } else {
//            //     $list['company_introduction'] = $this->destroySpace(strip_tags($list['company_info']['summary']));
//            // }
//
//            /* 查询企业联系方式 */
//            // $list['company_contact'] = M('CompanyLink')->field('address,subphone telephone')->where(array('company_id'=>$list['company_info']['company_id']))->find();
//            // !$list['company_contact']['address'] && $list['company_contact']['address'] = '';
//            // !$list['company_contact']['telephone'] && $list['company_contact']['telephone'] = '';
//
//            /* 宣传图 */
//            $where_ad['status'] = array('eq', 1);
//            $where_ad['company_id'] = array('eq', $id);
//            $where_ad['is_ad'] = array('eq', 1);
//            $adList = M('ProductSale')->field('id product_id,ad_img')->where($where_ad)->order('company_recommend DESC,company_sort DESC,id DESC')->limit(5)->select();
//            if ($adList) {
//                /* 图片处理 */
//                $adList = $this->getAbsolutePath($adList, 'ad_img');
//                /* 大宣传图 */
//                $list['company_ad'] = $adList[0];
//                if (count($adList) > 1) {
//                    /* 去掉大宣传图，剩下的即为小宣传图 */
//                    array_shift($adList);
//                    /* 小宣传图 */
//                    $list['ad_list'] = $adList;
//                } else {
//                    /* 小宣传图 */
//                    $list['ad_list'] = array();
//                }
//            } else {
//                /* 大宣传图 */
//                $list['company_ad'] = array('product_id' => '', 'ad_img' => '');
//                /* 小宣传图 */
//                $list['ad_list'] = array();
//            }
//
//            /* 榜单产品 */
//            switch ($type) {
//                case '1':
//                    /* 销量榜 */
//                    $order = 'sale_num DESC,company_recommend DESC,company_sort DESC,id DESC';
//                    break;
//                case '2':
//                    /* 收藏榜 */
//                    $order = 'click DESC,company_recommend DESC,company_sort DESC,id DESC';
//                    break;
//                case '3':
//                    /* 增长榜 */
//                    $order = 'click DESC,company_recommend DESC,company_sort DESC,id DESC';
//                    break;
//                default:
//                    /* 默认排序 */
//                    $order = 'sale_num DESC,company_recommend DESC,company_sort DESC,id DESC';
//                    break;
//            }
//            $where_top['status'] = array('eq', 1);
//            $where_top['company_id'] = array('eq', $id);
//            $list['top_list'] = M('ProductSale')->field('id product_id,title,img,price,sale_num,activity_price,activity_type')->where($where_top)->order($order)->select();
//            if ($list['top_list']) {
//                /* 图片处理 */
//                $list['top_list'] = $this->getAbsolutePath($list['top_list'], 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');
//                /* 数据处理 */
//                foreach ($list['top_list'] as $key => $value) {
//                    if (in_array(1, $activityList)) {
//                        /* 有活动的商品显示活动价格 */
//                        in_array($value['activity_type'], $activityList) && $list['top_list'][$key]['price'] = $value['activity_price'];
//                    }
//                    /* 销量拼接 */
//                    $list['top_list'][$key]['sale_num'] .= '人购买';
//                    /* 销毁客户端不需要的数据 */
//                    unset($list['top_list'][$key]['activity_price'], $list['top_list'][$key]['activity_type']);
//                }
//            } else {
//                $list['top_list'] = array();
//            }
//
//            /* 可分页的商品列表 */
//            $condition['status'] = array('eq', 1);
//            $condition['company_id'] = array('eq', $id);
//            $condition['company_recommend'] = array('eq', 1);
//            $list['product_list'] = M('ProductSale')->field('id product_id,title,img,price,CONCAT(sale_num,"人购买") sale_num,activity_price,activity_type')->where($condition)->order('company_recommend DESC,company_sort DESC,id DESC')->limit($page, 10)->select();
//            if ($list['product_list']) {
//                $list['product_list'] = $this->getAbsolutePath($list['product_list'], 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');
//                if (in_array(1, $activityList)) {
//                    foreach ($list['product_list'] as $key => $value) {
//                        /* 有活动的商品显示活动价格 */
//                        in_array($value['activity_type'], $activityList) && $list['product_list'][$key]['price'] = $value['activity_price'];
//                        /* 销毁客户端不需要的数据 */
//                        unset($list['product_list'][$key]['activity_price'], $list['product_list'][$key]['activity_type']);
//                    }
//                }
//            } else {
//                $list['product_list'] = array();
//            }
//
//            /* 销毁客户端不需要的数据 */
//            unset($list['company_info']['status']);
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业简介
//     */
//    public function introduction() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            !$id && $this->ajaxJson('70000', '参数错误');
//            /* 查询相关信息 */
//            $detail = M('Company')->field('id,name,summary,user_id,introduction_bgimg bgimg,logo')->where(array('id' => $id))->find();
//            !$detail && $this->ajaxJson('70000', '企业不存在');
//            $list['company_id'] = $detail['id'];
//            $list['name'] = $detail['name'];
//            $list['introduction'] = $this->destroySpace(strip_tags($detail['summary']));
//            $list['bgimg'] = $this->getAbsolutePath($detail['bgimg'], '', C('HTTP_APPS_IMG') . 'company_bgimg.png');
//            $list['logo'] = $this->getAbsolutePath($detail['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//            /* 判断用户是否能编辑、是否收藏 登陆信息过期或未登录-按未登录情况展示 */
//            $list['edit_code'] = '40011';
//            $list['collect_code'] = '40005';
//            if (!empty($token)) {
//                /* 判断是否是自己的企业 */
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo) {
//                    $memberInfo['uid'] == $detail['user_id'] && $list['edit_code'] = '40012';
//                    /* 判断是否收藏 */
//                    $where_favorite['uid'] = array('eq', $memberInfo['uid']);
//                    $where_favorite['aid'] = array('eq', $detail['id']);
//                    $where_favorite['favorite_category'] = array('eq', 3);
//                    $collectCount = M('UserFavorite')->where($where_favorite)->count('id');
//                    $collectCount > 0 && $list['collect_code'] = '40006';
//                }
//            }
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业简介修改
//     */
//    public function introductionEdit() {
//        if (IS_POST) {
//            $token = I('post.token');
//            /* 检测用户登陆状态 */
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//            $model = D('Company');
//            $result = $model->update();
//            if ($result) {
//                $this->ajaxJson('40000', $opt . '成功');
//            } else {
//                $this->ajaxJson('70000', $model->getError());
//            }
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业产品列表
//     */
//    public function productList() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            $page = I('get.page', 1, 'intval');
//            $page = ($page - 1) * 10;
//            !$id && $this->ajaxJson('70000', '参数错误');
//
//            /* 查询企业信息 */
//            $where['status'] = array('eq', 1);
//            $where['id'] = array('eq', $id);
//            $list['company_info'] = M('Company')->field('id company_id,user_id,name,logo,bgimg')->where($where)->find();
//            !$list['company_info'] && $this->ajaxJson('70000', '企业不存在');
//            $list['company_info']['logo'] = $this->getAbsolutePath($list['company_info']['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//            $list['company_info']['bgimg'] = $this->getAbsolutePath($list['company_info']['bgimg'], '', C('HTTP_APPS_IMG') . 'company_bgimg.png');
//            $list['company_info']['edit_code'] = '40011';
//            /* 判断是否是自己的企业 */
//            if (!empty($token)) {
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo && $memberInfo['uid'] == $list['company_info']['user_id']) {
//                    $list['company_info']['edit_code'] = '40012';
//                }
//            }
//            unset($list['company_info']['user_id']);
//
//            /* 产品信息 */
//            $map['company_id'] = array('eq', $id);
//            $map['status'] = array('eq', 1);
//            $list['product_list'] = M('ProductSale')->field('id product_id,title,short_title,price,img')->where($map)->order('flags DESC,sort DESC,issue_time DESC')->limit($page, 10)->select();
//            // foreach ($list['product_list'] as $key => $value) {
//            //     /* 处理价格 */
//            //     $list['product_list'][$key]['price'] = explode(',', $list['product_list'][$key]['price']);
//            //     foreach ($list['product_list'][$key]['price'] as $pk => $pv) {
//            //         $list['product_list'][$key]['price'][$pk] = '￥' . $pv  . '元';
//            //     }
//            //     $list['product_list'][$key]['price'] = implode(' - ', $list['product_list'][$key]['price']);
//            // }
//            $list['product_list'] = $this->getAbsolutePath($list['product_list'], 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业产品发布
//     */
//    public function productAdd() {
//        $id = I('request.id', 0, 'intval');
//        $token = I('request.token');
//        /* 检测用户登陆状态 */
//        empty($token) && $this->ajaxJson('70000', '请先登陆');
//        $memberInfo = D('Token')->getMemberInfo($token);
//        !$memberInfo && $this->ajaxJson('40004', '登陆信息已过期');
//
//        /* 发布、修改 */
//        $model = D('ProductSale');
//        if (IS_POST) {
//            $postDatas = $this->analyticalDatas($_POST);
//            $postDatas && $_POST = $postDatas;
//            $opt = $id > 0 ? '修改' : '发布';
//            $result = $model->update();
//            if ($result) {
//                $this->ajaxJson('40000', $opt . '成功');
//            } else {
//                $this->ajaxJson('70000', $model->getError());
//            }
//        }
//
//        /* 客户端图片地址 */
//        $appsImg = C('HTTP_APPS_IMG');
//
//        /* app版本信息：1电商版；2资讯版 */
//        $appType = C('FLASHFLAG');
//
//        /* 分销功能标识：1开启；0关闭 */
//        $distributionFlag = M('Conf')->where(array('name' => 'distribution'))->getField('value');
//
//        /* 查询用户企业信息 */
//        $company_info = M('Company')->field('id company_id,name company_name,logo company_logo,business')->where(array('user_id' => $memberInfo['uid']))->find();
//        !$company_info && $this->error('70000', '企业异常');
//        $company_info['business'] = '主营行业：' . $company_info['business'];
//        $company_info['company_logo'] = $this->getAbsolutePath($company_info['company_logo'], '', $appsImg . 'company_default.png');
//        $list['other_info'] = $company_info;
//        $list['other_info']['category_pic'] = $detail['category_pic'];
//        // $detail = array_merge($detail, $company_info);
//
//        /* 修改、发布时返回详情数据 */
//        if ($id) {
//            $detail = $model->getOneInfo(array('id' => $id), 'id,company_id,sale_category_id,title,short_title,price,activity_price,num,summary,status,activity_type,is_spec,buymin,oprice,weight,distribution,commission1,commission2,commission3,preferential,group_id');
//            !$detail && $this->ajaxJson('70000', '产品不存在或已下架');
//            $detail['company_id'] != $company_info['company_id'] && $this->ajaxJson('70000', '没有权限');
//            $detail['pic_url'] = M('ProdcutPicture')->where(array('product_id' => $id))->getField('pic_url', true);
//            !is_array($detail['pic_url']) && $detail['pic_url'] = array();
//            $detail['pic_url'] = $this->getAbsolutePath($detail['pic_url']);
//            empty($dettail['summary']) && $detail['summary'] = '';
//        } else {
//            $detail['id'] = '';
//            $detail['company_id'] = '';
//            $detail['sale_category_id'] = '';
//            $detail['title'] = '';
//            $detail['short_title'] = '';
//            $detail['price'] = '';
//            $detail['activity_price'] = '';
//            $detail['num'] = '9999';
//            $detail['summary'] = '';
//            $detail['status'] = '1';
//            $detail['activity_type'] = '0';
//            $detail['is_spec'] = '0';
//            $detail['buymin'] = '1';
//            $detail['oprice'] = '';
//            $detail['weight'] = '';
//            $detail['distribution'] = '';
//            $detail['commission1'] = '';
//            $detail['commission2'] = '';
//            $detail['commission3'] = '';
//            $detail['preferential'] = '';
//            $detail['group_id'] = '';
//            $detail['pic_url'] = array();
//        }
//        $detail['category_pic'] = C('HTTP_ORIGIN') . '/Public/Apps/images/category_default.png';
//
//        /* 分类 */
//        $categoryList = M('ProductSaleCategory')->field('id cid,parent_id,name')->where(array('status' => 1))->order('sort DESC,id DESC')->select();
//        foreach ($categoryList as $ck => $cv) {
//            $categoryList[$ck]['child'] = array();
//            $category[$cv['cid']] = $cv;
//        }
//        $categoryList = list_to_tree($categoryList, 'cid', 'parent_id', 'child');
//        $list['category'] = $categoryList;
//
//        /* 查询分类名称 */
//        if ($detail['sale_category_id']) {
//            $sale_category_id = explode(',', $detail['sale_category_id']);
//            foreach ($sale_category_id as $sck => $scv) {
//                $sale_category_id_arr[] = $category[$scv]['name'];
//            }
//            $detail['sale_category_name'] = implode(',', $sale_category_id_arr);
//        } else {
//            $detail['sale_category_name'] = '';
//        }
//
//        /* 商品规格 */
//        $specList = M('ProductSpec')->field('title1,title2,spec1,spec2,price,stock,buymin,sort,img,oprice,activity_price,weight')->where(array('product_id' => $id))->order('sort DESC,id ASC')->select();
//        if ($specList) {
//            $specList = $this->getAbsolutePath($specList, 'img', C('HTTP_APPS_IMG') . 'product_defalt.png');
//            $specChildList[] = array(
//                'type' => 'switch',
//                'required' => '1',
//                'title' => '开启规格',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '60',
//                'height' => '44',
//                'top' => '10',
//                'name' => 'is_spec',
//                'value' => array($detail['is_spec']),
//                'child' => array()
//            );
//            $specChildList[] = array(
//                'type' => 'title',
//                'required' => '1',
//                'title' => '添加规格名称',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '90',
//                'height' => '44',
//                'top' => '10',
//                'name' => '',
//                'value' => array(),
//                'child' => array()
//            );
//            $specChildList[] = array(
//                'type' => 'parent_two',
//                'required' => '0',
//                'title' => '规格一,规格二',
//                'placeholder' => '如：颜色,如：尺寸',
//                'num' => '50,50',
//                'width' => '',
//                'height' => '44',
//                'top' => '1',
//                'name' => 'title1,title2',
//                'value' => array($specList[0]['title1'], $specList[0]['title2'] ? $specList[0]['title2'] : ''),
//                'child' => array()
//            );
//            foreach ($specList as $sk => $sv) {
//                $specChildList[] = array(
//                    'type' => 'title',
//                    'required' => '1',
//                    'title' => '规格' . ($sk + 1),
//                    'placeholder' => '',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '10',
//                    'name' => 'spec_id|array',
//                    'value' => array(strval($sk + 1)),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'child_two',
//                    'required' => '0',
//                    'title' => $specList[0]['title1'] . ',' . $specList[0]['title2'],
//                    'placeholder' => '如：红色,如：均码',
//                    'num' => '2',
//                    'width' => '',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec1,spec2',
//                    'value' => array($sv['spec1'], $sv['spec2']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'title',
//                    'required' => '0',
//                    'title' => '价格设置',
//                    'placeholder' => '',
//                    'num' => '',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => '',
//                    'value' => array(),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '销售价格',
//                    'placeholder' => '请填写销售价格',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_price|array',
//                    'value' => array($sv['price']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '0',
//                    'title' => '市场价格',
//                    'placeholder' => '默认为销售价的1.2倍',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_oprice|array',
//                    'value' => array($sv['oprice']),
//                    'child' => array()
//                );
//                if ($appType == 1) {
//                    $specChildList[] = array(
//                        'type' => 'edit_text',
//                        'required' => '0',
//                        'title' => '活动价格',
//                        'placeholder' => '参与活动时的价格',
//                        'num' => '12',
//                        'width' => '60',
//                        'height' => '44',
//                        'top' => '1',
//                        'name' => 'spec_activity_price|array',
//                        'value' => array($sv['activity_price']),
//                        'child' => array()
//                    );
//                }
//                $specChildList[] = array(
//                    'type' => 'title',
//                    'required' => '0',
//                    'title' => '属性设置',
//                    'placeholder' => '',
//                    'num' => '',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => '',
//                    'value' => array(),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '库存',
//                    'placeholder' => '该规格的库存数量',
//                    'num' => '5',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_stock|array',
//                    'value' => array($sv['stock']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '重量',
//                    'placeholder' => '单位：千克',
//                    'num' => '12',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_weight|array',
//                    'value' => array($sv['weight']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '最小购买数量',
//                    'placeholder' => '请填写最小购买数量',
//                    'num' => '10',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_buymin|array',
//                    'value' => array($sv['buymin']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'edit_text',
//                    'required' => '0',
//                    'title' => '规格排序',
//                    'placeholder' => '数字越大越靠前，范围0-99',
//                    'num' => '2',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_sort|array',
//                    'value' => array($sv['sort']),
//                    'child' => array()
//                );
//                $specChildList[] = array(
//                    'type' => 'img_view',
//                    'required' => '0',
//                    'title' => '规格图片',
//                    'placeholder' => '',
//                    'num' => '1',
//                    'width' => '',
//                    'height' => '',
//                    'top' => '1',
//                    'name' => 'spec_img|array',
//                    'value' => array($sv['img']),
//                    'child' => array()
//                );
//            }
//        } else {
//            $specChildList[] = array(
//                'type' => 'switch',
//                'required' => '1',
//                'title' => '开启规格',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '60',
//                'height' => '44',
//                'top' => '10',
//                'name' => 'is_spec',
//                'value' => array($detail['is_spec']),
//                'child' => array()
//            );
//            $specChildList[] = array(
//                'type' => 'title',
//                'required' => '1',
//                'title' => '添加规格名称',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '90',
//                'height' => '44',
//                'top' => '10',
//                'name' => '',
//                'value' => array(),
//                'child' => array()
//            );
//            $specChildList[] = array(
//                'type' => 'parent_two',
//                'required' => '0',
//                'title' => '规格一,规格二',
//                'placeholder' => '如：颜色,如:尺寸',
//                'num' => '50,50',
//                'width' => '45',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'title1,title2',
//                'value' => array('', ''),
//                'child' => array()
//            );
//            $specAddArr = array(
//                array(
//                    'type' => 'title',
//                    'required' => '1',
//                    'title' => '规格1',
//                    'placeholder' => '',
//                    'num' => '12',
//                    'width' => '',
//                    'height' => '44',
//                    'top' => '10',
//                    'name' => 'spec_id|array',
//                    'value' => array('1'),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'child_two',
//                    'required' => '0',
//                    'title' => '规格一,规格二',
//                    'placeholder' => '如：红色,如：均码',
//                    'num' => '2',
//                    'width' => '',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec1,spec2',
//                    'value' => array('', ''),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'title',
//                    'required' => '0',
//                    'title' => '价格设置',
//                    'placeholder' => '',
//                    'num' => '',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => '',
//                    'value' => array(),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '销售价格',
//                    'placeholder' => '请填写销售价格',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_price|array',
//                    'value' => array(''),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '0',
//                    'title' => '市场价格',
//                    'placeholder' => '默认为销售价的1.2倍',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_oprice|array',
//                    'value' => array(''),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '0',
//                    'title' => '活动价格',
//                    'placeholder' => '参与活动时的价格',
//                    'num' => '12',
//                    'width' => '60',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_activity_price|array',
//                    'value' => array(''),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'title',
//                    'required' => '0',
//                    'title' => '属性设置',
//                    'placeholder' => '',
//                    'num' => '',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => '',
//                    'value' => array(),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '库存',
//                    'placeholder' => '该规格的库存数量',
//                    'num' => '5',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_stock|array',
//                    'value' => array('9999'),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '重量',
//                    'placeholder' => '单位：千克',
//                    'num' => '12',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_weight|array',
//                    'value' => array(''),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '1',
//                    'title' => '最小购买数量',
//                    'placeholder' => '请填写最小购买数量',
//                    'num' => '10',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_buymin|array',
//                    'value' => array('1'),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'edit_text',
//                    'required' => '0',
//                    'title' => '规格排序',
//                    'placeholder' => '数字越大越靠前，范围0-99',
//                    'num' => '2',
//                    'width' => '90',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'spec_sort|array',
//                    'value' => array('50'),
//                    'child' => array()
//                ),
//                array(
//                    'type' => 'img_view',
//                    'required' => '0',
//                    'title' => '规格图片',
//                    'placeholder' => '',
//                    'num' => '1',
//                    'width' => '',
//                    'height' => '',
//                    'top' => '1',
//                    'name' => 'spec_img|array',
//                    'value' => array(),
//                    'child' => array()
//                )
//            );
//            if ($appType != 1) {
//                unset($specAddArr[5]);
//                $specAddArr = array_values($specAddArr);
//            }
//            $specChildList[] = array(
//                'type' => 'hidden',
//                'required' => '0',
//                'title' => '添加规格',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '',
//                'top' => '',
//                'name' => '',
//                'value' => array(),
//                'child' => $specAddArr
//            );
//        }
//        $specChildList[] = array(
//            'type' => 'spec_add',
//            'required' => '0',
//            'title' => '添加规格',
//            'placeholder' => '',
//            'num' => '',
//            'width' => '',
//            'height' => '44',
//            'top' => '20',
//            'name' => '',
//            'value' => array(),
//            'child' => array()
//        );
//
//        /* 商家分组信息 */
//        $where_group['company_id'] = array('eq', $company_info['company_id']);
//        $where_group['status'] = array('eq', 1);
//        $groupAllList = M('ProductGroup')->field('id,title')->where($where_group)->select();
//        if ($groupAllList) {
//            foreach ($groupAllList as $galv) {
//                $selected = $detail['group_id'] == $galv['id'] ? '1' : '0';
//                $groupList[] = array(
//                    'type' => 'radio',
//                    'required' => '0',
//                    'title' => $galv['title'],
//                    'placeholder' => '',
//                    'num' => '1',
//                    'width' => '',
//                    'height' => '44',
//                    'top' => '1',
//                    'name' => 'group_id|array',
//                    'value' => array($galv['id'] . ',' . $selected),
//                    'child' => array()
//                );
//                unset($selected);
//            }
//        } else {
//            $groupList = array();
//        }
//        /* 添加分组 */
//        $groupList[] = array(
//            'type' => 'group_add',
//            'required' => '0',
//            'title' => '添加分组',
//            'placeholder' => '',
//            'num' => '',
//            'width' => '',
//            'height' => '44',
//            'top' => '20',
//            'name' => '',
//            'value' => array(),
//            'child' => array()
//        );
//
//        /* 价格设置数据 */
//        $prictArray = array(
//            array(
//                'type' => 'edit_text',
//                'required' => '1',
//                'title' => '销售价格',
//                'placeholder' => '请输入销售价格',
//                'num' => '12',
//                'width' => '60',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'price',
//                'value' => array($detail['price']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '市场价格',
//                'placeholder' => '请输入市场价格',
//                'num' => '12',
//                'width' => '60',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'oprice',
//                'value' => array($detail['oprice']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '活动价格',
//                'placeholder' => '参与活动时的价格',
//                'num' => '12',
//                'width' => '60',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'activity_price',
//                'value' => array($detail['activity_price']),
//                'child' => array()
//            )
//        );
//
//        /* 属性设置 */
//        $attributeArray = array(
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '重量',
//                'placeholder' => '单件商品的重量',
//                'num' => '12',
//                'width' => '90',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'weight',
//                'value' => array($detail['weight']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '1',
//                'title' => '库存',
//                'placeholder' => '商品总库存(含规格库存)',
//                'num' => '12',
//                'width' => '90',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'num',
//                'value' => array($detail['num']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '1',
//                'title' => '最小购买数量',
//                'placeholder' => '请填写最小购买数量',
//                'num' => '12',
//                'width' => '90',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'buymin',
//                'value' => array($detail['buymin']),
//                'child' => array()
//            )
//        );
//
//        /* 活动设置 */
//        $activityArray = array(
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '优惠券最多可抵金额',
//                'placeholder' => '0无上限，-1禁用',
//                'num' => '',
//                'width' => '135',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'preferential',
//                'value' => array($detail['preferential']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'title',
//                'required' => '0',
//                'title' => '选择商品参与的活动',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '135',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => array()
//            ),
//            array(
//                'type' => 'radio',
//                'required' => '0',
//                'title' => '限时抢购',
//                'placeholder' => '',
//                'num' => '1',
//                'width' => '135',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'activity_type|array',
//                'value' => array('1,' . $detail['activity_type']),
//                'child' => array()
//            )
//        );
//
//        /* 分销设置 */
//        $distributionArray = array(
//            array(
//                'type' => 'switch',
//                'required' => '0',
//                'title' => '是否参与分销',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '100',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'distribution',
//                'value' => array($detail['distribution']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '本级佣金(元)',
//                'placeholder' => '购买者自己获得的佣金',
//                'num' => '',
//                'width' => '100',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'commission1',
//                'value' => array($detail['commission1']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '上级佣金(元)',
//                'placeholder' => '请填写上级佣金',
//                'num' => '',
//                'width' => '100',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'commission2',
//                'value' => array($detail['commission2']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '上上级佣金(元)',
//                'placeholder' => '请填写上上级佣金',
//                'num' => '',
//                'width' => '100',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'commission3',
//                'value' => array($detail['commission3']),
//                'child' => array()
//            )
//        );
//
//        /* 第一块 */
//        $section_first = array(
//            array(
//                'type' => 'hidden',
//                'required' => '0',
//                'title' => '',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '',
//                'top' => '',
//                'name' => 'id',
//                'value' => array(strval($id)),
//                'child' => array()
//            ),
//            array(
//                'type' => 'hidden',
//                'required' => '0',
//                'title' => '',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '',
//                'top' => '',
//                'name' => 'token',
//                'value' => array($token),
//                'child' => array()
//            ),
//            array(
//                'type' => 'img_view',
//                'required' => '1',
//                'title' => '上传产品轮播图',
//                'placeholder' => '(不超过5张)',
//                'num' => '5',
//                'width' => '',
//                'height' => '',
//                'top' => '0',
//                'name' => 'carousel',
//                'value' => $detail['pic_url'],
//                'child' => array()
//            )
//        );
//
//        /* 第二块 */
//        $section_second = array(
//            array(
//                'type' => 'edit_text',
//                'required' => '1',
//                'title' => '标题',
//                'placeholder' => '请填写标题',
//                'num' => '50',
//                'width' => '60',
//                'height' => '44',
//                'top' => '1',
//                'name' => 'title',
//                'value' => array($detail['title']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'edit_text',
//                'required' => '0',
//                'title' => '副标题',
//                'placeholder' => '一句话简单介绍产品',
//                'num' => '100',
//                'width' => '60',
//                'height' => '44',
//                'top' => '1',
//                'name' => 'short_title',
//                'value' => array($detail['short_title']),
//                'child' => array()
//            )
//        );
//
//        /* 第三块 */
//        $section_third = array(
//            array(
//                'type' => 'href',
//                'required' => '1',
//                'title' => '价格设置',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $prictArray
//            ),
//            array(
//                'type' => 'href',
//                'required' => '0',
//                'title' => '属性设置',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $attributeArray
//            ),
//            array(
//                'type' => 'select_category',
//                'required' => '1',
//                'title' => '选择分类',
//                'placeholder' => '',
//                'num' => '5',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => 'sale_category_id',
//                'value' => array($detail['sale_category_id'], $detail['sale_category_name']),
//                'child' => array()
//            ),
//            array(
//                'type' => 'href',
//                'required' => '0',
//                'title' => '选择分组',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $groupList
//            )
//        );
//
//        /* 第四块 */
//        $section_fourth = array(
//            array(
//                'type' => 'href',
//                'required' => '0',
//                'title' => '活动设置',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $activityArray
//            ),
//            array(
//                'type' => 'href',
//                'required' => '0',
//                'title' => '分销设置',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $distributionArray
//            ),
//            array(
//                'type' => 'href',
//                'required' => '0',
//                'title' => '规格设置',
//                'placeholder' => '',
//                'num' => '',
//                'width' => '',
//                'height' => '44',
//                'top' => '0',
//                'name' => '',
//                'value' => array(),
//                'child' => $specChildList
//            ),
//            // array(
//            //     'type'        => 'rich_text',
//            //     'required'    => '0',
//            //     'title'       => '图文详情',
//            //     'placeholder' => '',
//            //     'num'         => '',
//            //     'width'       => '',
//            //     'height'      => '44',
//            //     'top'         => '0',
//            //     'name'        => '',
//            //     'value'       => array($detail['summary']),
//            //     'child'       => array()
//            // ),
//        );
//
//        /* 为开启分销功能 */
//        if ($distributionFlag != 1) {
//            unset($section_fourth[1]);
//            $section_fourth = array_values($section_fourth);
//        }
//
//        /* 非电商版 */
//        if ($appType != 1) {
//            /* 去掉活动价 */
//            array_pop($section_third[0]['child']);
//            /* 去掉重量 */
//            array_shift($section_third[1]['child']);
//            /* 去掉最小购买数量 */
//            array_pop($section_third[1]['child']);
//            /* 去掉活动设置 */
//            array_shift($section_fourth);
//            /*  */
//        }
//
//        /* 区信息 */
//        $list['section'] = array(
//            $section_first,
//            $section_second,
//            $section_third,
//            $section_fourth
//        );
//
//        $this->returnJson($list);
//    }
//
//    /**
//     * 企业产品删除(支持批量删除)
//     */
//    public function productDel() {
//        if (IS_POST) {
//            $id = I('post.id');
//            $id = explode(',', $id);
//            if (!is_array($id) || !$id[0]) {
//                $this->ajaxJson('70000', '参数错误');
//            }
//            /* 检测用户登陆状态 */
//            $token = I('post.token');
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            $model = D('ProductSale');
//            $condition['id'] = array('in', $id);
//            $return = $model->del($condition);
//            if ($return) {
//                $this->ajaxJson('40000', '删除成功');
//            } else {
//                $this->ajaxJson('70000', '删除失败');
//            }
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业求购列表
//     */
//    public function needList() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            $page = I('get.page', 1, 'intval');
//            $page = ($page - 1) * 10;
//            !$id && $this->ajaxJson('70000', '参数错误');
//
//            /* 查询企业信息 */
//            $where['status'] = array('eq', 1);
//            $where['id'] = array('eq', $id);
//            $list['company_info'] = M('Company')->field('id company_id,user_id,name,logo,bgimg')->where($where)->find();
//            !$list['company_info'] && $this->ajaxJson('70000', '企业不存在');
//            $list['company_info']['logo'] = $this->getAbsolutePath($list['company_info']['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//            $list['company_info']['bgimg'] = $this->getAbsolutePath($list['company_info']['bgimg'], '', C('HTTP_APPS_IMG') . 'company_bgimg.png');
//            $list['company_info']['edit_code'] = '40011';
//            /* 判断是否是自己的企业 */
//            if (!empty($token)) {
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo && $memberInfo['uid'] == $list['company_info']['user_id']) {
//                    $list['company_info']['edit_code'] = '40012';
//                }
//            }
//            unset($list['company_info']['user_id']);
//
//            /* 求购信息 */
//            $map['company_id'] = array('eq', $id);
//            $map['status'] = array('eq', 1);
//            $list['need_list'] = M('ProductBuy')->field('id need_id,title,short_title,price,img,days,num')->where($map)->order('flags DESC,sort DESC,issue_time DESC')->limit($page, 10)->select();
//            $list['need_list'] = $this->getAbsolutePath($list['need_list'], 'img', C('HTTP_APPS_IMG') . 'need_default.png');
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业求购发布
//     */
//    public function needAdd() {
//        $id = I('request.id', 0, 'intval');
//        $token = I('request.token');
//        /* 检测用户登陆状态 */
//        empty($token) && $this->ajaxJson('70000', '请先登陆');
//        $memberInfo = D('Token')->getMemberInfo($token);
//        !$memberInfo && $this->ajaxJson('40004');
//
//
//        /* 发布、修改 */
//        $model = D('ProductBuy');
//        if (IS_POST) {
//            $opt = $id > 0 ? '修改' : '发布';
//            $result = $model->update();
//            if ($result) {
//                $this->ajaxJson('40000', $opt . '成功');
//            } else {
//                $this->ajaxJson('70000', $model->getError());
//            }
//        }
//
//        /* 取产品分类的所有一级分类作为求购的分类 */
//        $list['category'] = M('ProductSaleCategory')->where(array('status' => 1, 'parent_id' => 0))->getField('id cid,name,logo', true);
//
//        /* 修改、发布时返回详情数据 */
//        if ($id) {
//            $list['detail'] = $model->getOneInfo(array('id' => $id), 'id need_id,buy_category_id,title,short_title,price,summary,days,num');
//            !$list['detail'] && $this->ajaxJson('70000', '求购不存在或已删除');
//            $list['detail']['pic_url'] = M('NeedPicture')->where(array('need_id' => $id))->getField('pic_url', true);
//            !is_array($list['detail']['pic_url']) && $list['detail']['pic_url'] = array();
//            $list['detail']['pic_url'] = $this->getAbsolutePath($list['detail']['pic_url']);
//            $list['detail']['buy_category_name'] = $list['category'][$list['detail']['buy_category_id']]['name'];
//        } else {
//            $list['detail']['need_id'] = '';
//            $list['detail']['buy_category_id'] = '';
//            $list['detail']['title'] = '';
//            $list['detail']['short_title'] = '';
//            $list['detail']['price'] = '';
//            $list['detail']['summary'] = '';
//            $list['detail']['pic_url'] = array();
//            $list['detail']['days'] = '';
//            $list['detail']['num'] = '';
//            $list['detail']['buy_category_name'] = '';
//        }
//        $list['detail']['category_pic'] = C('HTTP_ORIGIN') . '/Public/Apps/images/category_pic.png';
//        foreach ($list['category'] as $key => $value) {
//            unset($list['category'][$key]['logo']);
//        }
//        $list['category'] = array_values($list['category']);
//
//        /* 查询用户企业信息 */
//        $company_info = M('Company')->field('id company_id,name company_name,logo company_logo,business')->where(array('user_id' => $memberInfo['uid']))->find();
//        $company_info['business'] = '主营行业' . $company_info['business'];
//        $company_info['company_logo'] = $this->getAbsolutePath($company_info['company_logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//        $list['detail'] = array_merge($list['detail'], $company_info);
//
//        $this->returnJson($list);
//    }
//
//    /**
//     * 企业求购删除
//     */
//    public function needDel() {
//        if (IS_POST) {
//            $id = I('post.id');
//            $id = explode(',', $id);
//            if (!is_array($id) || !$id[0]) {
//                $this->ajaxJson('70000', '参数错误');
//            }
//            /* 检测用户登陆状态 */
//            $token = I('post.token');
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            $model = D('ProductBuy');
//            $condition['id'] = array('in', $id);
//            $return = $model->del($condition);
//            if ($return) {
//                $this->ajaxJson('40000', '删除成功');
//            } else {
//                $this->ajaxJson('70000', '删除失败');
//            }
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业相册展示
//     */
//    public function album() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            $page = I('get.page', 1, 'intval');
//            $page = ($page - 1) * 10;
//            !$id && $this->ajaxJson('70000', '参数错误');
//
//            /* 查询相册信息 */
//            $list['album'] = M('CompanyAlbumPicture')->where(array('pid' => $id))->limit($page, 10)->getField('img', true);
//            $list['album'] = $list['album'] ? $this->getAbsolutePath($list['album']) : array();
//
//            /* 查询企业所属用户id */
//            $company_info = M('Company')->field('id company_id,user_id,name company_name')->where(array('id' => $id))->find();
//
//            /* 判断是否是自己的企业 */
//            $list['edit_code'] = '40011';
//            if (!empty($token)) {
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo && $memberInfo['uid'] == $company_info['user_id']) {
//                    $list['edit_code'] = '40012';
//                }
//            }
//            $list['company_id'] = $company_info['company_id'];
//            $list['company_name'] = $company_info['company_name'];
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业相册管理
//     */
//    public function albumAdd() {
//        if (IS_POST) {
//            $id = I('post.id', 0, 'intval');
//            $album = $_POST['album'];
//            $token = I('post.token');
//            if (!$id) {
//                $this->ajaxJson('70000', '参数错误');
//            }
//            /* 检测用户登陆状态 */
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            /* 验证用户身份 */
//            $user_id = M('Company')->where(array('id' => $id))->getField('user_id');
//            !$user_id && $this->ajaxJson('70000', '企业不存在');
//            $user_id != $memberInfo['uid'] && $this->ajaxJson('70000', '当前用户与企业所属用户不符');
//
//            /* 删除之前的相册图片，并添加新相册图片 */
//            M('CompanyAlbumPicture')->where(array('pid' => $id))->delete();
//            if (is_array($album) || count($album) > 0) {
//                $album = explode(',', $album);
//                foreach ($album as $key => $value) {
//                    $datas[$key]['pid'] = $id;
//                    $datas[$key]['img'] = $value;
//                }
//                $add = M('CompanyAlbumPicture')->addAll($datas);
//            } else {
//                $add = true;
//            }
//            if ($add !== false) {
//                $this->ajaxJson('40000', '操作成功');
//            }
//            $this->ajaxJson('70000', '操作失败');
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业相册相片设为企业背景图
//     */
//    public function setBgImg() {
//        if (IS_POST) {
//            $img = I('post.img');
//            $token = I('post.token');
//            if (empty($img)) {
//                $this->ajaxJson('70000', '参数错误');
//            }
//            /* 检测用户登陆状态 */
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            /* 检测企业是否存在 */
//            $memberInfo['gid'] != 2 && $this->ajaxJson('70000', '企业会员才能进行该操作');
//            $company_id = M('Company')->where(array('user_id' => $memberInfo['uid'], 'status' => 1))->getField('id');
//            !$company_id && $this->ajaxJson('70000', '您的企业状态异常');
//
//            /* 设置企业背景图 */
//            $save = M('Company')->where(array('id' => $company_id))->setField('bgimg', $img);
//            if ($save !== false) {
//                $this->ajaxJson('40000', '设置成功');
//            }
//            $this->ajaxJson('70000', '设置失败');
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业相册相片设为企业简介背景图
//     */
//    public function setIntroductionBgImg() {
//        if (IS_POST) {
//            $img = I('post.img');
//            $token = I('post.token');
//            if (empty($img)) {
//                $this->ajaxJson('70000', '参数错误');
//            }
//            /* 检测用户登陆状态 */
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            /* 检测企业是否存在 */
//            $memberInfo['gid'] != 2 && $this->ajaxJson('70000', '企业会员才能进行该操作');
//            $company_id = M('Company')->where(array('user_id' => $memberInfo['uid'], 'status' => 1))->getField('id');
//            !$company_id && $this->ajaxJson('70000', '您的企业状态异常');
//
//            /* 设置企业背景图 */
//            $save = M('Company')->where(array('id' => $company_id))->setField('introduction_bgimg', $img);
//            if ($save !== false) {
//                $this->ajaxJson('40000', '设置成功');
//            }
//            $this->ajaxJson('70000', '设置失败');
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业地图导航
//     */
//    public function map() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            !$id && $this->ajaxJson('70000', '参数错误');
//
//            $company_info = M('Company')
//                ->alias('l')
//                ->join(C('DB_PREFIX') . 'company_link r ON l.id = r.company_id', 'LEFT')
//                ->field('r.lat,r.lng')
//                ->where(array('l.id' => $id))
//                ->find();
//            if (!$company_info['lat'] || !$company_info['lng']) {
//                $this->ajaxJson('70000', '该公司没有在地图上标注公司位置');
//            }
//            $this->returnJson($company_info);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业联系我们
//     */
//    public function contact() {
//        if (IS_GET) {
//            $id = I('get.id', 0, 'intval');
//            $token = I('get.token');
//            !$id && $this->ajaxJson('70000', '参数错误');
//
//            /* 查询相关信息 */
//            $company_info = M('Company')
//                ->alias('l')
//                ->join(C('DB_PREFIX') . 'company_link r ON l.id = r.company_id', 'LEFT')
//                ->field('l.id company_id,l.user_id,l.name,l.logo,l.bgimg,r.address,r.contact_user,r.subphone,r.telephone,r.site,r.wechat,r.lng,r.lat')
//                ->where(array('l.id' => $id))
//                ->find();
//            $company_info['logo'] = $this->getAbsolutePath($company_info['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//            $company_info['bgimg'] = $this->getAbsolutePath($company_info['bgimg'], '', C('HTTP_APPS_IMG') . 'company_bgimg.png');
//
//            /* 判断是否是自己的企业 */
//            $company_info['edit_code'] = '40011';
//            if (!empty($token)) {
//                $memberInfo = D('Token')->getMemberInfo($token);
//                if ($memberInfo && $memberInfo['uid'] == $company_info['user_id']) {
//                    $company_info['edit_code'] = '40012';
//                }
//            }
//            $company_info['company_id'] = $company_info['company_id'];
//
//            $this->returnJson($company_info);
//        }
//        $this->ajaxJson('70002');
//    }
//
//    /**
//     * 企业联系我们编辑
//     */
//    public function contactEdit() {
//        if (IS_POST) {
//            $id = I('post.id', 0, 'intval');
//            $token = I('post.token');
//            /* 检测用户登陆状态 */
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            $companyModel = D('Company');
//            $companyLinkModel = D('CompanyLink');
//            if (IS_POST) {
//                $companyDatas = $companyModel->update('contact');
//                if ($companyDatas) {
//                    $linkDates = $companyLinkModel->update($companyDatas['id']);
//                    if ($linkDates) {
//                        $this->ajaxJson('40000', '保存成功');
//                    } else {
//                        $this->ajaxJson('70000', $companyLinkModel->getError());
//                    }
//                } else {
//                    $this->ajaxJson('70000', $companyModel->getError());
//                }
//            }
//        }
//        $this->ajaxJson('70001');
//    }
//
//    /**
//     * 企业申请、编辑
//     */
//    public function add() {
//        /* 检测用户登陆状态 */
//        $token = I('request.token');
//        empty($token) && $this->ajaxJson('70000', '请先登陆');
//        $memberInfo = D('Token')->getMemberInfo($token);
//        !$memberInfo && $this->ajaxJson('40004');
//
//        /* 接收参数 */
//        $id = I('request.id', 0, 'intval');
//        $linkid = I('request.linkid', 0, 'intval');
//        $cert = $_REQUEST['cert'];
//
//        /* 申请、修改 */
//        if (IS_POST) {
//            $opt = $id ? '修改' : '申请';
//            $companyModel = D('CompanyManage');
//            $companyLinkModel = D('CompanyLinkManage');
//            $companyCertModel = M('CompanyCert');
//            $companyDatas = $companyModel->update();
//            if ($companyDatas) {
//                /* 企业资质 */
//                $companyCertModel->where(array('company_id' => $companyDatas['id']))->delete();
//                if (!empty($cert)) {
//                    $certList = explode(',', $cert);
//                    foreach ($certList as $key => $value) {
//                        $certDatas[$key]['company_id'] = $companyDatas['id'];
//                        $certDatas[$key]['img'] = $value;
//                    }
//                    $companyCertModel->addAll($certDatas);
//                }
//                /* 企业副表 */
//                $linkDates = $companyLinkModel->update($companyDatas['id']);
//                if ($linkDates) {
//                    /* 改变会员状态 */
//                    M('Member')->where(array('uid' => $memberInfo['uid']))->setField('gid', 2);
//                    /* 新增时发送站内信 */
//                    if (!$id) {
//                        /* 判断企业状态 */
//                        if ($companyDatas['status'] == 1) {
//                            R('Apps/General/sendSiteMessage', array('恭喜您成为企业会员', '恭喜您成为企业会员', $companyDatas['user_id']));
//                        } else {
//                            R('Apps/General/sendSiteMessage', array('您的申请已成功发送', '您的申请已成功发送，请耐心等待审核结果', $companyDatas['user_id']));
//                        }
//                    }
//                    $this->ajaxJson('40000', $opt . '成功');
//                } else {
//                    $this->ajaxJson('70000', $companyLinkModel->getError());
//                }
//            } else {
//                $this->ajaxJson('70000', $companyModel->getError());
//            }
//        }
//
//        /* 查询信息 */
//        if ($id) {
//            $prefix = C('DB_PREFIX');
//            $detail = M('Company')
//                ->alias('l')
//                ->join($prefix . 'company_link r ON l.id = r.company_id', 'LEFT')
//                ->join($prefix . 'company_cert c ON l.id = c.company_id', 'LEFT')
//                ->field('l.id company_id,l.company_category_id,l.name,l.logo,l.bgimg,l.introduction_bgimg,l.business,l.summary,r.id linkid,r.address,r.contact_user,r.site,r.subphone,r.telephone,r.lng,r.lat,r.wechat,group_concat(c.img) cert')
//                ->where(array('l.id' => $id))
//                ->find();
//            !$detail && $this->ajaxJson('70000', '企业不存在');
//            $detail['logo'] = $this->getAbsolutePath($detail['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
//            $detail['bgimg'] = $this->getAbsolutePath($detail['bgimg'], '', C('HTTP_APPS_IMG') . 'company_bgimg.png');
//            $detail['introduction_bgimg'] = $this->getAbsolutePath($detail['introduction_bgimg'], '', C('HTTP_APPS_IMG') . 'company_introduction_bgimg.png');
//            if ($detail['cert']) {
//                $detail['cert'] = explode(',', $detail['cert']);
//                $detail['cert'] = $this->getAbsolutePath($detail['cert']);
//            } else {
//                $detail['cert'] = array();
//            }
//            !$detail['linkid'] && $detail['linkid'] = '';
//            !$detail['address'] && $detail['address'] = '';
//            !$detail['contact_user'] && $detail['contact_user'] = '';
//            !$detail['site'] && $detail['site'] = '';
//            !$detail['subphone'] && $detail['subphone'] = '';
//            !$detail['telephone'] && $detail['telephone'] = '';
//            !$detail['lng'] && $detail['lng'] = '';
//            !$detail['lat'] && $detail['lat'] = '';
//            !$detail['wechat'] && $detail['wechat'] = '';
//        } else {
//            $detail['company_id'] = '';
//            $detail['company_category_id'] = '';
//            $detail['name'] = '';
//            $detail['logo'] = C('HTTP_ORIGIN') . '/Public/Apps/images/company_default.png';
//            $detail['bgimg'] = C('HTTP_ORIGIN') . '/Public/Apps/images/company_bgimg.png';
//            $detail['introduction_bgimg'] = C('HTTP_ORIGIN') . '/Public/Apps/images/company_introduction_bgimg.png';
//            $detail['business'] = '';
//            $detail['summary'] = '';
//            $detail['linkid'] = '';
//            $detail['address'] = '';
//            $detail['contact_user'] = '';
//            $detail['site'] = '';
//            $detail['subphone'] = '';
//            $detail['telephone'] = '';
//            $detail['lng'] = '';
//            $detail['lat'] = '';
//            $detail['wechat'] = '';
//            $detail['cert'] = array();
//        }
//        $list['detail'] = $detail;
//
//        /* 企业分类 */
//        $list['category'] = M('CompanyCategory')->field('id category_id,name')->where(array('status' => 1))->order('sort DESC,id DESC')->select();
//        $this->returnJson($list);
//    }
//
//    /**
//     * 查询企业状态
//     */
//    public function status() {
//        if (IS_GET) {
//            /* 检测用户登陆状态 */
//            $token = I('get.token');
//            empty($token) && $this->ajaxJson('70000', '请先登陆');
//            $memberInfo = D('Token')->getMemberInfo($token);
//            !$memberInfo && $this->ajaxJson('40004');
//
//            /* 查询企业状态信息 */
//            $company_info = M('Company')->field('id company_id,status,reason')->where(array('user_id' => $memberInfo['uid']))->find();
//            $company_info['status'] = strval($company_info['status']);
//            $servicePhone = M('Conf')->where(array('name' => 'companphone'))->getField('value');
//            if ($company_info) {
//                switch ($company_info['status']) {
//                    case '0':
//                        $company_info['service_phone'] = $servicePhone;
//                        $company_info['notice'] = '您的企业帐号已被停封，请联系客服';
//                        break;
//                    case '1':
//                        $company_info['service_phone'] = '';
//                        $company_info['notice'] = '恭喜您成为我们的企业会员';
//                        break;
//                    case '2':
//                        $company_info['service_phone'] = '';
//                        $company_info['notice'] = '您的企业会员申请已提交，请耐心等待';
//                        break;
//                    case '3':
//                        $company_info['service_phone'] = $servicePhone;
//                        $company_info['notice'] = '很遗憾，您的企业会员申请未通过';
//                        break;
//                    default:
//                        $this->ajaxJson('70000', '企业状态异常');
//                        break;
//                }
//                $company_info['reason'] && $company_info['reason'] = '原因：' . $company_info['reason'];
//                $list['info'] = $company_info;
//            } else {
//                $list['info']['company_id'] = '';
//                $list['info']['status'] = '-1';
//                $list['info']['service_phone'] = '';
//                $list['info']['notice'] = '您还没有成为我们的企业会员';
//                $list['info']['reason'] = '';
//            }
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70001');
//    }
}