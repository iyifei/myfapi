<?php
/**
 * 登录页面
 * Author: 闵益飞
 * Date: 2018/5/25
 */

namespace App\Pages\Auth;


use App\Pages\BasePage;

class LoginPage extends BasePage
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