<?php
namespace Admin\Controller;

use Think\Controller;

class NewsController extends AdminController {

    protected function _initialize() {
        parent::_initialize();
        $this->assign('site', 'news');
    }

    /**
     * 资讯管理首页
     * @return [type] [description]
     * @author 83961014@qq.com
     */
    public function index() {
        $search = I('search');
        $flags = I('flags');
        $condition['title'] = array('like', '%' . $search . '%');
        if ($flags) {
            $condition['flags'] = $flags;
            $this->assign('f', $flags);
        }
        $order = 'id DESC';
        $field = 'id,categoryid,title,image,is_display,flags,uptime';
        $list = $this->lists('Article', $condition, $order, $field);
        $classify = M('Artcategory');
        $flags = M('NewsFlags');
        foreach ($list as &$val) {
            $val['classify'] = $classify->where(array('cid' => $val['categoryid']))->getField('cname');
            $val['flags'] = $flags->where(array(array('flags' => $val['flags']), array('flags' => array('neq', ''))))->getField('title');
        }
        $where['flags'] = array('neq', '');
        $where['status'] = array('eq', 1);
        $flags = $flags->where($where)->order('sort DESC,id DESC')->select();
        $this->assign('flags', $flags);
        $this->assign('list', $list);
        $this->assign('search', $search);
        /*页面基本设置*/
        $this->site_title = "资讯管理-资讯列表";
        $this->assign('left', 'index');
        $this->display();
    }

