<?php
namespace Apps\Model;
use Think\Model;
class NewsPraiseModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('pid', 'require', '参数错误', self::MUST_VALIDATE),
        array('pid', 'checkPid', '要点赞的资讯不存在', self::MUST_VALIDATE, 'callback'),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUnique', '您已点赞', self::MUST_VALIDATE, 'callback')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('ctime', 'time', Model::MODEL_INSERT, 'function')
    );

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        unset($datas['token']);
        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '点赞失败';
                return false;
            }
            $datas['id'] = $id;
        }
        return $datas;
    }

    /**
     * 删除数据
     * @param  array $condition 删除条件
     * @author 406764368@qq.com
     * @return bool 布尔类型的返回结果
     */
    public function del($condition) {
        $checkToken = $this->checkToken();
        if ( !$checkToken ) {
            return false;
        }
        $uid = $this->getUid();
        $condition['uid'] = array('eq', $uid);
        return $this->where($condition)->delete();
    }

    /**
     * 检测要点赞资讯是否存在
     */
    protected function checkPid() {
        $id = I('post.pid', 0, 'intval');
        $result = M('Article')->where(array('id'=>$id))->getField('id');
        if ( !$result ) {
            return false;
        }
        return true;
    }

    /**
     * 检测token信息，判断用户登陆信息是否过期
     */
    protected function checkToken() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        if ( !$memberInfo ) {
            return false;
        }
        return ture;
    }

    /**
     * 检测是否已经点赞
     */
    protected function checkUnique() {
        $pid = I('post.pid', 0, 'intval');
        $uid = $this->getUid();
        $where['pid'] = array('eq', $pid);
        $where['uid'] = array('eq', $uid);
        $result = $this->where($where)->getField('id');
        if ( !$result ) {
            return true;
        }
        return false;
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