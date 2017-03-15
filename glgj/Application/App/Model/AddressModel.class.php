<?php
namespace App\Model;
use Think\Model;
class AddressModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('name', 'require', '收货人姓名不能为空', self::MUST_VALIDATE),
        array('name', '2,20', '收货人姓名长度为2-100位字符', self::MUST_VALIDATE ,'length'),
        array('phone', 'require', '手机号码必须', self::MUST_VALIDATE),
        array('phone', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '请输入正确的手机号码', self::MUST_VALIDATE,'regex'),
        array('region', 'require', '省市区不能为空', self::MUST_VALIDATE),
        array('address', 'require', '详细地址不能为空', self::MUST_VALIDATE),
        array('address', '2,100', '详细地址长度为2-200位字符', self::MUST_VALIDATE,'length'),
        
    );
    /*自动完成*/
    protected $_auto = array (
        array('owner',UID,Model::MODEL_INSERT,'string'),
        array('lastuse','time',Model::MODEL_BOTH,'function'),
    );
    /**
     * 添加/编辑新闻
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data=I('post.');
        if(!$data['city']){
            $data['region']='';
        }else{
            if($data['towns']){
                $data['region']=M('Regions')->where(array('id'=>$data['towns']))->getField('path');
            }else{
                $data['region']=M('Regions')->where(array('id'=>$data['city']))->getField('path');
            }
        }
        unset($data['province'],$data['city'],$data['towns']);
        
        $data = $this->create($data);
        
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
    
        } else { //更新
    
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }
        return $data;
    }


    
}