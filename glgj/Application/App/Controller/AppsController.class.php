<?php
namespace App\Controller;
use Think\Controller;
class AppsController extends Controller {
    /**
     * 架构函数
     */
    public function _initialize() {
        $this->assign('download_url', 'http://dl2.unionapp.org/?code=' . C('DB_USER'));
    }

    /**
     * 资讯详情页
     */
    public function news_detail() {
        /* 接收参数 */
        $id    = I('request.id', 0, 'intval');
        $num   = I('request.num', 0, 'intval');
        !$id && $this->error('参数错误');

        /* 定义数据库相关信息 */
        $prefix              = C('DB_PREFIX');
        $commentModel        = M('NewsComment');
        $replyModel          = M('NewsReply');
        $commentPraiseModel  = M('NewsCommentPraise');

        /* 资讯详情 */
        $where_news['id']         = array('eq', $id);
        $where_news['is_display'] = array('eq', 1);
        $detail = M('Article')->field('id news_id,title,content,image,addtime,source')->where($where_news)->find();
        !$detail && $this->error('该资讯不存在');
        /* 资讯评论数 */
        $detail['comment_count'] = $commentModel->where(array('pid'=>$detail['news_id']))->count('id');
        /* 时间处理 */
        $detail['addtime'] = $this->dateTimeDeal($detail['addtime']);

        /* 评论信息 */
        $comment = $commentModel
                 ->alias('l')
                 ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                 ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                 ->field('l.id comment_id,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                 ->where(array('l.pid'=>$detail['news_id']))
                 ->order('ctime DESC')
                 ->limit($num, 10)
                 ->select();
        foreach ($comment as $key => $value) {
            !$comment[$key]['head_pic'] && $comment[$key]['head_pic'] = C('HTTP_ORIGIN') . '/Public/Apps/images/member_head_pic.png';
            $comment[$key]['ctime'] = $this->dateTimeDeal($value['ctime']);
            $comment[$key]['praise_count'] = $commentPraiseModel->where(array('pid'=>$value['comment_id']))->count('id');
            $comment[$key]['reply_count']  = $replyModel->where(array('pid'=>$value['comment_id']))->count('id');
            /* 回复 */
            $comment[$key]['reply'] = $replyModel
                                    ->alias('l')
                                    ->join($prefix.'member r ON l.uid = r.uid', 'LEFT')
                                    ->join($prefix.'company c ON c.user_id = r.uid', 'LEFT')
                                    ->field('l.id reply_id,l.uid,l.content,l.ctime,r.name username,r.head_pic,c.id company_id,c.name company_name')
                                    ->where(array('l.pid'=>$value['comment_id']))
                                    ->order('ctime DESC')
                                    ->limit(3)
                                    ->select();
            foreach ($comment[$key]['reply'] as $k => $v) {
                /* 默认头像 */
                !$comment[$key]['reply'][$k]['head_pic'] && $comment[$key]['reply'][$k]['head_pic'] = C('HTTP_ORIGIN') . '/Public/Apps/images/member_head_pic.png';
                /* 时间处理 */
                $comment[$key]['reply'][$k]['ctime'] = $this->dateTimeDeal($v['ctime']);
            }
        }
        
        if ( $num > 0 ) {
            $this->ajaxReturn($comment);
        }
        $this->assign('detail', $detail);
        $this->assign('comment', $comment);

        /* 页面基本设置 */
        $this->site_title       = $detail['title'];
        $this->site_keywords    = $detail['title'];
        $this->site_description = $detail['title'];
    
        $this->display();
    }

    /**
     * 产品详情页
     */
    public function product_detail() {
        /* 接收参数 */
        $id    = I('get.id', 0, 'intval');
        $num  = I('get.num', 0, 'intval');
        !$id && $this->error('参数错误');

        /* 查询产品轮播图 */
        $carousel = M('ProdcutPicture')->where(array('product_id'=>$id))->order('id ASC')->getField('pic_url', true);

        /* 查询产品详情和企业信息 */
        $detail = M('ProductSale')
                ->alias('l')
                ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
                ->field('l.id product_id,l.title,l.short_title,l.price,l.summary,r.id company_id,r.name company_name,r.business,r.logo')
                ->where(array('l.id'=>$id, 'l.status'=>1))
                ->find();
        !$detail['product_id'] && $this->error('产品不存在或已被下架');
        $detail['price'] = explode(',', $detail['price']);
        $detail['price'] = array_unique($detail['price']);
        sort($detail['price']);
        foreach ($detail['price'] as $pk => $pv) {
            $detail['price'][$pk] = '￥' . sprintf('%.2f', $pv)  . '元';
            if ( $pv == 0 ) {
                $detail['price'] = '保密';
                break;
            }
        }
        is_array($detail['price']) && $detail['price'] = implode(' - ', $detail['price']);

        /* 为您推荐 */
        $where_recommend['status']     = array('eq', 1);
        $where_recommend['company_id'] = array('eq', $detail['company_id']);
        $where_recommend['id']         = array('neq', $detail['product_id']);
        $recommend = M('ProductSale')->field('id product_id,title,short_title,price,img')->where($where_recommend)->order('flags DESC,sort DESC,modify_time DESC')->limit($num,10)->select();
        foreach ($recommend as $key => $value) {
            $recommend[$key]['price'] = explode(',', $value['price']);
            $recommend[$key]['price'] = array_unique($recommend[$key]['price']);
            sort($recommend[$key]['price']);
            foreach ($recommend[$key]['price'] as $pk => $pv) {
                if ( $pv == 0 ) {
                    $recommend[$key]['price'] = '保密';
                    break;
                } else {
                    $recommend[$key]['price'][$pk] = '￥' . sprintf('%.2f', $pv)  . '元';
                }
            }
            is_array($recommend[$key]['price']) && $recommend[$key]['price'] = implode(' - ', $recommend[$key]['price']);
        }

        if ( $num > 0 ) {
            $this->ajaxReturn($recommend);
        }
        $this->assign('carousel', $carousel);
        $this->assign('detail', $detail);
        $this->assign('recommend', $recommend);

        /* 页面基本设置 */
        $this->site_title       = $detail['title'];
        $this->site_keywords    = $detail['title'];
        $this->site_description = $detail['title'];
    
        $this->display();
    }

    /**
     * 求购详情页
     */
    public function need_detail() {
        /* 接收参数 */
        $id    = I('get.id', 0, 'intval');
        $num  = I('get.num', 0, 'intval');
        !$id && $this->error('参数错误');

        /* 查询产品轮播图 */
        $carousel = M('NeedPicture')->where(array('need_id'=>$id))->order('id ASC')->getField('pic_url', true);

        /* 查询产品详情和企业信息 */
        $detail = M('ProductBuy')
                ->alias('l')
                ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
                ->field('l.id need_id,l.title,l.short_title,l.price,l.days,l.num,l.summary,l.issue_time,r.id company_id,r.name company_name,r.business,r.logo')
                ->where(array('l.id'=>$id, 'l.status'=>1))
                ->find();
        !$detail['need_id'] && $this->error('求购不存在或已被下架');
        if ( $detail['days'] ) {
            $limitTime = $detail['issue_time'] + $detail['days'] * 86400;
            $detail['days'] = date('Y-m-d H:i:s', $limitTime);
            $limitTime < time() && $detail['days'] .= '(已过期)';
        } else {
            $detail['days'] = '长期有效';
        }
        $detail['price'] = explode(',', $detail['price']);
        $detail['price'] = array_unique($detail['price']);
        sort($detail['price']);
        foreach ($detail['price'] as $pk => $pv) {
            if ( $pv == 0 ) {
                $recommend[$key]['price'] = '保密';
                break;
            } else {
                $recommend[$key]['price'][$pk] = '￥' . sprintf('%.2f', $pv)  . '元';
            }
        }
        is_array($detail['price']) && $detail['price'] = implode(' - ', $detail['price']);

        /* 为您推荐 */
        $where_recommend['status']     = array('eq', 1);
        $where_recommend['company_id'] = array('eq', $detail['company_id']);
        $where_recommend['id']         = array('neq', $detail['need_id']);
        $recommend = M('ProductBuy')->field('id need_id,title,short_title,price,img')->where($where_recommend)->order('flags DESC,sort DESC,modify_time DESC')->limit($num,10)->select();
        foreach ($recommend as $key => $value) {
            $recommend[$key]['price'] = explode(',', $value['price']);
            $recommend[$key]['price'] = array_unique($recommend[$key]['price']);
            sort($recommend[$key]['price']);
            foreach ($recommend[$key]['price'] as $pk => $pv) {
                if ( $pv == 0 ) {
                    $recommend[$key]['price'] = '保密';
                    break;
                } else {
                    $recommend[$key]['price'][$pk] = '￥' . sprintf('%.2f', $pv)  . '元';
                }
            }
            is_array($recommend[$key]['price']) && $recommend[$key]['price'] = implode(' - ', $recommend[$key]['price']);
        }

        if ( $num > 0 ) {
            $this->ajaxReturn($recommend);
        }
        $this->assign('carousel', $carousel);
        $this->assign('detail', $detail);
        $this->assign('recommend', $recommend);

        /* 页面基本设置 */
        $this->site_title       = $detail['title'];
        $this->site_keywords    = $detail['title'];
        $this->site_description = $detail['title'];
    
        $this->display();
    }

    /**
     * 企业主页
     */
    public function company_home() {
        /* 接收参数 */
        $id = I('get.id', 0, 'intval');
        !$id && $this->error('参数错误');

        /* 查询企业信息 */
        $where['status'] = array('eq', 1);
        $where['id']     = array('eq', $id);
        $detail = M('Company')->field('id company_id,name,logo,bgimg,summary')->where($where)->find();
        !$detail && $this->error('企业不存在');

        /* 查询企业所有产品 */
        $map['status']     = array('eq', 1);
        $map['company_id'] = array('eq', $id);
        $productList = M('ProductSale')->field('id product_id,title,img')->where($map)->order('flags DESC,sort DESC,id DESC')->select();

        /* 查询企业联系方式 */
        $contact = M('CompanyLink')->field('address,subphone')->where(array('company_id'=>$detail['company_id']))->find();

        $this->assign('productList', $productList);
        $this->assign('detail', $detail);
        $this->assign('contact', $contact);

        /* 页面基本设置 */
        $this->site_title       = $detail['name'];
        $this->site_keywords    = $detail['name'];
        $this->site_description = $detail['name'];
    
        $this->display();
    }
    
    /**
     * 注册协议
     * @return [type] [description]
     */
    public function register_agreement() {
        $map['linkid'] = array('eq','link-explain');
        $info = M('Page')->where($map)->find();
        $info['content'] = stripslashes($info['content']);
        $count = preg_match_all('/\{\#([\w]+)\#\}/isU', $info['content'], $match);
        $content = $info['content'];
        if($count>0) {
            $where_sql = implode(',', $match[1]);
            $where['name'] = array('in',$where_sql);
            $sql    = M('Conf')->where($where)->select();
            foreach ($sql as $key=>$val){
                $content = preg_replace('/\{\#'.$val['name'].'\#\}/isU',$val['value'], $content);
            }
        }
        $content = str_replace('&gt;','>',$content);
        $content = str_replace('&lt;','<',$content);
        $content = str_replace('&amp;','&',$content);
        $info['content'] = $content;
        $this->assign('info',$content);
        /* 页面基本设置 */
        $this->site_title       = '服务协议';
        $this->site_keywords    = '服务协议';
        $this->site_description = '服务协议';
    
        $this->display();
    }

    /**
     * 关于我们
     */
    public function about() {
        $map['linkid'] = array('eq','link-aboutus');
        $info = M('Page')->where($map)->find();
        $info['content'] = stripslashes($info['content']);
        $count = preg_match_all('/\{\#([\w]+)\#\}/isU', $info['content'], $match);
        $content = $info['content'];
        if($count>0) {
            $where_sql = implode(',', $match[1]);
            $where['name'] = array('in',$where_sql);
            $sql    = M('Conf')->where($where)->select();
            foreach ($sql as $key=>$val){
                $content = preg_replace('/\{\#'.$val['name'].'\#\}/isU',$val['value'], $content);
            }
        }
        $content = str_replace('&gt;','>',$content);
        $content = str_replace('&lt;','<',$content);
        $content = str_replace('&amp;','&',$content);
        $info['content'] = $content;
        $this->assign('info',$content);
        /* 页面基本设置 */
        $this->site_title       = '关于我们';
        $this->site_keywords    = '关于我们';
        $this->site_description = '关于我们';

        $this->display();
    }

    /**
     * PC后台登陆
     * @author 406764368@qq.com
     * @version 2016年12月11日 16:48:37
     */
    public function instruction() {
        /* 页面基本设置 */
        $this->site_title       = 'PC后台登陆';
        $this->site_keywords    = 'PC后台登陆';
        $this->site_description = 'PC后台登陆';

        $this->display();
    }

    /**
     * 我的邀请码
     * @author 406764368@qq.com
     * @version 2016年11月28日 11:53:53
     */
    public function inviteCode() {
        $appsign = I('get.appsign', 0, 'intval');
        $uuid    = I('get.uuid');
        $uid     = I('get.uid', 0, 'intval');
        $share   = I('get.share', 0, 'intval');
        if ( !$share && !$uuid ) {
            $this->error('参数错误');
        }
        if ( $share && !$uid ) {
            $this->error('参数错误');
        }

        /* 查询邀请码 */
        if ( $share == 1 ) {
            $where['uid']    = array('eq', $uid);
            $where['status'] = array('eq', 1);
            $memberInfo = M('Member')->field('uid,invite_code')->where($where)->find();
            /* 查询邀请码填写规则 */
            $map['linkid'] = array('eq', 'invite_fill_rule');
            $inviteRule = M('Page')->where($map)->getField('content');
            $inviteRule = stripslashes($inviteRule);
            $count = preg_match_all('/\{\#([\w]+)\#\}/isU', $inviteRule, $match);
            $content = $inviteRule;
            if( $count>0 ) {
                $where_sql     = implode(",", $match[1]);
                $where['name'] = array('in',$where_sql);
                $sql           = M('Conf')->where($where)->select();
                foreach ($sql as $key=>$val){
                    $inviteRule = preg_replace('/\{\#'.$val['name'].'\#\}/isU',$val['value'], $inviteRule);
                }
            }
            $inviteRule = str_replace('&gt;','>',$inviteRule);
            $inviteRule = str_replace('&lt;','<',$inviteRule);
            $inviteRule = str_replace('&amp;','&',$inviteRule);
            $this->assign('rule',$inviteRule);
        } else {
            $where['l.uuid']   = array('eq', $uuid);
            $where['r.status'] = array('eq', 1);
            $memberInfo = M('Token')
                        ->alias('l')
                        ->field('r.uid,r.invite_code')
                        ->join(C('DB_PREFIX').'member r ON l.id = r.token_id', 'LEFT')
                        ->where($where)
                        ->find();
            /* 查询下级个数 */
            $inviteCount = M('Member')->where(array('pid'=>$memberInfo['uid']))->count('uid');
            $this->assign('inviteCount', $inviteCount);
            /* 查询邀请码分享规则 */
            $map['linkid'] = array('eq', 'invite_code_rule');
            $inviteRule = M('Page')->where($map)->getField('content');
            $inviteRule = stripslashes($inviteRule);
            $count = preg_match_all('/\{\#([\w]+)\#\}/isU', $inviteRule, $match);
            $content = $inviteRule;
            if( $count>0 ) {
                $where_sql     = implode(",", $match[1]);
                $where['name'] = array('in',$where_sql);
                $sql           = M('Conf')->where($where)->select();
                foreach ($sql as $key=>$val){
                    $inviteRule = preg_replace('/\{\#'.$val['name'].'\#\}/isU',$val['value'], $inviteRule);
                }
            }
            $inviteRule = str_replace('&gt;','>',$inviteRule);
            $inviteRule = str_replace('&lt;','<',$inviteRule);
            $inviteRule = str_replace('&amp;','&',$inviteRule);
            $this->assign('rule',$inviteRule);
        }
        $this->assign('inviteCode', $memberInfo['invite_code']);
        !$memberInfo && $this->error('参数异常');

        /* 页面基本设置 */
        $this->site_title       = $share ? '好友邀请' : '我的邀请码';
        $this->site_keywords    = $share ? '好友邀请' : '我的邀请码';
        $this->site_description = $share ? '好友邀请' : '我的邀请码';

        $this->display();
    }

    /**
     * 我的邀请记录
     * @author 406764368@qq.com
     * @version 2016年12月2日 16:04:36
     */
    public function inviteRecord() {
        $appsign = I('get.appsign', 0, 'intval');
        $uuid    = I('get.uuid');
        !$uuid && $this->error('参数错误');

        /* 查询邀请码 */
        $where['l.uuid']   = array('eq', $uuid);
        $where['r.status'] = array('eq', 1);
        $uid = M('Token')
             ->alias('l')
             ->join(C('DB_PREFIX').'member r ON l.id = r.token_id', 'LEFT')
             ->where($where)
             ->getField('r.uid');
        !$uid && $this->error('参数异常');
        /* 查询下级个数 */
        $list = M('Member')->field('name,head_pic')->where(array('pid'=>$uid))->order('uid DESC')->select();
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title       = '我的邀请记录';
        $this->site_keywords    = '我的邀请记录';
        $this->site_description = '我的邀请记录';

        $this->display();
    }

    /**
     * 邀请好友
     * @author 406764368@qq.com
     * @version 2016年12月2日 16:04:32
     */
    public function inviteFriend() {
        $this->error('请在APP中打开');
    }

    /**
     * 按规则处理时间 unix时间戳转字符串
     * 规则：
     * 1、一分钟之内显示：刚刚
     * 2、一小时之内显示：具体多少分钟前，例：23分钟前
     * 3、一天之内显示：具体多少小时前，例：12小时前
     * 4、超过1天不足2天显示：1天前
     * 5、超过2天不足3天显示：2天前
     * 6、超过三天并且是当前年份显示：月-日，例：04-23
     * 7、超过三天并且不是当前年份显示：年-月-日，例：2015-04-23
     * @param  [Integer] $dateTime [unix时间戳]
     * @return [String]            [处理之后的时间]
     */
    private function dateTimeDeal( $dateTime ) {
        $nowYear    = date('Y');
        $dateYear   = date('Y', $dateTime);
        $difference = time() - $dateTime;
        if ( $difference < 3600 ) {
            /* 一小时之内显示具体多少分钟前 */
            if ( $difference < 60 ) {
                $result = '刚刚';
            } else {
                $result = floor($difference / 60) . '分钟前';
            }
        } elseif ( $difference < 86400 ) {
            /* 一天之内显示具体多少小时前 */
            $result = floor($difference / 3600) . '小时前';
        } elseif ( $difference < 172800 ) {
            /* 超过1天不足2天显示：1天前 */
            $result = '1天前';
        } elseif ( $difference < 259200 ) {
            /* 超过2天不足3天显示：2天前 */
            $result = '2天前';
        } else {
            /* 超过三天 */
            if ( $nowYear == $dateYear ) {
                /* 当前年份显示：月-日 */
                $result = date('m-d', $dateTime);
            } else {
                $result = date('Y-m-d', $dateTime);
            }
        }
        return $result;
    }
}