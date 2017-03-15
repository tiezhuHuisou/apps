<?php
namespace Apps\Model;
use Think\Model;
class CompanyModel extends Model{
    /* 企业简介自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkId', '企业不存在', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUid', '只能修改自己企业的信息', self::MUST_VALIDATE, 'callback'),
        array('name', 'require', '企业名称不能为空', self::MUST_VALIDATE),
        array('name', '1,50', '企业名称应为1-50个字符', self::MUST_VALIDATE, 'length'),
        array('summary', 'require', '企业简介不能为空', self::MUST_VALIDATE),
        array('summary', '1,500', '企业简介应为1-500个字符', self::MUST_VALIDATE, 'length'),
        array('logo', 'require', '请上传企业logo', self::MUST_VALIDATE)
    );

    /* 企业联系我们自动验证 */
    protected $contact_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkId', '企业不存在', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUid', '只能修改自己企业的信息', self::MUST_VALIDATE, 'callback'),
        array('name', 'require', '企业名称不能为空', self::MUST_VALIDATE),
        array('name', '1,50', '企业名称应为1-50个字符', self::MUST_VALIDATE, 'length')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('modify_time', 'time', Model::MODEL_UPDATE, 'function')
    );

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update( $opt = '' ) {
        /* 自动验证 */
        $opt .= '_validate';
        $this->_validate = $this->$opt;

        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }

        /* 上传图片 */
        /*if ( !empty($_POST['logo']) ) {
            $result = R('Apps/General/uploadImgs', array($_POST['logo'], $_POST['suffix']));
            if ( empty($result['img']) ) {
                $this->error = $result['info'];
                return false;
            } else {
                $datas['logo'] = $result['img'];
            }
        }*/

        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '保存失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '保存失败';
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
        $condition['user_id'] = array('eq', $uid);
        return $this->where($condition)->delete();
    }

    /**
     * 检测企业是否存在
     */
    protected function checkId() {
        $id = I('post.id', 0, 'intval');
        $result = $this->where(array('id'=>$id))->getField('id');
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
     * 检测是否是自己的企业
     */
    protected function checkUid() {
        $id = I('post.id', 0, 'intval');
        $uid = $this->getUid();
        $user_id = $this->where(array('id'=>$id))->getField('user_id');
        if ( $user_id == $uid ) {
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