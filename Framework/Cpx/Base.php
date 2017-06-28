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
}