<?php
namespace Apps\Model;
use Think\Model;
class CompanyLinkManageModel extends Model{
    protected $tableName = 'company_link';
    /* 自动验证 */
    protected $_validate = array(

    );

    /* 自动完成 */
    protected $_auto = array (
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
    );

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update( $company_id ) {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas['id']         = $_POST['linkid'];
        $datas['company_id'] = $company_id;
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
}