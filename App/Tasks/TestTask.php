<?php

use Common\Libs\Task;
use Common\Model\UserModel;

/**
 * 后台运行脚本
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class TestTask extends Task
{

    /**
     * 入口函数
     * @param array $vars
     * @return mixed
     */
    public function execute($vars = [])
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();
        var_dump($users);
    }
}