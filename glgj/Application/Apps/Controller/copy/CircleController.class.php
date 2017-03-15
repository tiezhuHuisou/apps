<?php
namespace Apps\Controller;
use Think\Controller;

class CircleController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 行业圈分类
     */
    public function category() {
        if ( IS_GET ) {
            $list = M('CircleCategory')->field('id,title')->where(array('status'=>1))->order('sort DESC,id DESC')->select();
            array_unshift($list, array('id' => '', 'title' => '全部'));
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 行业圈列表
     */
    public function index() {
        if ( IS_GET ) {
            $content = I('get.content');
            $cid     = I('get.cid', 0, 'intval');
            $page    = I('get.page', 1, 'intval');
            $page    = ( $page - 1 ) * 10;
            $token   = I('get.token');
            $user_id = I('get.user_id', 0, 'intval');
            !empty($content) && $where['l.content'] = array('like', '%'.$content.'%');
            $cid > 0 && $where['l.cid'] = array('eq', $cid);
            $where['l.status'] = array('eq', 1);
            $user_id && $where['l.uid'] = array('eq', $user_id);

            /* 查询行业圈数据 */
            $prefix        = C('DB_PREFIX');
            $memberModel   = M('Member');
            $companyModel  = M('Company');
            $imageModel    = M('CirclePicture');
            $praiseModel   = M('CirclePraise');
            $commentModel  = M('CircleComment');
            $tokenModel    = D('Token');
            $favoriteModel = M('UserFavorite');
            $list = M('Circle')
                  ->alias('l')
                  ->field('l.id,l.gid,l.uid,l.content,l.ctime,r.title as cname,l.data_id,l.share_model')
                  ->join($prefix.'circle_category as r ON l.cid = r.id', 'LEFT')
                  ->where($where)
                  ->order('l.sort DESC,l.id DESC')
                  ->limit($page,10)
                  ->select();
            foreach ($list as $key => $value) {
                /* 处理分享信息 */
                $list[$key] = $this->dealShareToCircle($value, $value['share_model'], $value['data_id']);
                /* 处理时间 */
                $list[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                /* 查询发布人信息 */
                if ( $value['gid'] == 1 ) {
                    $issuerInfo = $memberModel->field('name username,mphone mobile,head_pic logo')->where(array('uid'=>$value['uid']))->find();
                    $issuerInfo['logo'] = $this->getAbsolutePath($issuerInfo['logo'], '', C('HTTP_APPS_IMG') . 'member_default.png');
                    $issuerInfo['company_name'] = '';
                } else {
                    $issuerInfo = $companyModel
                                ->alias('l')
                                ->field('l.id company_id,m.name username,r.subphone mobile,r.telephone,l.logo,l.name company_name')
                                ->join($prefix.'company_link as r ON l.id = r.company_id', 'LEFT')
                                ->join($prefix.'member as m ON l.user_id = m.uid', 'LEFT')
                                ->where(array('l.user_id'=>$value['uid']))
                                ->find();
                    $issuerInfo['logo'] = $this->getAbsolutePath($issuerInfo['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
                }
                $list[$key]['logo']         = $issuerInfo['logo'];
                $list[$key]['username']     = $issuerInfo['username'];
                if ( $issuerInfo['telephone'] ) {
                    $list[$key]['mobile']   = array($issuerInfo['mobile'], $issuerInfo['telephone']);
                } else {
                    $list[$key]['mobile']   = $issuerInfo['mobile'] ? array($issuerInfo['mobile']) : array();
                }
                $list[$key]['company_id']   = $issuerInfo['company_id'] ? $issuerInfo['company_id'] : '';
                $list[$key]['company_name'] = $issuerInfo['company_name'];
                unset($issuerInfo);
                /* 查询图片信息 */
                $list[$key]['image'] = $imageModel->where(array('pid'=>$value['id']))->getField('img', true);
                if ( is_array($list[$key]['image']) ) {
                    $list[$key]['image'] = $this->getAbsolutePath($list[$key]['image']);
                } else {
                    $list[$key]['image'] = array();
                }
                /* 查询点赞信息 */
                $list[$key]['praise_count'] = $praiseModel->where(array('pid'=>$value['id']))->count('id');
                $list[$key]['praise_count'] = $this->dealNumber($list[$key]['praise_count']);
                /* 查询评论信息 */
                $list[$key]['comment_count'] = $commentModel->where(array('pid'=>$value['id'], 'status'=>1))->count('id');
                $list[$key]['comment_count'] = $this->dealNumber($list[$key]['comment_count']);
                /* 判断用户是否点赞、收藏 登陆信息过期或未登录-按未登录情况展示 */
                $list[$key]['praise_code']  = '40007';
                $list[$key]['collect_code'] = '40005';
                if ( $token ) {
                    /* 通过token获取用户信息 */
                    $memberInfo = $tokenModel->getMemberInfo($token);
                    if ( $memberInfo ) {
                        /* 判断是否点赞 */
                        $where_praise['uid'] = array('eq', $memberInfo['uid']);
                        $where_praise['pid'] = array('eq', $value['id']);
                        $praiseCount = $praiseModel->where($where_praise)->count('id');
                        $praiseCount > 0 && $list[$key]['praise_code'] = '40008';
                        /* 判断是否收藏 */
                        $where_favorite['uid'] = array('eq', $memberInfo['uid']);
                        $where_favorite['aid'] = array('eq', $value['id']);
                        $where_favorite['favorite_category'] = array('eq', 5);
                        $collectCount = $favoriteModel->where($where_favorite)->count('id');
                        $collectCount > 0 && $list[$key]['collect_code'] = '40006';
                    }
                }
            }
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 行业圈详情
     */
    public function detail() {
        if ( IS_GET ) {
            $id    = I('get.id', 0, 'intval');
            $page  = I('get.page', 1, 'intval');
            $page  = ( $page - 1 ) * 10;
            $token = I('get.token');
            !$id && $this->ajaxJson('70000', '参数错误');
            /* 查询详情信息 */
            $prefix             = C('DB_PREFIX');
            $circleModel        = M('Circle');
            $circleCommentModel = M('CircleComment');
            $circlePraiseModel  = M('CirclePraise');
            $tokenModel         = D('Token');
            $favoriteModel      = M('UserFavorite');
            $list['detail'] = $circleModel
                            ->alias('l')
                            ->field('l.id circle_id,l.uid,l.share_model,l.data_id,m.gid,m.mphone mobile,l.content,l.ctime,r.title as cname,m.name username,m.head_pic,c.id company_id,c.name company_name,cl.subphone,cl.telephone')
                            ->join($prefix.'circle_category as r ON l.cid = r.id', 'LEFT')
                            ->join($prefix.'member as m ON l.uid = m.uid', 'LEFT')
                            ->join($prefix.'company as c ON l.uid = c.user_id', 'LEFT')
                            ->join($prefix.'company_link as cl ON cl.company_id = c.id', 'LEFT')
                            ->where(array('l.id'=>$id))
                            ->find();
            !$list['detail']['circle_id'] && $this->ajaxJson('70000', '行业圈不存在');
            /* 处理数据 */
            $list['detail'] = $this->dealShareToCircle($list['detail'], $list['detail']['share_model'], $list['detail']['data_id']);
            $list['detail']['head_pic'] = $this->getAbsolutePath($list['detail']['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
            $list['detail']['ctime'] = $this->dateTimeDeal($list['detail']['ctime']);
            !$list['detail']['company_id'] && $list['detail']['company_id'] = '';
            !$list['detail']['company_name'] && $list['detail']['company_name'] = '';
            if ( $list['detail']['gid'] == 2 ) {
                if ( $list['detail']['telephone'] ) {
                    $list['detail']['mobile'] = array($list['detail']['subphone'], $list['detail']['telephone']);
                } else {
                    $list['detail']['mobile'] = $list['detail']['subphone'] ? array($list['detail']['subphone']) : array();
                }
            } elseif ( $list['detail']['gid'] == 1 ) {
                $list['detail']['mobile'] = array($list['detail']['mobile']);
            }
            /* 行业圈图片 */
            $list['detail']['image'] = M('CirclePicture')->where(array('pid'=>$id))->getField('img',true);
            if ( $list['detail']['image'] ) {
                $list['detail']['image'] = $this->getAbsolutePath($list['detail']['image']);
            } else {
                $list['detail']['image'] = array();
            }
            /* 查询评论数 */
            $list['detail']['comment_count'] = $circleCommentModel->where(array('pid'=>$id, 'status'=>1))->count();
            $list['detail']['comment_count'] = $this->dealNumber($list['detail']['comment_count']);
            /* 查询点赞数 */
            $list['detail']['praise_count'] = $circlePraiseModel->where(array('pid'=>$id))->count();
            $list['detail']['praise_count'] = $this->dealNumber($list['detail']['praise_count']);
            /* 判断用户是否点赞、收藏、是否能删除 登陆信息过期或未登录-按未登录情况展示 */
            $list['detail']['praise_code']  = '40007';
            $list['detail']['collect_code'] = '40005';
            $list['detail']['delete_code']  = '40009';

            if ( $token ) {
                /* 通过token获取用户信息 */
                $memberInfo = $tokenModel->getMemberInfo($token);
                if ( $memberInfo ) {
                    /* 判断是否点赞 */
                    $where_praise['uid'] = array('eq', $memberInfo['uid']);
                    $where_praise['pid'] = array('eq', $id);
                    $praiseCount = $circlePraiseModel->where($where_praise)->count('id');
                    $praiseCount > 0 && $list['detail']['praise_code'] = '40008';
                    /* 判断是否收藏 */
                    $where_favorite['uid'] = array('eq', $memberInfo['uid']);
                    $where_favorite['aid'] = array('eq', $id);
                    $where_favorite['favorite_category'] = array('eq', 5);
                    $collectCount = $favoriteModel->where($where_favorite)->count('id');
                    $collectCount > 0 && $list['detail']['collect_code'] = '40006';
                    /* 判断是否能删除 */
                    $list['detail']['uid'] == $memberInfo['uid'] && $list['detail']['delete_code'] = '40010';
                }
            }
            unset($list['detail']['uid'], $list['detail']['gid'], $list['detail']['subphone'], $list['detail']['telephone']);
            
            /* 查询评论、回复 被删除的评论下的回复仍然显示 */
            $list['comment'] = $circleCommentModel
                             ->alias('l')
                             ->join($prefix.'member as m ON l.uid = m.uid', 'LEFT')
                             ->join($prefix.'company as c ON l.uid = c.user_id', 'LEFT')
                             ->field('l.id comment_id,l.uid,l.cid,l.content,l.ctime,l.status,m.name username,m.head_pic,c.id company_id,c.name company_name')
                             ->where(array('l.pid'=>$id))
                             ->order('l.id ASC')
                             ->select();
            $list['comment'] = $this->getAbsolutePath($list['comment'], 'head_pic', C('HTTP_APPS_IMG') . 'member_default.png');
            /* 已id为下标的评论、回复 评论、回复用户名为值的 一维数组 */
            foreach ($list['comment'] as $key => $value) {
                $commentList[$value['comment_id']]['username'] = $value['username'];
                $commentList[$value['comment_id']]['company_id'] = $value['company_id'];
            }
            foreach ($list['comment'] as $key => $value) {
                if ( $value['status'] == 1 ) {
                    /* 处理数据 */
                    $list['comment'][$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
                    !$value['company_id'] && $list['comment'][$key]['company_id'] = '';
                    !$value['company_name'] && $list['comment'][$key]['company_name'] = '';
                    /* 判断评论是否能回复、删除 登陆信息过期或未登录-按未登录情况展示 */
                    $list['comment'][$key]['reply_code']  = '40013';
                    $list['comment'][$key]['delete_code']  = '40009';
                    /* 判断是否能删除 */
                    $value['uid'] == $memberInfo['uid'] && $list['comment'][$key]['delete_code'] = '40010';
                    /* 回复对象名称 */
                    $list['comment'][$key]['reply_object']     = '';
                    $list['comment'][$key]['reply_company_id'] = '';
                    /* 判断回复和评论 */
                    if ( $value['cid'] ) {
                        /* 若为回复则获取被回复对象名称 */
                        $list['comment'][$key]['reply_object'] = $commentList[$value['cid']]['username'] ? $commentList[$value['cid']]['username'] : '';
                        $list['comment'][$key]['reply_company_id'] = $commentList[$value['cid']]['company_id'] ? $commentList[$value['cid']]['company_id'] : '';
                    } else {
                        /* 若为评论判断是否能回复 不能回复自己的评论 */
                        $value['uid'] != $memberInfo['uid'] && $list['comment'][$key]['reply_code'] = '40014';
                    }
                    unset($list['comment'][$key]['uid'],$list['comment'][$key]['cid'],$list['comment'][$key]['status']);
                } else {
                    unset($list['comment'][$key]);
                }
            }

            $list['comment'] = array_values($list['comment']);

            /* 点击量 */
            M('Circle')->where(array('id'=>$id))->setInc('click', 1);

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 发布行业圈发布、修改
     */
    public function add() {
        $id    = I('request.id', 0, 'intval');
        $token = I('request.token');
        /* 检测用户登陆状态 */
        empty($token) && $this->ajaxJson('70000', '请先登陆');
        $memberInfo = D('Token')->getMemberInfo($token);
        !$memberInfo && $this->ajaxJson('40004');

        /* 发布、修改 */
        $model = D('Circle');
        if( IS_POST ) {
            $opt = $id > 0 ? '修改' : '发布';
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', $opt.'成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 修改、发布时返回详情数据 */
        if ( $id ) {
            $list['detail'] = $model->getOneInfo(array('id'=>$id), 'id,uid,content,cid selected_id');
            $list['detail']['image'] = M('CirclePicture')->where(array('pid'=>$id))->getField('img',true);
            if ( $list['detail']['image'] ) {
                $list['detail']['image'] = $this->getAbsolutePath($list['detail']['image']);
            } else {
                $list['detail']['image'] = array();
            }
            $list['id']          = $list['detail']['id'];
            $list['content']     = $list['detail']['content'];
            $list['selected_id'] = $list['detail']['selected_id'];
            $list['image']       = $list['detail']['image'];
            unset($list['detail']);
        } else {
            $list['id']          = '';
            $list['content']     = '';
            $list['selected_id'] = '';
            $list['image']       = array();
        }

        /* 分类 */
        $list['category'] = M('CircleCategory')->field('id cid,title,share_model')->where(array('status'=>1))->order('sort DESC,id DESC')->select();

        $this->returnJson($list);
    }

    /**
     * 行业圈删除
     */
    public function del() {
        if ( IS_POST ) {
            $id    = I('post.id');
            $token = I('post.token');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('Circle');
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
     * 行业圈点赞
     */
    public function praiseAdd() {
        if ( IS_POST ) {
            $token = I('post.token');
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('CirclePraise');
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
     * 行业圈取消点赞
     */
    public function praiseDel() {
        if ( IS_POST ) {
            $pid   = I('post.pid');
            $token = I('post.token');
            empty($pid) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            
            $model = D('CirclePraise');
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
     * 行业圈评论、回复
     */
    public function comment() {
        if ( IS_POST ) {
            $model = D('CircleComment');
            $result = $model->update();
            if ( $result ) {
                $opt = $result['cid'] ? '回复' : '评论';
                $this->ajaxJson('40000', $opt.'成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 删除行业圈评论、回复
     */
    public function commentDel() {
        if ( IS_POST ) {
            $id    = I('post.id');
            $token = I('post.token');
            empty($id) && $this->ajaxJson('70000', '参数错误');
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            
            $model = D('CircleComment');
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
     * 行业圈发布历史记录
     */
    public function histroy() {
        if ( IS_GET ) {
            $page    = I('get.page', 1, 'intval');
            $page    = ( $page - 1 ) * 10;
            $token   = I('get.token');
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            $list = M('Circle')
                  ->alias('l')
                  ->join(C('DB_PREFIX').'circle_picture r ON l.id = r.pid', 'LEFT')
                  ->field('l.id circle_id,l.content,l.ctime,count(r.id) total,group_concat(r.img) img,l.share_model,l.data_id')
                  ->where(array('l.uid'=>$memberInfo['uid']))
                  ->order('l.id DESC')
                  ->group('l.id')
                  ->limit($page,10)
                  ->select();
            if ( !$list ) {
                $list['circle'] = array();
                $this->returnJson($list);
            }
            /* 数据处理 */
            $month = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');
            foreach ($list as $key => $value) {
                /* 处理分享信息 */
                $list[$key] = $this->dealShareToCircle($value, $value['share_model'], $value['data_id']);
                /* 处理图片 */
                $list[$key]['img'] = $list[$key]['img'] ? explode(',', $value['img']) : array();
                $list[$key]['img'] = $this->getAbsolutePath($list[$key]['img']);
                foreach ($list[$key]['img'] as $ik => $iv) {
                    if ( $ik > 3 ) {
                        unset($list[$key]['img'][$ik]);
                    }
                }
                /* 处理时间 */
                $list[$key]['month'] = date('n', $value['ctime']) - 1;
                $list[$key]['month'] = $month[$list[$key]['month']];
                $list[$key]['day']   = date('d', $value['ctime']);
                $list[$key]['ctime']   = date('m-d', $value['ctime']);
                $dateList['circle'][$list[$key]['day'].$list[$key]['month']]['day']   = $list[$key]['day'];
                $dateList['circle'][$list[$key]['day'].$list[$key]['month']]['month'] = $list[$key]['month'];
                $dateList['circle'][$list[$key]['day'].$list[$key]['month']]['data'][$key]  = $list[$key];
                array_pop($dateList['circle'][$list[$key]['day'].$list[$key]['month']]['data'][$key]);
                array_pop($dateList['circle'][$list[$key]['day'].$list[$key]['month']]['data'][$key]);
                $dateList['circle'][$list[$key]['day'].$list[$key]['month']]['data'] = array_values($dateList['circle'][$list[$key]['day'].$list[$key]['month']]['data']);
            }
            $dateList['circle'] = array_values($dateList['circle']);

            $this->returnJson($dateList);
        }
        $this->ajaxJson('70002');
    }
}