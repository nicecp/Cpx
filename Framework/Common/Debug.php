<?php
namespace Framework\Common;

use Framework\Cpx\Base;
use Workerman\Protocols\Http;

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

	/**
	 * 输出JSON格式数据
	 *
	 * @param mixed $msg
	 * @param bool  $format
	 * @return void
	 */
	public static function displayJSON($msg = '', $format = false)
	{
		$flag = $format ? JSON_PRETTY_PRINT : 0;

		Http::header('Content-Type:application/json; charset=utf-8');
		Http::end(json_encode(array('Frame' => Base::$appName, 'Version' => '0.0.1', 'Message' => $msg), JSON_PRETTY_PRINT).PHP_EOL);
	}

	/**
	 * 调用栈
	 *
	 * @param array $trace
	 * @return array
	 */
	public static function stackTrace($msg, $trace = array())
	{
		$debug = array("{$msg} in {$trace[0]['file']}: {$trace[0]['line']}", "Stack trace:");

		array_walk($trace, function($line, $key) use (&$debug) {
			$debug[] = "#{$key} " . self::getStraceString($line);
		});

		return array('trace' => $debug);
	}

	/**
	 * 将每一条调用栈转换成字符串
	 *
	 * @param array $line
	 * @return string
	 */
	private static function getStraceString($line)
	{
		$str = (isset($line['file']) ? $line['file'] : "[internal function]") . " ({$line['line']}): {$line['class']}{$line['type']}{$line['function']}";

		if (empty($line['args'])) {
			return $str."()";
		}

		$param = "('";
		array_walk($line['args'], function($item, $key) use (&$param) {
			switch (gettype($item)) {
				case 'array':
					$param .= "Array', '";
					break;
				case 'object':
					$param .= get_class($item) . "', '";
					break;
				default:
					$param .= "{$item}', '";
					break;
			}
		});

		return $str . substr($param, 0, -3) . ')';
	}
}