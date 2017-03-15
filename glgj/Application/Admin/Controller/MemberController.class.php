<?php
namespace Admin\Controller;

use Think\Controller;

class MemberController extends AdminController {

    protected function _initialize() {
        parent::_initialize();
        $this->assign('site', 'member');
    }

    /**
     * 会员管理首页
     * @return [type] [description]
     */
    public function index() {
        $search = I('search');
        $gid = I('group');
        if ($search != '') {
            $condition['name|mphone'] = array('like', '%' . $search . '%');
        }
        if ($gid != '') {
            $condition['gid'] = $gid;
        }
        $condition['id'] = array('GT', 1);
        $group = M('MemberGroup')->order('addtime desc')->select();
        $order = 'regdate desc';
        $list = $this->lists('Member', $condition, $order);
        $gr = D('MemberGroup');
        foreach ($list as &$val) {
            $detail = $gr->getGroupInfo(array('gid' => $val['gid']));
            $val['gname'] = $detail['gname'];
        }
        $this->assign('group', $group);
        $this->assign('list', $list);
        $this->assign('gid', $gid);
        $this->assign('search', $search);
        /*页面基本设置*/
        $this->site_title = "会员管理-会员列表";
        $this->assign('left', 'index');
        $this->display();
    }

    /**
     * 添加会员
     * @return [type] [description]
     */
    public function add() {
        $id = I('id');
        $condition['uid'] = $id;
        $member = D('Member');
        $detail = $member->getMemberInfo($condition);
        if ($_POST) {
            if (!$id) {
                $phone = I('mphone');
                $tmp = $member->getMemberInfo(array('mphone' => $phone));
                if ($tmp && empty($id)) {
                    $this->error('该手机号已存在');
                }
            }
            $status = $member->update();
            if ($status) {
                if ($id) {
                    $this->success('修改成功', '?g=admin&m=member');
                } else {
                    $this->success('添加成功', '?g=admin&m=member');
                }
            } else {
                $errorInfo = $member->getError();
                $this->error($errorInfo);
            }
        }
        $group = M('MemberGroup')->select();
        $this->assign('group', $group);
        // $now_y = date("Y");
        // $year  = '';
        // for ($i = $now_y; $i>($now_y-80); $i--) {
        //     $year[]['val'] = $i;
        // }
        // $month=range(1, 12);
        // $days=range(1,31);
        // $this->assign('year',$year);
        // $this->assign('month',$month);
        // $this->assign('days',$days);
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑会员";
        $this->assign('left', 'index');
        $this->display();
    }

