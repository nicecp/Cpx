<?php
namespace Framework\Cpx;

use Framework\Cpx\Base;
use Framework\Cpx\CpxException;

/**
 * 渲染器
 */
class Render extends Base {

	/**
	 * 模板路径
	 *
	 * @var string
	 */
	public static $template = '';

	/**
	 * 资源文件路径
	 *
	 * @var string
	 */
	public static $resource = '';

	/**
	 * 正则替换，模板引擎核心，用于替换模板语言
	 *
	 * @var array [pattern => replace]
	 */
	protected static $regular = array();

	/**
	 * 模板替换规则
	 *
	 * @var array[patter => replace]
	 */
	protected static $pregPattern = array(
		"/\{\{\s*(loop|foreach)\s*\\$([\w_]+)(\s*\['?[\$\w_]*'?\])?\}\}/"                                          => "<?php foreach( \$$2$3 as \$key => \$value) { ; ?>",  // {{ foreach $a}} {{ foreach $a['b']}} {{foreach $a[$c]}}
		"/\{\{\s*(loop|foreach)\s*\\$([\w_]+)(\s*\['?[\$\w_]*'?\])?\s*as\s*\\$([\w_]+)\s*=>\s*\\$([\w_]+)\s*\}\}/" => "<?php foreach( \$$2$3 as \$$4 => \$$5) { ; ?>", // {{ foreach $a as $k => $v}}
		"/\{\{\s*(\/loop|\/foreach)\s*\}\}/"                                                                       => "<?php } ; ?>",  // {{ /foreach }}
		"/\{\{\s*if(.*?)\s*\}\}/"                                                                                  => "<?php if ($1) { ; ?>",  // {{ if $a}}
		"/\{\{\s*(elseif|else if)(.*?)\s*\}\}/"                                                                    => "<?php } else if ($2) { ; ?>",  //  {{ elseif }} {{ else if }}
		"/\{\{\s*else\s*\}\}/"                                                                                     => "<?php } else { ; ?>",  // {{ else }}
		"/\{\{\s*\/if\s*\}\}/"                                                                                     => "<?php } ; ?>",  // {{ /if }}
		"/\{\{\s*\\$([\w_]+)(\['?[\$\w_]*'?\])?\s*\}\}/"                                                           => "<?php echo \$$1$2; ?>",  // 变量  {{ $a }} {{ $a[$b] }} {{ $a['c']}}
		);

	/**
	 * 设置模板路径
	 *
	 * @param string $dir
	 * @return void
	 */
	public static function setTemplate($dir)
	{
		if (!is_dir($dir)) {
			throw new CpxException("模板路径不存在: {$dir}");
		}

		self::$template = realpath($dir);
	}

	/**
	 * 设置资源文件路径
	 *
	 * @param string $dir
	 * @return void
	 */
	public static function setResource($dir)
	{
		if (!is_dir($dir)) {
			throw new CpxException("资源文件路径不存在: {$dir}");
		}

		self::$resource = "/{$dir}";
	}

	/**
	 * 加载模板文件
	 *
	 * @param string $path
	 * @param string $header
	 * @param string $footer
	 * @return string
	 */
	public static function template($body = '', $header = '', $footer = '')
	{
		$path = '';
		if (!Cache::status()) { // 没有开启缓存
			$html = file_get_contents($header) . self::getHtml($body) . file_get_contents($footer);
			$path = Cache::tmpCache($body, $html);
		}
		else if (!Cache::exists($file)) { // 已开启缓存但缓存文件不存在
			$html  = file_get_contents($header) . self::getHtml($body) . file_get_contents($footer);
			// 缓存模板
			$path = Cache::cache($body, $html);
		} else { // 已开启缓存且缓存文件存在
			$path = Cache::getCachePath($body);
		}

		// 返回文件路径
		return $path;
	}

	/**
	 * 替换模板语言，得到替换后的html
	 *
	 * @param string $path
	 * @return string
	 */
	protected static function getHtml($name = '')
	{
		if (!strlen($name)) {
			// 此时只传了一个参数，比如heaer或者footer
			return "";
		}
		$path = self::$template . DIRECTORY_SEPARATOR . "{$name}.html";
		if (!file_exists($path)) {
			throw new CpxException("模板文件不存在: {$path}");
		}

		return self::pregReplace(file_get_contents($path));
	}

	/**
	 * 渲染器
	 *
	 * @param string $html
	 * @param array $params
	 * @return string
	 */
	protected static function pregReplace($html = '')
	{
		array_walk(self::regular(), function($replace, $preg) use (&$html) {
			$html = preg_replace($preg, $replace, $html);
		});

		return $html;
	}

	/**
	 * 添加常量表达式
	 *
	 * @return void
	 */
	protected static function regular()
	{
		if (empty(self::$regular)) {
			foreach (self::getConsts() as $key => $value) {
				$const["/{$key}/"] = $value;
			}
			self::$regular = self::$pregPattern + $const;
		}
		return self::$regular;
	}
}