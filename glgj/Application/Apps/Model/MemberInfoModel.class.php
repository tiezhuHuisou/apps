<?php
namespace Apps\Model;
use Think\Model;

class MemberInfoModel extends Model {
    /* 设置表 */
    protected $tableName = 'member'; 
    /*自动验证-注册*/
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::MUST_VALIDATE),
        array('name', '1,50', '姓名长度应为1-50个字符', self::MUST_VALIDATE, 'length'),
        array('head_pic', 'require', '请上传头像', self::MUST_VALIDATE),
        array('head_pic', 'url', '头像格式不正确', self::MUST_VALIDATE)
    );

    /*自动完成*/
    protected $_auto = array(
        
    );

    /*用户修改资料*/
    public function update() {
        $data = I('post.');
        unset($data['mphone']);
        $data = $this->create($data);
        if (empty($data)) {
            return false;
        }
        $uid    = $this->getUid();
        $result = $this->where(array('uid'=>$uid))->save($data);
        if ($result === false) {
            $this->error = '修改失败';
            return false;
        }
        return $data;
    }

    /**
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }
}
