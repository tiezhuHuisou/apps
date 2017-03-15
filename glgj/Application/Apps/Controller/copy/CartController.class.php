<?php
namespace Apps\Controller;
use Think\Controller;

class CartController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
        $token = I('request.token');
        empty($token) && $this->ajaxJson('70000', '请先登陆');
        /* 根据token获取用户信息 */
        $prefix = C('DB_PREFIX');
        $this->uid = M('Token')
                   ->alias('l')
                   ->join($prefix.'member r on l.id = r.token_id', 'LEFT')
                   ->where(array('l.uuid'=>$token))
                   ->getField('r.uid');
        /* 处理数据 */
        !$this->uid && $this->ajaxJson('40004');
    }

    /**
     * 购物车列表
     */
    public function index() {
        if ( IS_GET ) {
            $uid  = $this->uid;
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            /* 购物车列表 直接取最新数据 */
            // $cartList = M('OrderShopcart')
            //           ->alias('l')
            //           ->field('l.subid cart_id,c.id sellerid,c.logo,c.name seller,r.id goodid,r.img gpic,s.img spec_img,r.title goodname,s.id specid,s.title1,s.title2,s.spec1 spec_1,s.spec2 spec_2,s.price spec_price,r.price unitprice,l.nums,r.num stock,s.stock spec_stock')
            //           ->join(C('DB_PREFIX') . 'product_sale r ON l.goodid = r.id', 'LEFT')
            //           ->join(C('DB_PREFIX') . 'product_spec s ON l.specid = s.id', 'LEFT')
            //           ->join(C('DB_PREFIX') . 'company c ON r.company_id = c.id', 'LEFT')
            //           ->where(array('userid'=>$uid, 'status'=>1))
            //           ->select();
            /* 购物车列表 取添加时的数据 结算时再对比商品参数是否有变动 */
            $cartList = M('OrderShopcart')->field('subid,sellerid,logo,seller,goodid,gpic,goodname,specid,title1,title2,spec_1,spec_2,unitprice,nums,stock')->where(array('userid'=>$uid, 'status'=>1))->order('sellerid ASC')->limit($page,10)->select();
            !$cartList && $this->returnJson(array('cartList'=>array()));
            /* 数据处理 */
            $cartList = $this->getAbsolutePath($cartList, 'logo', C('HTTP_APPS_IMG') . 'category_default.png');
            $cartList = $this->getAbsolutePath($cartList, 'gpic', C('HTTP_APPS_IMG') . 'product_defalt.png');
            foreach ($cartList as $key => $value) {
                /* 有规格商品处理 */
                if ( $value['specid'] ) {
                    /* 拼接规格信息 */
                    $cartList[$key]['spec_info'] = $value['title1'] . '：' . $value['spec_1'];
                    $value['title2'] && $cartList[$key]['spec_info'] .= ' ' . $value['title2'] . '：' . $value['spec_2'];
                    /* 价格、图片、库存取规格里的设置 直接取最新数据时才需要 */
                    // $cartList[$key]['unitprice'] = $value['spec_price'];
                    // $cartList[$key]['stock']     = $value['spec_stock'];
                    // $cartList[$key]['gpic']      = $value['spec_img'];
                } else {
                    /* 无规格商品 */
                    $cartList[$key]['spec_info'] = '';
                }
                // unset($cartList[$key]['spec_price'], $cartList[$key]['spec_stock'], $cartList[$key]['spec_img']);
                unset($cartList[$key]['title1'], $cartList[$key]['title2'], $cartList[$key]['spec_1'], $cartList[$key]['spec_2'], $cartList[$key]['sellerid'], $cartList[$key]['logo'], $cartList[$key]['seller']);
                /* 按商家分组 */
                $list['cart_list'][$value['sellerid']]['company_id']     = $value['sellerid'];
                $list['cart_list'][$value['sellerid']]['company_logo']   = $value['logo'];
                $list['cart_list'][$value['sellerid']]['company_name']   = $value['seller'];
                $list['cart_list'][$value['sellerid']]['product_list'][] = $cartList[$key];
            }
            $list['cart_list'] = array_values($list['cart_list']);
            
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 购物车添加
     */
    public function add() {
        if ( IS_POST ) {
            $uid    = $this->uid;
            $id     = I('post.id', 0, 'intval');
            $specid = I('post.specid', 0, 'intval');
            $nums   = I('post.nums', 0, 'intval');
            $nums < 1 && $this->ajaxJson('70000', '购买数量不能小于1');
            $specid = $specid > 0 ? $specid : 0;
            /* 数据验证 */
            $where['l.id'] = array('eq', $id);
            if ( $specid ) {
                $where['s.id'] = array('eq', $specid); 
            }
            $detail = M('ProductSale')
                    ->alias('l')
                    ->field('l.id,l.title,l.img,l.num,l.price,c.id company_id,c.logo,c.name,s.id specid,s.title1,s.title2,s.spec1,s.spec2,s.img spec_img,s.stock,s.price spec_price,l.activity_type,l.activity_price,s.activity_price spec_activity_price')
                    ->join(C('DB_PREFIX') . 'product_spec s ON l.id = s.product_id', 'LEFT')
                    ->join(C('DB_PREFIX') . 'company c ON l.company_id = c.id', 'LEFT')
                    ->where($where)
                    ->find();
            !$detail && $this->ajaxJson('70000', '参数错误');
            $detail['specid'] != $specid && $this->ajaxJson('70000', '参数异常');

            /* 查询活动信息 */
            $where_activity['status']        = array('eq', 1);
            $where_activity['start_time']    = array('lt', time());
            $where_activity['end_time']      = array('gt', time());
            $where_activity['activity_type'] = array('gt', 0);
            $activityList = M('Activity')->where($where_activity)->getField('activity_type,title', true);

            /* 构建数据 */
            $datas['userid']        = $uid;
            $datas['goodid']        = $detail['id'];
            $datas['goodname']      = $detail['title'];
            $datas['gpic']          = $specid ? $detail['spec_img'] : $detail['img'];
            $datas['nums']          = $nums;
            /* 判断现价 */
            if ( $activityList && $activityList[$detail['activity_type']] ) {
                $datas['unitprice'] = $specid ? $detail['spec_activity_price'] : $detail['activity_price'];
            } else {
                $datas['unitprice'] = $specid ? $detail['spec_price'] : $detail['price'];
            }
            $datas['addtime']       = time();
            $datas['sellerid']      = $detail['company_id'];
            $datas['seller']        = $detail['name'];
            $datas['specid']        = $specid;
            $datas['spec_1']        = $detail['spec1'];
            $datas['spec_2']        = $detail['spec2'];
            $datas['stock']         = $specid ? $detail['stock'] : $detail['num'];
            $datas['logo']          = $detail['logo'];
            $datas['title1']        = $detail['title1'];
            $datas['title2']        = $detail['title2'];
            $datas['activity_name'] = $activityList[$detail['activity_type']] ? $activityList[$detail['activity_type']] : '';
            $nums > $datas['stock'] && $this->ajaxJson('70000', '库存不足');

            /* 立即购买的情况要加插入一条特殊状态的购物车，status = 2，表示是立即购买时生成的购物车记录，方便生成订单 */
            $model = M('OrderShopcart');
            if ( I('post.operate') == 2 ) {
                $datas['status'] = 2;
                $subid = $model->add($datas);
                $this->returnJson($subid);
            } else {
                /* 查询购物车里是否存在该商品 */
                $map['goodid'] = array('eq', $id);
                $map['specid'] = array('eq', $specid);
                $map['status'] = array('eq', 1);
                $map['userid'] = array('eq', $uid);
                $subid  = $model->where($map)->getField('subid');
                if ( $subid ) {
                    /* 累加更新 */
                    $result = $model->where($map)->setInc('nums', $nums);
                } else {
                    /* 新增加 */
                    $result = $model->add($datas);
                    $subid  = $result;
                }
                !$result && $this->ajaxJson('70000', '系统繁忙请稍候');
                $this->ajaxJson('40000', '添加成功');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 购物车删除(支持批量删除)
     */
    public function del() {
        if ( IS_POST ) {
            $id  = I('post.id');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            $id  = explode(',', $id);
            $uid = $this->uid;

            $map['userid'] = array('eq', $uid);
            $map['status'] = array('eq', 1);
            foreach ($id as $value) {
                $map['subid'] = array('eq', $value);
                $return = M('OrderShopcart')->where($map)->setField('status', 0);
                $return === false && $this->ajaxJson('70000', '删除失败');
            }
            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 购物车更新数量(支持批量更新)
     */
    public function updateNum() {
        if ( IS_POST ) {
            $id   = I('post.id');
            $nums = I('post.nums');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            empty($nums) && $this->ajaxJson('70000', '数量不能为空');
            $uid  = $this->uid;
            $id   = explode(',', $id);
            $nums = explode(',', $nums);
            count($id) != count($nums) && $this->ajaxJson('70000', '参数异常');

            /* 更新购物车信息 */
            $model = M('OrderShopcart');
            $map['userid'] = array('eq', $uid);
            $map['status'] = array('eq', 1);
            $where['userid'] = array('eq', $uid);
            $where['status'] = array('eq', 1);
            $where['subid']  = array('in', $id);
            $stockList = $model->where($where)->getField('stock', true);
            foreach ($id as $key => $value) {
                $stockList[$key] < $nums[$key] && $this->ajaxJson('70000', '库存不足');
                $map['subid'] = array('eq', $value);
                $save = $model->where($map)->setField('nums', $nums[$key]);
                $save === false && $this->ajaxJson('70000', '更新失败');
            }
            $this->ajaxJson('40000', '更新成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 购物车获取商品规格信息
     */
    public function getSpecInfo() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 查询活动信息 */
            $where_activity['status']        = array('eq', 1);
            $where_activity['start_time']    = array('lt', time());
            $where_activity['end_time']      = array('gt', time());
            $where_activity['activity_type'] = array('gt', 0);
            $activityList = M('Activity')->where($where_activity)->getField('activity_type,title', true);

            /* 查询规格信息 */
            $where['l.status']  = array('eq', 1);
            $where['l.is_spec'] = array('eq', 1);
            $where['l.id']      = array('eq', $id);
            $list['spec_list'] = M('ProductSale')
                            ->alias('l')
                            ->join(C('DB_PREFIX').'product_spec r ON l.id = r.product_id', 'LEFT')
                            ->field('r.id spec_id,r.title1,r.title2,r.spec1,r.spec2,r.price,r.stock,r.buymin,r.img,l.activity_type')
                            ->where($where)
                            ->select();
            !$list['spec_list'] && $this->ajaxJson('70000', '商品参数已变更，请删除商品后重新添加至购物车');
            /* 数据处理 */
            foreach ($list['spec_list'] as $key => $val) {
                /* 所有规格1内容数据 */
                $list['spec1'][$key] = $val['spec1'];
                /* 所有规格2内容数据 */
                $val['spec2'] && $list['spec2'][$key] = $val['spec2'];
                /* 判断是否存在活动 */
                if ( $activityList && $activityList[$val['activity_type']] ) {
                    $list['spec_list'][$key]['price'] = $value['activity_price'];
                }
                /* 销毁客户端不需要的数据 */
                unset($list['spec_list'][$key]['activity_type'], $list['spec_list'][$key]['activity_price']);
            }
            $list['spec1'] = array_values(array_unique($list['spec1']));
            $list['spec2'] = $list['spec2'] ? array_values(array_unique($list['spec2'])) : array();

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 购物车更新商品规格信息
     */
    public function updateSpecInfo() {
        if ( IS_POST ) {
            $id      = I('post.id');
            $spec_id = I('post.spec_id');
            $nums    = I('post.nums');
            !$id && $this->ajaxJson('70000', '参数错误');
            !$nums && $this->ajaxJson('70000', '请选择规格');
            !$spec_id && $this->ajaxJson('70000', '数量不能为空');
            $uid = $this->uid;

            /* 判断购物车是否存在该规格 */
            $where['userid'] = array('eq', $uid);
            $where['status'] = array('eq', 1);
            $where['specid'] = array('eq', $spec_id);
            $shopcartInfo = M('OrderShopcart')->field('stock,nums')->where($where)->find();

            if ( $shopcartInfo ) {
                /**
                 * 购物车存在新规格数据 合并购物车数据
                 * 判断库存
                 */
                $shopcartInfo['nums'] + $nums > $shopcartInfo['stock'] && $this->ajaxJson('70000', '库存不足');
                /* 更新已存在的规格数据数量 */
                $save = M('OrderShopcart')->where($where)->setInc('nums', $nums);
                /* 删除新规格数据 */
                M('OrderShopcart')->where(array('subid'=>$id))->delete();
            } else {
                /**
                 * 购物车不存在新规格数据 修改购物车数据
                 * 获取新规格信息
                 */
                $specInfo = M('ProductSpec')
                          ->alias('l')
                          ->field('l.id,l.title1,l.title2,l.spec1,l.spec2,l.price,l.stock,l.buymin,l.img,l.activity_price,r.activity_type')
                          ->join(C('DB_PREFIX') . 'product_sale r ON l.product_id = r.id', 'LEFT')
                          ->where(array('l.id'=>$spec_id))
                          ->find();
                !$specInfo && $this->ajaxJson('70000', '商品规格不存在');
                $nums > $specInfo['stock'] && $this->ajaxJson('70000', '库存不足');

                /* 查询活动信息 */
                $where_activity['status']        = array('eq', 1);
                $where_activity['start_time']    = array('lt', time());
                $where_activity['end_time']      = array('gt', time());
                $where_activity['activity_type'] = array('gt', 0);
                $activityList = M('Activity')->where($where_activity)->getField('activity_type,title', true);

                /* 判断商品是否参与活动 */
                if ( $activityList && $activityList[$specInfo['activity_type']] ) {
                    $specInfo['price'] = $specInfo['activity_price'];
                }

                /* 构建更新数据 */
                $datas['gpic']      = $specInfo['img'];
                $datas['unitprice'] = $specInfo['price'];
                $datas['specid']    = $specInfo['id'];
                $datas['spec_1']    = $specInfo['spec1'];
                $datas['spec_2']    = $specInfo['spec2'];
                $datas['stock']     = $specInfo['stock'];
                $datas['title1']    = $specInfo['title1'];
                $datas['title2']    = $specInfo['title2'];

                /* 更新购物车信息 */
                $map['userid'] = array('eq', $uid);
                $map['status'] = array('eq', 1);
                $map['subid']  = array('eq', $id);
                $save = M('OrderShopcart')->where($map)->save($datas);
            }
            $save === false && $this->ajaxJson('70000', '更新失败');
            $this->ajaxJson('40000', '更新成功');
        }
        $this->ajaxJson('70001');
    }
}