<?php
namespace Common\Service;

use Common\Exception\MyfErrorCode;
use Common\Exception\MyfException;
use Common\Model\UserModel;
use Illuminate\Container\Container;

/**
 * 用户管理
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class UserService
{
    /**
     * @var UserModel $userModel
     */
    private $userModel;

    /**
     * @var Container $container
     */
    private $container;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
        $this->container =  Container::getInstance();
    }

    /**
     * 注册用户
     * @param string $email 邮箱
     * @param string $username 用户名
     * @param string $password 密码
     * @return array
     */
    public function registerUser($email,$username,$password){
        //邮箱是否注册
        if($this->userModel->isExistEmail($email)){
            MyfException::throwExp(MyfErrorCode::AUTH_REGISTER_EMAIL_EXIST,'email has registered');
        }
        //用户是否注册
        if($this->userModel->isExistUsername($username)){
            MyfException::throwExp(MyfErrorCode::AUTH_REGISTER_USERNAME_EXIST,'username has registered');
        }
        //加密后的密码
        $enPassword = encodePassword($password,$email);
        $data = [
            'username'=>$username,
            'email'=>$email,
            'password'=>$enPassword,
        ];
        $this->userModel->begin();
        try{
            $userId = $this->userModel->add($data);
            //获取用户token
            /**
             * @var UserTokenService $userTokenService
             */
            $userTokenService = $this->container->make(UserTokenService::class);
            $token = $userTokenService->newTokenByUserId($userId);
            $res = [
                'user_id'=>$userId,
                'token'=>$token,
            ];
            $this->userModel->commit();
            return $res;
        }catch (\Exception $e){
            $this->userModel->rollback();
            MyfException::throwException($e);
        }
    }

}