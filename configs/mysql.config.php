<?php
/**
 * mysql配置文件
 * Author: 闵益飞
 * Date: 2018/5/23
 */
return [
    'database' => array(
        'test' => array(
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'password' => 'minyifei.cn',
            'database' => 'test',
            'charset' => 'utf8',
            'prefix' => ''
        ),
        'road'=>array(
            'host' => 'localhost',
            'port' => '3306',
            'user' => 'root',
            'password' => 'minyifei.cn',
            'database' => 'road',
            'charset' => 'utf8',
            'prefix' => ''
        ),
    ),
    'default'=>'test',
];