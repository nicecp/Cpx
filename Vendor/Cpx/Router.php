<?php
namespace Cpx;

/**
 * 路由器
 */
class Router extends BaseFrame {

	/**
	 * 路由到指定控制器
	 *
	 * @param object $request
	 * @return void
	 */
	public static function route($request = null)
	{
		// 过滤掉URL末尾的文件类型
		$path = str_replace(array('index.php','.php', '.html'), '', $request->path);
		$spices = explode("/", $path);
		$spices = array_filter($spices, function($item) {
			return $item;
		});
		switch (count($spices)) {
			case 0: // 只有域名，可能包含入口文件
				$method = 'index';
				$className = '\\Controller\\Index';
				break;

			case 1:  // 只有控制器
				$method = 'index';
				$className = '\\Controller\\' . implode("\\", $spices);
				break;

			default: // 其他情况，可能包含版本路径
				$method = array_pop($spices);
				$className = '\\Controller\\' . implode("\\", $spices);
				break;
		}
		
		// 路由至控制器
		if (class_exists($className) || method_exists($className, $method)) {
			$controller = new $className($request, $className, $method);
			if (is_callable(array($controller, $method))) {
				$controller->$method();
			} else {
				Debug::debug("方法不可访问：{$className}::{$method}");
			}
		} else {
			Debug::debug("找不到对应路由器：{$className}::{$method}, 类或者方法不存在.");
		}
	}
}