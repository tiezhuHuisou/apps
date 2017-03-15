<?php
namespace Admin\Model;
use Think\Model;
class JdpicModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE),
        array('name', '2,50', '名称长度为2-50位字符', self::MUST_VALIDATE ,'length'),
        array('tid', 'number', '分类异常', self::MUST_VALIDATE),
        array('url', 'require', '请输入跳转地址', self::VALUE_VALIDATE),
        array('thumbnail', 'require', '图片不能为空', self::MUST_VALIDATE),
        array('listorder', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('listorder', 'number', '排序只能为1~99的数字', self::MUST_VALIDATE),
        array('listorder', '1,99', '排序只能为1~99的数字', self::MUST_VALIDATE,'between'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('url','encapsulation',Model::MODEL_BOTH,'callback'),
        array('addtime','time',Model::MODEL_INSERT,'function')
    );
    /**
     * 取得单个焦点图信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getJdpicInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑焦点图
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
        if(empty($data['id'])){ //新增
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
     * 删除焦点图
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delJdpic($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 封装url
     */
    protected function encapsulation() {
        $url = I('post.url');
        $href_type = I('post.href_type', 0, 'intval');
        if ( $href_type == 1 ) {
            /* 外链 */
            return $url;
        } elseif ( $href_type == 2 ) {
            /* 内链 封装url */
            $data_id    = I('post.data_id', 0, 'intval');
            $href_model = I('post.href_model');
            $url = C('HTTP_ORIGIN') . '/?g=app&m=apps&a=' . $href_model . '&id=' . $data_id;
            return $url;
        } else {
            return '';
        }
    }
}