    /**
     * 资讯删除
     * @author 83961014@qq.com
     */
    public function del() {
        $id = I('id');
        $article = D('Admin/Article');
        $condition['id'] = $id;
        $return = $article->delArticle($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 资讯批量删除
     * @author 83961014@qq.com
     */
    public function delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $article = D('Article');
            $condition['id'] = array('in', $ids);
            $tem = $article->delArticle($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 添加资讯
     * @author 83961014@qq.com
     */
    public function add() {
        $id = I('request.id', 0, 'intval');
        $opt = $id ? '修改' : '添加';
        $article = D('Article');

        if (IS_POST) {
            $status = $article->update();
            if ($status) {
                if ($id) {
                    $this->success('修改成功', '?g=admin&m=news');
                } else {
                    $this->success('添加成功', '?g=admin&m=news');
                }
            } else {
                $errorInfo = $article->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改时查询资讯详情 */
        if ($id) {
            $condition['id'] = $id;
            $detail = $article->getArticleInfo($condition);
            $this->assign('detail', $detail);

            $allpic = M('NewsPicture')->field('img')->where(array('pid' => $id))->select();
            $this->assign('allpic', $allpic);
        }

        /* 分类 */
        $classify = M('Artcategory')->field('cid,cname')->where(array('status' => 1))->order('sort DESC,cid DESC')->select();
        $this->assign('classify', $classify);

        /* 属性 */
        $flags = M('NewsFlags')->field('title,flags')->where(array('status' => 1))->order('sort DESC,id DESC')->select();
        $this->assign('flags', $flags);

        /*页面基本设置*/
        $this->site_title = "资讯管理-编辑资讯";
        $this->assign('left', 'index');
        $this->display();
    }

    /**
     * 资讯分类
     * @return [type] [description]
     */
    public function classify() {
        $order = 'addtime desc';
        //$list = $this->lists('artcategory','',$order);
        $list = M('Artcategory')->order($order)->select();
        $list = list_to_tree($list, 'cid', 'pid');
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "资讯管理-资讯分类列表";
        $this->assign('left', 'classify');
        $this->display();
    }

    /**
     * 添加资讯分类
     * @return [type] [description]
     */
    public function classify_add() {
        $cid = I('cid');
        $condition['cid'] = $cid;
        $artcategory = D('Artcategory');
        $detail = $artcategory->getArtcategoryInfo($condition);
        // $where['status'] = array('eq', 1);
        // $cid && $where['cid'] = array('neq', $id);
        // $list = $artcategory->where($where)->select();
        if (IS_POST) {
            $status = $artcategory->update();
            if ($status) {
                if ($cid) {
                    $this->success('修改成功', '?g=admin&m=news&a=classify');
                } else {
                    $this->success('添加成功', '?g=admin&m=news&a=classify');
                }
            } else {
                $errorInfo = $artcategory->getError();
                $this->error($errorInfo);
            }
        }
        // $this->assign('list',$list);
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title = "资讯管理-添加资讯分类";
        $this->assign('left', 'classify');
        $this->display();
    }

    /**
     * 资讯分类删除
     * @author 83961014@qq.com
     */
    public function classify_del() {
        $cid = I('cid');
        $artcategory = D('Artcategory');
        $condition['cid'] = $cid;
//      $condition['pid'] = $cid;
//      $condition['_logic'] = 'OR';
        $return = $artcategory->delArtcategory($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 资讯分类批量删除
     * @author 83961014@qq.com
     */
    public function classify_delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $artcategory = D('Artcategory');
            $condition['cid'] = array('in', $ids);
//          $condition['pid'] = array('in',$ids);
//          $condition['_logic'] = 'OR';
            $tem = $artcategory->delArtcategory($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 公告管理
     * @return [type] [description]
     */
    public function notice() {
        $flags = I('post.flags', '', 'strval');
        $search = I('post.search', '', 'strval');
        if ($flags) {
            $where['flags'] = array('eq', $flags);
            $this->assign('f', $flags);
        }
        if ($search) {
            $where['title'] = array('like', '%' . $search . '%');
            $this->assign('search', $search);
        }

        $order = 'uptime desc';
        $list = $this->lists('announce', $where, $order);
        $flags = M('Flags')->field('cid,att flags,attname title')->select();
        $this->assign('list', $list);
        $this->assign('flags', $flags);
        /*页面基本设置*/
        $this->site_title = "资讯管理-公告列表";
        $this->assign('left', 'notice');
        $this->display();
    }

    /**
     * 添加公告
     * @return [type] [description]
     */
    public function notice_add() {
        $id = I('get.id', 0, 'intval');
        $model = D('Announce');
        if($id){
            $where['id'] = array('eq',$id);
            $detail = $model->getOneInfo($where);
            $this->assign('detail', $detail);    //单条信息
        }
        if (IS_POST) {
            $status = $model->update();
            if ($status) {
                $this->success('添加成功', '?g=admin&m=news&a=notice');

            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }
        $flags = $this->lists('flags');
        $this->assign('flags', $flags);

        /*页面基本设置*/
        $this->site_title = "资讯管理-编辑公告";
        $this->assign('left', 'notice');
        $this->display();
    }

    /**
     * 公告删除
     * @author 83961014@qq.com
     */
    public function announce_del() {
        $id = I('id');
        $artcategory = D('announce');
        $condition['id'] = $id;
        $return = $artcategory->delArtcategory($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 公告批量删除
     * @author 83961014@qq.com
     */
    public function announce_delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Announce');

            $condition['id'] = array('in', $ids);
            $tem = $model->delArtcategory($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 行业圈列表
     */
    public function circle() {
        $content = I('request.search');
        $where['content'] = array('like', '%' . $content . '%');
        $this->assign('search', $content);

        $category = M('CircleCategory')->field('id,title')->select();
        $category = array_column($category, 'title', 'id');

        $list = $this->lists('Circle', $where, 'sort DESC,id DESC', 'id,cid,content,sort,ctime');
        foreach ($list as $key => $value) {
            $list[$key]['content'] = mb_substr($value['content'], 0, 20, 'utf-8') . '...';
            $list[$key]['cname'] = $category[$value['cid']];
        }
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = '行业圈列表';
        $this->assign('left', 'circle');
        $this->display();
    }

    /**
     * 行业圈添加、修改
     */
    public function circleAdd() {
        $id = I('request.id', 0, 'intval');
        $model = D('Circle');
        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();
            if ($result) {
                $this->success($opt . '成功', '?g=admin&m=news&a=circle');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ($id) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail', $detail);

            $allpic = M('CirclePicture')->field('img')->where(array('pid' => $id))->select();
            $this->assign('allpic', $allpic);
        }

        /* 发布人 */
        if ($detail['uid'] > 0 && $detail['gid'] == 1) {
            $company = M('Member')->field('uid as id,name,mphone as mobile')->select();
        } else {
            $map['status'] = array('eq', 1);
            $company = M('Company')->field('user_id id,name')->where($map)->select();
        }
        $this->assign('company', $company);

        /* 分类 */
        $category = M('CircleCategory')->field('id,title')->where(array('status' => 1))->order('sort DESC,id DESC')->select();
        $this->assign('category', $category);

        /* 页面基本设置 */
        $this->site_title = $opt . '行业圈';
        $this->assign('left', 'circle');
        $this->display();
    }

    /**
     * 行业圈删除
     */
    public function circleDel() {
        $id = I('id');
        $model = D('Circle');
        $condition['id'] = $id;
        $return = $model->del($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 行业圈批量删除
     */
    public function circleDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Circle');
            $condition['id'] = array('in', $ids);
            $tem = $model->del($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 行业圈分类列表
     */
    public function circleCategory() {
        $list = $this->lists('CircleCategory', $where, 'sort DESC,id DESC', 'id,title,sort,status');
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = '行业圈分类列表';
        $this->assign('left', 'circlecategory');
        $this->display();
    }

    /**
     * 行业圈分类添加、修改
     */
    public function circleCategoryAdd() {
        $id = I('request.id', 0, 'intval');
        $model = D('CircleCategory');
        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();
            if ($result) {
                $this->success($opt . '成功', '?g=admin&m=news&a=circlecategory');
            } else {
                $this->error($model->getError());
            }
        }

        /* 修改 */
        if ($id) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail', $detail);
        }

        /* 页面基本设置 */
        $this->site_title = $opt . '行业圈分类';
        $this->assign('left', 'circlecategory');
        $this->display();
    }

    /**
     * 行业圈分类删除
     */
    public function circleCategoryDel() {
        $id = I('id');
        if ($id == 1 || $id == 2) {
            $this->error('默认分类不能删除');
        }
        $model = D('CircleCategory');
        $condition['id'] = $id;
        $return = $model->del($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 行业圈分类批量删除
     */
    public function circleCategoryDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            if (in_array(1, $ids) || in_array(2, $ids)) {
                $return['errno'] = 1;
                $return['error'] = "默认分类不能删除";
                $this->ajaxReturn($return);
            }
            $ids = implode(',', $ids);
            $model = D('CircleCategory');
            $condition['id'] = array('in', $ids);
            $tem = $model->del($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }
}