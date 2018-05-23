<?php
namespace App\Pages\User;

use App\Pages\BasePage;
use Common\Libs\Logger;
use Common\Model\UserModel;
use Myf\Libs\Log;

/**
 * 获取用户信息
 * Author: 闵益飞
 * Date: 2018/5/23
 */

class GetUserInfoPage extends BasePage
{

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    function execute($vars = [])
    {
        $id = intval($vars['id']);
        $userModel = new UserModel();
        $user = $userModel->findById($id);
        Log::debug("user",$user);
        Logger::debug("user",$user);
        echo json_encode($user);
    }
}