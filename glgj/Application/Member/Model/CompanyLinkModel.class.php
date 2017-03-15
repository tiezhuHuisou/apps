<?php
namespace Member\Model;

use Think\Model;

/**
 * 公司简介模型
 * @author 122837594@qq.com
 */
class CompanyLinkModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('contact_user', 'require', '企业负责人姓名不能为空', self::MUST_VALIDATE),
        array('contact_user', '2,16', '企业负责人姓名长度为2-16个字符', self::MUST_VALIDATE, "length"),
        array('subphone', 'require', '手机号码不能为空', self::MUST_VALIDATE),
        array('subphone', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '请输入正确的手机号码', self::MUST_VALIDATE, 'regex'),
        array('province_id', 'require', '请选择公司所在省', self::MUST_VALIDATE),
        array('city_id', 'require', '请选择公司所在市', self::MUST_VALIDATE),
//        array('countyN', 'require', '请选择公司所在区/县', self::MUST_VALIDATE),
        array('address', 'require', '详细地址不能为空', self::MUST_VALIDATE),
        //array('point', 'require', '请选择公司坐标', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array(
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
        array('point', 'getPoint', Model::MODEL_BOTH, 'callback'),
//        array('company_id','get_company',Model::MODEL_BOTH,'callback'),
    );

    /**
     * 添加/编辑部门
     * @author 122837594@qq.com
     */
    public function test() {
        $datas = I('post.companyLink');
        $datas = $this->create($datas);
        if ($datas) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加/编辑部门
     * @author 122837594@qq.com
     */
    public function update($id) {
        $datas = I('post.companyLink');
//        print_r($datas);exit;
        /* 获取数据对象 */
        $datas = $this->create($datas);
        if (empty($datas)) {
            return false;
        }
        if ($datas['company_id']) {
            $status = $this->where(array('company_id' => $datas['company_id']))->save($datas);
        } else {
            $datas['company_id'] = $id;
            $status = $this->add($datas);
        }
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 得到经纬度
     */
    protected function getPoint() {
        $post = I('post.companyLink');
        return $post['lng'] . ',' . $post['lat'];
    }

}