<?php
namespace Admin\Model;
use Think\Model;
class CompanyAlbumModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('pid', 'require', '参数错误', self::MUST_VALIDATE),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '标题长度应为1-50位字符', self::MUST_VALIDATE, 'length'),
        array('album_sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('album_sort', 'number', '排序应为数字', self::MUST_VALIDATE),
        array('album_sort', '0,99', '排序范围0~99之间', self::MUST_VALIDATE, 'between')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('ctime', 'time', Model::MODEL_INSERT, 'function'),
        array('etime', 'time', Model::MODEL_BOTH, 'function')
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
        if ( empty($datas) ) {
            return false;
        }
        $count = is_array($_POST['img']) ? count($_POST['img']) : 0;
        if ( !$count ) {
            $this->error = '至少上传一张相册图片';
            return false;
        }
        if ( is_array($_POST['is_cover']) ) {
            $cover_count = array_count_values($_POST['is_cover']);
            $cover_count = $cover_count[1];
            if ( $cover_count == 0 ) {
                $this->error = '请设置封面图';
                return false;
            }
            if ( $sort_count > 1 ) {
                $this->error = '只能设置一个封面图';
                return false;
            }

        } else {
            $this->error = '参数错误';
            return false;
        }
        
        /* 相册图片数据验证 */
        for ($i=0; $i < $count; $i++) {
            if ( empty($_POST['img'][$i]) ) {
                $this->error = '请上传相册图片';
                return false;
            }
            $allpicDates[$i]['img']      = $_POST['img'][$i];
            $allpicDates[$i]['is_cover'] = $_POST['is_cover'][$i];
            $allpicDates[$i]['sort']     = $_POST['sort'][$i];
        }

        /* 添加或更新 */
        $datas['sort'] = $_POST['album_sort'];
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
            M('CompanyAlbumPicture')->where(array('pid'=>$datas['id']))->delete();
        } else {
            /* 添加 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '添加失败';
                return false;
            }
            $datas['id'] = $id;
        }
        /* 添加图片 */
        for ($i=0; $i < $count; $i++) {
            $allpicDates[$i]['pid']      = $datas['id'];
        }
        M('CompanyAlbumPicture')->addAll($allpicDates);
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
}