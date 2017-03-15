<?php
namespace Apps\Controller;

use Think\Controller;

class GoodsController extends ApiController {

//    /**
//     * 架构函数
//     */
//    protected function _initialize() {
//        parent::_initialize();
//    }

    /**
     * 货源列表
     * @param page
     * @param
     */
    public function index() {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = $page * 10;
            $goodsList = M('Goods')->where(array('status' => 1))->field('name,provinceN,cityN,provinceN2,cityN2,weight,weight_unit,volume,volume_unit')->order('id desc')->limit($page, 10)->select();
            $cache = $this->regionsCache();
            foreach ($goodsList as $key => $val) {
                $list['list'][$key]['title'] = $val['name'];
                $list['list'][$key]['detail'] = array(
                    0 => '线路：' . $cache[$val['provincen']] . $cache[$val['cityn']] . '->' . $cache[$val['provincen2']] . $cache[$val['cityn2']],
                    1 => '货物重量：' . $val['weight'] . $val['weight_unit'],
                    2 => '货物体积：' . $val['volume'] . $val['volume_unit'],
                );
            }
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 新增和编辑货源
     * @param token
     * @param id 货源id
     * 45f3711cfa384c26336023a27a65208b
     *
     * deliver_time  发货日期
     * end_time   截至日期，为空标识长期有效
     *
     */
    public function add() {
        $token = I('get.token', '', 'strval');
        $id = I('get.id', '', 'intval');
        /*检测用户登录状态*/
        empty($token) && $this->ajaxJson('70000', '请先登录');
        $memberInfo = D('Token')->getMemberInfo($token);
        !$memberInfo && $this->ajaxJson('40004', '登录信息已过期');
//        if($memberInfo['goods'] !=1){
//            $this->ajaxJson('70000', '您还没有发布货源权限');
//        }
        /*发布，修改*/
        $model = D('Goods');
        if (IS_POST) {
            $postDatas = $this->analyticalDatas($_POST);
            $postDatas && $_POST = $postDatas;
            $opt = $id > 0 ? '修改' : '发布';
            $result = $model->update();
            if ($result) {
                $this->ajaxJson('40000', $opt . '成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        $truck_type = $this->truck_type;        //车辆类型
        $truck_length = $this->truck_length;    //车辆长度
        $goods_type = $this->goods_type;        //货物类型
        $transport = $this->transport;          //运输方式

        /* 客户端图片地址 */
        $appsImg = C('HTTP_APPS_IMG');
        if ($id) {
            $detail = M('Goods')->where(array('id' => $id))->find();
            !$detail && $this->ajaxJson('70000', '货源不存在或已删除');
            $detail['img'] = M('GoodsPicture')->where(array('product_id' => $id))->getField('pic_url', true);
            !is_array($detail['img']) && $detail['img'] = array();
            empty($dettail['remark']) && $detail['remark'] = '';
            $detail['categoryName'] = $goods_type[$detail['category_id']];
            $detail['deliver_time'] = date('Y-m-d H:i:s', $detail['deliver_time']);

        } else {
            $detail['id'] = '';
            $detail['uid'] = '';
            $detail['provincen'] = '';
            $detail['cityn'] = '';
            $detail['countryn'] = '';
            $detail['provincen2'] = '';
            $detail['cityn2'] = '';
            $detail['countryn2'] = '';
            $detail['name'] = '';
            $detail['deliver_time'] = '';
            $detail['category_id'] = '';
            $detail['categoryName'] = '';
            $detail['transport_id'] = '';
            $detail['end_time'] = '';
            $detail['truck_length'] = '';
            $detail['truck_type'] = '';
            $detail['truck_num'] = '';
            $detail['freight_price'] = '';
            $detail['freight'] = '';
            $detail['receive_name'] = '';
            $detail['receive_phone'] = '';
            $detail['weight'] = '';
            $detail['weight_unit'] = '';
            $detail['volume'] = '';
            $detail['volume_unit'] = '';
            $detail['img'] = array();
            $detail['remark'] = '';
        }


        /*车长*/
        foreach ($truck_length as $key => $val) {
            $childLength[$key]['type'] = 'radio';       //单选
            $childLength[$key]['required'] = '0';
            $childLength[$key]['title'] = $val;
            $childLength[$key]['placeholder'] = '';
            $childLength[$key]['num'] = '1';
            $childLength[$key]['width'] = '';
            $childLength[$key]['height'] = '44';
            $childLength[$key]['top'] = '1';
            $childLength[$key]['name'] = 'truck_length';
            $childLength[$key]['value'] = $val;
            $childLength[$key]['child'] = array();
        }
        /*车辆类型*/
        foreach ($truck_type as $key => $val) {
            $childType[$key]['type'] = 'radio';        //单选
            $childType[$key]['required'] = '0';
            $childType[$key]['title'] = $val;
            $childType[$key]['placeholder'] = '';
            $childType[$key]['num'] = '1';
            $childType[$key]['width'] = '';
            $childType[$key]['height'] = '44';
            $childType[$key]['top'] = '1';
            $childType[$key]['name'] = 'truck_type';
            $childType[$key]['value'] = $val;
            $childType[$key]['child'] = array();
        }
        /*货物类型*/
        foreach ($goods_type as $key => $val) {
            $childGoodsType[$key]['type'] = 'radio';        //单选
            $childGoodsType[$key]['required'] = '0';
            $childGoodsType[$key]['title'] = $val;
            $childGoodsType[$key]['placeholder'] = '';
            $childGoodsType[$key]['num'] = '1';
            $childGoodsType[$key]['width'] = '';
            $childGoodsType[$key]['height'] = '44';
            $childGoodsType[$key]['top'] = '1';
            $childGoodsType[$key]['name'] = 'category_id';
            $childGoodsType[$key]['value'] = $key;
            $childGoodsType[$key]['child'] = array();
        }
        /*运输方式*/
        foreach ($transport as $key => $val) {
            $childTransport[$key]['type'] = 'radio';        //单选
            $childTransport[$key]['required'] = '0';
            $childTransport[$key]['title'] = $val;
            $childTransport[$key]['placeholder'] = '';
            $childTransport[$key]['num'] = '1';
            $childTransport[$key]['width'] = '';
            $childTransport[$key]['height'] = '44';
            $childTransport[$key]['top'] = '1';
            $childTransport[$key]['name'] = 'transport_id';
            $childTransport[$key]['value'] = $key;
            $childTransport[$key]['child'] = array();
        }
        /* 第一块 */
        $section_first = array(
            array(
                'type' => 'hidden',
                'required' => '0',
                'title' => '',
                'placeholder' => '',
                'num' => '',
                'width' => '',
                'height' => '',
                'top' => '',
                'name' => 'id',
                'value' => array(strval($id)),
                'child' => array()
            ),
            array(
                'type' => 'hidden',
                'required' => '0',
                'title' => '',
                'placeholder' => '',
                'num' => '',
                'width' => '',
                'height' => '',
                'top' => '',
                'name' => 'token',
                'value' => array($token),
                'child' => array()
            ),
            array(
                'type' => 'hidden',
                'required' => '0',
                'title' => '',
                'placeholder' => '',
                'num' => '',
                'width' => '',
                'height' => '',
                'top' => '',
                'name' => 'uid',
                'value' => array($memberInfo['uid']),
                'child' => array()
            ),
            //起始地目的地没写好
            array(
                'type' => 'locationModel',
                'required' => '1',
                'title' => '',
                'placeholder' => ' ',
                'num' => '',
                'width' => '45',
                'height' => '100',
                'top' => '1',
                'name' => 'location',
                'value' => array($detail['linkman']),
                'child' => array()
            ),
        );
        /* 第二块 */
        $section_second = array(
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '收货人姓名标题',
                'placeholder' => '请填写收货人姓名',
                'num' => '10',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'receive_name',
                'value' => array($detail['receive_name']),
                'child' => array()
            ),
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '联系电话',
                'placeholder' => '请填写联系电话',
                'num' => '10',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'receive_phone',
                'value' => array($detail['receive_phone']),
                'child' => array()
            ),
        );
        /* 第三块 */
        $section_third = array(
            array(
                'type' => 'href',   //单选
                'required' => '1',
                'title' => '车长要求',
                'placeholder' => '',
                'num' => '1',
                'width' => '',
                'height' => '44',
                'top' => '0',
                'name' => 'truck_length',
                'value' => array($detail['truck_length'], $detail['truck_length']),
                'child' => $childLength
            ),
            array(
                'type' => 'href',   //单选
                'required' => '1',
                'title' => '车辆类型',
                'placeholder' => '',
                'num' => '1',
                'width' => '',
                'height' => '44',
                'top' => '0',
                'name' => 'truck_type',
                'value' => array($detail['truck_type'], $detail['truck_type']),
                'child' => $childType
            ),
            array(
                'type' => 'href',   //单选
                'required' => '1',
                'title' => '运输方式',
                'placeholder' => '',
                'num' => '1',
                'width' => '',
                'height' => '44',
                'top' => '0',
                'name' => 'transport_id',
                'value' => array($detail['transport_id'], $detail['transport_id']),
                'child' => $childTransport
            ),
        );
        /* 第四块 */
        $section_fourth = array(
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '货物名称',
                'placeholder' => '请填写货物名称',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'name',
                'value' => array($detail['name']),
                'child' => array()
            ),
            array(
                'type' => 'href',   //单选
                'required' => '1',
                'title' => '货物类型',
                'placeholder' => '',
                'num' => '1',
                'width' => '',
                'height' => '44',
                'top' => '0',
                'name' => 'category_id',
                'value' => array($detail['category_id'], $detail['categoryName']),
                'child' => $childGoodsType
            ),
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '运费意向',
                'placeholder' => '请填写运费意向',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'freight_price',
                'value' => array($detail['freight_price']),
                'child' => array()
            ),
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '货物体积',
                'placeholder' => '请填写货物体积',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'volume',
                'value' => array($detail['volume'] . '方'),
                'child' => array()
            ),
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '货物重量',
                'placeholder' => '请填货物重量',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'weight',
                'value' => array($detail['weight']),
                'child' => array()
            ),
            array(
                'type' => 'time',
                'required' => '1',
                'title' => '发货日期',
                'placeholder' => '选择发货日期',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'deliver_time',
                'value' => array($detail['deliver_time'], $detail['deliver_time']),
                'child' => array()
            ),
            array(
                'type' => 'edit_text',
                'required' => '1',
                'title' => '截止日期',
                'placeholder' => '请选择截止日期',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'end_time',
                'value' => array($detail['end_time'], $detail['end_time']),
                'child' => array()
            ),


