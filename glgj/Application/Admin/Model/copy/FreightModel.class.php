<?php
namespace Admin\Model;
use Think\Model;
class FreightModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '非法操作', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('title', 'require', '模版名称不能为空', self::MUST_VALIDATE),
        array('title', '1,15', '模版名称长度应为1-15位字符', self::MUST_VALIDATE , 'length'),
        array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', 'number', '排序应为数字', self::MUST_VALIDATE),
        array('sort', '0,99', '排序范围0~99之间', self::MUST_VALIDATE, 'between'),
        array('delivery_id', 'require', '请选择物流公司', self::MUST_VALIDATE),
        array('piece', 'require', '请选择计价方式', self::MUST_VALIDATE),
    );

    /* 自动完成 */
    protected $_auto = array (
        // array('company_id', '0', Model::MODEL_BOTH, 'callback'),
        array('proprietary', 1, Model::MODEL_INSERT),
    );

    /**
     * 取得单条记录
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 406764368@qq.com
     * @return 返回一条满足条件的记录
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 添加、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( !$datas ) {
            return false;
        }

        /* 配送规则 */
        foreach ($_POST['placeAllId'] as $key => $val) {
            $temp[$key]['placeallid'] = $val;
            $temp[$key]['package_first'] = $_POST['package_first'][$key] ? $_POST['package_first'][$key] : 0;
            $temp[$key]['freight_first'] = $_POST['freight_first'][$key] ? $_POST['freight_first'][$key] : 0;
            $temp[$key]['package_other'] = $_POST['package_other'][$key] ? $_POST['package_other'][$key] : 0;
            $temp[$key]['freight_other'] = $_POST['freight_other'][$key] ? $_POST['freight_other'][$key] : 0;
        }
        $datas['postage'] = json_encode($temp);

        /* 添加或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 添加 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '添加失败';
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
    public function del($condition){
        return $this->where($condition)->delete();
    }

    protected function getCompanyId() {
        $company_id = M('Company')->where(array('proprietary'=>1))->getField('id');
        return $company_id ? $company_id : 0;
    }
}