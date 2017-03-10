<?php
namespace Cpx;

use Cpx\Db\Mysql;
use CpxException;

class Db {
	/**
	 * 数据库配置
	 *
	 * @var array
	 */
	protected static $config = array();

	/**
	 * 数据库连接
	 *
	 * @var object ['write' => db, 'read' => [db1, ...]]
	 */
	protected static $instance = array();

	/**
	 * 初始化配置
	 *
	 * @return void
	 */
	public static function config($config = array())
	{
		if (empty($config)) {
			throw new CpxException("缺少数据库配置");
		}

		self::$config = $config;
	}

	/**
	 * 获取数据库配置
	 *
	 * @param string $name
	 * @return array
	 */
	private static function get($name)
	{
		$spices = explode('.', $name);
		$config = self::$config;
		while(count($spices)) {
			$item = array_shift($spices);
			if (!isset($config[$item])) {
				throw new CpxException("缺少相应的数据库配置项：{$name}");
			}
			if (count($spices)) {
				$config = $config[$item];
				continue ;
			}
			return $config[$item];
		}
	}

	/**
	 * 获取数据库连接
	 *
	 * @param string $inst
	 * @return object
	 */
	public static function instance($inst)
	{
		if (!isset(self::$instance[$inst])) {
			self::$instance[$inst] = self::connection($inst);
		}

		return self::$instance[$inst];
	}
	/**
	 * 初始化数据库连接
	 *
	 * @return object
	 */
	private static function connection($inst)
	{
		$config = self::get($inst);
		return new Mysql($config['host'], $config['port'], $config['user'], $config['password'], $config['dbname']);
	}
}