            array(
                'type' => 'img_view',
                'required' => '1',
                'title' => '货物图片',
                'placeholder' => '(不超过5张)',
                'num' => '5',
                'width' => '',
                'height' => '',
                'top' => '0',
                'name' => 'carousel',
                'value' => $detail['pic_url'],
                'child' => array()
            ),
            array(
                'type' => 'edit_text',
                'required' => '0',
                'title' => '货物说明',
                'placeholder' => '',
                'num' => '200',
                'width' => '45',
                'height' => '44',
                'top' => '1',
                'name' => 'remark',
                'value' => array($detail['remark']),
                'child' => array()
            ),
        );
        /* 区信息 */
        $list['section'] = array(
            array(
                'section_title' => '',
                'section_height' => '',
                'section_items' => $section_first
            ),
            array(
                'section_title' => '联系信息',
                'section_height' => '10',
                'section_items' => $section_second
            ),
            array(
                'section_title' => '车辆信息',
                'section_height' => '10',
                'section_items' => $section_third
            ),
            array(
                'section_title' => '载货信息',
                'section_height' => '10',
                'section_items' => $section_fourth
            ),
        );
        $this->returnJson($list);
    }

    /**
     * 我的货源
     * @param token
     */
    public function mine() {
        if (IS_GET) {
            $page = I('get.page', 1, 'intval');
            $page = $page * 10;
            $token = I('get.token', '', 'strval');
            empty($token) && $this->ajaxJson('70000', '请先登录');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004', '登录信息已过期');

            $goodsList = M('Goods')->where(array('status' => 1, 'uid' => $memberInfo['uid']))->field('name,provinceN,cityN,provinceN2,cityN2,weight,weight_unit,volume,volume_unit')->order('id desc')->limit($page, 10)->select();
            $cache = $this->regionsCache();
            foreach ($goodsList as $key => $val) {
                $list['list'][$key]['title'] = $val['name'];
                $list['list'][$key]['detail'] = array(
                    0 => '线路：' . $cache[$val['provincen']] . $cache[$val['cityn']] . '->' . $cache[$val['provincen2']] . $cache[$val['cityn2']],
                    1 => '货物重量：' . $val['weight'] . $val['weight_unit'],
                    2 => '货物体积：' . $val['volume'] . $val['volume_unit'],
                );
            }
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 货源详情
     */
    public function detail() {


    }
}


?>