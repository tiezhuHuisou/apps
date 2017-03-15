<?php
namespace Apps\Controller;
use Think\Controller;

class IndexController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 新版首页
     * @author 406764368@qq.com
     * @version 2016年12月3日 00:06:50
     */
    public function index() {
        if ( IS_GET ) {
            /* 接收分页参数 */
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            
            /* 定义客户端图片路径 */
            $appsPath = C('HTTP_APPS_IMG');

            /* 第二页以后直接返回产品数据即可 */
            if ( $page > 0 ) {
                /* 推荐产品 */
                $where_product['status'] = array('eq', 1);
                $where_product['flags']  = array('in', array('a','c'));
                $productRecommendList = M('ProductSale')->field('id data_id,title,short_title sub_title,img,"100" line_height,"0" radius,"" href,"2" href_type,"product_detail" href_model,"" time,price,"" oprice')->where($where_product)->order('flags DESC,sort DESC,id DESC')->limit($page,10)->select();
                $productRecommendList = $this->getAbsolutePath($productRecommendList, 'img', $appsPath . 'product_default.png');
                /* 分页布局类型 */
                $list['section'][] = array(
                    'section_type'        => 'page',
                    'section_magic'       => '',
                    'section_top'         => '0',
                    'section_bottom'      => '0',
                    'section_title_show'  => '0',
                    'section_title_icon'  => '',
                    'section_title_text'  => '推荐产品',
                    'section_title_sub'   => '更多',
                    'section_title_arrow' => '1',
                    'section_title_href'  => 'product_list',
                    'section_datas'       => $productRecommendList
                );

                $this->returnJson($list);
            }

            /* 定义数据库模型 */
            $confModel           = M('Conf');
            $jdpicModel          = M('jdpic');
            $productModel        = M('ProductSale');
            $producCategoryModel = M('ProductSaleCategory');
            $newsModel           = M('Article');
            $activityModel       = M('Activity');
            $companyModel        = M('Company');
            $needModel           = M('ProductBuy');

            /* 网站全称 */
            $webname = $confModel->where(array('name'=>'webname'))->getField('value');

            /**
             * 导航栏
             * 黑色文字用:#151618
             */
            $list['navigation'] = array(
                'bgcolor'           => '',
                'navigation_items'  => array(
                    /* 定位 */
                    array( 'type' => 'location', 'icon' => $appsPath . 'index_nav_location_white.png', 'text' => '全国', 'bg_color' => '', 'text_color' => '#FFFFFF', 'sub_icon' => '', 'magic' => '', 'href_model' => 'near_list', 'is_show' => '1' ),
                    /* 大搜索模块 */
                    array( 'type' => 'search_big', 'icon' => $appsPath . 'index_nav_search_small_black.png', 'text' => '请输入关键词搜索', 'bg_color' => '#F5F5F5', 'text_color' => '#999999', 'sub_icon' => $appsPath . 'index_nav_code_black.png', 'magic' => 'qr_code', 'href_model' => 'search', 'is_show' => '1' ),
                    /* 消息 */
                    array( 'type' => 'message', 'icon' => $appsPath . 'index_nav_message_white.png', 'text' => '', 'bg_color' => '', 'text_color' => '', 'sub_icon' => '', 'magic' => '', 'href_model' => 'message', 'is_show' => '1' ),
                    /* 网站全称 */
                    array( 'type' => 'webname', 'icon' => '', 'text' => $webname, 'bg_color' => '', 'text_color' => '#FFFFFF', 'sub_icon' => '', 'magic' => '', 'href_model' => '', 'is_show' => '1' ),
                    /* 小搜索模块 */
                    array( 'type' => 'search', 'icon' => $appsPath . 'index_nav_search_big_white.png', 'text' => '', 'bg_color' => '', 'text_color' => '', 'sub_icon' => '', 'magic' => '', 'href_model' => 'search', 'is_show' => '1' ),
                    /* 购物车 */
                    array( 'type' => 'cart', 'icon' => $appsPath . 'index_nav_cart_white.png', 'text' => '', 'bg_color' => '', 'text_color' => '#FFFFFF', 'sub_icon' => '', 'magic' => '', 'href_model' => 'cart', 'is_show' => '1' )
                )
            );

            /* 首页轮播图 */
            $where_carousel['tid'] = array('eq', 2);
            $carouselList = $jdpicModel->field('data_id,name title,"" sub_title,thumbnail img,"300" line_height,"0" radius,url href,href_type,href_model,"" time,"" price,"" oprice')->where($where_carousel)->order('listorder DESC,id DESC')->select();
            $carouselList = $this->getAbsolutePath($carouselList, 'img');
            /* 轮播图类型 */
            $list['section'][] = array(
                'section_type'        => 'carousel',
                'section_magic'       => '',
                'section_top'         => '0',
                'section_bottom'      => '0',
                'section_title_show'  => '0',
                'section_title_icon'  => '',
                'section_title_text'  => '',
                'section_title_sub'   => '',
                'section_title_arrow' => '0',
                'section_title_href'  => '',
                'section_datas'       => $carouselList
            );

            /* 产品分类 */
            $where_category['status']  = array('eq', 1);
            $where_category['is_home'] = array('eq', 1);
            $productCategoryList = $producCategoryModel->field('id data_id,name title,"" sub_title,logo img,"50" line_height,"0" radius,"" href,"2" href_type,"product_category" href_model,"" time,"" price,"" oprice')->where($where_category)->order('sort DESC,id DESC')->limit(10)->select();
            $productCategoryList = $this->getAbsolutePath($productCategoryList, 'img', $appsPath . 'category_default.png');
            /* 表格式布局类型 */
            $list['section'][] = array(
                'section_type'        => 'table',
                'section_magic'       => '',
                'section_top'         => '0',
                'section_bottom'      => '1',
                'section_title_show'  => '0',
                'section_title_icon'  => '',
                'section_title_text'  => '',
                'section_title_sub'   => '',
                'section_title_arrow' => '0',
                'section_title_href'  => '',
                'section_datas'       => $productCategoryList
            );

            /* 头条信息 */
            $where_top['is_display'] = array('eq', 1);
            $where_top['flags']      = array('eq', 'a');
            $newsTopList = $newsModel->field('id data_id,title,"" sub_title,"" img,"" line_height,"0" radius,"" href,"2" href_type,"news_detail" href_model,"" time,"" price,"" oprice')->where($where_top)->order('flags DESC,sort DESC,id DESC')->limit(5)->select();
            /* 滚动类型 */
            $list['section'][] = array(
                'section_type'        => 'scroll',
                'section_magic'       => '',
                'section_top'         => '0',
                'section_bottom'      => '10',
                'section_title_show'  => '0',
                'section_title_icon'  => $appsPath . 'index_news_top.png',
                'section_title_text'  => '',
                'section_title_sub'   => '',
                'section_title_arrow' => '1',
                'section_title_href'  => '',
                'section_datas'       => $newsTopList
            );

            /* app版本 1->电商；2->资讯； */
            if ( C('FLASHFLAG') == 1 ) {
                /* 活动模块 */
                $where_activity['status']     = array('eq', 1);
                $where_activity['start_time'] = array('lt', time());
                $where_activity['end_time']   = array('gt', time());
                $activityConfList = $activityModel->field('"" data_id,title,`desc` sub_title,img,"192" line_height,"0" radius,"" href,"2" href_type,activity_type href_model,end_time time,"" price,"" oprice')->where($where_news)->order('id ASC')->where($where_activity)->select();
                /* 存在活动 */
                if ( $activityConfList ) {
                    /* 定义活动布局类型 */
                    $activitySectionInfo = array(
                        'section_type'        => 'activity',
                        'section_magic'       => '',
                        'section_top'         => '0',
                        'section_bottom'      => '10',
                        'section_title_show'  => '0',
                        'section_title_icon'  => '',
                        'section_title_text'  => '',
                        'section_title_sub'   => '',
                        'section_title_arrow' => '0',
                        'section_title_href'  => '',
                        'section_datas'       => array()
                    );
                    /* 活动个数 */
                    $activityConfListCount = count($activityConfList);
                    /* 只存在一种活动 */
                    if ( $activityConfListCount == 1 ) {
                        /* 判断活动数据处理 */
                        switch ( $activityConfList[0]['href_model'] ) {
                            case '1':
                                /* 只存在限时抢购活动 */
                                $activitySectionInfo['section_magic'] = strval($activityConfList[0]['time'] - time());
                                $activitySectionInfo['section_title_show']  = '1';
                                $activitySectionInfo['section_title_text']  = $activityConfList[0]['title'];
                                $activitySectionInfo['section_title_sub']   = '更多';
                                $activitySectionInfo['section_title_arrow'] = '1';
                                $activitySectionInfo['section_title_href']  = 'flash_list';
                                /* 查询限时抢购商品 */
                                $where_flash['status']         = array('eq', 1);
                                $where_flash['activity_type']  = array('eq', 1);
                                $activitySectionInfo['section_datas'] = $productModel->field('id data_id,title,short_title sub_title,img,"175" line_height,"0" radius,"" href,"2" href_type,"product_detail" href_model,"" time,activity_price price,"" oprice')->where($where_flash)->order('flags DESC,sort DESC,id DESC')->limit(9)->select();
                                /* 判断活动信息是否齐全(判断是否有限时抢购商品) */
                                if ( $activitySectionInfo['section_datas'] ) {
                                    /* 限时抢购商品图片处理 */
                                    $activitySectionInfo['section_datas'] = $this->getAbsolutePath($activitySectionInfo['section_datas'], 'img', $appsPath . 'product_default.png');
                                } else {
                                    /* 没有限时抢购商品则不显示活动信息 */
                                    $activitySectionInfo = array();
                                }
                                break;
                            case '2':
                                /* 只存在充值活动 则查询充值规则 用于判断活动信息是否齐全 */
                                $rechargeRuleCount = M('Recharge')->count('id');
                                /* 判断活动信息是否齐全(判断是否有充值规则) */
                                if ( $rechargeRuleCount > 0 ) {
                                    /* 主题布局类型 */
                                    $activitySectionInfo = array(
                                        'section_type'        => 'theme',
                                        'section_magic'       => '',
                                        'section_top'         => '0',
                                        'section_bottom'      => '10',
                                        'section_title_show'  => '0',
                                        'section_title_icon'  => '',
                                        'section_title_text'  => '',
                                        'section_title_sub'   => '',
                                        'section_title_arrow' => '0',
                                        'section_title_href'  => '',
                                        'section_datas'       => array(
                                            array(
                                                'data_id'     => '',
                                                'title'       => '',
                                                'sub_title'   => '',
                                                'img'         => $this->getAbsolutePath($activityConfList[0]['img']),
                                                'line_height' => '200',
                                                'radius'      => '1',
                                                'href'        => '',
                                                'href_type'   => '2',
                                                'href_model'  => 'mine_wallet',
                                                'time'        => '',
                                                'price'       => '',
                                                'oprice'      => '',
                                            )
                                        )
                                    );
                                } else {
                                    /* 没有充值规则则不显示活动信息 */
                                    $activitySectionInfo = array();
                                }
                                break;
                            case '3':
                                /* 只存在优惠券活动 */
                                $activitySectionInfo['section_title_show']  = '1';
                                $activitySectionInfo['section_title_text']  = $activityConfList[0]['title'];
                                $activitySectionInfo['section_title_sub']   = '更多';
                                $activitySectionInfo['section_title_arrow'] = '1';
                                $activitySectionInfo['section_title_href']  = 'coupon_receive_list';
                                /* 查询可领取优惠券信息 */
                                $where_coupon['status'] = array('eq', 1);
                                $activitySectionInfo['section_datas'] = M('coupon')->field('id data_id,title,"" sub_title,img,"175" line_height,"0" radius,"" href,"2" href_type,"coupon_receive_list" href_model,end_time time,money price,"" oprice')->where($where_coupon)->order('id DESC')->limit(9)->select();
                                /* 判断活动信息是否齐全(判断是否可领取优惠券) */
                                if ( $activitySectionInfo['section_datas'] ) {
                                    /* 转变为表格行布局类型 */
                                    $activitySectionInfo['section_type'] = 'product_row';
                                    /* 可领取优惠券图片处理 */
                                    $activitySectionInfo['section_datas'] = $this->getAbsolutePath($activitySectionInfo['section_datas'], 'img', $appsPath . 'product_default.png');
                                } else {
                                    /* 没有可领取优惠券则不显示活动信息 */
                                    $activitySectionInfo = array();
                                }
                                break;
                            default:
                                $activitySectionInfo = array();
                                break;
                        }
                        /* 存在活动信息齐全则返回数据 */
                        $activitySectionInfo && $list['section'][] = $activitySectionInfo;
                    } else {
                        /* 同时存在多种活动 */
                        $activitySectionInfo['section_type'] = $activityConfListCount == 2 ? 'activity_row' : 'activity_three';
                        foreach ($activityConfList as $aclk => $aclv) {
                            $activityList[$aclk]['data_id']     = $aclv['data_id'];
                            $activityList[$aclk]['title']       = $aclv['title'];
                            $activityList[$aclk]['sub_title']   = $aclv['sub_title'];
                            $activityList[$aclk]['img']         = $this->getAbsolutePath($aclv['img']);
                            $activityList[$aclk]['line_height'] = $aclv['line_height'];
                            $activityList[$aclk]['radius']      = $aclv['radius'];
                            $activityList[$aclk]['href']        = $aclv['href'];
                            $activityList[$aclk]['href_type']   = $aclv['href_type'];
                            switch ( $aclv['href_model'] ) {
                                case '1':
                                    $activityList[$aclk]['href_model']  = 'flash_list';
                                    break;
                                case '2':
                                    $activityList[$aclk]['href_model']  = 'mine_wallet';
                                    break;
                                case '3':
                                    $activityList[$aclk]['href_model']  = 'coupon_receive_list';
                                    break;
                                default:
                                    $activityList[$aclk]['href_model']  = '';
                                    break;
                            }
                            $activityList[$aclk]['time']        = strval( $aclv['time'] - time() );
                            $activityList[$aclk]['price']       = $aclv['price'];
                            $activityList[$aclk]['oprice']      = $aclv['oprice'];
                        }
                        $activitySectionInfo['section_datas'] = $activityList;
                        $list['section'][] = $activitySectionInfo;
                    }
                }

                /* 产品分类主题 */
                $productCategoryThemeList = $producCategoryModel->field('id data_id,name title,"" sub_title,theme_img img,"200" line_height,"0" radius,"" href,"2" href_type,"product_category" href_model,"" time,"" price,"" oprice')->where(array('status'=>1, 'is_theme'=>1))->order('sort DESC,id DESC')->select();
                if ( $productCategoryThemeList ) {
                    /* 图片处理 */
                    $productCategoryThemeList = $this->getAbsolutePath($productCategoryThemeList, 'img');
                    /* 一个产品可以同时属于多个分类。特殊情况，原则上是不能在循环里操作数据库的，暂无优化方案 */
                    $where_theme_product['status']  = array('eq', 1);
                    foreach ($productCategoryThemeList as $key => $value) {
                        $where_theme_product['_string'] = 'FIND_IN_SET('.$value['data_id'].',sale_category_id)';
                        $productCategoryThemeList[$key]['product_list'] = $productModel->field('id data_id,title,"" sub_title,img,"170" line_height,"0" radius,"" href,"2" href_type,"product_detail" href_model,"" time,price,"" oprice')->where($where_theme_product)->order('flags DESC,sort DESC,id DESC')->limit(9)->select();
                        /* 主题产品分类下没有产品则不返回给客户端 */
                        if ( !$productCategoryThemeList[$key]['product_list'] ) {
                            unset($productCategoryThemeList[$key]);
                        } else {
                            /* 图片处理 */
                            $productCategoryThemeList[$key]['product_list'] = $this->getAbsolutePath($productCategoryThemeList[$key]['product_list'], 'img', $appsPath . 'product_default.png');
                        }
                    }
                    /* 存在产品分类主题 */
                    if ( $productCategoryThemeList ) {
                        /* 最多返回3个主题给客户端 */
                        $productCategoryThemeList = array_slice($productCategoryThemeList, 0, 3);
                        foreach ($productCategoryThemeList as $pctk => $pctv) {
                            /* 主题布局类型 */
                            unset($pctv['product_list']);
                            $list['section'][] = array(
                                'section_type'        => 'theme',
                                'section_magic'       => '',
                                'section_top'         => '0',
                                'section_bottom'      => '0',
                                'section_title_show'  => '0',
                                'section_title_icon'  => '',
                                'section_title_text'  => '',
                                'section_title_sub'   => '',
                                'section_title_arrow' => '0',
                                'section_title_href'  => '',
                                'section_datas'       => array($pctv)
                            );
                            /* 表格式行布局类型 */
                            $list['section'][] = array(
                                'section_type'        => 'product_row',
                                'section_magic'       => '',
                                'section_top'         => '0',
                                'section_bottom'      => '10',
                                'section_title_show'  => '0',
                                'section_title_icon'  => '',
                                'section_title_text'  => '',
                                'section_title_sub'   => '',
                                'section_title_arrow' => '0',
                                'section_title_href'  => '',
                                'section_datas'       => $productCategoryThemeList[$pctk]['product_list']
                            );
                        }
                    }
                }

                /* 企业主题 */
                $where_company['status'] = array('eq', 1);
                $where_company['flags']  = array('in', array('a','c'));
                $companyThemeList = $companyModel->field('id data_id,"" title,"" sub_title,theme_img img,"200" line_height,"0" radius,"" href,"2" href_type,"company_home" href_model,"" time,"" price,"" oprice')->where($where_company)->order('flags DESC,sort DESC,modify_time DESC')->select();
                if ( $companyThemeList ) {
                    /* 图片处理 */
                    $companyThemeList = $this->getAbsolutePath($companyThemeList, 'img');
                    /* 获取所有主题企业id */
                    $companyThemeIds = array_column($companyThemeList, 'data_id');
                    /* 查询所有主题企业下的所有商品 */
                    $where_theme_company['status']     = array('eq', 1);
                    $where_theme_company['company_id'] = array('in', $companyThemeIds);
                    $companyThemeProductList = $productModel->field('id data_id,title,"" sub_title,img,"170" line_height,"0" radius,"" href,"2" href_type,"product_detail" href_model,"" time,price,"" oprice,company_id')->where($where_theme_company)->order('flags DESC,sort DESC,id DESC')->select();
                    if ( $companyThemeProductList ) {
                        /* 图片处理 */
                        $companyThemeProductList = $this->getAbsolutePath($companyThemeProductList, 'img', $appsPath . 'product_default.png');
                        /* 所有主题企业下的所有产品按企业分组 */
                        foreach ($companyThemeProductList as $ctplk => $ctplv) {
                            /* 销毁不需要的字段 */
                            unset($ctplv['company_id']);
                            /* 每个企业主题下最多返回9个产品 */
                            count($companyThemeProductGroupList[$companyThemeProductList[$ctplk]['company_id']]) < 9 && $companyThemeProductGroupList[$companyThemeProductList[$ctplk]['company_id']][] = $ctplv;
                        }
                        /* 判断企业主题数据是否完整 */
                        foreach ($companyThemeList as $ctlk => $ctlv) {
                            if ( $companyThemeProductGroupList[$ctlv['data_id']] ) {
                                /* 将企业主题下的产品放入企业主题数据数组中 */
                                $companyThemeList[$ctlk]['product_list'] = $companyThemeProductGroupList[$ctlv['data_id']];
                            } else {
                                /* 若企业主题下没有任何产品 则不返回给客户端 */
                                unset($companyThemeList[$ctlk]);
                            }
                        }
                    }
                    /* 存在企业主题 */
                    if ( $companyThemeList ) {
                        /* 最多返回3个主题给客户端 */
                        $companyThemeList = array_slice($companyThemeList, 0, 3);
                        foreach ($companyThemeList as $ctlkey => $ctlval) {
                            /* 主题布局类型 */
                            unset($ctlval['product_list']);
                            $list['section'][] = array(
                                'section_type'        => 'theme',
                                'section_magic'       => '',
                                'section_top'         => '0',
                                'section_bottom'      => '0',
                                'section_title_show'  => '0',
                                'section_title_icon'  => '',
                                'section_title_text'  => '',
                                'section_title_sub'   => '',
                                'section_title_arrow' => '0',
                                'section_title_href'  => '',
                                'section_datas'       => array($ctlval)
                            );
                            /* 表格式行布局类型 */
                            $list['section'][] = array(
                                'section_type'        => 'product_row',
                                'section_magic'       => '',
                                'section_top'         => '0',
                                'section_bottom'      => '10',
                                'section_title_show'  => '0',
                                'section_title_icon'  => '',
                                'section_title_text'  => '',
                                'section_title_sub'   => '',
                                'section_title_arrow' => '0',
                                'section_title_href'  => '',
                                'section_datas'       => $companyThemeList[$ctlkey]['product_list']
                            );
                        }
                    }
                }
            } else {
                /* 推荐资讯 */
                $where_news['is_display'] = array('eq', 1);
                $where_news['flags']      = array('eq', 'c');
                $newsRecommendList = $newsModel->field('id data_id,title,short_title sub_title,image img,"100" line_height,"0" radius,"" href,"2" href_type,"news_detail" href_model,addtime time,"" price,"" oprice')->where($where_news)->order('flags DESC,sort DESC,id DESC')->limit(5)->select();
                $newsRecommendList = $this->getAbsolutePath($newsRecommendList, 'img', $appsPath . 'news_default.png');
                foreach ($newsRecommendList as $key => $value) {
                    $newsRecommendList[$key]['time'] = $this->dateTimeDeal($value['time']);
                }
                /* 资讯式列布局类型 */
                $list['section'][] = array(
                    'section_type'        => 'news_column',
                    'section_magic'       => '',
                    'section_top'         => '0',
                    'section_bottom'      => '10',
                    'section_title_show'  => '1',
                    'section_title_icon'  => '',
                    'section_title_text'  => '推荐资讯',
                    'section_title_sub'   => '更多',
                    'section_title_arrow' => '1',
                    'section_title_href'  => 'news_list',
                    'section_datas'       => $newsRecommendList
                );

                /* 推荐品牌 */
                $where_company['status'] = array('eq', 1);
                $where_company['flags']  = array('in', array('a','c'));
                $companyRecommendList = $companyModel->field('id data_id,name title,"" sub_title,logo img,"150" line_height,"0" radius,"" href,"2" href_type,"company_home" href_model,"" time,"" price,"" oprice')->where($where_company)->order('flags DESC,sort DESC,modify_time DESC')->limit(9)->select();
                $companyRecommendList = $this->getAbsolutePath($companyRecommendList, 'img', $appsPath . 'company_default.png');
                /* 表格式行布局类型 */
                $list['section'][] = array(
                    'section_type'        => 'table_row',
                    'section_magic'       => '',
                    'section_top'         => '0',
                    'section_bottom'      => '10',
                    'section_title_show'  => '1',
                    'section_title_icon'  => '',
                    'section_title_text'  => '推荐品牌',
                    'section_title_sub'   => '更多',
                    'section_title_arrow' => '1',
                    'section_title_href'  => 'company_list',
                    'section_datas'       => $companyRecommendList
                );

                /* 热门求购 */
                $where_need['status'] = array('eq', 1);
                $where_need['flags']  = array('in', array('a','c'));
                $needHotList = $needModel->field('id data_id,title,short_title sub_title,img,"100" line_height,"0" radius,"" href,"2" href_type,"need_detail" href_model,"" time,price,"" oprice')->where($where_need)->order('flags DESC,sort DESC,id DESC')->limit(6)->select();
                $needHotList = $this->getAbsolutePath($needHotList, 'img', $appsPath . 'need_default.png');
                /* 产品式列布局类型 */
                $list['section'][] = array(
                    'section_type'        => 'product_column',
                    'section_magic'       => '',
                    'section_top'         => '0',
                    'section_bottom'      => '10',
                    'section_title_show'  => '1',
                    'section_title_icon'  => '',
                    'section_title_text'  => '热门求购',
                    'section_title_sub'   => '更多',
                    'section_title_arrow' => '1',
                    'section_title_href'  => 'need_list',
                    'section_datas'       => $needHotList
                );
            }

            /* 推荐产品 */
            $where_product['status'] = array('eq', 1);
            $where_product['flags']  = array('in', array('a','c'));
            $productRecommendList = $productModel->field('id data_id,title,short_title sub_title,img,"100" line_height,"0" radius,"" href,"2" href_type,"product_detail" href_model,"" time,price,"" oprice')->where($where_product)->order('flags DESC,sort DESC,id DESC')->limit($page,10)->select();
            $productRecommendList = $this->getAbsolutePath($productRecommendList, 'img', $appsPath . 'product_default.png');
            /* 分页布局类型 */
            $list['section'][] = array(
                'section_type'        => 'page',
                'section_magic'       => '',
                'section_top'         => '0',
                'section_bottom'      => '0',
                'section_title_show'  => '1',
                'section_title_icon'  => '',
                'section_title_text'  => '推荐产品',
                'section_title_sub'   => '更多',
                'section_title_arrow' => '1',
                'section_title_href'  => 'product_list',
                'section_datas'       => $productRecommendList
            );

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 省市区
     */
    public function regions() {
        if ( IS_GET ) {
            $list['regions'] = M('Regions')->field('id regions_id,parent,name')->cache(true)->select();
            foreach ($list['regions'] as $key => $val) {
                $val['parent'] === NULL && $list['regions'][$key]['parent'] = 0;
                $list['regions'][$key]['child'] = array();
            }
            $list['regions'] = list_to_tree($list['regions'], $pk = 'regions_id', $pid = 'parent', $child = 'child', $root = 0);
            
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 求购分类
     */
    public function needCategory() {
        if ( IS_GET ) {
            $where['status']    = array('eq', 1);
            $where['parent_id'] = array('eq', 0);
            $list['category']   = M('ProductSaleCategory')->field('id category_id,name,logo')->where($where)->order('sort DESC,id DESC')->select();
            $list['category']   = $this->getAbsolutePath($list['category'], 'logo', C('HTTP_APPS_IMG') . 'category_default.png');
            array_unshift($list['category'], array('category_id' => '', 'name' => '全部', 'logo' => C('HTTP_APPS_IMG') . 'category_default.png'));

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 求购列表
     */
    public function need() {
        if ( IS_GET ) {
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            $title = I('get.title');
            $cid   = I('get.cid', 0, 'intval');

            /* 搜索标题 */
            !empty($title) && $map['title']  = array('like', '%'.$title.'%');
            $cid && $map['buy_category_id'] = array('eq', $cid);

            /* 求购信息 */
            $map['status']  = array('eq', 1);
            $list['need_list'] = M('ProductBuy')->field('id need_id,title,short_title,price,img')->where($map)->order('flags DESC,sort DESC,modify_time DESC')->limit($page,10)->select();
            $list['need_list'] = $this->getAbsolutePath($list['need_list'], 'img', C('HTTP_APPS_IMG') . 'need_default.png');

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 求购详情
     */
    public function needDetail() {
        if ( IS_GET ) {
            $id    = I('get.id', 0, 'intval');
            $token = I('get.token');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 查询求购轮播图 */
            $list['carousel'] = M('NeedPicture')->where(array('need_id'=>$id))->order('id ASC')->getField('pic_url', true);
            $list['carousel'] = $this->getAbsolutePath($list['carousel']);

            /* 查询产品详情和企业信息 */
            $list['detail'] = M('ProductBuy')
                            ->alias('l')
                            ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
                            ->join(C('DB_PREFIX').'company_link c ON c.company_id = r.id', 'LEFT')
                            ->field('l.id need_id,l.title,l.short_title,l.price,l.img share_img,l.days,l.num,l.summary,l.issue_time,r.id company_id,r.name company_name,r.business,r.logo,c.subphone,c.telephone')
                            ->where(array('l.id'=>$id, 'l.status'=>1))
                            ->find();
            !$list['detail']['need_id'] && $this->ajaxJson('70000', '求购不存在');
            /* 求购详情里的所有图片地址 */
            preg_match_all("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/", $list['detail']['summary'], $content_img);
            $list['detail']['content_img']   = $content_img[3];
            if ( $list['detail']['content_img'] ) {
                foreach ($list['detail']['content_img'] as $iv) {
                    $list['detail']['summary'] = str_replace($iv, $this->getAbsolutePath($iv), $list['detail']['summary']);
                }
            }
            $list['detail']['content_img']   = $this->getAbsolutePath($list['detail']['content_img']);
            /* 处理数据 */
            $list['detail']['logo']      = $this->getAbsolutePath($list['detail']['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
            $list['detail']['share_img'] = $this->getAbsolutePath($list['detail']['share_img'], '', C('HTTP_APPS_IMG') . 'need_default.png');
            if ( $list['detail']['telephone'] ) {
                $list['detail']['mobile'] = array($list['detail']['subphone'], $list['detail']['telephone']);
            } else {
                $list['detail']['mobile'] = $list['detail']['subphone'] ? array($list['detail']['subphone']) : array();
            }
            !$list['detail']['company_id'] && $list['detail']['company_id'] = '';
            !$list['detail']['company_name'] && $list['detail']['company_name'] = '';
            !$list['detail']['business'] && $list['detail']['business'] = '';
            if ( $list['detail']['days'] ) {
                $limitTime = $list['detail']['issue_time'] + $list['detail']['days'] * 86400;
                $list['detail']['days'] = date('Y-m-d', $limitTime);
                $limitTime < time() && $list['detail']['days'] .= '(已过期)';
            } else {
                $list['detail']['days'] = '长期有效';
            }
            !$list['detail']['num'] && $list['detail']['num'] = '不限';
            unset($list['detail']['subphone'], $list['detail']['telephone'], $list['detail']['issue_time']);
            /* 产品详情内容web地址 */
            $list['detail']['weburl']       = C('HTTP_ORIGIN') . '?g=app&m=apps&a=need_detail&appsign=1&id=' . $list['detail']['need_id'];
            /* 产品详情分享地址 */
            $list['detail']['shareurl']     = C('HTTP_ORIGIN') . '/?g=app&m=apps&a=need_detail&id=' . $list['detail']['need_id'];
            /* 判断用户是否收藏 登陆信息过期或未登录-按未登录情况展示 */
            $list['detail']['collect_code'] = '40005';
            if ( $token ) {
                /* 通过token获取用户信息 */
                $memberInfo = D('Token')->getMemberInfo($token);
                if ( $memberInfo ) {
                    /* 判断是否收藏 */
                    $where_favorite['uid'] = array('eq', $memberInfo['uid']);
                    $where_favorite['aid'] = array('eq', $list['detail']['need_id']);
                    $where_favorite['favorite_category'] = array('eq', 4);
                    $collectCount = M('UserFavorite')->where($where_favorite)->count('id');
                    $collectCount > 0 && $list['detail']['collect_code'] = '40006';
                }
            }

            /* 为您推荐 */
            $where_recommend['status']     = array('eq', 1);
            $where_recommend['company_id'] = array('eq', $list['detail']['company_id']);
            $where_recommend['id']         = array('neq', $list['detail']['need_id']);
            $list['recommend'] = M('ProductBuy')->field('id need_id,title,short_title,price,img')->where($where_recommend)->order('flags DESC,sort DESC,modify_time DESC')->limit($page,6)->select();
            $list['recommend'] = $this->getAbsolutePath($list['recommend'], 'img', C('HTTP_APPS_IMG') . 'need_default.png');

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 附近公司
     */
    public function near() {
        if ( IS_GET ) {
            $title     = I('get.title');
            $longitude = I('get.longitude');
            $latitude  = I('get.latitude');
            if ( empty($longitude) || empty($latitude) ) {
                $this->ajaxJson('70000', '参数错误');
            }

            /* 附近经纬度 */
            $arounds = $this->getAround($latitude, $longitude);

            /* 查询附近 */
            $map['r.lat'] = array("between", array($arounds['minLat'], $arounds['maxLat']));
            $map['r.lng'] = array("between", array($arounds['minLng'], $arounds['maxLng']));
            !empty($title) && $map['l.name'] = array('like', '%'.$title.'%');
            $list = M('Company')
                  ->alias('l')
                  ->join(C('DB_PREFIX').'company_link r ON l.id = r.company_id', 'LEFT')
                  ->field('l.id company_id,l.name,l.business,l.logo,r.lat,r.lng,r.subphone,r.telephone')
                  ->where($map)
                  ->select();
            $list = $this->getAbsolutePath($list, 'logo', C('HTTP_APPS_IMG') . 'company_default.png');
            $keyval = array();

            /* 数据处理 */
            foreach ($list as $k => $v) {
                /* 主营行业处理 */
                $v['business'] && $list[$k]['business'] = '主营行业：' . $v['business'];
                /* 计算距离 */
                $meters = $this->getMeter($latitude, $longitude, $v['lat'], $v['lng']);
                $list[$k]['sortmeter'] = $meters;
                if ($meters > 1000) {
                    $list[$k]['meters'] = sprintf("%.2f", $meters / 1000) . 'km';
                } else {
                    $list[$k]['meters'] = $meters . 'm';
                }
                $keyval[] = $meters;
                /* 企业电话 */
                if ( $v['telephone'] ) {
                    $list[$k]['mobile'] = array($v['subphone'], $v['telephone']);
                } else {
                    $list[$k]['mobile'] = $v['subphone'] ? array($v['subphone']) : array();
                }
                unset($list[$k]['sortmeter'], $list[$k]['subphone'], $list[$k]['telephone']);
            }

            /* 按距离排序 */
            array_multisort($keyval, SORT_ASC, $list);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 根据经纬度和半径计算出经纬度的范围
     * @param  [integer]  $latitude   [纬度]
     * @param  [integer]  $longitude  [经度]
     * @param  {integer}  $raidusMile [范围（单位：米）]
     * @return [Array]                [最小纬度，最大纬度，最小经度，最大纬度]
     */
    private function getAround($latitude, $longitude, $raidusMile = 3000) {
        $PI = 3.14159265;
        $degree = (24901 * 1609) / 360.0;
        $dpmLat = 1 / $degree;
        $radiusLat = $dpmLat * $raidusMile;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;
        $mpdLng = $degree * cos($latitude * ($PI / 180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng * $raidusMile;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        return array('minLat' => $minLat, 'maxLat' => $maxLat, 'minLng' => $minLng, 'maxLng' => $maxLng);
    }

    /**
     * 获取位置PHP计算用
     * 根据经纬度计算实际距离 米
     * @param unknown_type $lat1 纬度1
     * @param unknown_type $lng1 经度1
     * @param unknown_type $lat2 纬度2
     * @param unknown_type $lng2 经度2
     */
    private function getMeter($lat1, $lng1, $lat2, $lng2) {
        $PI = pi();
        $earthR = 6378137;
        $radlat1 = $lat1 * ($PI / 180);
        $radlng1 = $lng1 * ($PI / 180);
        $radlat2 = $lat2 * ($PI / 180);
        $radlng2 = $lng2 * ($PI / 180);
        $a = $radlat1 - $radlat2;
        $b = $radlng1 - $radlng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = round($s * $earthR);
        return $s;
    }

    /**
     * curl get 方法
     * @param $url 请求地址
     */
    private function http_get($url) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
}