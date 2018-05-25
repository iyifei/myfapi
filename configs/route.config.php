<?php
/**
 * 路由配置
 * Author: 闵益飞
 * Date: 2018/5/23
 */

use App\Pages\Auth\LoginJson;
use App\Pages\Auth\LoginPage;
use App\Pages\Auth\RegisterJson;
use App\Pages\Index\IndexPage;

return [
    //首页
    ['method' => 'GET', 'route' => '/', 'handler' => IndexPage::class],
    //用户注册
    ['method' => 'POST', 'route' => '/auth/register', 'handler' => RegisterJson::class],
    ['method' => 'POST', 'route' => '/auth/login', 'handler' => LoginJson::class],
    //登录页面
    ['method' => 'GET', 'route' => '/auth/login', 'handler' => LoginPage::class],
    //获取用户信息
    //['method'=>['GET', 'POST'],'route'=>'/user/{id:\d+}','handler'=>App\Pages\User\GetUserInfo::class],
    //['method'=>['GET', 'POST'],'route'=>'/user/my/{id:\d+}','handler'=>'222'],
];