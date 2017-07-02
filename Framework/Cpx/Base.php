<?php
namespace Framework\Cpx;

class Base {
	/**
	 * 应用名称
	 *
	 * @var string
	 */
	public static $appName = 'Cpx';

	/**
	 * 自定义常量数组
	 *
	 * @var array
	 */
	protected static $constMap = array();

	/**
	 * 设置或获取应用名称
	 *
	 * @param string $name
	 */
	public static function appName($name = '')
	{
		if (strlen($name)) {
			self::$appName = $name;
		}

		return self::$appName;
	}

	/**
	 * 渲染器
	 * 直接渲染指定文件，不加载头部和尾部
	 *
	 * @param string $view
	 * @param mixed $params
	 * @return void
	 */
	public function show($view, $params)
	{
		// 加载模板
		$cache = Render::template($view);
		// 导入符号表
		extract($params);
		
		include $cache;
	}

	/**
	 * 渲染器入口
	 * 通用渲染器，自动加载Header和footer
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
		include $cache;
	}

	/**
	 * 获取用户自定义系统常量
	 *
	 * @return array
	 */
	public static function getConsts()
	{
		if (empty($constMap)) {
			$const = get_defined_constants(true);
			self::$constMap = isset($const['user']) ? $const['user'] : array();
		}

		return self::$constMap;
	}

	/**
	 * 命令行模式下，设置当前工作路径
	 * 兼容从不同目录下启动情况
	 *
	 * @return void
	 */
	public static function workingDir()
	{
		chdir(realpath(__DIR__.'/../../'));
	}

	/**
	 * 初始化常量
	 *
	 * @param array $const 自定义常量
	 * @return void
	 */
	public static function consts($const = array())
	{
		define('__PUBLIC__', Render::$resource);

		array_walk($const, function($v, $k) {
			define($k, $v);
		});	
	}
}