<?php
namespace Apps\Controller;
use Think\Controller;

class AddressController extends ApiController {
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
     * 收货地址列表
     */
    public function index() {
        $uid = $this->uid;
        /* 省市区 */
        $regions = M('Regions')->cache(true)->getField('id,name');
        /* 收货地址列表 */
        $list['address_list'] = M('Address')->field('id address_id,name,region,address,phone,type')->where(array('owner'=>$uid))->select();
        /* 省市区+详细地址处理 */
        foreach ($list['address_list'] as $key => $value) {
            $value['region'] = explode(',', $value['region']);
            $list['address_list'][$key]['address'] = $regions[$value['region'][0]] . $regions[$value['region'][1]] . $regions[$value['region'][2]] . $value['address'];
            unset($list['address_list'][$key]['region']);
        }
        
        $this->returnJson($list);
    }

    /**
     * 新增、编辑收货地址
     */
    public function add() {
        if ( IS_POST ) {
            $model = D('Address');
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', $result['opt'] . '成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 编辑 */
        $id = I('request.id', 0, 'intval');
        if ( $id ) {
            /* 省市区 */
            $regions = M('Regions')->cache(true)->getField('id,name');
            /* 收货地址信息 */
            $list['address_detail'] = M('Address')->field('id address_id,name,region,phone,address')->where(array('id'=>$id))->find();
            $list['address_detail']['region'] = explode(',', $list['address_detail']['region']);
            $list['address_detail']['region_detail'] = $regions[$list['address_detail']['region'][0]] . $regions[$list['address_detail']['region'][1]] . $regions[$list['address_detail']['region'][2]];
        }

        $this->returnJson($list);
    }

    /**
     * 删除收货地址
     */
    public function del() {
        if ( IS_POST ) {
            $id = I('post.id');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            $condition['id'] = array('in', $id);
            $return = D('Address')->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '删除成功');
            } else {
                $this->ajaxJson('70000', '删除失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 设为默认地址
     */
    public function setDefaultAddress() {
        if ( IS_POST ) {
            $id  = I('post.id');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            $uid = $this->uid;

            $condition['id']    = array('eq', $id);
            $condition['owner'] = array('eq', $uid);
            $model  = M('Address');
            $model->where(array('owner'=>$uid))->setField('type', 0);
            $return = $model->where($condition)->setField('type', 1);
            if ( $return ) {
                $this->ajaxJson('40000', '设置成功');
            } else {
                $this->ajaxJson('70000', '设置失败');
            }
        }
        $this->ajaxJson('70001');
    }
}