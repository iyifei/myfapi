<?php
/**
 * 异常管理
 * Author: 闵益飞
 * Date: 2018/5/24
 */

namespace Common\Exception;


class MyfErrorCode
{

    //成功
    const SUCCESS = 0;

    //未知失败
    const FAIL = 1;

    //参数错误
    const PARAM_ERROR = 2;


    //注册的用户名已经存在
    const AUTH_REGISTER_USERNAME_EXIST = 1001;
    //注册的邮箱账号已经存在
    const AUTH_REGISTER_EMAIL_EXIST = 1002;

}