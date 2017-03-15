<?php
namespace Apps\Controller;
use Think\Controller;

class NewsController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 资讯分类接口
     */
    public function category() {
        if ( IS_GET ) {
            /* 分类 */
            $list = M('Artcategory')->field('cid,cname')->where(array('status'=>1))->order('sort DESC,cid DESC')->select();
            foreach ($list as $key => $value) {
                $list[$key]['flags'] = '';
                $list[$key]['type']  = '';
            }
            /* 属性 */
            $where_flags['status'] = array('eq', 1);
            $where_flags['flags']  = array('neq', '');
            $flagsList = M('NewsFlags')->field('title cname,flags')->where($where_flags)->order('sort DESC,id DESC')->select();
            foreach ($flagsList as $fk => $fv) {
                $flagsList[$fk]['cid']  = '';
                $flagsList[$fk]['type'] = '';
                ksort($flagsList[$fk]);
            }
            $list = array_merge($flagsList,$list);
            /* 固定筛选项 */
            array_unshift($list, array('cid' => '', 'cname' => '最新', 'flags' => '', 'type' => 'addtime'));
            array_unshift($list, array('cid' => '', 'cname' => '热门', 'flags' => '', 'type' => 'click'));
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 资讯列表接口
     */
    public function index() {
        if ( IS_GET ) {
            $title      = I('get.title');
            $categoryid = I('get.categoryid', 0, 'intval');
            $page       = I('get.page', 1, 'intval');
            $page       = ( $page - 1 ) * 10;
            $flags      = I('get.flags');
            $type       = I('get.type');
            $token      = I('get.token');
            $order      = 'sort DESC,id DESC';
            !empty($title) && $where['title'] = array('like', '%'.$title.'%');
            $categoryid > 0 && $where['categoryid'] = array('eq', $categoryid);
            !empty($flags) && $where['flags'] = array('eq', $flags);
            !empty($type) && $order = $type . ' DESC,sort DESC,id DESC';
            $where['is_display'] = array('eq', 1);

            /* 资讯轮播图 */
            $list['carousel'] = M('jdpic')->field('thumbnail,name,url,href_type,href_model,data_id')->where(array('tid'=>1))->order('listorder DESC,id DESC')->select();
            $list['carousel'] = $this->getAbsolutePath($list['carousel'], 'thumbnail');

            /* 查询资讯数据 */
            $prefix        = C('DB_PREFIX');
            $imageModel    = M('NewsPicture');
            $commentModel  = M('NewsComment');
            $tokenModel    = D('Token');
            $favoriteModel = M('UserFavorite');
            $list['newslist'] = M('Article')->field('id,title,short_title,addtime')->where($where)->order($order)->limit($page,10)->select();
            foreach ($list['newslist'] as $key => $value) {
                /* 处理时间 */
                $list['newslist'][$key]['addtime'] =  $this->dateTimeDeal($value['addtime']);
                /* 查询图片信息 */
                $list['newslist'][$key]['image'] = $imageModel->where(array('pid'=>$value['id']))->getField('img', true);
                if ( is_array($list['newslist'][$key]['image']) ) {
                    $list['newslist'][$key]['image'] = $this->getAbsolutePath($list['newslist'][$key]['image']);
                } else {
                    $list['newslist'][$key]['image'] = array();
                }
                /* 统计收藏数 */
                $list['newslist'][$key]['collect_count'] = $favoriteModel->where(array('aid'=>$value['id'], 'favorite_category'=>1))->count('id');
                /* 统计评论数 */
                $list['newslist'][$key]['comment_count'] = $commentModel->where(array('pid'=>$value['id']))->count('id');
                /* 判断用户是否收藏 登陆信息过期或未登录-按未登录情况展示 */
                $list['newslist'][$key]['collect_code'] = '40005';
                if ( $token ) {
                    /* 通过token获取用户信息 */
                    $memberInfo = $tokenModel->getMemberInfo($token);
                    if ( $memberInfo ) {
                        /* 判断是否收藏 */
                        $where_favorite['uid'] = array('eq', $memberInfo['uid']);
                        $where_favorite['aid'] = array('eq', $value['id']);
                        $where_favorite['favorite_category'] = array('eq', 1);
                        $collectCount = $favoriteModel->where($where_favorite)->count('id');
                        $collectCount > 0 && $list['newslist'][$key]['collect_code'] = '40006';
                    }
                }
            }
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 资讯详情
     */
    public function detail() {
        if ( IS_GET ) {
            /* 接收参数 */
            $id    = I('get.id', 0, 'intval');
            $token = I('get.token');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 定义数据库相关信息 */
            $prefix              = C('DB_PREFIX');
            $commentModel        = M('NewsComment');
            $replyModel          = M('NewsReply');
            $commentPraiseModel  = M('NewsCommentPraise');
            $tokenModel          = D('Token');
            $favoriteModel       = M('UserFavorite');

            /* 资讯详情 */
            $where_news['id']         = array('eq', $id);
            $where_news['is_display'] = array('eq', 1);
            $list['news'] = M('Article')->field('id news_id,title,content,image img')->where($where_news)->find();
            !$list['news'] && $this->ajaxJson('70000', '该资讯不存在');
            /* 资讯详情里的所有图片地址 */
            preg_match_all("/<img([^>]*)\s*src=('|\")([^'\"]+)('|\")/", $list['news']['content'], $content_img);
            $list['news']['content_img']   = $content_img[3];
            $list['news']['content_img']   = $this->getAbsolutePath($list['news']['content_img']);
            /* 资讯评论数 */
            $list['news']['comment_count'] = $commentModel->where(array('pid'=>$list['news']['news_id']))->count('id');
            /* 资讯详情内容web地址 */
            $list['news']['weburl']        = C('HTTP_ORIGIN') . '?g=app&m=apps&a=news_detail&appsign=1&id=' . $list['news']['news_id'];
            /* 分享链接 */
            $list['news']['shareurl']      = C('HTTP_ORIGIN') . '?g=app&m=apps&a=news_detail&id=' . $list['news']['news_id'];
            /* 分享内容 */
            $list['news']['content']       = $this->destroySpace(strip_tags($list['news']['content']));
            /* 分享图片 */
            $list['news']['img']           = $this->getAbsolutePath($list['news']['img'], '', C('HTTP_APPS_IMG') . 'news_default.png');
            /* 判断用户是否收藏 登陆信息过期或未登录-按未登录情况展示 */
            $list['news']['collect_code']  = '40005';
            if ( $token ) {
                /* 通过token获取用户信息 */
                $memberInfo = $tokenModel->getMemberInfo($token);
                if ( $memberInfo ) {
                    /* 判断是否收藏 */
                    $where_news_favorite['uid'] = array('eq', $memberInfo['uid']);
                    $where_news_favorite['aid'] = array('eq', $list['news']['news_id']);
                    $where_news_favorite['favorite_category'] = array('eq', 1);
                    $newsCollectCount = $favoriteModel->where($where_news_favorite)->count('id');
                    $newsCollectCount > 0 && $list['news']['collect_code'] = '40006';
                }
            }

            /* 评论信息 */
            $list['comment'] = $commentModel
                             ->alias('l')
                             ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                             ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                             ->field('l.id comment_id,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                             ->where(array('l.pid'=>$list['news']['news_id']))
                             ->order('ctime DESC')
                             ->limit($page, 10)
                             ->select();
            foreach ($list['comment'] as $key => $value) {
                !$value['company_name'] && $list['comment'][$key]['company_name'] = '';
                !$value['company_id'] && $list['comment'][$key]['company_id'] = '';
                $list['comment'][$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                $list['comment'][$key]['praise_count'] = $commentPraiseModel->where(array('pid'=>$value['comment_id']))->count('id');
                $list['comment'][$key]['reply_count']  = $replyModel->where(array('pid'=>$value['comment_id']))->count('id');
                /* 判断用户是否为该评论点赞，是否属于自己的评论 登陆信息过期或未登录-按未登录情况展示 */
                $list['comment'][$key]['praise_code'] = '40007';
                $list['comment'][$key]['delect_code'] = '40009';
                if ( $memberInfo ) {
                    /* 判断是否点赞 */
                    $where_comment_praise['uid'] = array('eq', $memberInfo['uid']);
                    $where_comment_praise['pid'] = array('eq', $value['comment_id']);
                    $commentPraiseCount = $commentPraiseModel->where($where_comment_praise)->count('id');
                    $commentPraiseCount > 0 && $list['comment'][$key]['praise_code'] = '40008';
                    /* 判断是否属于自己的评论 */
                    $memberInfo['uid'] == $value['uid'] && $list['comment'][$key]['delect_code'] = '40010';
                }
                unset($list['comment'][$key]['uid']);
                /* 回复 */
                $list['comment'][$key]['reply'] = $replyModel
                                                ->alias('l')
                                                ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                                                ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                                                ->field('l.id reply_id,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                                                ->where(array('l.pid'=>$value['comment_id']))
                                                ->order('ctime DESC')
                                                ->limit(3)
                                                ->select();
                foreach ($list['comment'][$key]['reply'] as $k => $v) {
                    !$v['company_name'] && $list['comment'][$key]['reply'][$k]['company_name'] = '';
                    !$v['company_id'] && $list['comment'][$key]['reply'][$k]['company_id'] = '';
                    $list['comment'][$key]['reply'][$k]['ctime'] = $this->dateTimeDeal($list['comment'][$key]['reply'][$k]['ctime']);
                    /* 判断是否属于自己的回复 登陆信息过期或未登录-按未登录情况展示 */
                    $list['comment'][$key]['reply'][$k]['delect_code'] = '40009';
                    /* 判断是否属于自己的回复 */
                    if ( $memberInfo && $memberInfo['uid'] == $v['uid'] ) {
                        $list['comment'][$key]['reply'][$k]['delect_code'] = '40010';
                    }
                    unset($list['comment'][$key]['reply'][$k]['uid']);
                }
                $list['comment'][$key]['reply'] = $this->getAbsolutePath($list['comment'][$key]['reply'], 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            }
            $list['comment'] = $this->getAbsolutePath($list['comment'], 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');

            /* 点击量 */
            M('Article')->where(array('id'=>$id))->setInc('click', 1);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 资讯评论
     */
    public function comment() {
        if ( IS_POST ) {
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('NewsComment');
            $result = $model->update();
            if ( $result ) {
                /* 查询评论信息 */
                $prefix = C('DB_PREFIX');
                $info = M('NewsComment')
                      ->alias('l')
                      ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                      ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                      ->field('l.id comment_id,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                      ->where(array('l.id'=>$result['id']))
                      ->find();
                !$info['company_name'] && $info['company_name'] = '';
                !$info['company_id'] && $info['company_id'] = '';
                $info['ctime'] = $this->dateTimeDeal($info['ctime']);
                $info['praise_count'] = '0';
                $info['reply_count']  = '0';
                $info['praise_code']  = '40007';
                $info['delect_code']  = '40010';
                $info['head_pic']     = $this->getAbsolutePath($info['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
                $info['reply']        = array();
                $list['comment'][]    = $info;
                $this->returnJson($list, '40000', '评论成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯评论详情
     */
    public function commentDetail() {
        if ( IS_GET ) {
            $id    = I('get.id', 0, 'intval');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            $token = I('get.token');
            !$id && $this->ajaxJson('70000', '参数错误');

            /* 定义数据库相关信息 */
            $prefix              = C('DB_PREFIX');
            $newsModel           = M('Article');
            $newsPictureModel    = M('NewsPicture');
            $commentModel        = M('NewsComment');
            $replyModel          = M('NewsReply');
            $commentPraiseModel  = M('NewsCommentPraise');
            $tokenModel          = D('Token');
            $favoriteModel       = M('UserFavorite');


            /* 查询评论详情信息 */
            $list['comment'] = $commentModel
                             ->alias('l')
                             ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                             ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                             ->field('l.id comment_id,l.pid,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                             ->where(array('l.id'=>$id))
                             ->order('ctime DESC')
                             ->find();
            !$list['comment']['comment_id'] && $this->ajaxJson('70000', '评论不存在或已删除');
            /* 处理数据 */
            !$list['comment']['company_name'] && $list['comment']['company_name'] = '';
            !$list['comment']['company_id'] && $list['comment']['company_id'] = '';
            $list['comment']['head_pic']     = $this->getAbsolutePath($list['comment']['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
            $list['comment']['ctime']        = $this->dateTimeDeal($list['comment']['ctime']);
            $list['comment']['praise_count'] = $commentPraiseModel->where(array('pid'=>$list['comment']['comment_id']))->count('id');
            $list['comment']['praise_count'] = $this->dealNumber($list['comment']['praise_count']);
            $list['comment']['reply_count']  = $replyModel->where(array('pid'=>$list['comment']['comment_id']))->count('id');
            $list['comment']['reply_count']  = $this->dealNumber($list['comment']['reply_count']);
            /* 判断用户是否为该评论点赞，是否属于自己的评论 登陆信息过期或未登录-按未登录情况展示 */
            $list['comment']['praise_code'] = '40007';
            $list['comment']['delect_code'] = '40009';
            if ( $token ) {
                /* 通过token获取用户信息 */
                $memberInfo = $tokenModel->getMemberInfo($token);
                if ( $memberInfo ) {
                    /* 判断是否点赞 */
                    $where_comment_praise['uid'] = array('eq', $memberInfo['uid']);
                    $where_comment_praise['pid'] = array('eq', $list['comment']['comment_id']);
                    $commentPraiseCount = $commentPraiseModel->where($where_comment_praise)->count('id');
                    $commentPraiseCount > 0 && $list['comment']['praise_code'] = '40008';
                    /* 判断是否属于自己的评论 */
                    $memberInfo['uid'] == $list['comment']['uid'] && $list['comment']['delect_code'] = '40010';
                }
            }
            unset($list['comment']['uid']);

            /* 查询该评论下的回复 */
            $list['comment']['reply'] = $replyModel
                           ->alias('l')
                           ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                           ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                           ->field('l.id reply_id,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                           ->where(array('l.pid'=>$id))
                           ->order('ctime DESC')
                           ->limit($page,10)
                           ->select();
            if ( $list['comment']['reply'] ) {
                foreach ($list['comment']['reply'] as $k => $v) {
                    !$v['company_name'] && $list['comment']['reply'][$k]['company_name'] = '';
                    !$v['company_id'] && $list['comment']['reply'][$k]['company_id'] = '';
                    $list['comment']['reply'][$k]['ctime'] = $this->dateTimeDeal($list['comment']['reply'][$k]['ctime']);
                    /* 判断是否属于自己的回复 登陆信息过期或未登录-按未登录情况展示 */
                    $list['comment']['reply'][$k]['delect_code'] = '40009';
                    /* 判断是否属于自己的回复 */
                    if ( $memberInfo && $memberInfo['uid'] == $v['uid'] ) {
                        $list['comment']['reply'][$k]['delect_code'] = '40010';
                    }
                    unset($list['comment']['reply'][$k]['uid']);
                }
                $list['comment']['reply'] = $this->getAbsolutePath($list['comment']['reply'], 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            } else {
                $list['comment']['reply'] = array();
            }

            /* 查询来源资讯信息 */
            $list['news']            = $newsModel->field('id news_id,image,title,short_title,addtime')->where(array('id'=>$list['comment']['pid']))->find();
            /* 数据处理 */
            $list['news']['image']   = $this->getAbsolutePath($list['news']['image'], '', C('HTTP_APPS_IMG') . 'news_default.png');
            $list['news']['addtime'] = $this->dateTimeDeal($list['news']['addtime']);
            /* 资讯评论数 */
            $list['news']['comment_count'] = $commentModel->where(array('pid'=>$list['news']['news_id']))->count('id');
            $list['news']['comment_count'] = $this->dealNumber($list['news']['comment_count']);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 删除资讯评论
     */
    public function commentDel() {
        if ( IS_POST ) {
            $id = I('post.id');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model      = D('NewsComment');
            $replyModel = M('NewsReply');
            /* 删除评论下的所有回复 */
            $replyIds = $replyModel->where(array('pid'=>$id))->getField('id', true);
            if ( $replyIds ) {
                $map['id'] = array('in', $replyIds);
                $replyModel->where($map)->delete();
            }
            /* 删除评论 */
            $condition['id'] = array('eq', $id);
            $return = $model->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '删除成功');
            } else {
                $this->ajaxJson('70000', '删除失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 回复资讯评论
     */
    public function reply() {
        if ( IS_POST ) {
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            
            $model = D('NewsReply');
            $result = $model->update();
            if ( $result ) {
                /* 查询回复信息 */
                $prefix = C('DB_PREFIX');
                $info = M('NewsReply')
                      ->alias('l')
                      ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                      ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                      ->field('l.id reply_id,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                      ->where(array('l.id'=>$result['id']))
                      ->find();
                $info['head_pic'] = $this->getAbsolutePath($info['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
                !$info['company_id'] && $info['company_id'] = '';
                !$info['company_name'] && $info['company_name'] = '';
                $info['ctime'] = $this->dateTimeDeal($info['ctime']);
                $info['delect_code'] = '40010';
                $list['reply'][]  = $info;

                $this->returnJson($list, '40000', '回复成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 删除资讯评论的回复
     */
    public function replyDel() {
        if ( IS_POST ) {
            $id = I('post.id');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('NewsReply');
            $condition['id'] = array('eq', $id);
            $return = $model->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '删除成功');
            } else {
                $this->ajaxJson('70000', '删除失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯点赞
     */
    public function praiseAdd() {
        if ( IS_POST ) {
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('NewsPraise');
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', '点赞成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯取消点赞
     */
    public function praiseDel() {
        if ( IS_POST ) {
            $pid = I('post.pid');
            empty($pid) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('NewsPraise');
            $condition['pid'] = array('eq', $pid);
            $return = $model->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '取消点赞成功');
            } else {
                $this->ajaxJson('70000', '取消点赞失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯评论点赞
     */
    public function commentPraiseAdd() {
        if ( IS_POST ) {
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('NewsCommentPraise');
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', '点赞成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 资讯评论取消点赞
     */
    public function commentPraiseDel() {
        if ( IS_POST ) {
            $pid = I('post.pid');
            empty($pid) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            $token = I('post.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            
            $model = D('NewsCommentPraise');
            $condition['pid'] = array('eq', $pid);
            $return = $model->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '取消点赞成功');
            } else {
                $this->ajaxJson('70000', '取消点赞失败');
            }
        }
        $this->ajaxJson('70001');
    }
}