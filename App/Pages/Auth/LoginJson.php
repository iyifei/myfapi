<?php
/**
 * 登录
 * Author: 闵益飞
 * Date: 2018/5/24
 */

namespace App\Pages\Auth;


use App\Pages\BasePage;
use Common\Service\UserService;
use Illuminate\Container\Container;

class LoginJson extends BasePage
{

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    function execute($vars = [])
    {
        //注册用户名
        $username = requestNotEmpty('username');
        //密码
        $password = requestNotEmpty('password');

        /**
         * @var UserService $userService
         */
        $userService = Container::getInstance()->make(UserService::class);
        $res = $userService->userLogin($username,$password);
        $this->successJson($res);
    }
}