<?php
namespace Admin\Controller;
use Think\Controller;
class PushController extends AdminController{
    
    protected function _initialize() {
        parent::_initialize();
        $this->assign('site','more');
        $this->assign('left','push');
        if ( !C('PUSH_APPKEY') || !C('PUSH_SECRET') ) {
            $this->error('推送功能暂未开通');
        }
    }
    /**
     * 推送消息管理首页
     * @return [type] [description]
     */
    public function index(){
        $order = "addtime desc";
        $list = $this->lists('Push','',$order,'');
        $this->assign('list',$list);
        /*页面基本设置*/
        $this->site_title="推送消息管理";
        $this->display();
    }

    /**
     * 添加推送消息
     * @return [type] [description]
     */
    public function add(){

        $id = I('request.id', 0, 'intval');
        $model = D('Push');
        if( IS_POST ) {
            $result = $model->update();
            if ( $result && $result['push'] ) {
                if( $id ) {
                    $this->success('推送成功', '?g=admin&m=push');
                } else {
                    $this->success('推送成功', '?g=admin&m=push');
                }
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ( $id ) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail',$detail);
        }

        /* 查询资讯 */
        $list = M('Article')->field('id,title,content')->where(array('is_display'=>1))->order('sort DESC,flags DESC,uptime DESC')->select();
        foreach ($list as $key => $value) {
            if ( mb_strlen($value['content'], 'utf-8') > 120 ) {
                $list[$key]['content'] = mb_substr(strip_tags($value['content']), 0, 117, 'utf-8') . '...';
                $list[$key]['content'] = str_ireplace('&nbsp;', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\t/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\r\n/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\r/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/ /', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/  /', '', $list[$key]['content']);
            }
        }
        $this->assign('list', $list);

        /*页面基本设置*/
        $this->site_title = '推送消息管理';
        $this->assign('left', 'push');
        $this->display();
    }

    /**
     * 根据推送页面类型获取对应数据
     */
    public function getList() {
        if ( IS_POST ) {

            $model = D('Push');
            $param = $model->getParam();

            $list  = $param['model']->field($param['field'])->where($param['where'])->order($param['order'])->select();

            foreach ($list as $key => $value) {
                mb_strlen($value['title'], 'utf-8') > 20 && $list[$key]['title'] = mb_substr(strip_tags($value['title']), 0, 17, 'utf-8') . '...';
                mb_strlen($value['content'], 'utf-8') > 120 && $list[$key]['content'] = mb_substr(strip_tags($value['content']), 0, 117, 'utf-8') . '...';
                $list[$key]['content'] = str_ireplace('&nbsp;', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\t/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\r\n/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/\r/', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/ /', '', $list[$key]['content']);
                $list[$key]['content'] = preg_replace('/  /', '', $list[$key]['content']);
            }
            $this->ajaxReturn($list);
        }
    }

    /**
     * 推送消息删除
     * @author 83961014@qq.com
     */
    public function del(){
        $id = I('id');
        $push = D('Push');
        $condition['id'] = $id;
        $return = $push->del($condition);
        if($return != false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 推送消息批量删除
     * @author 83961014@qq.com
     */
    public function delall(){
        if (IS_POST){
            $ids        = I('ids');
            $ids        = implode(',', $ids);
            $push = D('Push');
            $condition['id'] = array('in',$ids);
            $tem = $push->del($condition);
            if($tem != false){
                $return['errno']        = 0;
                $return['error']        = "删除成功";
                $this->ajaxReturn($return);
            }else{
                $return['errno']        = 1;
                $return['error']        = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }
}