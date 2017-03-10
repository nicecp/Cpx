<?php
namespace Cpx;

use Cpx\Cpx;
/**
 * 调试器
 *
 */
class Debug extends BaseFrame {

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