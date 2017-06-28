<?php
namespace Framework\Common;

use Framework\Cpx\Base;
/**
 * 调试器
 *
 */
class Debug extends Base {

	/**
	 * 调试信息
	 *
	 * @param string $msg
	 * @return void
	 */
	public static function debug($msg = '')
	{
		echo $msg;
	}
}