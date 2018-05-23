<?php
/**
 * 路由配置
 * Author: 闵益飞
 * Date: 2018/5/23
 */

return [
    //创建用户
    ['method' => 'GET', 'route' => '/user/{id:\d+}', 'handler' => App\Pages\User\GetUserInfoPage::class,],
    ['method' => 'GET', 'route' => '/user/main', 'handler' => App\Pages\User\UserMainPage::class,],
    //获取用户信息
    //['method'=>['GET', 'POST'],'route'=>'/user/{id:\d+}','handler'=>App\Pages\User\GetUserInfo::class],
    //['method'=>['GET', 'POST'],'route'=>'/user/my/{id:\d+}','handler'=>'222'],
];