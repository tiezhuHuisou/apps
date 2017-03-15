<?php
namespace Admin\Controller;
use Think\Controller;
class DistributionController extends AdminController{
    
    protected function _initialize() {
        parent::_initialize();
        $this->assign('site','distribution');
    }

    /**
     * 分销设置首页
     */
    public function index() {
        if (IS_POST) {
            $post = I('post.');
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);
            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
            $this->assign('detail', $info_arr);

            /*页面基本设置*/
            $this->site_title="分销设置";
            $this->assign('left','index');
            $this->display();
        }
    }


    /**
     * 佣金提现申请管理
     */
    public function financialManagement() {
        /* 佣金提现申请 */
        $status = I('request.status', 0, 'intval');
        if ( $status ) {
            $where['status'] = array('eq', $status);
        } else {
            $where['status'] = array('in', array(2,3,4,5));
        }
        $list = $this->lists('Commission', $where, 'id DESC');

        if ( $list ) {
            /* 数据处理 */
            foreach ($list as $key => $value) {
                /* 用户id */
                $memberIds[] = $value['uid'];
                /* 提现账户id */
                $accountIds[] = $value['account_id'];
            }

            /* 查询用户信息 */
            $where_member['uid'] = array('in', $memberIds);
            $memberList = M('Member')->where($where_member)->getField('uid,name,mphone', true);
            $this->assign('memberList', $memberList);

            /* 提现方式 */
            $typeList = array(1=>'微信',2=>'支付宝',3=>'转余额',4=>'银行卡');
            $this->assign('typeList', $typeList);

            /* 查询帐号信息 */
            $where_account['id'] = array('in', $accountIds);
            $accountList = M('WithdrawalsAccount')->where($where_account)->getField('id,account,truename,bank_card,bank_address,img', true);
            $this->assign('accountList', $accountList);

            /* 状态 */
            $statusList = array(2=>'申请提现中',3=>'待打款',4=>'申请被拒',5=>'已打款');
            $this->assign('statusList', $statusList);

            /* 收款信息 */
            foreach ($list as $k => $v) {
                if ( $v['withdrawal_type'] == 3 ) {
                    $list[$k]['html'] = '<div>';
                    $list[$k]['html'] .= '<p>收款人：'.$memberList[$v['uid']]['name'].'</p>';
                    $list[$k]['html'] .= '<p>收款帐号：'.$memberList[$v['uid']]['mphone'].'</p>';
                    $list[$k]['html'] .= '</div>';
                } else {
                    $list[$k]['html'] = '<div>';
                    $list[$k]['html'] .= '<p>收款人：'.$accountList[$v['account_id']]['truename'].'</p>';
                    $list[$k]['html'] .= '<p>收款帐号：'.$accountList[$v['account_id']]['account'].'</p>';
                    $accountList[$v['account_id']]['bank_card'] && $list[$k]['html'] .= '<p>银行类型：'.$accountList[$v['account_id']]['bank_card'].'</p>';
                    $accountList[$v['account_id']]['bank_address'] && $list[$k]['html'] .= '<p>开户行地址：'.$accountList[$v['account_id']]['bank_address'].'</p>';
                    $list[$k]['html'] .= '</div>';
                }
            }
            $this->assign('list', $list);
        }

        /* 筛选状态 */
        $stateList = array(
            array('id'=>0, 'title'=>'全部'),
            array('id'=>2, 'title'=>'申请提现中'),
            array('id'=>3, 'title'=>'待打款'),
            array('id'=>4, 'title'=>'申请被拒'),
            array('id'=>5, 'title'=>'已打款')
        );
        $this->assign('stateList', $stateList);

        /* 页面基本设置 */
        $this->site_title = '佣金提现申请';
        $this->assign('left', 'financialmanagement');
        $this->display();
    }

    /**
     * 佣金提现申请详情
     */
    public function financialDetail() {
        /* 页面基本设置 */
        $this->site_title = '佣金提现申请详情';
        $this->assign('left', 'financialdetail');
        $this->display();
    }

    /**
     * 佣金提现申请通过
     */
    public function agree() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            $datas['status'] = 3;
            $datas['remark'] = '申请通过';
            $save = M('Commission')->where(array('id'=>$id, 'status'=>2))->save($datas);
            $save && $this->success('操作成功');
            $this->success('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 佣金提现申请拒绝
     */
    public function refuse() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            $datas['status'] = 4;
            $datas['remark'] = '申请被拒';
            $save = M('Commission')->where(array('id'=>$id, 'status'=>2))->save($datas);
            $save && $this->success('操作成功');
            $this->success('操作失败');
        }
        $this->error('非法操作');
    }

    /**
     * 佣金提现打款完成
     */
    public function complete() {
        if ( IS_GET ) {
            $id = I('get.id', 0, 'intval');
            $datas['status'] = 5;
            $datas['remark'] = '打款完成';
            $save = M('Commission')->where(array('id'=>$id, 'status'=>3))->save($datas);
            if ( $save ) {
                /* 转入余额的提现 增加用户余额 */
                $where_info['id'] = array('eq', $id);
                $info = M('Commission')->field('withdrawal_type,uid,commission')->where($where_info)->find();
                if ( $info['withdrawal_type'] == 3 ) {
                    M('Member')->where(array('uid'=>$info['uid']))->setInc('balance', $info['commission']);
                }
                $this->success('操作成功');
            }
            $this->error('操作失败');
        }
        $this->error('非法操作');
    }
}