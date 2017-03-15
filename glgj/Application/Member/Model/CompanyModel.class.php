<?php
namespace Member\Model;

use Think\Model;

/**
 * 公司简介模型
 * @author 122837594@qq.com
 */
class CompanyModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('name', 'require', '企业名称不能为空', self::MUST_VALIDATE),
        array('name', '1,100', '企业名称长度为1-100个字符', self::MUST_VALIDATE, "length"),
        array('summary', 'require', '企业详情不能为空', self::MUST_VALIDATE),
        // array('business', '1,225', '主营产品名称长度为1-225个字符', self::MUST_VALIDATE,"length"),
        array('logo', 'require', '企业LOGO不能为空', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array(
        array('status', 'get_status', Model::MODEL_BOTH, 'callback'),
        array('user_id', UID, Model::MODEL_INSERT),
        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
    );

    /**
     * 添加/编辑部门
     * @author 122837594@qq.com
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $this->create(I('post.company'));
        if (empty($datas)) {
            return false;
        }
        /* 添加或更新 */
        if (empty($datas['id'])) { //新增
            $id = $this->add($datas);
            if (!$id) {
                return false;
            }
            $datas['id'] = $id;
        } else {
            $status = $this->save($datas);
            if (false === $status) {
                return false;
            }
        }
        return $datas;
    }

    /**
     * 是否需要审核
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_status() {
        $info = M('Conf')->where(array('name' => 'company_type'))->find();
        if ($info['value'] == 1) {
            return 2;
        } else {
            M('Member')->where(array('uid' => UID))->setField('gid', 2);
            return 1;
        }
    }


}