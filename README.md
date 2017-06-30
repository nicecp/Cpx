# Cpx

​    Cpx 是一款基于组件式开发的`非典型`MVC框架，去掉 Nginx/Apache 等容器，Cpx 是一个实验性的框架，支持常驻内存，命名空间、IO复用等特性，基于workerman框架开发。

### 特性

- 组件式开发


- Daemon、IO复用[workerman提供]
- 模板语言
- 读写分离
- 高速缓存

### 依赖

* php >= 5.4
* workerman
* semantic-ui

### 安装
Step 1. composer依赖
> clone
> composer install

Step 2. semantic-ui
* 请参照[官网](http://www.semantic-ui.cn/introduction/getting-started.html)安装
> cp -r semantic Resource 
**可拷贝至任意目录，在`Conf/App.php`指定快捷路径即可**

### 配置

* Core 框架核心配置文件，App 项目配置文件

> `cp App.php.example App.php`

> `cp Core.php.example Core.php`

### 启动

> `php Bin/webserver.php start`