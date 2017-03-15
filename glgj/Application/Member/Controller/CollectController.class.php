<?php
namespace Member\Controller;

use Think\Controller;

class CollectController extends MemberController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('site', 'collect');
    }

    /**
     * 资讯收藏
     * @return [type] [description]
     */
    public function index()
    {
        $where['uid'] = UID;
        $where['favorite_category'] = 1;
        $order = "addtime desc";
        $list = M('UserFavorite')->where($where)->order($order)->select();
        $news = M('article');
        foreach ($list as &$val) {
            $detail = $news->where(array('id' => $val['aid']))->find();
            $val['title'] = $detail['title'];
            $val['short_title'] = $detail['short_title'];
            $val['desc'] = $detail['desc'];
            $val['img'] = $detail['image'];
        }
        $this->assign('list', $list);
        $this->site_title = '资讯收藏';
        $this->assign('header', 'index');
        $this->display();
    }

    /**
     * 产品收藏
     * @return [type] [description]
     */
    public function product()
    {
        $data['uid'] = UID;
        $data['favorite_category'] = 2;
        $order = "addtime desc";
        $list = M('UserFavorite')->where($data)->order($order)->select();
        $product = M('ProductSale');
        foreach ($list as $key => $val) {
            $detail = $product->where(array('id' => $val['aid']))->find();
            $list[$key]['title'] = $detail['title'];
            $list[$key]['short_title'] = $detail['short_title'];
            $list[$key]['price'] = $detail['price'];
            $list[$key]['image'] = $detail['img'];
            $list[$key]['summary'] = $detail['summary'];
        }
        $this->assign('list', $list);
        $this->site_title = '产品收藏';
        $this->assign('header', 'product');
        $this->display();
    }

    /**
     * 企业收藏
     * @return [type] [description]
     */
    public function company()
    {
        $data['uid'] = UID;
        $data['favorite_category'] = 3;
        $order = "addtime desc";
        $list = M('UserFavorite')->where($data)->order($order)->select();
        $company = M('Company');
        $companylink = M('CompanyLink');
        foreach ($list as $key => $val) {
            $detail = $company->where(array('id' => $val['aid']))->find();
            $link = $companylink->where(array('company_id' => $val['aid']))->find();
            $list[$key]['logo'] = $detail['logo'];
            $list[$key]['name'] = $detail['name'];
            $list[$key]['contact_user'] = $link['contact_user'];
            $list[$key]['telephone'] = $link['telephone'];
            $list[$key]['address'] = $link['address'];
            $list[$key]['business'] = $detail['business'];
        }
        $this->assign('list', $list);
        $this->site_title = '企业收藏';
        $this->assign('header', 'company');
        $this->display();
    }

    /**
     * 求购收藏
     * @return [type] [description]
     */
    public function need()
    {
        $data['uid'] = UID;
        $data['favorite_category'] = 4;
        $order = "addtime desc";
        $list = M('UserFavorite')->where($data)->order($order)->field('id,aid,addtime')->select();
        $array = array_column($list, 'aid');
        foreach ($list as $key => $val) {
            $lists[$val['aid']] = $val['id'];
            $listss[$val['aid']] = $val['addtime'];
        }
        $map['id'] = array('in', $array);
        $content = M('ProductBuy')->where($map)->field('id aid,title,img')->select();
        foreach ($content as $key => $val) {
            $content[$key]['id'] = $lists[$val['aid']];
            $content[$key]['addtime'] = $listss[$val['aid']];
        }
        $this->assign('content', $content);
        $this->site_title = '企业收藏';
        $this->assign('header', 'need');
        $this->display();
    }

    /**
     * 行业圈
     * @return [type] [description]
     */
    public function circle()
    {
        $where['uid'] = UID;
        $where['favorite_category'] = array('in', '4,5');
        $order = "addtime desc";
        $num = M('UserFavorite')->where($where)->order($order)->getField('id,aid');
        $num = array_values($num);
        unset($where);
        $where['id'] = array('in', $num);
        $where['status'] = array('eq', 1);
        $list = M('Circle')->where($where)->field('id,content,img,ctime')->select();

        $this->assign('list', $list);
        $this->site_title = '行业圈收藏';
        $this->assign('header', 'circle');
        $this->display();
    }

    /**
     * 删除收藏
     * @return [type] [description]
     */
    public function del()
    {
        $id = I('id');
        $classify = I('classify');
        empty($id) && $this->error('参数错误');
        $condition['id'] = $id;
        $condition['uid'] = UID;
        $status = M('UserFavorite')->where($condition)->delete();
        if ($status) {
            $this->success('删除成功');
        } else {
            $this->error("删除失败");
        }
    }
}