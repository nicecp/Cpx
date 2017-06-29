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
	 * 初始化常量
	 *
	 * @return void
	 */
	public static function consts()
	{
		define('__PUBLIC__', Render::$resource);
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
}