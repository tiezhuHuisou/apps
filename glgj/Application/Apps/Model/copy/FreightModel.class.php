<?php
namespace Apps\Model;
use Think\Model;
class FreightModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('id', 'require', '非法操作', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('company_id', 'require', '参数错误', self::MUST_VALIDATE),
        array('title', 'require', '请填写运费模版名称', self::MUST_VALIDATE),
        array('title', '1,15', '运费模版名称应为1-15个字符', self::MUST_VALIDATE, 'length'),
        array('package_first', 'money', '首件/首重格式不正确', self::VALUE_VALIDATE),
        array('freight_first', 'money', '优惠券面值格式不正确', self::VALUE_VALIDATE),
        array('package_other', 'money', '首件/首重格式不正确', self::VALUE_VALIDATE),
        array('freight_other', 'money', '优惠券面值格式不正确', self::VALUE_VALIDATE),
        array('placeallid', 'require', '请填写标识', self::MUST_VALIDATE),
        array('sort', 'require', '请填写排序', self::MUST_VALIDATE),
        array('sort', 'number', '排序应为数字', self::MUST_VALIDATE),
        array('sort', '0,99', '排序范围0-99', self::MUST_VALIDATE, 'between'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
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
        
        /* 解析物流公司 */
        if ( $_POST['delivery_id'] ) {
            $datas['delivery_id'] = $this->analysisRadio($_POST['delivery_id']);
            if ( empty($datas['delivery_id']) ) {
                $this->error = '请选择物流公司';
                return false;
            }
        }
        /* 解析按件计费 */
        if ( $_POST['piece'] ) {
            $datas['piece'] = $this->analysisRadio($_POST['piece']);
            if ( empty($datas['piece']) ) {
                $this->error = '请选择计费方式';
                return false;
            }
        }
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }

        /* 运费模版规则 */
        $datas['postage'][] = array(
            'placeallid'    => $_POST['placeallid'],
            'package_first' => $_POST['package_first'],
            'freight_first' => $_POST['freight_first'],
            'package_other' => $_POST['package_other'],
            'freight_other' => $_POST['freight_other'],
        );
        $datas['postage'] = json_encode($datas['postage']);

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
     * 解析数据
     */
    protected function analysisRadio( $arr ) {
        if ( $arr ) {
            foreach ($arr as $key => $value) {
                $value = explode(',', $value);
                if ( $value[1] == 1 ) {
                    $datas[] = $value[0];
                }
            }
        }
        return $datas ? implode(',', $datas) : '';
    }
}