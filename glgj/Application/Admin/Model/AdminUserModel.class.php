<?php
namespace Admin\Model;
use Think\Model;
class AdminUserModel extends Model{
    
    /*自动验证*/
    protected $_validate = array(
        array('user_name', 'require', '用户名不能为空', self::MUST_VALIDATE),
        array('user_name', '2,100', '用户名长度为2-50位字符', self::MUST_VALIDATE ,'length'),
        //array('user_name', 'unique', '用户名已存在', self::MUST_VALIDATE ,'',self::MODEL_INSERT),
        array('email', 'email', '请输入正确的邮箱', self::EXISTS_VALIDATE),
        array('password', 'require', '密码不能为空', self::MUST_VALIDATE),
        array('password', '6,40', '密码长度为6-40位字符', self::MUST_VALIDATE,'length'),
        array('nickname', '2,40', '昵称长度为2-40位字符', self::VALUE_VALIDATE,'length'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('lin_salt','get_lin_salt',Model::MODEL_INSERT,'callback'),
        array('password','get_password',Model::MODEL_BOTH,'callback'),
        array('role_id','get_role',Model::MODEL_INSERT,'callback'),
        array('add_time','time',Model::MODEL_INSERT,'function'),
    );
    /**
     * 取得单个管理员信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getAdminUserInfo($condition,$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑管理员
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data=$_POST;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['user_id'])){ //新增
            $re = $this->getAdminUserInfo(array('user_name'=>$data['user_name']));
            if($re){
                 $this->error = '用户名已存在';
                 return false;;
            }
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
    /**
     * 删除管理员
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delAdminUser($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 创建管理员时生成salt值
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_lin_salt(){
        $pw_length = 6;
        $randpwd ='';
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= chr(mt_rand(33, 126));
        }
        session("tmp",$randpwd);
        return $randpwd;
    }
    /**
     * 加密密码
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_password(){
         $password = I('password');
        $username = I('user_name');
        $user = $this->getAdminUserInfo(array('user_name'=>$username));
        if($user != '' || $user != false){
            $salt = $user['lin_salt'];
        }else{
            $salt = session("tmp");
        }
        if($password != $user['password']){
           $password = md5(md5($password).$salt);
        }
        session("tmp",'');
        return $password;
    }
    /**
     * 判断是不是admin增加超级管理员
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_role(){
        if(session("name") == 'admin'){
            return 1;
        }else{
            return 0;
        }
    }
}