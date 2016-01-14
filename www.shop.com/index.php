<?php
//>>1.检测PHP的版本,版本低于5.3就退出
version_compare(PHP_VERSION, '5.3', '>') or exit('版本太低!');
//>>2.定义项目的根目录常量
define('ROOT_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
//>>3.定义框架的目录
define('THINK_PATH', dirname(ROOT_PATH) . '/ThinkPHP/');
//>>4.定义应用目录
define('APP_PATH', ROOT_PATH . 'Application/');
//>>5.定义运行时目录(Runtime)
define('RUNTIME_PATH', ROOT_PATH . 'Runtime/');
//>>6.设置调试模式
define('APP_DEBUG', true);
//>>7.绑定指定的一个模块
define('BIND_MODULE','Home');
//>>8.加载框架的入口文件
require THINK_PATH . 'ThinkPHP.php';
//加载了框架代码之后不要在后面写其他的代码