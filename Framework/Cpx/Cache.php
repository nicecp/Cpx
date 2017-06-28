<?php
namespace Framework\Cpx;

use Framework\Cpx\CpxException;

class Cache {
	/**
	 * 是否开启缓存
	 *
	 * @var bool
	 */
	protected static $switch = false;

	/**
	 * 缓存目录，默认为WEB目录下
	 *
	 * @var string
	 */
	protected static $cacheDirectory = '/';

	/**
	 * 临时缓存路径
	 *
	 * @var string
	 */
	protected static $tmpDirectory = '/tmp/cpx-';

	/**
	 * 获取缓存状态
	 *
	 * @return bool
	 */
	public static function status()
	{
		return (bool)self::$switch;
	}

	/**
	 * 设置开关
	 *
	 * @param bool $switch
	 * @return void
	 */
	public static function setSwitch($switch = false)
	{
		if (!is_bool($switch)) {
			throw new CpxException("Cache switch should be boolen.");
		}

		self::$switch = $switch;
	}
	/**
	 * 设置缓存目录
	 *
	 * @param string $path
	 * @return void
	 */
	public static function setCache($path = '')
	{
		if (!is_dir($path)) {
			throw new CpxException("Cache directory {$path} not exists.");
		}

		self::$cacheDirectory = $path;
	}

	/**
	 * 文件是否存在
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function exists($file = '')
	{
		return file_exists(self::cachePath($file));
	}

	/**
	 * 生成缓存
	 *
	 * @param string $file
	 * @param string $content
	 * @return void
	 */
	public static function cache($file, $content)
	{
		$path = self::cachePath($file);
		file_put_contents($path, $content);
		return $path;
	}

	/**
	 * 获取缓存文件路劲
	 *
	 * @param string $file
	 * @return string
	 */
	public static function getCachePath($file)
	{
		return self::cachePath($file);
	}

	/**
	 * 计算缓存文件路径
	 *
	 * @param string $file
	 */
	protected static function cachePath($file)
	{
		return self::$cacheDirectory . DIRECTORY_SEPARATOR . md5($file) . '.html';
	}

	/**
	 * 不开启缓存时临时文件存储以方便读取显示
	 * 总觉得这方案有点傻逼!!!
	 *
	 * @param string $name
	 * @param string $html
	 * @return string
	 */
	public static function tmpCache($name, $html)
	{
		$path = self::tmpPath($name);
		file_put_contents($path, $html);
		return $path;
	}

	/**
	 * 删除临时缓存文件
	 *
	 * @param string $name
	 * @return void
	 */
	public static function deleteTmp($name)
	{
		@unlink(self::tmpPath($name));
	}

	/**
	 * 临时文件路径
	 *
	 * @param string $name
	 * @return string
	 */
	protected static function tmpPath($name)
	{
		return self::$tmpDirectory . md5($name) . '.html';
	}
}