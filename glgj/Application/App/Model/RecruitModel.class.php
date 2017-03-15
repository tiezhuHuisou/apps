<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-04
 * Time: 15:50
 */

namespace Member\Model;

use Think\Model;

class RecruitModel extends Model {

	protected $_validate = array();
	protected $_auto = array();

	/*新增编辑数据*/
	public function update() {
		$data = $this->create($_POST);
		if (empty($data)) {
			return false;
		}
		if (empty($data['uid'])) {
			//add
			$uid = $this->add();
			if (!$uid) {
				$this->error = '新增出错';
				return false;
			}
		} else {
			//update
			$status = $this->save($data);
			if (false === $status) {
				$this->error = '修改出错';
				return false;
			}
		}
		return $data;
	}
}