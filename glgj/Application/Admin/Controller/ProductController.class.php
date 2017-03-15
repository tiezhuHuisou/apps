<?php
namespace Admin\Controller;

use Think\Controller;

class ProductController extends AdminController {

    protected function _initialize() {
        parent::_initialize();
        $this->assign('site', 'product');
    }

    /**
     * 缓存省市区
     * 用户后代码查询省市区
     */
    protected function regionsCache() {
        $info = M('Regions')->cache(true)->getField('id,name', true);
        return $info;
    }

    /* 车辆类型 */
    protected $truck_type = array('平板车', '高栏车', '集装车', '厢式车', '半封闭', '单桥车', '双桥车', '冷藏车', '轿车运输车', '特种车', '大件车', '危险品车', '封闭车', '半挂车', '商品运输车', '挂车', '爬梯车', '可拼车', '低栏车', '半挂一拖二', '半挂一拖三', '半挂二拖二', '半挂二拖三', '前四后四', '前四后六', '前四后八', '前四后十', '五轮车', '后八轮', '罐式车', '自卸车', '棉被车', '高低高板', '高低平板', '超低板', '金杯车', '敞篷车', '笼子车', '起重车', '面包车', '仓栏车', '翻斗车', '中栏车', '保温车', '其他');

    /* 车辆长度 */
    protected $truck_length = array('21.0m', '20.8m', '20.6m', '20.5m', '20m', '19.5m', '19m', '18.5m', '18.0m', '17.8m', '17.5m', '17.0m', '16.5m', '16.0m', '15.5m', '15.0m', '14.5m', '14m', '13.5m', '13.0m', '12.5m', '12m', '11m', '10.5m', '10m', '9.8m', '9.6m', '9.2m', '9m', '8.8m', '8.7m', '8.6m', '8.5m', '8.2m', '8.0m', '7.8m', '7.7m', '7.6m', '7.5m', '7.4m', '7.2m', '7m', '6.8m', '6.5m', '6.3m', '6.2m', '6m', '5.8m', '5.7m', '5.3m', '5m', '4.8m', '4.5m', '4.3m', '4.2m', '4m', '3.8m');

    /* 货物类型 */
    protected $goods_type = array('重货', '轻货');

//    /* 运输类型 */
//    protected $transport = array('物流公司', '整车配货', '零担配货');

    /*车源类型*/
    protected $source_type = array('单程', '返程', '专线', '长途', '倒短', '配送');

    /*车辆数量*/
    protected function truck_num() {
        $k = '';
        $array = array();
        for ($k = 1; $k < 71; $k++) {
            $array[] = $k;
        }
        return $array;
    }

    /*重货单位*/
    protected $heavy_unit = array('吨', '公斤');
    /*轻货单位*/
    protected $light_unit = array('方');

    /*仓储,类型*/
    protected $depotCategory = array('1' => '厂房', '2' => '仓库', '3' => '车位', '4' => '土地', '5' => '其他', '6' => '标准库', '7' => '恒温库', '8' => '冷库', '9' => '保税库', '10' => '危险品', '12' => '电商库');
    /*仓储，库内地面*/
    protected $depotGround = array('防尘', '高标水泥', '地砖', '环氧', '防潮', '防静电', '金刚砂', '其他');
    /*仓储，主体结构*/
    protected $depotStructure = array('钢混结构', '彩钢结构', '砖混结构', '其他');
    /*仓储，建筑标准*/
    protected $depotStandard = array('钢混结构', '彩钢结构', '砖混结构', '其他');

    /*货物管理*/
    /*货物运输方式*/
    protected $goodsTransport = array('1' => '物流公司', '2' => '整车配货', '3' => '零担配货');
    /*货物类型*/
    protected $goodsType = array('1' => '货', '2' => '重货', '3' => '轻货');
    /*货物名称*/
    protected $goodsName = array('1' => '设备', '2' => '煤炭', '3' => '矿产', '4' => '钢材', '5' => '饲料', '6' => '建材', '7' => '木材', '8' => '粮食', '9' => '食品', '10' => '饮料', '11' => '蔬菜', '12' => '电器', '13' => '化工产品', '14' => '畜产品');

