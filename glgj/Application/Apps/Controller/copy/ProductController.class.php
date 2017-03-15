<?php
namespace Apps\Controller;
use Think\Controller;

class ProductController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 产品分类
     */
    public function category() {
        if ( IS_GET ) {
            $list['category'] = M('ProductSaleCategory')->field('id cid,parent_id,name,logo')->where(array('status'=>1))->order('sort DESC,id DESC')->select();
            foreach ($list['category'] as $ck => $cv) {
                $list['category'][$ck]['child'] = array();
            }
            $list['category'] = $this->getAbsolutePath($list['category'], 'logo', C('HTTP_APPS_IMG') . 'category_default.png');
            $list['category'] = list_to_tree($list['category'], 'cid', 'parent_id', 'child');
            /* 定义全部分类 */
            // $all = array(
            //     'cid'       => '0',
            //     'parent_id' => '0',
            //     'name'      => '全部',
            //     'logo'      => '',
            //     'child'     => array()
            // );
            /* 加入全部分类选项 */
            // array_unshift($list['category'], $all);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 产品列表
     */
    public function index() {
        if ( IS_GET ) {
            $cid        = I('get.cid', 0, 'intval');
            $page       = I('get.page', 1, 'intval');
            $page       = ( $page - 1 ) * 10;
            $title      = I('get.title');
            $nav_id     = I('get.nav_id', '', 'strval');
            $company_id = I('get.company_id');

            /* 导航排序功能 */
            if ( $nav_id ) {
                switch ( $nav_id ) {
                    case '1':
                        /* 按价格升序 */
                        $order = 'price ASC,';
                        break;
                    case '2':
                        /* 按价格降序 */
                        $order = 'price DESC,';
                        break;
                    case '3':
                        /* 按销量升序 */
                        $order = 'sale_num ASC,';
                        break;
                    case '4':
                        /* 按销量降序 */
                        $order = 'sale_num DESC,';
                        break;
                    case '5':
                        /* 按最新升序 */
                        $order = 'modify_time ASC,';
                        break;
                    case '6':
                        /* 按最新降序 */
                        $order = 'modify_time DESC,';
                        break;
                    default:
                        $order = '';
                        break;
                }
            }

            /* 按企业分组 */
            $company_id && $map['company_id'] = array('eq', $company_id);

            /* 搜索标题、筛选分类 */
            !empty($title) && $map['title'] = array('like', '%'.$title.'%');
            $cid && $map['_string'] = 'FIND_IN_SET(' . $cid . ',sale_category_id)';

            /* 产品轮播图 */
            $list['carousel'] = M('jdpic')->field('thumbnail,name,url,href_type,href_model,data_id')->where(array('tid'=>6))->order('listorder DESC,id DESC')->select();
            $list['carousel'] = $this->getAbsolutePath($list['carousel'], 'thumbnail');

            /* 查询活动信息 有活动的商品要显示活动价格 */
            $where_activity['status']        = array('eq', 1);
            $where_activity['start_time']    = array('lt', time());
            $where_activity['end_time']      = array('gt', time());
            $where_activity['activity_type'] = array('gt', 0);
            $activityList = M('Activity')->where($where_activity)->getField('activity_type', true);

            /* 产品信息 */
            $map['status']  = array('eq', 1);
            $list['product_list'] = M('ProductSale')->field('id product_id,title,short_title,price,img,activity_price,activity_type,sale_num')->where($map)->order($order . 'flags DESC,sort DESC,id DESC')->select();
            if ( $list['product_list'] ) {
                /* 手动分页 */
                $list['product_list'] = array_slice($list['product_list'], $page, 10);
                /* 图片处理 */
                $list['product_list'] = $this->getAbsolutePath($list['product_list'], 'img', C('HTTP_APPS_IMG') . 'product_default.png');
                /* 数据处理 */
                foreach ($list['product_list'] as $key => $value) {
                    /* 销量拼接 */
                    $list['product_list'][$key]['sale_num'] .= '人购买';
                    if ( $activityList ) {
                        /* 有活动的商品显示活动价格 */
                        in_array($value['activity_type'], $activityList) && $list['product_list'][$key]['price'] = $value['activity_price'];
                    }
                    /* 销毁客户端不需要的数据 */
                    unset($list['product_list'][$key]['activity_price'], $list['product_list'][$key]['activity_type']);
                }
            } else {
                $list['product_list'] = array();
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 产品详情
     */
    public function detail() {
        if ( IS_GET ) {
            $id    = I('get.id', 0, 'intval');
            $token = I('get.token');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 查询产品轮播图 */
            $list['carousel'] = M('ProdcutPicture')->where(array('product_id'=>$id))->order('id ASC')->getField('pic_url', true);
            $list['carousel'] = $list['carousel'] ? $this->getAbsolutePath($list['carousel']) : array();

            /* 查询产品详情和企业信息 */
            $where['l.id']         = array('eq', $id);
            $where['l.status']     = array('eq', 1);
            $where['a.start_time'] = array('lt', time());
            $where['a.end_time']   = array('gt', time());
            $where['a.status']     = array('eq', 1);
            $list['detail'] = M('ProductSale')
                            ->alias('l')
                            ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
                            ->join(C('DB_PREFIX').'activity a ON a.activity_type = l.activity_type', 'LEFT')
                            ->join(C('DB_PREFIX').'product_comment pc ON pc.pid = l.id', 'LEFT')
                            ->field('l.id product_id,l.title,l.short_title,l.price,l.oprice,l.activity_price,l.num,l.sale_num,l.img share_img,l.summary,l.is_spec,l.activity_type,a.status activity_status,r.id company_id,r.name company_name,r.business,r.logo,COUNT(pc.id) product_comment,a.end_time')
                            ->where(array('l.id'=>$id, 'l.status'=>1))
                            ->find();
            !$list['detail']['product_id'] && $this->ajaxJson('70000', '产品不存在或已被下架');
            /* 产品详情里的所有图片地址 */
            preg_match_all("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/", $list['detail']['summary'], $content_img);
            $list['detail']['content_img']   = $content_img[3];
            if ( $list['detail']['content_img'] ) {
                foreach ($list['detail']['content_img'] as $iv) {
                    $list['detail']['summary'] = str_replace($iv, $this->getAbsolutePath($iv), $list['detail']['summary']);
                }
            }
            $list['detail']['content_img']   = $this->getAbsolutePath($list['detail']['content_img']);
            /* 处理数据 */
            $list['detail']['share_img'] = $this->getAbsolutePath($list['detail']['share_img'], '', C('HTTP_APPS_IMG') . 'product_default.png');
            $list['detail']['logo']      = $this->getAbsolutePath($list['detail']['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
            !$list['detail']['company_id'] && $list['detail']['company_id'] = '';
            !$list['detail']['company_name'] && $list['detail']['company_name'] = '';
            /* 产品详情内容web地址 */
            $list['detail']['weburl']       = C('HTTP_ORIGIN') . '?g=app&m=apps&a=product_detail&appsign=1&id=' . $list['detail']['product_id'];
            /* 产品详情分享地址 */
            $list['detail']['shareurl']     = C('HTTP_ORIGIN') . '/?g=app&m=apps&a=product_detail&id=' . $list['detail']['product_id'];
            /* 判断用户是否收藏 登陆信息过期或未登录-按未登录情况展示 */
            $list['detail']['collect_code'] = '40005';
            if ( $token ) {
                /* 通过token获取用户信息 */
                $memberInfo = D('Token')->getMemberInfo($token);
                if ( $memberInfo ) {
                    /* 判断是否收藏 */
                    $where_favorite['uid'] = array('eq', $memberInfo['uid']);
                    $where_favorite['aid'] = array('eq', $list['detail']['product_id']);
                    $where_favorite['favorite_category'] = array('eq', 2);
                    $collectCount = M('UserFavorite')->where($where_favorite)->count('id');
                    $collectCount > 0 && $list['detail']['collect_code'] = '40006';
                }
            }
            /* 判断活动是否开启 */
            if ( $list['detail']['activity_status'] == 1 ) {
                /* 活动剩余结束时间(秒) */
                $list['detail']['residue_time'] = $list['detail']['end_time'] - time();
                /* 产品销量 应该连订单表查询活动时间范围内的销量 时间关系先不做 */
                $list['detail']['sale_num'] = '已抢：' . $list['detail']['sale_num'] . '件';
                /* 现价(活动价) */
                $list['detail']['price'] = $list['detail']['activity_price'];
            } else {
                /* 活动剩余结束时间(秒) */
                $list['detail']['residue_time'] = '';
                /* 产品销量 */
                $list['detail']['sale_num'] = '销量：' . $list['detail']['sale_num'] . '件';
            }
            /* 库存 */
            $list['detail']['num'] = '库存：' . $list['detail']['num'] . '件';
            /* 平台客服电话 */
            $list['detail']['service_phone'] = M('Conf')->where(array('name'=>'companphone'))->getField('value');
            unset($list['detail']['summary'], $list['detail']['end_time'], $list['detail']['activity_price'], $list['detail']['activity_type']);
            /* 商品评论 */
            $list['detail']['product_comment'] = $list['detail']['product_comment'] ? '查看' . $list['detail']['product_comment'] . '条评论' : '暂无评论';

            /* 产品规格 */
            if ($list['detail']['is_spec'] == 1) {
                $list['spec'] = M('ProductSpec')->field('id spec_id,title1,title2,spec1,spec2,price,stock,buymin,img')->where(array('product_id'=>$id))->order('sort DESC,id ASC')->select();
                $list['spec'] = $this->getAbsolutePath($list['spec'], 'img', C('HTTP_APPS_IMG') . 'product_default.png');
                if ( $list['spec'] ) {
                    foreach ($list['spec'] as $key => $val) {
                        $list['spec1'][$key] = $val['spec1'];
                        $val['spec2'] && $list['spec2'][$key] = $val['spec2'];
                    }
                    $list['spec1'] = array_values(array_unique($list['spec1']));
                    $list['spec2'] = $list['spec2'] ? array_values(array_unique($list['spec2'])) : array();
                    /* 库存为0则不返回给客户端 */
                    foreach ($list['spec'] as $speck => $speckv) {
                        if ( !$speckv['stock'] ) {
                            unset($list['spec'][$speck]);
                        }
                    }
                    $list['spec'] = array_values($list['spec']);
                } else {
                    $list['spec'] = array();
                    $list['spec1'] = array();
                    $list['spec2'] = array();
                }
            } else {
                $list['spec'] = array();
                $list['spec1'] = array();
                $list['spec2'] = array();
            }

            /* 为您推荐 */
            $where_recommend['status']     = array('eq', 1);
            $where_recommend['company_id'] = array('eq', $list['detail']['company_id']);
            $where_recommend['id']         = array('neq', $list['detail']['product_id']);
            $list['recommend'] = M('ProductSale')->field('id product_id,title,short_title,price,img')->where($where_recommend)->order('flags DESC,sort DESC,modify_time DESC')->limit($page,6)->select();
            $list['recommend'] = $this->getAbsolutePath($list['recommend'], 'img', C('HTTP_APPS_IMG') . 'product_default.png');

            /* 点击量+1 */
            M('ProductSale')->where(array('id'=>$id))->setInc('click', 1);

            /* 判断是否显示领取优惠券板块 */
            $list['detail']['show_coupon'] = '0';

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 产品评价列表
     */
    public function comment() {
        if ( IS_GET ) {
            $id   = I('get.id', 0, 'intval');
            !$id && $this->ajaxJson('70000', '参数错误');
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            $nav  = I('get.nav', '', 'strval');
            switch ( $nav ) {
                case '1':
                    /* 有图 */
                    $where['l.img'] = array('neq', '');
                    break;
                case '2':
                    /* 满意 */
                    $where['l.praise'] = array('eq', 1);
                    break;
                case '3':
                    /* 一般 */
                    $where['l.praise'] = array('eq', 2);
                    break;
                case '4':
                    /* 不满意 */
                    $where['l.praise'] = array('eq', 3);
                    break;
                default:
                    /* 全部 */
                    break;
            }

            /* 查询商品评论数据 */
            $where['l.pid']    = array('eq', $id);
            $where['l.status'] = array('gt', 0);
            $commentList = M('ProductComment')
                         ->alias('l')
                         ->field('l.id,r.head_pic,r.name,l.praise,l.ctime,s.spec_info,l.content,l.img')
                         ->join(C('DB_PREFIX') . 'member r ON l.uid = r.uid', 'LEFT')
                         ->join(C('DB_PREFIX') . 'order_sub s ON l.order_id = s.orderid', 'LEFT')
                         ->where($where)
                         ->order('l.id DESC')
                         ->group('l.id')
                         ->limit($page,10)
                         ->select();
            !$commentList && $this->returnJson(array('comment_list'=>array()));
            /* 数据处理 */
            // $praiseList = array('1' => '好评', '2' => '中评', '3' => '差评');
            foreach ($commentList as $key => $value) {
                if ( $value['spec_info'] ) {
                    $commentList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']) . ' 商品规格：' . $value['spec_info'];
                } else {
                    $commentList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']);
                }
                $commentList[$key]['img'] = $commentList[$key]['img'] ? explode(',', $commentList[$key]['img']) : array();
                $commentList[$key]['img'] = $this->getAbsolutePath($commentList[$key]['img']);
                // $commentList[$key]['praise'] = $praiseList[$value['praise']];

                /* 销毁客户端不需要的数据 */
                unset($commentList[$key]['ctime'], $commentList[$key]['spec_info']);
            }
            $commentList = $this->getAbsolutePath($commentList, 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            $list['comment_list'] = $commentList;

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }
}