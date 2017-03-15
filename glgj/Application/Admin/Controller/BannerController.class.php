<?php
namespace Admin\Controller;
use Think\Controller;
class BannerController extends AdminController{
    
    protected function _initialize() {
        parent::_initialize();
        $this->assign('site','more');
        $this->assign('left','banner');
    }
    /**
     * 焦点图管理首页
     * @return [type] [description]
     */
    public function index(){
        $order = "listorder desc,id desc";
        $list = $this->lists('Jdpic','',$order,'');
        $classify = D('Jdtype');
        foreach ($list as &$val){
            $tmp = $classify->getJdtypeInfo(array('id'=>$val['tid']));
            $val['classify'] = $tmp['name']; 
        }
        $this->assign('list',$list);
        /*页面基本设置*/
        $this->site_title="焦点图管理-焦点图列表";
        $this->display();
    }

    /**
     * 添加焦点图
     * @return [type] [description]
     */
    public function add(){
        $id = I('id');
        $condition['id'] = $id;
        $pic = D('Jdpic');
        
        if(IS_POST){
            $status = $pic->update();
            if($status){
                if($id){
                    $this->success('修改成功','?g=admin&m=banner');
                }else{
                $this->success('添加成功','?g=admin&m=banner');
                }
            }else{
                $errorInfo=$pic->getError();
                $this->error($errorInfo);
            }
        }
        
        if ( $id ) {
            $detail = $pic->getJdpicInfo($condition);
        }
//        dump($detail['href_model']);exit;
//        $dataList = $this->getModelAllDatas($detail['href_model']);
        $list = M('Jdtype')->order('listorder DESC')->select();
        $this->assign('dataList', $dataList);
        $this->assign('list',$list);
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="焦点图管理-编辑焦点图";
        $this->display();
    }

    /**
     * 焦点图切换请求对应模块下的所有数据
     */
    public function getDatas() {
        $href_model = I('post.href_model');
//        dump($href_model);exit;
        !$href_model && $this->error('参数错误');
        $dataList = $this->getModelAllDatas($href_model);
        $this->ajaxReturn($dataList);
    }

    /**
     * 焦点图删除
     * @author 83961014@qq.com
     */
    public function del(){
        $id = I('id');
        $pic = D('Jdpic');
        $condition['id'] = $id;
        $return = $pic->delJdpic($condition);
        if($return != false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 焦点图批量删除
     * @author 83961014@qq.com
     */
    public function delall(){
        if (IS_POST){
            $ids        = I('ids');
            $ids        = implode(',', $ids);
            $pic = D('Jdpic');
            $condition['id'] = array('in',$ids);
            $tem = $pic->delJdpic($condition);
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

    /**
     * 焦点图分类
     * @return [type] [description]
     */
    public function classify(){
        $order = "listorder desc,addtime desc";
        $list = $this->lists('Jdtype','',$order,'');
        $this->assign('list',$list);
        /*页面基本设置*/
        $this->site_title="焦点图管理-焦点图分类列表";
        $this->assign('left','banner_classify');
        $this->display();
    }

    /**
     * 添加焦点图分类
     * @return [type] [description]
     */
    public function classify_add(){
        $id = I('id');
        $condition['id'] = $id;
        $classify = D('Jdtype');
        $detail = $classify->getJdtypeInfo($condition);
        $list = $this->lists('artcategory');
        if(IS_POST){
            $status = $classify->update();
            if($status){
                if($id){
                    $this->success('修改成功','?g=admin&m=banner&a=classify');
                }else{
                $this->success('添加成功','?g=admin&m=banner&a=classify');
                }
            }else{
                $errorInfo=$classify->getError();
                $this->error($errorInfo);
            }
        }
         
         
        $this->assign('list',$list);
        $this->assign('detail',$detail);
        /*页面基本设置*/
        $this->site_title="焦点图管理-编辑焦点图分类";
        $this->assign('left','banner_classify');
        $this->display();
    }
    /**
     * 焦点图分类删除
     * @author 83961014@qq.com
     */
    public function classify_del(){
        $id = I('id');
        $classify = D('Jdtype');
        $condition['id'] = $id;
        $return = $classify->delJdtype($condition);
        if($return != false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 焦点图分类批量删除
     * @author 83961014@qq.com
     */
    public function classify_delall(){
        if (IS_POST){
            $ids        = I('ids');
            $ids        = implode(',', $ids);
            $classify = D('Jdtype');
            $condition['id'] = array('in',$ids);
            $tem = $classify->delJdtype($condition);
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