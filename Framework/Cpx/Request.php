<?php
namespace Framework\Cpx;

class Request {

	/**
	 * 链接
	 *
	 * @var string
	 */
	public $url = "";

	/**
	 * 域名
	 *
	 * @var string
	 */
	public $host = "";

	/**
	 * 路劲
	 *
	 * @var string
	 */
	public $path = "";

	/**
	 * GET请求参数
	 *
	 * @var array
	 */
	public $queryParams = array();

	/**
	 * POST 请求参数
	 *
	 * @var array
	 */
	public $postParams = array();

	/**
	 * HTTP请求方式
	 *
	 * @var string
	 */
	public $method = '';

	/**
	 * 初始化参数
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->host = $_SERVER['HTTP_HOST'];
		$temp = explode('?', $_SERVER['REQUEST_URI']);
		$this->path = array_shift($temp);
		$this->method = strtoupper($_SERVER['REQUEST_METHOD']);

		switch ($this->method) {
			case 'GET':
				$this->queryParams = $_GET;
				break;
			
			case 'POST':
				$this->postParams = $_POST;
				break;

			default:
				# code...
				break;
		}
	}

	/**
	 * 获取当前实例
	 *
	 * @return object
	 */
	public static function instance()
	{
		return new self;
	}
}