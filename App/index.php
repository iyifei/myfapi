<?php
/**
 * 入口文件
 * Author: 闵益飞
 * Date: 2018/5/23
 */
//定义项目名称
define('APP_NAME','App');
//定项目路径
define('APP_PATH',__DIR__);
//系统路径
define('SYS_PATH',dirname(APP_PATH));
//来源于入口文件
define('FROM_FILE',__FILE__);
//加载autoload
require SYS_PATH.'/vendor/autoload.php';
