<?php

use Common\Libs\Utils;
use Common\Model\UserModel;
use Common\Model\UserTokenModel;
use Common\Service\UserService;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

/**
 * 用户服务单元测试
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class UserServiceTest extends TestCase
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * @var UserModel $userModel
     */
    private $userModel;

    /**
     * @var UserTokenModel $userTokenModel
     */
    private $userTokenModel;

    //初始化
    private function init(){
        $container = Container::getInstance();
        $this->userService = $container->make(UserService::class);
        $this->userModel = $container->make(UserModel::class);
        $this->userTokenModel = $container->make(UserTokenModel::class);
    }

    public function testRegisterUser(){
        $this->init();

        $uuid = Utils::getUUID();
        $mail = sprintf('%s@test.com',$uuid);
        $username = $uuid;
        $password = $uuid;
        //正常情况测试
        $data = $this->userService->registerUser($mail,$username,$password);
        $userId = $data['user_id'];
        $user = $this->userModel->findById($userId);
        $this->assertEquals($user['username'],$uuid);
        //删除记录
        $this->userModel->deleteById($userId);
        $row = $this->userModel->findById($userId);
        $this->assertEmpty($row);
        //删除对应的token
        $rowCount = $this->userTokenModel->where('user_id=:userId',['userId'=>$userId])->delete();
        $this->assertTrue($rowCount>0);
    }

}