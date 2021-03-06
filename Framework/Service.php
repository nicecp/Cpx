<?php
namespace Framework;

use Framework\Common\Config;
use Framework\Cpx\Base;
use Framework\Cpx\Db;
use Framework\Cpx\Render;
use Framework\Cpx\Cache;
use Workerman\Worker;
use Workerman\WebServer;

/*
 * 框架
 */
class Service extends Base {
	/**
	 * 启动入口
	 *
	 */
	public static function run()
	{
		// 初始化配置
		self::config();
		// 初始化worker
		self::initWorker();
	}

	/**
	 * 初始化
	 *
	 * @return void
	 */
	protected static function config()
	{
		// 设置当前工作路径
		self::workingDir();

		// 配置个资源命名空间
		Config::setNameSpace('Conf');
		Render::setTemplate(Config::get('Core.template'));
		Render::setResource(Config::get('Core.resource'));
		Cache::setSwitch(Config::get('Core.cache.switch'));
		Cache::setCache(Config::get('Core.cache.path'));
		Db::config(Config::get('App.db'));

		// 初始化
		self::appName(Config::get('Core.appName'));
		self::consts(Config::get('App.const'));
	}

	/**
	 * 启动webserver服务
	 *
	 * @return void
	 */
	public static function initWorker()
	{
		$webserver = new WebServer('http://0.0.0.0:'.Config::get('Core.port'));

		$webserver::$logFile = Config::get('Core.log');
		$webserver::$pidFile = Config::get('Core.masterPidFile');

		// 监听多个域名，类似Nginx的root配置
		foreach(Config::get('Core.listen') as $domain => $webroot) {
			$webserver->addRoot($domain, realpath(__DIR__.'/../').$webroot);
		}
		// 进程数量
		$webserver->count = Config::get('Core.count');

		Worker::runAll();
	}
}
