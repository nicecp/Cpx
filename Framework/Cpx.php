<?php
namespace Framework;

use Framework\Service;
use Framework\Common\Config;
use Framework\Cpx\Db;
use Framework\Cpx\CpxException;
use Framework\Cpx\Render;
use Workerman\Protocols\Http;

/**
 * 框架基类
 */
class Cpx {

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
	 * 输出JSON格式数据
	 *
	 * @param mixed $msg
	 * @param bool  $format
	 * @return void
	 */
	public function displayJSON($msg = '', $format = false)
	{
		$flag = $format ? JSON_PRETTY_PRINT : 0;
		Http::header('Content-Type:application/json; charset=utf-8');
		Http::end(json_encode(array('Frame' => Service::$appName, 'Version' => '0.0.1', 'Message' => $msg), JSON_PRETTY_PRINT).PHP_EOL);
	}

	/**
	 * 渲染器入口
	 *
	 * @param mixed $params
	 * @return void
	 */
	public function render($params)
	{
		// 加载模板
		$body = str_replace(array('\\Controller\\', '\\') , DIRECTORY_SEPARATOR, "{$this->controller}/{$this->method}");
		$cache = Render::template($body, Render::template('common/header'), Render::template('common/footer'));
		// 导入符号表
		extract($params);
		// 怎么检查html的php语法错误呢。。。进程直接退出，啥都看不到啊！！！
		// exec("php -l $cache", $output);
		// print_r($output);
		include $cache;
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