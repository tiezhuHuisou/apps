<?php
namespace App\Controller;
use Think\Controller;
class SearchController extends AppController {
	/**
	 * 搜索
	 * @return [type] [description]
	 */
	public function index() {
		/* 页面基本设置 */
        $this->site_title 		= "搜索";
        $this->site_keywords 	= "搜索";
        $this->site_description = "搜索";

        $this->assign('site','index');
		$this->display();
	}
}