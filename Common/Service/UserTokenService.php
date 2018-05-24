<?php
namespace Common\Service;

use Common\Model\UserTokenModel;
use Myf\Libs\Utils;

/**
 * 用户授权token管理
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class UserTokenService
{
    /**
     * @var UserTokenModel $userTokenModel
     */
    private $userTokenModel;

    public function __construct(UserTokenModel $userTokenModel)
    {
        $this->userTokenModel = $userTokenModel;
    }

    /**
     * 创建新的token
     * @param int $userId 用户id
     * @param int $day 几天后过期，默认是30天
     * @return string
     */
    public function newTokenByUserId($userId,$day=30){
        $token = Utils::getUUID();
        $expireTime = date("Y-m-d 23:59:59",strtotime(sprintf("+%d day",$day)));
        $data = [
            'user_id'=>$userId,
            'token'=>$token,
            'expire_time'=>$expireTime,
        ];
        $this->userTokenModel->add($data);
        return $token;
    }

    /**
     * 根据token获取user_id，如果token已经过期或token不存在，返回0
     * @param string $token token信息
     * @return int
     */
    public function getUserIdByToken($token){
        $userId = 0;
        $tokenInfo = $this->userTokenModel->find('token',$token);
        if($tokenInfo && strtotime($tokenInfo['expire_time'])>time()){
            $userId = $tokenInfo['user_id'];
        }
        return $userId;
    }

}