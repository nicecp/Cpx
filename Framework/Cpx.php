<?php
namespace Framework;

use Framework\Service;
use Framework\Common\Config;
use Framework\Cpx\Base;
use Framework\Cpx\Db;
use Framework\Cpx\CpxException;
use Framework\Cpx\Render;
use Workerman\Protocols\Http;

/**
 * 框架基类
 */
class Cpx extends Base{

	/**
	 * Request 请求
	 *
	 * @var object
	 */
	public $request = null;

	/**
	 * 控制器类
	 *
	 * @var string
	 */
	public $controller = '';

	/**
	 * 方法名
	 *
	 * @var string
	 */
	public $method = '';

	/**
	 * 构造函数，初始化工作
	 *
	 * @param object $request
	 * @param string $controller
	 * @param string $method
	 * @return void
	 */
	public function __construct($request = null, $controller, $method)
	{
		$this->request = $request;
		$this->controller = $controller;
		$this->method = $method;
	}
	
	/**
	 * 返回数据库连接
	 *
	 * @param string $inst 数据库实例名称
	 * @return object
	 */
	public function db($inst = '')
	{
		if (!strlen($inst)) {
			throw new CpxException("缺少数据库配置参数");
		}

		return Db::instance($inst);
	}
}