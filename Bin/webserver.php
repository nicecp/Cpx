<?php
/**
 * WebServer 脚本
 */
use Cpx\Cpx;
include_once __DIR__ . '/../Vendor/Bootstrap/Autoloader.php';

// 注册自动加载器
Bootstrap\Autoloader::instance()->clear()->setRoot('./')->setRoot('./Vendor/')->init();

Cpx::run();