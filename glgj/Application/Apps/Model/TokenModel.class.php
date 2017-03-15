<?php
namespace Apps\Model;
use Think\Model;

//手机用户token模型类
class TokenModel extends Model {
    /*用户信息*/
    public function getMemberInfo($uuid) {
        return $this->alias('l')->field('l.uuid,r.uid,r.gid,r.mphone,r.goods,r.truck,r.depot')->join(C('DB_PREFIX').'member r ON l.id = r.token_id', 'LEFT')->where(array('uuid'=>$uuid))->find();
    }

    public function update() {
        $token = md5(md5(rand(111111, 999999)) . time());
        $data_token['uuid'] = $token;
        $data_token['ctime'] = time();
        $data = $this->create($data_token);
        if (empty($data)) {
            return false;
        }
        if (empty($data['id'])) {
            $id = $this->add($data);
            if (!$id) {
                return false;
            }
            $data['id'] = $id;
        } else {
            //更新
            $map['id'] = array('eq', $data['id']);
            $this->save($data);
        }
        return $data;
    }

}