    /*运费意向单位*/
    protected $freight = array('元/车', '元/吨', '元/批', '价格面议');

//    /**
//     * 产品（供应）管理首页
//     * @author 83961014@qq.com
//     */
//    public function index() {
//        $title = I('title');
//        $flags = I('flags');
//        $activity_type = I('activity_type');
//        if (!empty($title)) {
//            $where['title'] = array('like', '%' . $title . '%');
//            $this->assign('title', $title);
//        }
//        if ($flags) {
//            $where['flags'] = $flags;
//            $this->assign('f', $flags);
//        }
//        if ($activity_type) {
//            $where['activity_type'] = $activity_type;
//            $this->assign('activity_type', $activity_type);
//        }
//        $classify = M('ProductSaleCategory');
//        $clist = $classify->order('sort DESC,id DESC')->select();
//        // $clist = lowest($clist);
//        $this->assign('classify', $clist); // 分类
//        $order = 'id DESC';
//        $list = $this->lists('ProductSale', $where, $order);
//        $flags = D('Flags');
//        $where_category['status'] = array('eq', 1);
//        $categoryList = M('ProductSaleCategory')->where($where_category)->getField('id,name', true);
//        foreach ($list as &$val) {
//            /* 分类处理 */
//            $val['sale_category_id'] = explode(',', $val['sale_category_id']);
//            foreach ($val['sale_category_id'] as $v) {
//                $val['classify'][] = $categoryList[$v];
//            }
//            $val['classify'] = implode(',', $val['classify']);
//            /* 属性 */
//            $val['flags'] = $flags->where(array(array('att' => $val['flags']), array('att' => array('neq', ''))))->getField('attname');
//            /* 价格处理 */
//            $val['price'] = explode(',', $val['price']);
//            $val['price'] = array_unique($val['price']);
//            sort($val['price']);
//            foreach ($val['price'] as $pk => $pv) {
//                $val['price'][$pk] = '￥' . $pv . '元';
//                if ($pv == 0) {
//                    $val['price'] = '保密';
//                    break;
//                }
//            }
//            is_array($val['price']) && $val['price'] = implode(' - ', $val['price']);
//        }
//        $this->assign('list', $list);
//        $where1['attname'] = array('neq', '普通');
//        $flags = $flags->where($where1)->select();
//        $this->assign('flags', $flags);
//        /*页面基本设置*/
//        $this->site_title = "产品管理";
//        $this->assign('left', 'index');
//        $this->display();
//    }
//
//    /**
//     * 添加、修改产品
//     */
//    public function add() {
//        $id = I('request.id', 0, 'intval');
//        $model = D('ProductSale');
//        $opt = $id > 0 ? '修改' : '添加';
//        if (IS_POST) {
//            $result = $model->update();
//            if ($result) {
//                $this->success($opt . '成功', '?g=admin&m=product');
//            } else {
//                $errorInfo = $model->getError();
//                $this->error($errorInfo);
//            }
//        }
//
//        /* 修改 */
//        if ($id) {
//            /* 商品详细信息 */
//            $condition['id'] = $id;
//            $detail = $model->getProductSaleInfo($condition);
//            $this->assign('detail', $detail);
//            /* 轮播图 */
//            $allpic = M('ProdcutPicture')->field('pic_url')->where(array('product_id' => $id))->order('id ASC')->select();
//            $this->assign('allpic', $allpic);
//            /* 商品规格 */
//            if ($detail['is_spec'] == 1) {
//                $specList = M('ProductSpec')->field('title1,title2,spec1,spec2,price,oprice,activity_price,weight,stock,buymin,sort,img')->where(array('product_id' => $id))->order('sort DESC,id ASC')->select();
//                $specTitle1 = $specList[0]['title1'];
//                $specTitle2 = $specList[0]['title2'];
//                $this->assign('specList', $specList);
//                $this->assign('specTitle1', $specTitle1);
//                $this->assign('specTitle2', $specTitle2);
//            }
//        }
//
//        /* 发布企业 */
//        $company = M('Company')->field('id,name')->where(array('status'=>1))->select();
//        $this->assign('company', $company);
//
//        /* 分类 */
//        $category = M('ProductSaleCategory')->field('id,name,parent_id')->select();
//        $category = $this->dumpTreeList($category);
//        $this->assign('category', $category);
//
//        /* 属性 */
//        $flags = M('Flags')->field('att,attname')->select();
//        $this->assign('flags', $flags);
//
//        /* 查询限时抢购活动开关 */
//        $this->assign('flashFlag', C('FLASHFLAG'));
//
//        /* 页面基本设置 */
//        $this->site_title = $opt . '产品';
//        $this->assign('left', 'index');
//        $this->display();
//    }
//
//    /* 产品生成二维码（支持批量） */
//    public function qrcode() {
//        if (IS_POST) {
//            $id = $_POST['id'];
//            if (!is_array($id)) {
//                $id = array($id);
//            }
//            $model = M('ProductSale');
//            foreach ($id as $key => $value) {
//                if ($value) {
//                    $href = C('HTTP_ORIGIN') . '/?g=app&m=apps&a=product_detail&id=' . $value;
//                    $savepath = '/Uploads/Admin/image/' . date('Ymd');
//                    $filename = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
//                    $qrcode = C('HTTP_ORIGIN') . $savepath . '/' . $filename;
//                    $savepath = '.' . $savepath;
//                    if (!is_dir($savepath)) {
//                        mkdir($savepath, 0755, true);
//                    }
//                    $savepath = $savepath . '/' . $filename;
//                    vendor('phpqrcode.qrlib');
//                    \QRcode::png($href, $savepath, 'H', 300, 2);
//                    $save = $model->where(array('id' => $value))->setField('qrcode', $qrcode);
//                    $save === false && $this->error('生成二维码失败');
//                    $save = false;
//                } else {
//                    $this->error('参数错误');
//                }
//            }
//            $this->success('生成二维码成功');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * 产品批量删除
//     * @author 83961014@qq.com
//     */
//    public function product_delall() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $ids = implode(',', $ids);
//            $product = D('ProductSale');
//            $condition['id'] = array('in', $ids);
//            $tem = $product->delProductSale($condition);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "删除成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "删除失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//    /**
//     * 产品删除
//     * @author 83961014@qq.com
//     */
//    public function product_del() {
//        $id = I('id');
//        $product = D('ProductSale');
//        $condition['id'] = $id;
//        $return = $product->delProductSale($condition);
//        if ($return != false) {
//            $this->success('删除成功');
//        } else {
//            $this->error('删除失败');
//        }
//    }
//
//    /**
//     * 改变产品分类
//     * @author 83961014@qq.com
//     */
//    public function up_classify() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $classify = I('classify');
//            $ids = implode(',', $ids);
//            $product = M('ProductSale');
//            $where['id'] = array('in', $ids);
//            $data['sale_category_id'] = $classify;
//            $tem = $product->where($where)->save($data);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "修改成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "修改失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//
//    /**
//     * 产品（供应）分类
//     * @author 83961014@qq.com
//     */
//    public function classify() {
//        $order = 'sort desc';
//        //$list = $this->lists('product_sale_category','',$order);
//        $list = M('ProductSaleCategory')->order($order)->select();
//        $list = list_to_tree($list, 'id', 'parent_id');
//        $this->assign('list', $list);
//        /*页面基本设置*/
//        $this->site_title = "产品分类列表";
//        $this->assign('left', 'classify');
//        $this->display();
//    }
//
//    /**
//     * ajax获取产品（供应）子分类
//     * @author 83961014@qq.com
//     */
//    public function get_classify() {
//        $pid = I('pid');
//        $order = 'sort desc';
//        $classify = M('ProductSaleCategory');
//        $list = $classify->where(array('parent_id' => $pid))->order($order)->select();
//        echo json_encode($list);
//    }
//
//    /**
//     * 添加产品（供应）分类
//     * @author 83961014@qq.com
//     */
//    public function classify_add() {
//        $id = I('id');
//        $condition['id'] = $id;
//        $classify = D('ProductSaleCategory');
//        $detail = $classify->getProductSaleCategoryInfo($condition);
//        $where_category['status'] = array('eq', 1);
//        $id && $where_category['id'] = array('neq', $id);
//        $list = $classify->where($where_category)->select();
//        $list = $this->dumpTreeList($list);
//        foreach ($list as $key => $value) {
//            /* 去掉三级分类 确保最多只有三级分类 */
//            if ($value['level'] == 3) {
//                unset($list[$key]);
//            }
//        }
//        array_values($list);
//        if (IS_POST) {
//            $status = $classify->update();
//            if ($status) {
//                if ($id) {
//                    $this->success('修改成功', '?g=admin&m=product&a=classify');
//                } else {
//                    $this->success('添加成功', '?g=admin&m=product&a=classify');
//                }
//
//            } else {
//                $errorInfo = $classify->getError();
//                $this->error($errorInfo);
//            }
//        }
//        $this->assign('list', $list);
//        $this->assign('detail', $detail);
//        $this->assign('origin', C('HTTP_ORIGIN'));
//        /*页面基本设置*/
//        $this->site_title = "添加/修改产品分类";
//        $this->assign('left', 'classify');
//        $this->display();
//    }
//
//    /**
//     * 产品分类删除
//     * @author 83961014@qq.com
//     */
//    public function classify_del() {
//        $id = I('id');
//        $classify = D('ProductSaleCategory');
//        $condition['id'] = $id;
////      $condition['parent_id'] = $id;
////      $condition['_logic'] = 'OR';
//        $return = $classify->delProductSaleCategory($condition);
//        if ($return != false) {
//            $this->success('删除成功');
//        } else {
//            $this->error('删除失败');
//        }
//    }
//
//    /**
//     * 产品分类批量删除
//     * @author 83961014@qq.com
//     */
//    public function classify_delall() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $ids = implode(',', $ids);
//            $classfiy = D('ProductSaleCategory');
//            $condition['id'] = array('in', $ids);
////          $condition['parent_id'] = array('in',$ids);
////          $condition['_logic'] = 'OR';
//            $tem = $classfiy->delProductSaleCategory($condition);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "删除成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "删除失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//    /**
//     * 求购管理
//     * @author 83961014@qq.com
//     */
//    public function need() {
//        if (IS_POST) {
//            $title = I('title');
//            if (!empty($title)) {
//                $where['title'] = array('like', '%' . $title . '%');
//                $this->assign('title', $title);
//            }
//        }
//        $order = 'flags DESC,id desc';
//        $list = $this->lists('product_buy', $where, $order);
//
//        $flags = M('Flags');
//        $where_category['parent_id'] = array('eq', 0);
//        $where_category['status'] = array('eq', 1);
//        $categoryList = M('ProductSaleCategory')->where($where_category)->getField('id,name', true);
//        foreach ($list as $key => $value) {
//            $list[$key]['classify'] = $categoryList[$value['buy_category_id']];
//            $list[$key]['flags'] = $flags->where(array(array('att' => $value['flags']), array('att' => array('neq', ''))))->getField('attname');
//            $list[$key]['price'] = explode(',', $value['price']);
//            $list[$key]['price'] = array_unique($list[$key]['price']);
//            sort($list[$key]['price']);
//            foreach ($list[$key]['price'] as $pk => $pv) {
//                $list[$key]['price'][$pk] = '￥' . $pv . '元';
//                if ($pv == 0) {
//                    $list[$key]['price'] = '面议';
//                    break;
//                }
//            }
//            is_array($list[$key]['price']) && $list[$key]['price'] = implode(' - ', $list[$key]['price']);
//        }
//        /*$classify = D('ProductBuyCategory');
//        $field="name";
//        foreach ($list as &$val){
//            $condition['id'] = $val['buy_category_id'];
//            $tmp = $classify->getProductBuyCategoryInfo($condition,$field);
//            $val['classify'] = $tmp['name'];
//        }*/
//        $this->assign('list', $list);
//        /*页面基本设置*/
//        $this->site_title = "求购管理";
//        $this->assign('left', 'need');
//        $this->display();
//    }
//
//    /**
//     * 添加求购
//     * @author 83961014@qq.com
//     */
//    public function need_add() {
//        $id = I('id');
//        $condition['id'] = $id;
//        $buy = D('ProductBuy');
//        if (IS_POST) {
//            $status = $buy->update();
//            if ($status) {
//                if ($id) {
//                    $this->success('修改成功', '?g=admin&m=product&a=need');
//                } else {
//                    $this->success('添加成功', '?g=admin&m=product&a=need');
//                }
//            } else {
//                $errorInfo = $buy->getError();
//                $this->error($errorInfo);
//            }
//        }
//        /*页面基本设置*/
//        $this->site_title = "添加求购";
//        /*$classify = M('ProductBuyCategory')->select();
//        $classify = lowest($classify);
//        $this->assign('classify',$classify);//分类*/
//
//        $detail = $buy->getProductBuyInfo($condition);
//        $this->assign('detail', $detail);
//
//        if ($id) {
//            $allpic = M('NeedPicture')->where(array('need_id' => $id))->select();
//            $this->assign('allpic', $allpic);//求购轮播图
//        }
//
//        $flags = M('Flags')->select();
//        $this->assign('flags', $flags);//属性
//
//        $where_category['parent_id'] = array('eq', 0);
//        $where_category['status'] = array('eq', 1);
//        $categoryList = M('ProductSaleCategory')->field('id,name')->where($where_category)->select();
//        $this->assign('classify', $categoryList);
//
//        /* 发布人(企业) */
//        /*if ( $detail['issue_type'] == 1 ) {
//            $company = M('Member')->field('uid id,name,mphone as mobile')->select();
//        } else {
//            $map['status'] = array('eq', 1);
//            $company = M('Company')->field('user_id id,name')->where($map)->select();
//        }*/
//        $company = M('Company')->field('id,name')->where(array('status' => 1))->select();
//        $this->assign('company', $company);
//
//        $this->assign('left', 'need');
//        $this->display();
//    }
//
//    /**
//     * 求购删除
//     * @author 83961014@qq.com
//     */
//    public function need_del() {
//        $id = I('id');
//        $classify = D('ProductBuy');
//        $condition['id'] = $id;
//        //      $condition['parent_id'] = $id;
//        //      $condition['_logic'] = 'OR';
//        $return = $classify->delProductBuy($condition);
//        if ($return != false) {
//            $this->success('删除成功');
//        } else {
//            $this->error('删除失败');
//        }
//    }
//
//    /**
//     * 求购批量删除
//     * @author 83961014@qq.com
//     */
//    public function need_delall() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $ids = implode(',', $ids);
//            $classfiy = D('ProductBuy');
//            $condition['id'] = array('in', $ids);
//            //          $condition['parent_id'] = array('in',$ids);
//            //          $condition['_logic'] = 'OR';
//            $tem = $classfiy->delProductBuy($condition);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "删除成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "删除失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//    /**
//     * 求购分类
//     * @author 83961014@qq.com
//     */
//    public function need_classify() {
//        $order = 'sort desc';
//        //$list = $this->lists('product_buy_category','',$order);
//        $list = M('ProductBuyCategory')->order($order)->select();
//        $list = list_to_tree($list, 'id', 'parent_id');
//        $this->assign('list', $list);
//        /*页面基本设置*/
//        $this->site_title = "求购分类";
//        $this->assign('left', 'need_classify');
//        $this->display();
//    }
//
//    /**
//     * 添加分类
//     * @author 83961014@qq.com
//     */
//    public function need_classify_add() {
//        $id = I('id');
//        $condition['id'] = $id;
//        $classify = D('ProductBuyCategory');
//        $detail = $classify->getProductBuyCategoryInfo($condition);
//        $where_category['status'] = array('eq', 1);
//        $id && $where_category['id'] = array('neq', $id);
//        $list = $classify->where($where_category)->select();
//        $list = lowest($list);
//        if (IS_POST) {
//            $status = $classify->update();
//            if ($status) {
//                if ($id) {
//                    $this->success('修改成功', '?g=admin&m=product&a=need_classify');
//                } else {
//                    $this->success('添加成功', '?g=admin&m=product&a=need_classify');
//                }
//            } else {
//                $errorInfo = $classify->getError();
//                $this->error($errorInfo);
//            }
//        }
//        $this->assign('list', $list);
//        $this->assign('detail', $detail);
//        /*页面基本设置*/
//        $this->site_title = "添加分类";
//        $this->assign('left', 'need_classify');
//        $this->display();
//    }
//
//    /**
//     * 求购分类删除
//     * @author 83961014@qq.com
//     */
//    public function need_classify_del() {
//        $id = I('id');
//        $classify = D('ProductBuyCategory');
//        $condition['id'] = $id;
//        //      $condition['parent_id'] = $id;
//        //      $condition['_logic'] = 'OR';
//        $return = $classify->delProductBuyCategory($condition);
//        if ($return != false) {
//            $this->success('删除成功');
//        } else {
//            $this->error('删除失败');
//        }
//    }
//
//    /**
//     * 求购分类批量删除
//     * @author 83961014@qq.com
//     */
//    public function need_classify_delall() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $ids = implode(',', $ids);
//            $classfiy = D('ProductBuyCategory');
//            $condition['id'] = array('in', $ids);
//            //          $condition['parent_id'] = array('in',$ids);
//            //          $condition['_logic'] = 'OR';
//            $tem = $classfiy->delProductBuyCategory($condition);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "删除成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "删除失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//    /**
//     * 供应管理
//     * @author 83961014@qq.com
//     */
//    public function supply() {
//        if (IS_POST) {
//            $title = I('title');
//            if (!empty($title)) {
//                $where['title'] = array('like', '%' . $title . '%');
//                $this->assign('title', $title);
//            }
//        }
//        $order = 'issue_time desc';
//        $list = $this->lists('product_supply', $where, $order);
//        $classify = D('ProductBuyCategory');
//        $field = "name";
//        foreach ($list as &$val) {
//            $condition['id'] = $val['supply_category_id'];
//            $tmp = $classify->getProductBuyCategoryInfo($condition, $field);
//            $val['classify'] = $tmp['name'];
//        }
//        $this->assign('list', $list);
//        /*页面基本设置*/
//        $this->site_title = "供应管理";
//        $this->assign('left', 'supply');
//        $this->display();
//    }
//
//    /**
//     * 添加供应
//     * @author 83961014@qq.com
//     */
//    public function supply_add() {
//        $id = I('id');
//        $condition['id'] = $id;
//        $buy = D('ProductSupply');
//        if (IS_POST) {
//            $status = $buy->update();
//            if ($status) {
//                if ($id) {
//                    $this->success('修改成功', '?g=admin&m=product&a=supply');
//                } else {
//                    $this->success('添加成功', '?g=admin&m=product&a=supply');
//                }
//            } else {
//                $errorInfo = $buy->getError();
//                $this->error($errorInfo);
//            }
//        }
//        /*页面基本设置*/
//        $this->site_title = "添加供应";
//        $classify = M('ProductBuyCategory')->select();
//        $classify = lowest($classify);
//        $this->assign('classify', $classify);//分类
//
//        $detail = $buy->getProductSupplyInfo($condition);
//        $this->assign('detail', $detail);
//
//        /* 发布人(企业) */
//        if ($detail['issue_type'] == 1) {
//            $company = M('Member')->field('uid as id,name,mphone as mobile')->select();
//        } else {
//            $map['status'] = array('eq', 1);
//            $company = M('Company')->field('user_id id,name')->where($map)->select();
//        }
//        $this->assign('company', $company);
//
//        $this->assign('left', 'supply');
//        $this->display();
//    }
//
//    /**
//     * 求购删除
//     * @author 83961014@qq.com
//     */
//    public function supply_del() {
//        $id = I('id');
//        $classify = D('ProductSupply');
//        $condition['id'] = $id;
//        //      $condition['parent_id'] = $id;
//        //      $condition['_logic'] = 'OR';
//        $return = $classify->delProductSupply($condition);
//        if ($return != false) {
//            $this->success('删除成功');
//        } else {
//            $this->error('删除失败');
//        }
//    }
//
//    /**
//     * 求购批量删除
//     * @author 83961014@qq.com
//     */
//    public function supply_delall() {
//        if (IS_POST) {
//            $ids = I('ids');
//            $ids = implode(',', $ids);
//            $classfiy = D('ProductSupply');
//            $condition['id'] = array('in', $ids);
//            //          $condition['parent_id'] = array('in',$ids);
//            //          $condition['_logic'] = 'OR';
//            $tem = $classfiy->delProductSupply($condition);
//            if ($tem != false) {
//                $return['errno'] = 0;
//                $return['error'] = "删除成功";
//                $this->ajaxReturn($return);
//            } else {
//                $return['errno'] = 1;
//                $return['error'] = "删除失败";
//                $this->ajaxReturn($return);
//            }
//        }
//    }
//
//    /**
//     * 供应、求购发布者类型拉取对应数据
//     */
//    public function issuetype() {
//        $type = I('post.type', 0, 'intval');
//        if ($type === 0) {
//            $this->error('参数错误');
//        }
//        if ($type == 1) {
//            $issuetype = M('Member')->field('uid as id,name,mphone as mobile')->select();
//        } else {
//            $issuetype = M('Company')->field('user_id id,name')->where(array('status' => 1))->select();
//        }
//        $this->ajaxReturn($issuetype);
//    }
//
//    /**
//     * 充值订单
//     * @author 83961014@qq.com
//     */
//    public function recharge() {
//        if (IS_POST) {
//            $orderid = I('orderid');
//            $time_start = I('time_start');
//            $time_end = I('time_end');
//            if (!empty($orderid)) {
//                $where['id'] = array('like', '%' . $orderid . '%');
//                $this->assign('orderid', $orderid);
//            }
//            if (!empty($time_start) || !empty($time_end)) {
//                $this->assign('time_start', $time_start);
//                $this->assign('time_end', $time_end);
//                $time_start = strtotime($time_start);
//                $time_end = strtotime($time_end);
//                $where['add_time'] = array('between', array($time_start, $time_end));
//            }
//        }
//        $order = 'add_time desc';
//        $list = $this->lists('recharge', $where, $order);
//        $order = array();
//        foreach ($list as $key => $val) {
//            $order[$key]['orderid'] = $val['id'];
//            $order[$key]['addtime'] = $val['add_time'];
//            $order[$key]['title'] = '余额充值';
//            $order[$key]['nums'] = '1';
//            $order[$key]['price'] = $val['pay_money'];
//            $order[$key]['paymoney'] = $val['pay_money'];
//            $order[$key]['expressprice'] = '0';
//            $order[$key]['state'] = $val['state'];
//            $order[$key]['img'] = '';
//            $order[$key]['type'] = '1';
//            $order[$key]['pay_time'] = $val['pay_time'];
//        }
//        $this->assign('list', $order);
//        $this->assign('state', '6');
//        /*页面基本设置*/
//        $this->site_title = "订单管理";
//        $this->assign('left', 'order');
//        $this->display();
//    }
//
//    /**
//     * 评价管理
//     * @author 83961014@qq.com
//     */
//    public function comment() {
//        $id = I('id');
//        $type = I('type');
//        $order = 'ctime desc';
//        $condition['pid'] = $id;
//        // $condition['type'] = $type;
//        $list = $this->lists('ProductComment', $condition, $order);
//        $review = M('ProductComment');
//        $member = M('Member');
//        foreach ($list as $key => $val) {
//            $user = $member->where(array('uid' => $val['uid']))->find();
//            $list[$key]['uname'] = $user['name'];
//            // $list[$key]['reply'] = $review->where(('pid = '.$val['id']))->select();
//            // foreach ($list[$key]['reply'] as $key1=>$val1){
//            //     $user = $member->where(array('uid'=>$val1['uid']))->find();
//            //     $list[$key]['reply'][$key1]['uname'] = $user['nickname'];
//            // }
//        }
//        $this->assign('list', $list);
//        /*判断产品是否是自营产品*/
//        // $where['l.id'] = array('eq', $id);
//        // $status = M('ProductSale')
//        // ->alias('l')
//        // ->join(C('DB_PREFIX') . 'company r ON l.company_id=r.id', 'LEFT')
//        // ->where($where)
//        // ->getField('r.proprietary');
//        // $this->assign('status', $status);
//
//        /*页面基本设置*/
//        if ($type == 1) {
//            $this->site_title = "产品管理";
//            $this->assign('left', 'index');
//        } else {
//            $this->site_title = "求购管理";
//            $this->assign('left', 'need');
//        }
//        $this->display();
//    }
//
//    /**
//     * 评价禁用
//     * @author 83961014@qq.com
//     */
//    public function comment_up() {
//        $id = I('id');
//        $state = I('state');
//        if ($state == 1) {
//            $data['state'] = 0;
//        } else {
//            $data['state'] = 1;
//        }
//        $condition['id'] = $id;
//        $return = M('Review')->where($condition)->save($data);
//        if ($return != false) {
//            $this->success('更改成功');
//        } else {
//            $this->error('更改失败');
//        }
//    }
//
//    /**
//     * 评价回复
//     * @author 83961014@qq.com
//     */
//    public function comment_reply() {
//        $id = I('id');
//        $content = I('content');
//        $type = I('type');
//        if ($content == '') {
//            $this->error('回复不能为空');
//            exit;
//        }
//        $info = M('Review')->where(array('id' => $id))->find();
//        if (empty($info)) {
//            $this->error('未找到该条评论');
//            exit;
//        }
//        $data['orderid'] = $info['orderid'];
//        $data['goodid'] = $info['goodid'];
//        $data['pid'] = $id;
//        $data['content'] = $content;
//        $data['type'] = $type;
//        $data['addtime'] = time();
//        $return = M('Review')->add($data);
//        if ($return != false) {
//            $this->success('回复成功');
//        } else {
//            $this->error('回复失败');
//        }
//        /*页面基本设置*/
//        if ($type == 1) {
//            $this->site_title = "产品管理";
//            $this->assign('left', 'index');
//        } else {
//            $this->site_title = "求购管理";
//            $this->assign('left', 'need');
//        }
//        $this->display();
//    }
//
//    private function &dumpTreeList($arr, $parentId = 0, $lv = 0) {
//        $lv++;
//        $tree = array();
//        foreach ((array)$arr as $row) {
//            if ($row['parent_id'] == $parentId) {
//                $row['level'] = $lv;
//                if ($row['parent_id'] != 0) {
//                    $row['sty'] = "|";
//                }
//
//                for ($i = 1; $i < $row['level']; $i++) {
//                    $row['sty'] .= "——";
//                    $row['sty2'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//                }
//                $row['sty2'] = $row['sty2'] . $row['sty'];
//                $tree[] = $row;
//                if ($children = $this->dumpTreeList($arr, $row['id'], $lv)) {
//                    $tree = array_merge($tree, $children);
//                }
//            }
//        }
//        return $tree;
//    }

