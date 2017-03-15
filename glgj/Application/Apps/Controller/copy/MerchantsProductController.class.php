<?php
namespace Apps\Controller;
use Think\Controller;

class MerchantsProductController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
        $token = I('request.token');
        empty($token) && $this->ajaxJson('70000', '请先登陆');
        /* 根据token获取用户信息 */
        $prefix = C('DB_PREFIX');
        $memberInfo = M('Token')
                    ->alias('l')
                    ->field('r.uid,c.id company_id,c.name company_name,c.logo,c.bgimg')
                    ->join($prefix.'member r on l.id = r.token_id', 'LEFT')
                    ->join($prefix.'company c on r.uid = c.user_id', 'LEFT')
                    ->where(array('l.uuid'=>$token))
                    ->find();
        /* 处理数据 */
        !$memberInfo && $this->ajaxJson('40004');
        $memberInfo['logo'] = $this->getAbsolutePath($memberInfo['logo'], '', C('HTTP_APPS_IMG') . 'company_default.png');
        !$memberInfo['company_id'] && $this->ajaxJson('70000', '您尚未成为企业会员');
        $this->memberInfo = $memberInfo;
    }

    /**
     * 商家商品管理主页(已上架)
     */
    public function index() {
        if ( IS_GET ) {
            /* 分页参数 */
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            /* 类型参数 1已上架；2已售罄；3仓库中 */
            $type = I('get.type', '', 'strval');
            if ( $type ) {
                !in_array($type, array('1','2','3','4')) && $this->ajaxJson('70000', '类型参数错误');
                switch ( $type ) {
                    case '1':
                        /* 已上架 */
                        $where['status'] = array('eq', 1);
                        break;
                    case '2':
                        /* 已售罄 */
                        $where['num']    = array('eq', 0);
                        $where['status'] = array('eq', 1);
                        break;
                    case '3':
                        /* 仓库中 */
                        $where['status'] = array('eq', 0);
                        break;
                    case '4':
                        /* 未参与活动的 */
                        $where['status']        = array('eq', 1);
                        $where['activity_type'] = array('eq', 0);
                        break;
                    default:
                        # code...
                        break;
                }
            }
            /* 排序标识 1销量升序；2销量降序；3人气升序；4人气降序；5售价升序；6售价降序 */
            $flag = I('get.flag', 0, 'intval');
            if ( $flag ) {
                !in_array($flag, array(1,2,3,4,5,6)) && $this->ajaxJson('70000', '排序标识错误');
                $flagList = array('sale_num ASC,flags DESC,sort DESC,id DESC', 'sale_num DESC,flags DESC,sort DESC,id DESC', 'click ASC,flags DESC,sort DESC,id DESC', 'click DESC,flags DESC,sort DESC,id DESC', 'price ASC,flags DESC,sort DESC,id DESC', 'price DESC,flags DESC,sort DESC,id DESC');
                $order = $flagList[$flag-1];
            } else {
                $order = 'flags DESC,sort DESC,id DESC';
            }
            /* 分组id */
            $group_id = I('get.group_id', 0, 'intval');
            $group_id && $where['group_id'] = array('eq', $group_id);
            /* 搜索 */
            $title = I('get.title');
            !empty($title) && $where['title'] = array('like', '%'.$title.'%');

            
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            
            /* 查询已上架商品 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $list['product_list'] = M('ProductSale')->field('id product_id,title,img,price,CONCAT("销量：",sale_num) sale_num,CONCAT("库存：",num) num')->where($where)->order($order)->limit($page,10)->select();
            !$list['product_list'] && $list['product_list'] = array();
            $list['product_list'] = $this->getAbsolutePath($list['product_list'], 'img', C('HTTP_APPS_IMG') . 'product_default.png');
            
            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商品下架(支持批量下架)
     */
    public function discarded() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['status']     = array('eq', 1);
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $save = M('ProductSale')->where($where)->setField('status', 0);
            !$save && $this->ajaxJson('70000', '下架失败');
            $this->ajaxJson('40000', '下架成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商品转移分组(支持批量操作)
     */
    public function moveGroup() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $group_id = I('post.group_id');
            !$group_id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $save = M('ProductSale')->where($where)->setField('group_id', $group_id);
            $save === false && $this->ajaxJson('70000', '转移分组失败');
            $this->ajaxJson('40000', '转移分组成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商品已售罄列表
     */
    // public function soldOut() {
    //     if ( IS_GET ) {
    //         $page = I('get.page', 1, 'intval');
    //         $page = ( $page - 1 ) * 10;
    //         /* 商家信息 */
    //         $memberInfo = $this->memberInfo;
            
    //         /* 查询已售罄商品 */
    //         $where['company_id'] = array('eq', $memberInfo['company_id']);
    //         $where['num']        = array('eq', 0);
    //         $list['product_list'] = M('ProductSale')->field('id product_id,title,img,price,CONCAT("销量：",sale_num) sale_num,CONCAT("库存：",num) num')->where($where)->order('flags DESC,sort DESC,id DESC')->limit($page,10)->select();
            
    //         $this->returnJson($list);
    //     }
    //     $this->ajaxJson('70002');
    // }

    /**
     * 商品添加库存(支持批量操作)
     */
    public function stockAdd() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $num = I('post.num');
            !$num && $this->ajaxJson('70000', '参数错误');
            !preg_match('/^\d+$/', $num) && $this->ajaxJson('70000', '数量格式不正确');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $save = M('ProductSale')->where($where)->setInc('num', $num);
            !$save && $this->ajaxJson('70000', '库存添加失败');
            $this->ajaxJson('40000', '库存添加成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商品仓库中列表
     */
    // public function warehouse() {
    //     if ( IS_GET ) {
    //         $page = I('get.page', 1, 'intval');
    //         $page = ( $page - 1 ) * 10;
    //         /* 商家信息 */
    //         $memberInfo = $this->memberInfo;
            
    //         /* 查询仓库中商品 */
    //         $where['company_id'] = array('eq', $memberInfo['company_id']);
    //         $where['status']     = array('eq', 0);
    //         $list['product_list'] = M('ProductSale')->field('id product_id,title,img,price,CONCAT("销量：",sale_num) sale_num,CONCAT("库存：",num) num')->where($where)->order('flags DESC,sort DESC,id DESC')->limit($page,10)->select();
            
    //         $this->returnJson($list);
    //     }
    //     $this->ajaxJson('70002');
    // }

    /**
     * 商品上架(支持批量上架)
     */
    public function shelves() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['status']     = array('eq', 0);
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $save = M('ProductSale')->where($where)->setField('status', 1);
            !$save && $this->ajaxJson('70000', '上架失败');
            $this->ajaxJson('40000', '上架成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商品分组列表
     */
    public function groupList() {
        if ( IS_GET ) {
            $page = I('get.page', 1, 'intval');
            $page = ( $page - 1 ) * 10;
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            
            /* 查询商品分组 */
            $where['l.company_id'] = array('eq', $memberInfo['company_id']);
            $list['group_list'] = M('ProductGroup')
                                ->alias('l')
                                ->field('l.id group_id,l.title,CONCAT("共有",COUNT(r.id),"件商品") product_num')
                                ->join(C('DB_PREFIX') . 'product_sale r ON l.id = r.group_id', 'LEFT')
                                ->where($where)
                                ->order('l.sort DESC,l.id DESC')
                                ->group('l.id')
                                ->limit($page,10)
                                ->select();
            !$list['group_list'] && $list['group_list'] = array();

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商品分组添加、编辑
     */
    public function groupAdd() {
        $id = I('request.id', 0, 'intval');
        $model = D('ProductGroup');
        $opt = $id > 0 ? '修改' : '添加';
        
        if( IS_POST ) {
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            $result = $model->update($memberInfo['company_id']);
            if ( $result ) {
                $list['group_id'] = $result['id'];
                $list['title']    = $result['title'];
                $this->returnJson($list, '40000', $opt . '成功');
                // $this->ajaxJson('40000', $opt . '成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }

        /* 修改 */
        if ( $id ) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition, 'id group_id,title');
            $list['detail'] = $detail;
        } else {
            $list['detail']['group_id'] = '';
            $list['detail']['title']    = '';
        }

        $this->returnJson($list);
    }

    /**
     * 商品分组删除(支持批量删除)
     */
    public function groupDel() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $where['id']         = array('in', $id);
            $del = M('ProductGroup')->where($where)->delete();
            !$del && $this->ajaxJson('70000', '删除失败');
            $this->ajaxJson('40000', '删除成功');
        }
        $this->ajaxJson('70001');
    }
    
    /**
     * 商家分组下的商品列表
     */
    // public function groupProductList() {
    //     if ( IS_GET ) {
    //         $page = I('get.page', 1, 'intval');
    //         $page = ( $page - 1 ) * 10;
    //         $id   = I('get.id', 0, 'intval');
    //         !$id && $this->ajaxJson('70000', '参数错误');
    //         /* 商家信息 */
    //         $memberInfo = $this->memberInfo;
            
    //         /* 查询仓库中商品 */
    //         $where['company_id'] = array('eq', $memberInfo['company_id']);
    //         $where['group_id']   = array('eq', $id);
    //         $list['product_list'] = M('ProductSale')->field('id product_id,title,img,price,CONCAT("销量：",sale_num) sale_num,CONCAT("库存：",num) num')->where($where)->order('flags DESC,sort DESC,id DESC')->limit($page,10)->select();
            
    //         $this->returnJson($list);
    //     }
    //     $this->ajaxJson('70002');
    // }

    /**
     * 商家商品管理筛选
     */
    public function screening() {
        if ( IS_GET ) {
            /* 商家信息 */
            $memberInfo = $this->memberInfo;
            
            /* 查询商品分组 */
            $where['company_id'] = array('eq', $memberInfo['company_id']);
            $list['group_list'] = M('ProductGroup')->field('id group_id,title')->where($where)->order('sort DESC,id DESC')->select();
            if ( $list['group_list'] ) {
                array_unshift($list['group_list'], array('group_id'=>'0', 'title'=>'全部分组'));
            } else {
                $list['group_list'][] = array('group_id'=>'0', 'title'=>'全部分组');
            }
            
            /* 商品排序 */
            $list['order_list'] = array(
                array( 'flag' => '0', 'title' => '默认排序' ),
                array( 'flag' => '1', 'title' => '销量升序' ),
                array( 'flag' => '2', 'title' => '销量降序' ),
                array( 'flag' => '3', 'title' => '人气升序' ),
                array( 'flag' => '4', 'title' => '人气降序' ),
                array( 'flag' => '5', 'title' => '售价升序' ),
                array( 'flag' => '6', 'title' => '售价降序' ),
            );

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商品评论列表
     */
    public function commentList() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            !$id && $this->ajaxJson('70000', '参数错误');
            $page   = I('get.page', 1, 'intval');
            $page   = ( $page - 1 ) * 10;
            $prefix = C('DB_PREFIX');
            $model  = M('ProductComment');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询商品所有评论列表 */
            $where_all['l.pid']    = array('eq', $id);
            $where_all['l.cid']    = array('eq', 0);
            $where_all['l.status'] = array('gt', 0);
            $commentAllList = $model
                            ->alias('l')
                            ->field('l.id comment_id,l.content,l.praise,l.ctime,r.name,r.head_pic,s.spec_info')
                            ->join($prefix . 'member r ON l.uid = r.uid', 'LEFT')
                            ->join($prefix . 'order_sub s ON l.order_id = s.orderid', 'LEFT')
                            ->where($where_all)
                            ->order('l.id DESC')
                            ->group('l.id')
                            ->limit($page,10)
                            ->select();
            !$commentAllList && $this->returnJson(array('comment_list'=>array()));
            /* 数据处理 */
            foreach ($commentAllList as $key => $value) {
                /* 时间和规格 */
                if ( $value['spec_info'] ) {
                    $commentAllList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']) . ' 商品规格：' . $value['spec_info'];
                } else {
                    $commentAllList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']);
                }
                /* 评论图片 */
                $commentAllList[$key]['img'] = $commentAllList[$key]['img'] ? explode(',', $commentAllList[$key]['img']) : array();
                $commentAllList[$key]['img'] = $this->getAbsolutePath($commentAllList[$key]['img']);
                /* 评论者头像 */
                $commentAllList[$key]['head_pic'] = $this->getAbsolutePath($commentAllList[$key]['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
                // $commentAllList[$key]['child'] = array();
                /* 销毁客户端不需要的数据 */
                unset($commentAllList[$key]['ctime'], $commentAllList[$key]['spec_info']);
                /* 以上级评论id分组 */
                // $commentArray[$value['cid']][$value['comment_id']] = $commentAllList[$key];
            }

            /* 查询商品买家评论列表 */
            // $where['pid']    = array('eq', $id);
            // $where['cid']    = array('eq', 0);
            // $where['status'] = array('gt', 0);
            // $commentList = $model->where($where)->limit($page,10)->getField('id',true);
            // if ( $commentList ) {
            //     /* 数据处理 */
            //     foreach ($commentList as $k => $v) {
            //         $commentList[$k] = $commentArray[0][$v];
            //         /* 买家追评和商家回复 */
            //         $commentArray[$v] && $commentList[$k]['child'] = array_values($commentArray[$v]);
            //     }
            // } else {
            //     $commentList = array();
            // }
            $list['comment_list'] = $commentAllList;

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商品评价详情
     */
    public function commentDetail() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            !$id && $this->ajaxJson('70000', '参数错误');
            $page   = I('get.page', 1, 'intval');
            $page   = ( $page - 1 ) * 10;
            $prefix = C('DB_PREFIX');
            $model  = M('ProductComment');

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            /* 查询商品所有评论列表 */
            $where_all['l.id|l.cid'] = array('eq', $id);
            $where_all['l.status']   = array('gt', 0);
            $commentAllList = $model
                            ->alias('l')
                            ->field('l.id comment_id,l.content,l.praise,l.ctime,l.type,r.name,r.head_pic,s.spec_info')
                            ->join($prefix . 'member r ON l.uid = r.uid', 'LEFT')
                            ->join($prefix . 'order_sub s ON l.order_id = s.orderid', 'LEFT')
                            ->where($where_all)
                            ->order('l.id DESC')
                            ->group('l.id')
                            ->limit($page,10)
                            ->select();
            !$commentAllList && $this->returnJson(array('comment_list'=>array()));
            /* 可回复次数 */
            $list['reply_num'] = 0;
            /* 数据处理 */
            foreach ($commentAllList as $key => $value) {
                /* 时间和规格 */
                if ( $value['spec_info'] ) {
                    $commentAllList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']) . ' 商品规格：' . $value['spec_info'];
                } else {
                    $commentAllList[$key]['time_spec_info'] = $this->dateTimeDeal($value['ctime']);
                }
                /* 评论图片 */
                $commentAllList[$key]['img'] = $commentAllList[$key]['img'] ? explode(',', $commentAllList[$key]['img']) : array();
                $commentAllList[$key]['img'] = $this->getAbsolutePath($commentAllList[$key]['img']);
                /* 评论者头像 */
                $commentAllList[$key]['head_pic'] = $this->getAbsolutePath($commentAllList[$key]['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');
                /* 销毁客户端不需要的数据 */
                unset($commentAllList[$key]['ctime'], $commentAllList[$key]['spec_info']);
                /* 可回复次数处理 */
                switch ( $value['type'] ) {
                    case '1':
                        $list['reply_num']++;
                        break;
                    case '2':
                        $list['reply_num']--;
                        break;
                    case '3':
                        $list['reply_num']++;
                        break;
                    default:
                        # code...
                        break;
                }
            }

            $list['reply_num']    = strval($list['reply_num']);
            $list['comment_list'] = $commentAllList;

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * 商品评价回复
     */
    public function reply() {
        if ( IS_POST ) {
            $model = D('ProductComment');
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', '回复成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 商品参与活动（支持批量操作）
     * @author 406764368@qq.com
     * @version 2016年11月23日 10:09:02
     */
    public function participationActivity() {
        if ( IS_POST ) {
            $id = I('post.id');
            !$id && $this->ajaxJson('70000', '参数错误');
            $id = explode(',', $id);

            /* 商家信息 */
            $memberInfo = $this->memberInfo;

            $where['status']        = array('eq', 1);
            $where['activity_type'] = array('eq', 0);
            $where['company_id']    = array('eq', $memberInfo['company_id']);
            $where['id']            = array('in', $id);
            $save = M('ProductSale')->where($where)->setField('activity_type', 1);
            !$save && $this->ajaxJson('70000', '操作失败');
            $this->ajaxJson('40000', '操作成功');
        }
        $this->ajaxJson('70001');
    }
}