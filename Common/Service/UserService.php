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
     * 创建用户
     * @param string $email 邮箱
     * @param string $username 用户名
     * @param string $password 密码
     * @return array
     */
    public function createUser($email,$username,$password){
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
            $token = $this->createNewToken($userId);
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


    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @return array
     */
    public function userLogin($username,$password){
        $user = $this->userModel->find('username',$username);
        //用户不存在或密码错误
        if(empty($user) || encodePassword($password,$user['email'])!=$user['password']){
            MyfException::throwExp(MyfErrorCode::AUTH_LOGIN_ERROR,'username or password error');
        }
        $userId = $user['id'];
        $token = $this->createNewToken($userId);
        $res = [
            'user_id'=>$userId,
            'token'=>$token,
        ];
        return $res;
    }

    /**
     * 生成一个用户的新token
     * @param int $userId 用户id
     * @return string
     */
    private function createNewToken($userId){
        /**
         * @var UserTokenService $userTokenService
         */
        $userTokenService = $this->container->make(UserTokenService::class);
        $token = $userTokenService->newTokenByUserId($userId);
        return $token;
    }
}