<?php
/**
 * 后台运行的基类
 * Author: 闵益飞
 * Date: 2018/5/24
 */

namespace Common\Libs;


abstract class Task
{

    public function beforeExecute(){
        echo "执行前打印".PHP_EOL;
    }

    /**
     * 入口函数
     * @param array $vars
     * @return mixed
     */
    abstract public function execute($vars=[]);

}