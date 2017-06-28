<?php
namespace Framework\Common;

use Framework\Cpx\CpxException;

class Config {

	/**
	 * 配置文件目录
	 *
	 * @var string
	 */
	private static $configNameSpace = '';

	/**
	 * Conig
	 *
	 * @var array
	 */
	private static $config = array();

	/**
	 * 设置配置文件命名空间
	 *
	 * @param string $namespace
	 * @return void
	 */
	public static function setNameSpace($namespace = '')
	{
		if (!$namespace) {
			throw new CpxException('配置文件命名空间为空');
		}

		self::$configNameSpace = $namespace;
	}

	/**
	 * 获取配置
	 *
	 * @param string $configClass
	 * @return array
	 */
	protected static function config($configClass = '')
	{
		$className = '\\' . self::$configNameSpace . '\\' . $configClass;
		if (!self::$configNameSpace || !class_exists($className)) {
			throw new CpxException("Config namespace is empty or {$className} not exist.");
		}

		$key = serialize($className);
		if (!isset(self::$config[$key])) {
			self::$config[$key] = (array) new $className();
		}

		return self::$config[$key];
	}

	/**
	 * 获取配置项
	 *
	 * @param string $configName
	 * @return mixed
	 */
	public static function get($configName = '')
	{
		if (!$configName) {
			throw new CpxException('配置项为空');
		}

		$spices = explode('.', $configName);
		$config = self::config(array_shift($spices));
		while(count($spices)) {
			$key = array_shift($spices);
			if (!isset($config[$key])) {
				throw new CpxException("{$configName} not exist.");
			}
			if (count($spices)) {
				$config = $config[$key];
				continue ;
			}
			return $config[$key];
		}
	}
}