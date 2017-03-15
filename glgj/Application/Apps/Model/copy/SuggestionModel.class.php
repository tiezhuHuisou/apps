<?php
namespace Apps\Model;
use Think\Model;
class SuggestionModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE),
        array('content', '1,1000', '内容应为1-1000个字符', self::MUST_VALIDATE, 'length')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('user_id', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('contact', 'getContact', Model::MODEL_BOTH, 'callback'),
        array('addtime', 'time', Model::MODEL_INSERT, 'function')
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

        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '发送失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '发送失败';
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
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }

    /**
     * 获取联系方式
     */
    protected function getContact() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['mphone'];
    }
}