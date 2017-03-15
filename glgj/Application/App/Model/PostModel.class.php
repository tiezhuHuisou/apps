<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-04
 * Time: 15:50
 */

namespace Member\Model;

use Think\Model;

class PostModel extends Model {

	protected $_validate = array(
	);
	protected $_auto = array(
		array('ctime', 'time', self::MODEL_INSERT, 'function'),
		array('etime', 'time', self::MODEL_UPDATE, 'function'),
	);
	/*新增编辑数据*/
	public function update() {
		$data = $this->create($_POST);
		if (empty($data)) {
			return false;
		}
		if (empty($data['id'])) {
			//add
			$id = $this->add($data);
			if (!$id) {
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