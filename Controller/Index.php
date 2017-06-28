<?php
namespace Controller;

use \Framework\Cpx;

class Index extends Cpx {
	public function index()
	{
		$this->request->getParams;
		$list = $this->db('write')->select('*')->from('woman')->query();
		$list = array(
			array('id'=>23, 'name'=>'pengc','phone'=>1500),
			array('id'=>24, 'name'=>'kaixiangz', 'phone'=>1600),
			array('id'=>25, 'name'=>'junz', 'phone'=>1700)
			);
		$this->render(array(
			'title'=>'Cpx',
			'explain'=>'欢迎使用 Cpx 框架, 这是一个欢迎页O(∩_∩)O哈哈~',
			'list' => $list,
			'footer'=>'【说明：Cpx 框架基于workerman，采用组件形式可开发API及网页<br/>支持缓存，读写分离，daemon，模板语言，无需安装nginx等web服务器】'
			));
	}
}