    /**
     * 货车管理首页
     * @author 83961014@qq.com
     */
    public function truck() {
        $where = array();
        $list = array();
        $order = '';
        $uids = '';
        $member = array();
        $regions = array();
        $regions = $this->regionsCache();
        $order = 'create_time desc';
        $list = $this->lists('Truck', $where, $order);
        $uids = implode(',', array_unique(array_column($list, 'uid')));
        unset($where);
        $where['uid'] = array('in', $uids);
        $member = M('Member')->where($where)->getField('uid,mphone', true);
        foreach ($list as &$item) {
            $item['provincen'] = $regions[$item['provincen']];
            $item['cityn'] = $regions[$item['cityn']];
            $item['countryn'] = $regions[$item['countryn']];
            $item['provincen2'] = $regions[$item['provincen2']];
            $item['cityn2'] = $regions[$item['cityn2']];
            $item['countryn2'] = $regions[$item['countryn2']];
            $item['uid'] = $member[$item['uid']];
        }
        $this->assign('list', $list);

        /*页面基本设置*/
        $this->site_title = "货车管理";
        $this->assign('left', 'truck');
        $this->display();
    }

    /**
     * 添加、修改货车
     */
    public function truckAdd() {
        $id = '';       //货车id
        $opt = '';
        $list = array();
        $condition = array();
        $model = array();
        $list = array();

        $id = I('get.id', 0, 'intval');
        $model = D('Truck');
//        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();

            if ($result) {
                $this->success('提交成功', '?g=admin&m=product&a=truck');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ($id) {
            /* 货车详细信息 */
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
//            dump($detail);
            $detail['mphone'] = M('Member')->where(array('uid' => $detail['uid']))->getField('mphone');
//            dump($detail['departure_time']);exit;
//            $time = '2010-11-18 08:00:00';
//            dump($detail['departure_time']);exit;
            $detail['departure_time'] = date('Y-m-d H:i:s', $detail['departure_time']);
            $detail['end_date'] = date('Y-m-d H:i:s', $detail['end_date']);

        }

//        /* 发布企业 */
//        $company = M('Company')->field('id,name')->where(array('status' => 1))->select();
//        $this->assign('company', $company);
//
//        /* 分类 */
//        $category = M('ProductSaleCategory')->field('id,name,parent_id')->select();
//        $category = $this->dumpTreeList($category);
//        $this->assign('category', $category);
//
//        /* 属性 */
//        $flags = M('Flags')->field('att,attname')->select();
//        $this->assign('flags', $flags);
        /*车辆类型*/
        $list['truck_type'] = $this->truck_type;
        /*车辆长度*/
        $list['truck_length'] = $this->truck_length;
        /*货物类型*/
        $list['goods_type'] = $this->goods_type;
        /*运输类型*/
        $list['transport'] = $this->transport;
        /*车源类型*/
        $list['source_type'] = $this->source_type;
        /*发车数量*/
        $list['truck_num'] = $this->truck_num();
        /*重货单位*/
        $list['heavy_unit'] = $this->heavy_unit;
        /*轻货单位*/
        $list['light_unit'] = $this->light_unit;
        $this->assign('list', $list);
        /* 省市区 */
        $region = M('Regions')->cache(true)->select();
        foreach ($region as $val) {
            if (!$val['parent']) {
                $region_arr[0][] = $val;
            } else {
                $region_arr[$val['parent']][] = $val;
            }
        }
        $this->assign('region_arr', $region_arr);
        $this->assign('region_arr_json', json_encode($region_arr));

        $this->assign('detail', $detail);
        /* 页面基本设置 */
        $this->site_title = $opt . '货车';
        $this->assign('left', 'truck');
        $this->display();
    }

    /**
     * 货车删除
     * @author 83961014@qq.com
     */
    public function truckDel() {
        $id = I('id');
        $model = D('Truck');
        $condition['id'] = $id;
        $return = $model->delDate($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 货车批量删除
     * @author 83961014@qq.com
     */
    public function truckDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Truck');
            $condition['id'] = array('in', $ids);
            $tem = $model->delDate($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }


    /**
     * 仓储管理首页
     * @author 83961014@qq.com
     */
    public function depot() {
        $where = array();
        $list = array();
        $order = '';
        $uids = '';
        $member = array();
        $regions = array();
        $depotCategory = array();
        $depotCategory = $this->depotCategory;
        $regions = $this->regionsCache();
        $order = 'create_time desc';
        $list = $this->lists('Depot', $where, $order);
        $uids = implode(',', array_unique(array_column($list, 'uid')));
        unset($where);
        $where['uid'] = array('in', $uids);
        $member = M('Member')->where($where)->getField('uid,name', true);
        foreach ($list as &$item) {
            $item['province_id'] = $regions[$item['province_id']];
            $item['city_id'] = $regions[$item['city_id']];
            $item['country_id'] = $regions[$item['country_id']];
            $item['category_id'] = $depotCategory[$item['category_id']];
            $item['uid'] = $member[$item['uid']];
            $item['create_time'] = date('Y-m-d', $item['create_time']);
            $item['update_time'] = date('Y-m-d', $item['update_time']);
        }
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "产品管理";
        $this->assign('left', 'depot');
        $this->display();
    }

    /**
     * 添加、修改仓储
     */
    public function depotAdd() {
        $id = '';       //货车id
        $opt = '';
        $condition = array();
        $model = array();
        $list = array();
        $id = I('get.id', 0, 'intval');
        $model = D('Depot');
//        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();
            if ($result) {
                $this->success('提交成功', '?g=admin&m=product&a=truck');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ($id) {
            /* 货车详细信息 */
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $detail['mphone'] = M('Member')->where(array('uid' => $detail['uid']))->getField('mphone');
        }

        /*仓储,类型*/
        $list['depotCategory'] = $this->depotCategory;
        /*仓储，库内地面*/
        $list['depotGround'] = $this->depotGround;
        /*仓储，主体结构*/
        $list['depotStructure'] = $this->depotStructure;
        /*仓储，建筑标准*/
        $list['depotStandard'] = $this->depotStandard;

        /* 省市区 */
        $region = M('Regions')->cache(true)->select();
        foreach ($region as $val) {
            if (!$val['parent']) {
                $region_arr[0][] = $val;
            } else {
                $region_arr[$val['parent']][] = $val;
            }
        }
        $this->assign('region_arr', $region_arr);
        $this->assign('region_arr_json', json_encode($region_arr));
        $this->assign('detail', $detail);
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = $opt . '仓储';
        $this->assign('left', 'depot');
        $this->display();
    }

    /**
     * 仓储批量删除
     * @author 83961014@qq.com
     */
    public function depotDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Depot');
            $condition['id'] = array('in', $ids);
            $tem = $model->delDate($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 仓储删除
     * @author 83961014@qq.com
     */
    public function depotDel() {
        $id = I('id');
        $model = D('Depot');
        $condition['id'] = $id;
        $return = $model->delDate($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 货源管理首页
     * @author 83961014@qq.com
     */
    public function goods() {
        $where = array();
        $list = array();
        $order = '';
        $uids = '';
        $member = array();
        $regions = array();
        $depotCategory = array();
        $depotCategory = $this->depotCategory;
        $regions = $this->regionsCache();
        $order = 'create_time desc';
        $list = $this->lists('Goods', $where, $order);

        $uids = implode(',', array_unique(array_column($list, 'uid')));
        unset($where);
        $where['uid'] = array('in', $uids);
        $member = M('Member')->where($where)->getField('uid,name', true);
        foreach ($list as &$item) {
            if ($item['end_time'] == 0) {
                $item['end_time'] = '长期有效';
            } else {
                $item['end_time'] = date('Y-m-d', $item['end_time']);
            }
            $item['provincen'] = $regions[$item['provincen']];
            $item['cityn'] = $regions[$item['cityn']];
            $item['countryn'] = $regions[$item['countryn']];
            $item['provincen2'] = $regions[$item['provincen2']];
            $item['cityn2'] = $regions[$item['cityn2']];
            $item['countryn2'] = $regions[$item['countryn2']];
            $item['uid'] = $member[$item['uid']];
        }
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "产品管理";
        $this->assign('left', 'goods');
        $this->display();
    }

    /**
     * 添加、修改货源
     */
    public function goodsAdd() {
        $id = '';       //货车id
//        $opt = '';
        $condition = array();
        $model = array();
        $list = array();
        $id = I('get.id', 0, 'intval');
        $model = D('Goods');
//        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();
            if ($result) {
                $this->success('提交成功', '?g=admin&m=product&a=goods');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ($id) {
            /* 货车详细信息 */
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $detail['mphone'] = M('Member')->where(array('uid' => $detail['uid']))->getField('mphone');
            $detail['deliver_time'] = date('Y-m-d H:i:s', $detail['deliver_time']);
            if ($detail['end_time'] == 0) {
                $detail['end_time'] = '';
            } else {
                $detail['end_time'] = date('Y-m-d H:i:s', $detail['end_time']);
            }

        }
        /*运输方式*/
        $list['goodsTransport'] = $this->goodsTransport;
        /*货物类型*/
        $list['goodsType'] = $this->goodsType;
        /*车辆类型*/
        $list['truck_type'] = $this->truck_type;
        /*车辆长度*/
        $list['truck_length'] = $this->truck_length;
        /*车辆数量*/
        $list['truck_num'] = $this->truck_num;
        /*价格意向*/
        $list['freight'] = $this->freight;
        /* 省市区 */
        $region = M('Regions')->cache(true)->select();
        foreach ($region as $val) {
            if (!$val['parent']) {
                $region_arr[0][] = $val;
            } else {
                $region_arr[$val['parent']][] = $val;
            }
        }
        $this->assign('region_arr', $region_arr);
        $this->assign('region_arr_json', json_encode($region_arr));
        $this->assign('detail', $detail);
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = $opt . '产品';
        $this->assign('left', 'goods');
        $this->display();
    }

    /**
     * 货源批量删除
     * @author 83961014@qq.com
     */
    public function goodsDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Depot');
            $condition['id'] = array('in', $ids);
            $tem = $model->delDate($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 货源删除
     * @author 83961014@qq.com
     */
    public function goodsDel() {
        $id = I('id');
        $model = D('Depot');
        $condition['id'] = $id;
        $return = $model->delDate($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

}