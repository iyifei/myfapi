<?php
namespace App\Pages\Auth;

use App\Pages\BasePage;
use Common\Service\UserService;
use Illuminate\Container\Container;

/**
 * 注册
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class RegisterJson extends BasePage
{

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    function execute($vars = [])
    {
        //邮箱
        $email = requestEmail('email');
        //注册用户名
        $username = requestNotEmpty('username');
        //密码
        $password = requestNotEmpty('password');

        /**
         * @var UserService $userService
         */
        $userService = Container::getInstance()->make(UserService::class);
        $res = $userService->registerUser($email,$username,$password);
        $this->successJson($res);
    }
}