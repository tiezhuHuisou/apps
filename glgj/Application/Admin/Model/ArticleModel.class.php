<?php
namespace Admin\Model;
use Think\Model;
class ArticleModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '标题长度为1-50位字符', self::MUST_VALIDATE ,'length'),
        array('short_title', 'require', '副标题不能为空', self::MUST_VALIDATE),
        array('short_title', '1,100', '副标题长度为1-100位字符', self::EXISTS_VALIDATE,'length'),
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE),
        array('categoryid', 'require', '分类不能为空', self::MUST_VALIDATE),
		array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', 'number', '排序应为数字', self::MUST_VALIDATE),
        array('sort', '1,99', '排序范围在1~99之间', self::MUST_VALIDATE,'between'),
        array('source', 'require', '来源不能为空', self::MUST_VALIDATE)
    );
    /*自动完成*/
    protected $_auto = array (
        array('addtime','time',Model::MODEL_INSERT,'function'),
        array('uptime','time',Model::MODEL_BOTH,'function'),
    );
    /**
     * 取得单个新闻信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getArticleInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑新闻
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data=$_POST;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        $count = 0;
        if ( is_array($_POST['allpic']) ) {
            $count = count($_POST['allpic']);
            /* 取第一张轮播图作为封面图 */
            $data['image'] = $_POST['allpic'][0];
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
            $data['id'] = $id;
        } else { //更新
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
            M('NewsPicture')->where(array('pid'=>$data['id']))->delete();
        }
        /* 添加图片 */
        for ($i=0; $i < $count; $i++) { 
            $allpicDates[$i]['pid'] = $data['id'];
            $allpicDates[$i]['img'] = $_POST['allpic'][$i];
        }
        M('NewsPicture')->addAll($allpicDates);
        return $data;
    }
    /**
     * 删除新闻
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delArticle($condition){
        return $this->where($condition)->delete();
    }
}