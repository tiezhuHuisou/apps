<?php
namespace Apps\Model;
use Think\Model;
class CompanyLinkModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('company_id', 'require', '操作失败', self::MUST_VALIDATE),
        array('lng', 'require', '请标注企业所在地', self::MUST_VALIDATE),
        array('lat', 'require', '请标注企业所在地', self::MUST_VALIDATE),
        /*array('province_id', 'require', '请选择企业所在省份', self::MUST_VALIDATE),
        array('city_id', 'require', '请选择企业所在城市', self::MUST_VALIDATE),
        array('countyN', 'require', '请选择企业所在区、县', self::MUST_VALIDATE),*/
        array('address', 'require', '企业地址不能为空', self::MUST_VALIDATE),
        array('contact_user', 'require', '企业联系人不能为空', self::MUST_VALIDATE),
        array('subphone', 'phone', '联系人电话1格式不正确', self::VALUE_VALIDATE),
        array('telephone', 'phone', '联系人电话2格式不正确', self::VALUE_VALIDATE),
        array('wechat', 'wechat', '企业官微格式不正确', self::VALUE_VALIDATE),
        array('site', 'url', '企业官网格式不正确', self::VALUE_VALIDATE)
    );

    /* 自动完成 */
    protected $_auto = array (
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
    );
    
    /**
     * 添加/编辑公司信息
     * @author 83961014@qq.com
     */
    public function update($company_id) {
        /* 获取数据对象 */
        $data = $_POST;
        $data['company_id'] = $company_id;
        /*$area = explode(',', $data['p_area']);
        unset($data['p_area']);
        $data['lng'] = $area[0];
        $data['lat'] = $area[1];*/
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '保存失败！';
                return false;
            }
        } else { //更新
            $status = $this->save($data);
            if(false === $status){
                $this->error = '保存失败！';
                return false;
            }
        }
        return $data;
    }
}