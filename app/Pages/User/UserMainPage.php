<?php
/**
 * 用户主页面
 * Author: 闵益飞
 * Date: 2018/5/23
 */

namespace App\Pages\User;


use App\Pages\BasePage;

class UserMainPage extends BasePage
{

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    function execute($vars = [])
    {
        $this->display();
    }
}