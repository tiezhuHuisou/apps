<?php
namespace Admin\Model;
use Think\Model;
class CompanyModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),

        array('name', 'require', '企业名称不能为空', self::MUST_VALIDATE),
        array('name', '2,128', '企业名称长度为2-128位字符', self::MUST_VALIDATE, 'length'),
        array('mphone', 'require', '登陆帐号不能为空', self::MUST_VALIDATE),
        array('mphone', 'mobile', '请以手机号码为登陆帐号', self::MUST_VALIDATE),
        array('mphone', 'mobileOnly', '该手机号已被注册', self::MUST_VALIDATE, 'callback'),
        array('password', 'require', '密码不能为空', self::MUST_VALIDATE),
        array('password', '6,16', '密码长度为6-16位字符', self::MUST_VALIDATE, 'length'),
        array('password1', 'require', '确认密码不能为空', self::MUST_VALIDATE),
        array('password1', '6,16', '确认密码长度为6-16位字符', self::MUST_VALIDATE, 'length'),
        array('password', 'equalPassword', '两次密码不一致', self::MUST_VALIDATE, 'callback'),
        array('logo', 'require', '企业logo不能为空', self::MUST_VALIDATE),
        array('bgimg', 'require', '企业主页背景图不能为空', self::MUST_VALIDATE),
        array('business', 'require', '主营行业不能为空', self::MUST_VALIDATE),
        array('business', '1,200', '主营行业长度为1-200位字符', self::MUST_VALIDATE, 'length'),
        array('summary', 'require', '企业简介不能为空', self::MUST_VALIDATE),
        array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', '0,99', '排序范围0-99', self::MUST_VALIDATE, 'between'),
        array('lng', 'require', '请正确标注企业所在地', self::MUST_VALIDATE),
        array('lat', 'require', '请正确标注企业所在地', self::MUST_VALIDATE),
        // array('province_id', 'require', '请选择企业所在省份', self::MUST_VALIDATE),
        // array('city_id', 'require', '请选择企业所在城市', self::MUST_VALIDATE),
        // array('countyN', 'require', '请选择企业所在区、县', self::MUST_VALIDATE),
        array('address', 'require', '详细地址不能为空', self::MUST_VALIDATE),
        array('contact_user', 'require', '联系人不能为空', self::MUST_VALIDATE),
        array('subphone', 'require', '电话1不能为空', self::MUST_VALIDATE),
        array('subphone', 'phone', '电话1格式不正确', self::MUST_VALIDATE),
        array('telephone', 'phone', '电话2格式不正确', self::VALUE_VALIDATE),
        array('site', 'url', '官网格式不正确', self::VALUE_VALIDATE),
        array('wechat', 'wechat', '官微格式不正确', self::VALUE_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
        array('user_id', 'createUserId', Model::MODEL_BOTH, 'callback'),
        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
    );
    /**
     * 取得单个分组信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getCompanyInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑分组
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $companyPost = $_POST['company'];
        $linkPost    = $_POST['companylink'];
        // $linkPost['linkid'] = $linkPost['id'];
        unset($linkPost['id']);
        $data = array_merge($companyPost, $linkPost);
        $area = explode(',', $data['p_area']);
        unset($data['p_area']);
        $data['lng'] = $area[0];
        $data['lat'] = $area[1];
        if ( I('post.user_id', 0, 'intval') > 0 ) {
            for ($i=3; $i < 11; $i++) { 
                unset($this->_validate[$i]);
            }
            $this->_validate = array_values($this->_validate);
        }
        if ( $companyPost['proprietary'] == 0 ) {
            unset($this->_validate[1]);
            $this->_validate = array_values($this->_validate);
        }
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '添加失败！';
                return false;
            }
            $data['id'] = $id;
        } else { //更新
            $status = $this->save($data);
            if(false === $status){
                $this->error = '修改失败！';
                return false;
            }
        }
        /* 更新自营商家运费模版 */
        // if ( $_POST['company']['proprietary'] == 1 ) {
        //     M('Freight')->where(array('proprietary'=>1))->setField('company_id', $data['id']);
        // }
        return $data;
    }
    /**
     * 删除分组
     *
     * @param int $id 记录ID
     * @param array $condition 删除条件
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delCompany($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 验证2次输入的密码是否一致
     */
    protected function equalPassword() {
        if ( $_POST['company']['password'] != $_POST['company']['password1'] ) {
            return false;
        }
        return true;
    }

    /**
     * 创建用户
     */
    protected function createUserId() {
        $user_id = I('post.user_id', 0, 'intval');
        if ( $user_id == 0 ) {
            $datas['mphone']    = $_POST['company']['mphone'];
            $datas['name']      = $_POST['company']['mphone'];
            $datas['lin_salt']  = '';
            for ($i = 0; $i < 6; $i++) {
                $datas['lin_salt'] .= chr(mt_rand(33, 126));
            }
            $datas['password']  = md5(md5($_POST['company']['password']).$datas['lin_salt']);
            $datas['gid']       = 2;
            $datas['regdate']   = time();
            $datas['addtime']   = time();
            return M('Member')->add($datas);
        } else {
            return $user_id;
        }
    }

    /**
     * 验证手机号是否被注册
     */
    protected function mobileOnly() {
        $mphone = $_POST['company']['mphone'];
        $result = M('Member')->where(array('mphone'=>$mphone))->getField('uid');
        if ( empty($result) ) {
            return true;
        }
        return false;
    }
}