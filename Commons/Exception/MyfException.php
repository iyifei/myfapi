<?php
namespace Common\Exception;
/**
 * 异常
 * Author: 闵益飞
 * Date: 2018/5/24
 */

class MyfException extends \RuntimeException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function throwException($e){
        self::throwExp($e->getCode(),$e->getMessage());
    }

    /**
     * 抛出异常
     * @param $code
     * @param string $msg
     * @throws MyfException
     */
    public static function throwExp($code,$msg=''){
        throw new MyfException($msg,$code);
    }

}