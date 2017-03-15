<?php
namespace Admin\Model;
use Think\Model;
class AdminGroupModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('gname', 'require', '分组名不能为空', self::MUST_VALIDATE),
        array('gname', '2,100', '分组名长度为2-50位字符', self::MUST_VALIDATE ,'length'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('addtime','time',Model::MODEL_INSERT,'function'),
        array('rule','dealrule',Model::MODEL_BOTH,'callback'),
    );
    /**
     * 取得单个管理员信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getAdminGroupInfo($condition = array(),$field="*") {
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
        if(empty($data['gid'])){ //新增
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
     * 权限处理成字符串
     */
    protected function dealrule(){
        $rule=I('post.rule');
        if($rule){
            return implode(",",$rule);
        }
    }

    /**
     * 删除管理员
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delAdminGroup($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 创建管理员时生成salt值
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_lin_salt(){
        global $tmp;
        $pw_length = 6;
        $randpwd ='';
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= chr(mt_rand(33, 126));
        }
        $_SESSION["tmp"] = $randpwd;
        return $randpwd;
    }
    /**
     * 加密密码
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_password(){
        $password = $_POST['password'];
        $username = $_POST['username'];
        $user = $this->getAdminGroupInfo(array('user_name='=>$username));
        if($user != '' || $user != false){
            $salt = $user['lin_sale'];
        }else{
            $salt = $_SESSION["tmp"];
        }
        $password = md5(md5($password).$salt);
        unset($_SESSION["tmp"]);
        return $password;
    }
}