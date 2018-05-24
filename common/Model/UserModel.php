<?php
/**
 * 用户
 * Author: 闵益飞
 * Date: 2018/5/24
 */

namespace Common\Model;


class UserModel extends Model
{

    /**
     * 邮箱是否已经注册
     * @param string $email 邮箱
     * @return bool
     */
    public function isExistEmail($email){
        $count = $this->where('email=:email',['email'=>$email])->count();
        return $count>0?true:false;
    }

    /**
     * 用户名称是否已经注册
     * @param string $username 用户名
     * @return bool
     */
    public function isExistUsername($username){
        $count = $this->where('username=:username',['username'=>$username])->count();
        return $count>0?true:false;
    }

}