<?php
namespace Apps\Model;
use Think\Model;
class CompanyManageModel extends Model{
    protected $tableName = 'company';
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkId', '要修改的企业不存在', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('linkid', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('linkid', 'checkLinkid', '参数异常', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('token', 'checkGid', '您已是企业会员或正在申请中', self::MUST_VALIDATE, 'callback', Model::MODEL_INSERT),
        array('token', 'checkUid', '只能修改自己企业的信息', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('name', 'require', '企业名称不能为空', self::MUST_VALIDATE),
        array('name', '1,50', '企业名称应为1-50个字符', self::MUST_VALIDATE, 'length'),
        array('name', 'checkName', '企业名称不能修改', self::MUST_VALIDATE, 'callback'),
        // array('business', 'require', '企业主营行业不能为空', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('business', '1,200', '企业主营行业应为1-200个字符', self::VALUE_VALIDATE, 'length'),
        // array('summary', 'require', '企业简介不能为空', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('summary', '1,1000', '企业简介应为1-1000个字符', self::VALUE_VALIDATE, 'length'),
        // array('logo', 'require', '请上传企业logo', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('logo', 'url', '企业logo异常', self::VALUE_VALIDATE),
        // array('bgimg', 'require', '请上传企业主页背景图', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('bgimg', 'url', '企业主页背景图异常', self::VALUE_VALIDATE),
        // array('introduction_bgimg', 'require', '请上传企业简介背景图', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('introduction_bgimg', 'url', '企业简介背景图异常', self::VALUE_VALIDATE),
        // array('address', 'require', '企业详细地址不能为空', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('address', '1,50', '企业详细地址应为1-50个字符', self::VALUE_VALIDATE, 'length'),
        array('contact_user', 'require', '企业联系人不能为空', self::MUST_VALIDATE),
        array('subphone', 'require', '联系电话不能为空', self::MUST_VALIDATE),
        array('subphone', 'phone', '联系电话格式不正确', self::MUST_VALIDATE),
        array('telephone', 'phone', '电话2格式不正确', self::VALUE_VALIDATE),
        array('site', 'url', '企业官网格式不正确', self::VALUE_VALIDATE),
        array('wechat', 'wechat', '企业官微格式不正确', self::VALUE_VALIDATE),
        // array('lng', 'require', '请在地图上标注企业所在地', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        // array('lat', 'require', '请在地图上标注企业所在地', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        // array('company_category_id', 'require', '请选择企业分类', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE)
    );

    /* 自动完成 */
    protected $_auto = array (
        array('user_id', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('status', 'getStatus', Model::MODEL_BOTH, 'callback'),
        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
        array('logo', 'logoDefault', Model::MODEL_BOTH, 'callback'),
        array('bgimg', 'bgimgDefault', Model::MODEL_BOTH, 'callback'),
        array('introduction_bgimg', 'introductionBgimgDefault', Model::MODEL_BOTH, 'callback'),
    );

    /**
     * 查询单挑记录
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update() {
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
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '申请失败';
                return false;
            }
            $datas['id'] = $id;
        }
        return $datas;
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
     * 检测企业副表是否存在
     */
    protected function checkLinkid() {
        $id     = I('post.id', 0, 'intval');
        $linkid = I('post.linkid', 0, 'intval');
        $result = M('CompanyLink')->where(array('id'=>$linkid, 'company_id'=>$id))->find();
        if ( !$result ) {
            return false;
        }
        return true;
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

    /**
     * 获取企业状态
     */
    protected function getStatus() {
        $id = I('post.id', 0, 'intval');
        $company_type = M('Conf')->where(array('name'=>'company_type'))->getField('value');
        if ( $company_type && !$id ) {
            return 2;
        } elseif ( $this->where(array('id'=>$id))->getField('status') == 3 && $company_type ) {
            return 2;
        } else {
            return 1;
        }
    }

    /**
     * 企业会员不能申请
     */
    protected function checkGid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        if ( $memberInfo['gid'] == 2 ) {
            return false;
        }
        return true;
    }

    /**
     * 若logo为空则设置默认logo
     */
    protected function logoDefault() {
        $logo = I('post.logo');
        if ( $logo ) {
            return $logo;
        }
        return C('HTTP_APPS_IMG') . 'company_default.png';
    }

    /**
     * 若企业主页背景图为空则设置默认企业主页背景图
     */
    protected function bgimgDefault() {
        $bgimg = I('post.bgimg');
        if ( $bgimg ) {
            return $bgimg;
        }
        return C('HTTP_APPS_IMG') . 'company_bgimg.png';
    }

    /**
     * 若logo为空则设置默认logo
     */
    protected function introductionBgimgDefault() {
        $introduction_bgimg = I('post.introduction_bgimg');
        if ( $introduction_bgimg ) {
            return $introduction_bgimg;
        }
        return C('HTTP_APPS_IMG') . 'company_introduction_bgimg.png';
    }

    /**
     * 企业名称不能修改
     */
    protected function checkName() {
        $id   = I('post.id', 0, 'intval');
        $name = I('post.name');
        if ( $id ) {
            $companyName = $this->where(array('id'=>$id))->getField('name');
            if ( $companyName != $name ) {
                return false;
            }
        }
        return true;
    }
}