    /**
     * 会员删除
     * @author 83961014@qq.com
     */
    public function del() {
        $id = I('id');
        $member = D('Member');
        $condition['uid'] = $id;
        $return = $member->delMember($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 会员批量删除
     * @author 83961014@qq.com
     */
    public function delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $member = D('Member');
            $condition['uid'] = array('in', $ids);
            $tem = $member->delMember($condition);
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
     * 会员分组列表
     * @return [type] [description]
     */
    public function group() {
        $order = 'gid asc';
        $list = $this->lists('MemberGroup', '', $order);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-会员分组列表";
        $this->assign('left', 'group');
        $this->display();
    }

    /**
     * 添加会员分组
     * @return [type] [description]
     */
    public function group_add() {
        $id = I('id');
        $condition['gid'] = $id;
        $group = D('MemberGroup');
        $detail = $group->getGroupInfo($condition);
        if (IS_POST) {
            $status = $group->update();
            if ($status) {
                if ($id) {
                    $this->success('修改成功', '?g=admin&m=member&a=group');
                } else {
                    $this->success('添加成功', '?g=admin&m=member&a=group');
                }
            } else {
                $errorInfo = $group->getError();
                $this->error($errorInfo);
            }
        }
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑会员分组";
        $this->assign('left', 'group');
        $this->display();
    }

    /**
     * 会员分类删除
     * @author 83961014@qq.com
     */
    public function group_del() {
        $id = I('id');
        in_array($id, array(1, 2)) && $this->error('默认分组不能删除');
        $group = D('MemberGroup');
        $condition['gid'] = $id;
        $return = $group->delGroup($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 会员分类批量删除
     * @author 83961014@qq.com
     */
    public function group_delall() {
        if (IS_POST) {
            $ids = I('ids');
            if (in_array(1, $ids) || in_array(2, $ids)) {
                $return['errno'] = 1;
                $return['error'] = "默认分组不能删除";
                $this->ajaxReturn($return);
            }
            $ids = implode(',', $ids);
            $group = D('MemberGroup');
            $condition['gid'] = array('in', $ids);
            $tem = $group->delGroup($condition);
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
     * 企业列表
     * @return [type] [description]
     */
    public function company() {
        $search = I('search');
        $flags = I('flags');
        if ($search != '') {
            $condition['name'] = array('like', '%' . $search . '%');
            $where['mphone'] = array('like', '%' . $search . '%');
            $id = M('Member')->where($where)->getField('uid', true);
            $id = implode(',', $id);
            if ($id) {
//                $condition['user_id']  = array('in',$id);
                $condition['_logic'] = 'or';
            }
            $this->assign('search', $search);
        }

        if ($flags) {
            $condition['flags'] = $flags;
        }
        $condition['status'] = array('lt', 2);
        $list = $this->lists('Company', $condition, 'proprietary DESC,id DESC');
        $link = M('CompanyLink');
        $flags = D('Flags');
        foreach ($list as &$val) {
            $tmp = $link->where(array("company_id" => $val['id']))->find();
            $val['email'] = $tmp['email'];
            $val['site'] = $tmp['site'];
            $val['flags'] = $flags->where(array(array('att' => $val['flags']), array('att' => array('neq', ''))))->getField('attname');
        }
        $where['att'] = array('neq', '');
        $flags = $flags->where($where)->select();
        $this->assign('flags', $flags);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-企业列表";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 添加企业
     * @return [type] [description]
     */
    public function company_add() {
       


        $id = I('get.id', 0, 'intval');
        $companyModel = D('Company');
        $companyLinkModel = D('CompanyLink');
        if (IS_POST) {
            print_r(I('post.'));
            exit;
            if ($_POST['company']['proprietary'] == 1) {
                $proprietary = M('Company')->where(array('proprietary' => 1))->getField('id');
                if ($proprietary) {
                    if ($_POST['company']['id'] != $proprietary || !$_POST['company']['id']) {
                        $this->error('只能设置一家自营企业');
                    }
                }
            }
            $companyDatas = $companyModel->update();
            if ($companyDatas) {
                $linkDates = $companyLinkModel->update($companyDatas['id']);
                if ($linkDates) {
//                    /* 企业资质 */
//                    M('CompanyCert')->where(array('company_id' => $companyDatas['id']))->delete();
//                    $cert = $_POST['cert'];
//                    if (is_array($cert) && count($cert)) {
//                        $count = count($cert);
//                        for ($i = 0; $i < $count; $i++) {
//                            $certDatas[$i]['company_id'] = $companyDatas['id'];
//                            $certDatas[$i]['img'] = $cert[$i];
//                        }
//                        M('CompanyCert')->addAll($certDatas);
//                    }
                    if ($id > 0) {
                        $this->success('修改成功', '?g=admin&m=member&a=company');
                    } else {
                        $this->success('添加成功', '?g=admin&m=member&a=company');
                    }
                } else {
                    $this->error($companyLinkModel->getError());
                }
            } else {
                $this->error($companyModel->getError());
            }
        }

        /* 修改时查询企业相关信息 */
        if ($id > 0) {
            $detail = $companyModel->getCompanyInfo(array('id' => $id));
            $link = $companyLinkModel->getCompanyLinkInfo(array('company_id' => $detail['id']));
            $link['linkid'] = $link['id'];
            unset($link['id']);
            $detail = array_merge($detail, $link);
            $detail['p_area'] = $detail['lnt'] . ',' . $detail['lat'];
//            $detail['summary'] = strip_tags($detail['summary']);
//            $detail['summary'] = str_ireplace('&nbsp;', '', $detail['summary']);
//            $detail['summary'] = preg_replace('/\t/', '', $detail['summary']);
//            $detail['summary'] = preg_replace('/\r\n/', '', $detail['summary']);
//            $detail['summary'] = preg_replace('/\r/', '', $detail['summary']);
//            $detail['summary'] = preg_replace('/ /', '', $detail['summary']);
//            $detail['summary'] = preg_replace('/  /', '', $detail['summary']);
            $this->assign('detail', $detail);
            /* 企业资质 */
//            $cert = M('CompanyCert')->where(array('company_id' => $id))->getField('id,img', true);
//            $this->assign('cert', $cert);
        }

        /* 企业分类 */
        $list = M('CompanyCategory')->field('id,name')->where(array('status' => 1))->order('sort DESC')->select();
        $this->assign('list', $list);

        /* 属性 */
        $flags = M('Flags')->field('att,attname')->order('cid DESC')->select();
        $this->assign('flags', $flags);

        /* 省市区 */
        $region = M('Regions')->cache(true)->select();
        foreach ($region as $val) {
            if (!$val['parent']) {
                $region_arr[0][] = $val;
            } else {
                $region_arr[$val['parent']][] = $val;
            }
        }
        $this->assign('region_arr', $region_arr);
        $this->assign('region_arr_json', json_encode($region_arr));

        /* 页面基本设置 */
        $this->site_title = "会员管理-编辑企业";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 查看企业
     * @return [type] [description]
     */
    public function applyDetail() {
        $id = I('get.id', 0, 'intval');
        $companyModel = D('Company');
        $companyLinkModel = D('CompanyLink');
        !$id && $this->error('参数错误');

        /* 查询企业相关信息 */
        $detail = $companyModel->getCompanyInfo(array('id' => $id));
        !$detail && $this->error('企业不存在');
        $link = $companyLinkModel->getCompanyLinkInfo(array('company_id' => $detail['id']));
        $link['linkid'] = $link['id'];
        unset($link['id']);
        $detail = array_merge($detail, $link);
        $detail['p_area'] = $detail['lnt'] . ',' . $detail['lat'];
        $detail['summary'] = strip_tags($detail['summary']);
        $detail['summary'] = str_ireplace('&nbsp;', '', $detail['summary']);
        $detail['summary'] = preg_replace('/\t/', '', $detail['summary']);
        $detail['summary'] = preg_replace('/\r\n/', '', $detail['summary']);
        $detail['summary'] = preg_replace('/\r/', '', $detail['summary']);
        $detail['summary'] = preg_replace('/ /', '', $detail['summary']);
        $detail['summary'] = preg_replace('/  /', '', $detail['summary']);
        $detail['company_category_id'] = M('CompanyCategory')->where(array('id' => $detail['company_category_id']))->getField('name');
        $this->assign('detail', $detail);

        /* 企业资质 */
        $cert = M('CompanyCert')->where(array('company_id' => $id))->getField('id,img', true);
        $this->assign('cert', $cert);

        /* 页面基本设置 */
        $this->site_title = "会员管理-查看企业";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 企业删除
     * @author 83961014@qq.com
     */
    public function company_del() {
        $id = I('id');
        $cate = D('CompanyLink');
        $condition['id'] = $id;
        $user_id = M('Company')->where($condition)->getField('user_id');
        M('Member')->where(array('uid' => $user_id))->setField('gid', 1);
        $return = $cate->delCompanyLink(array('company_id' => $id));
        $com = D('Company');
        $return1 = $com->delCompany($condition);
        M('CompanyAlbumPicture')->where(array('company_id' => $id))->delete();
        if ($return1 != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 企业批量删除
     * @author 83961014@qq.com
     */
    public function company_delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $cate = D('CompanyLink');
            $condition['id'] = array('in', $ids);
            $condition1['company_id'] = array('in', $ids);
            $tem = $cate->delCompanyLink($condition1);
            $com = D('Company');
            $return1 = $com->delCompany($condition);
            M('CompanyAlbumPicture')->where($condition1)->delete();
            if ($tem != false && $return1 != false) {
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
     * 企业分组列表
     * @return [type] [description]
     */
    public function company_group() {
        $condition['parent_id'] = array('like', '%');
        $list = $this->lists('CompanyCategory', $condition);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-企业分组列表";
        $this->assign('left', 'company_group');
        $this->display();
    }

    /**
     * 添加企业分组
     * @return [type] [description]
     */
    public function company_group_add() {
        $id = I('id');
        $cate = D('CompanyCategory');
        $detail = $cate->getCompanyCategoryInfo(array('id' => $id));
        if ($_POST) {
            $status = $cate->update();
            if ($status) {
                if ($id) {
                    $this->success('修改成功', '?g=admin&m=member&a=company_group');
                } else {
                    $this->success('添加成功', '?g=admin&m=member&a=company_group');
                }

            } else {
                $errorInfo = $cate->getError();
                $this->error($errorInfo);
            }
        }
        $condition['status'] = array('eq', 1);
        $id && $condition['id'] = array('neq', $id);
        $list = M('CompanyCategory')->where($condition)->select();
        $this->assign('list', $list);
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑企业分组";
        $this->assign('left', 'company_group');
        $this->display();
    }

    /**
     * 企业分类删除
     * @author 83961014@qq.com
     */
    public function company_category_del() {
        $id = I('id');
        $cate = D('CompanyCategory');
        $condition['id'] = $id;
        $return = $cate->delCompanyCategory($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 企业分类批量删除
     * @author 83961014@qq.com
     */
    public function company_category_delall() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $cate = D('CompanyCategory');
            $condition['id'] = array('in', $ids);
            $tem = $cate->delCompanyCategory($condition);
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
     * 公司相册
     */
    public function companyAlbum() {
        $pid = I('request.pid', 0, 'intval');
        !$pid && $this->error('参数错误');
        $where['pid'] = array('eq', $pid);
        $list = $this->lists('CompanyAlbum', $where, 'sort DESC,id DESC', 'id,title,sort,status,ctime,etime');
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = "公司相册管理";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 公司相册添加、修改
     */
    public function companyAlbumAdd() {
        $id = I('request.companyid', 0, 'intval');
        !$id && $this->error('参数错误');
        /*$model = D('CompanyAlbum');
        $opt = $id > 0 ? '修改' : '添加';*/
        if (IS_POST) {
            /*$result = $model->update();
            if ( $result ) {
                $this->success($opt.'成功', '?g=admin&m=member&a=company');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }*/
            M('CompanyAlbumPicture')->where(array('pid' => $id))->delete();
            $count = is_array($_POST['img']) ? count($_POST['img']) : 0;
            if (!$count) {
                $this->error = '至少上传一张相册图片';
                return false;
            }
            /*if ( is_array($_POST['is_cover']) ) {
                $cover_count = array_count_values($_POST['is_cover']);
                $cover_count = $cover_count[1];
                if ( $cover_count == 0 ) {
                    $this->error('请设置封面图');
                    return false;
                }
                if ( $sort_count > 1 ) {
                    $this->error('只能设置一个封面图');
                    return false;
                }
            } else {
                $this->error('参数错误');
                return false;
            }*/

            /* 相册图片数据验证 */
            for ($i = 0; $i < $count; $i++) {
                if (empty($_POST['img'][$i])) {
                    $this->error('请上传相册图片');
                    return false;
                }
                $allpicDates[$i]['pid'] = $id;
                $allpicDates[$i]['img'] = $_POST['img'][$i];
                // $allpicDates[$i]['is_cover'] = $_POST['is_cover'][$i];
                $allpicDates[$i]['sort'] = $_POST['sort'][$i];
            }
            $add = M('CompanyAlbumPicture')->addAll($allpicDates);
            if ($add) {
                $this->success('相册设置成功', '?g=admin&m=member&a=company');
            } else {
                $this->error('相册设置失败');
            }
        }

        /* 修改 */
        if ($id) {
            /*$condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail',$detail);*/

            $allpic = M('CompanyAlbumPicture')->field('img,sort')->where(array('pid' => $id))->select();
            $this->assign('allpic', $allpic);
        }

        /* 页面基本设置 */
        $this->site_title = $opt . "企业相册";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 企业相册删除
     */
    public function companyAlbumDel() {
        $id = I('id');
        $model = D('CompanyAlbum');
        $condition['id'] = $id;
        $return = $model->del($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 企业相册批量删除
     */
    public function companyAlbumDelAll() {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('CompanyAlbum');
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

    /* 产品生成二维码（支持批量） */
    public function qrcode() {
        if (IS_POST) {
            $id = $_POST['id'];
            if (!is_array($id)) {
                $id = array($id);
            }
            $model = M('Company');
            foreach ($id as $key => $value) {
                if ($value) {
                    $href = C('HTTP_ORIGIN') . '/?g=app&m=apps&a=company_home&id=' . $value;
                    $savepath = '/Uploads/Admin/image/' . date('Ymd');
                    $filename = date("YmdHis") . '_' . rand(10000, 99999) . '.png';
                    $qrcode = C('HTTP_ORIGIN') . $savepath . '/' . $filename;
                    $savepath = '.' . $savepath;
                    if (!is_dir($savepath)) {
                        mkdir($savepath, 0755, true);
                    }
                    $savepath = $savepath . '/' . $filename;
                    vendor('phpqrcode.qrlib');
                    \QRcode::png($href, $savepath, 'H', 300, 2);
                    $save = $model->where(array('id' => $value))->setField('qrcode', $qrcode);
                    $save === false && $this->error('生成二维码失败');
                    $save = false;
                } else {
                    $this->error('参数错误');
                }
            }
            $this->success('生成二维码成功');
        }
        $this->error('非法操作');
    }

    /**
     * 系统消息列表
     * @return [type] [description]
     */
    public function message() {
        $order = 'addtime desc';
        $list = $this->lists('UserMessage', '', $order);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-系统消息列表";
        $this->assign('left', 'message');
        $this->display();
    }

    /**
     * 添加系统消息
     * @return [type] [description]
     */
    public function message_add() {
        if (IS_POST) {
            $message = D('UserMessage');
            $status = $message->update();
            if ($status) {
                $this->success('添加成功', '?g=admin&m=member&a=message');
            } else {
                $errorInfo = $message->getError();
                $this->error($errorInfo);
            }
        }
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑系统消息";
        $this->assign('left', 'message');
        $this->display();
    }

    /**
     * 系统消息删除
     * @author 83961014@qq.com
     */
    public function message_del() {
        $id = I('id');
        $group = D('UserMessage');
        $condition['id'] = $id;
        $return = $group->delUserMessage($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 公司申请列表
     * @author 83961014@qq.com
     */
    public function apply() {
        $condition['status'] = array('eq', 2);
        $list = $this->lists('Company', $condition);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-企业申请列表";
        $this->assign('left', 'apply');
        $this->display();
    }

    /**
     * 通过公司申请
     * @author 83961014@qq.com
     */
    public function pass() {
        $id = I('id', 0, 'intval');
        $condition['id'] = array('eq', $id);
        $data['status'] = 1;
        $state = M('Company')->where($condition)->save($data);
        if ($state) {
            /* 查询企业会员uid */
            $uid = M('Company')->where($condition)->getField('user_id');
            /* 改变会员状态 */
            M('Member')->where(array('uid' => $uid))->setField('gid', 2);
            /* 发送站内信 */
            R('Apps/General/sendSiteMessage', array('您的企业会员申请已通过', '恭喜您，您的企业会员申请已通过，快去打理您的店铺吧', M('Company')->where(array('id' => $id))->getField('user_id')));
            $this->success('操作成功', '?g=admin&m=member&a=company');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 拒绝公司申请
     * @author 83961014@qq.com
     */
    public function refuse() {
        $id = I('id', 0, 'intval');
        $reason = I('post.reason');
        if ($reason && strlen($reason) > 200) {
            $this->error('拒绝理由不能超过200个字符');
        }
        $data['status'] = 3;
        $data['reason'] = $reason;
        $state = M('Company')->where(array('id' => $id))->save($data);
        if ($state) {
            /* 查询企业会员uid */
            $uid = M('Company')->where($condition)->getField('user_id');
            /* 改变会员状态 */
            M('Member')->where(array('uid' => $uid))->setField('gid', 1);
            /* 发送站内信 */
            $reason && $reason = '，原因：' . $reason;
            R('Apps/General/sendSiteMessage', array('您的企业会员申请失败了', '很遗憾，您的企业会员申请失败了' . $reason, M('Company')->where(array('id' => $id))->getField('user_id')));
            $this->success('操作成功', '?g=admin&m=member&a=company');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 网点管理中心
     * @return [type] [description]
     */
    public function point() {
        $id = I('id');
        $condition['c_id'] = $id;
        $order = 'p_id desc';
        $list = $this->lists('CompanyPoint', $condition, $order);
        $this->assign('list', $list);
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑会员";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 添加网店
     * @param  pid 这条网点的id
     * @param  cid 新增网点属于的企业id
     */
    public function pointAdd() {
        $pid = I('get.pid', '', 'intval');
        $cid = I('get.cid', '', 'intval');
        $model = D('CompanyPoint');
        /*单条网店记录*/
        if ($pid) {
            $condition['p_id'] = array('eq', $pid);
            $detail = $model->getOneInfo($condition);
            $area = explode(',', $detail['p_area']);
            $detail['lng'] = $area[0];
            $detail['lat'] = $area[1];
            $company = M('Company')->where(array('id' => $detail['c_id']))->field('id,name')->select();
            $detail['companyName'] = $company['name'];
            $urlId = $company['id'];
        }
        if ($cid) {
            $detail['companyName'] = M('Company')->where(array('id' => $cid))->getField('name');
            $detail['c_id'] = $cid;
            $urlId = $cid;
        }
        $this->assign('detail', $detail);

        if ($_POST) {
            $status = $model->update();
            if ($status) {
                if ($pid) {
                    $this->success('修改成功', '?g=admin&m=member&a=point&id=' . $urlId);
                } else {
                    $this->success('添加成功', '?g=admin&m=member&a=point&id=' . $urlId);
                }
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 省市区 */
        $region = M('Regions')->cache(true)->select();
        foreach ($region as $val) {
            if (!$val['parent']) {
                $region_arr[0][] = $val;
            } else {
                $region_arr[$val['parent']][] = $val;
            }
        }
        $this->assign('region_arr', $region_arr);
        $this->assign('region_arr_json', json_encode($region_arr));
        $this->assign('detail', $detail);
        /*页面基本设置*/
        $this->site_title = "会员管理-编辑会员";
        $this->assign('left', 'company');
        $this->display();
    }

    /**
     * 删除网点
     * @param $id 单条删除
     * @param $ids 多条删除
     */
    public function pointDel() {
        $id = I('get.id', '', 'intval');
        $ids = I('post.ids', '', 'strval');
        $model = D('CompanyPoint');
        $condition = array();
        if ($id) {      //单条删除
            $condition['p_id'] = array('eq', $id);
            $status = $model->delDate($condition);
            if (!$status) {
                $this->error('请稍候重试');
            } else {
                $this->success('删除成功');
            }
        }
        if ($ids) {     //多条删除
            $ids = implode(',', $ids);
            $condition['p_id'] = array('in', $ids);
            $status = $model->delDate($condition);
            if (!$status) {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     *申请仓储主
     */
    public function applyDepot() {


    }

    /**
     * 申请个人车主
     */
    public function applyPerTruck() {

    }

    /**
     * 申请企业车主
     */
    public function applyComTruck() {


    }

    /**
     * 申请个人货主
     */
    public function applyPerGoods() {


    }

    /**
     * 申请企业货主
     */
    public function applyComGoods() {


    }

}