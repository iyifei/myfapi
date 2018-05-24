<?php
/**
 * 日志处理
 * Author: 闵益飞
 * Date: 2018/5/23
 */

namespace Common\Libs;

use Monolog\Handler\StreamHandler;

/**
 * Class Logger
 * @package Common\Libs
 *
 * @method static log($level, $message, array $context = array())
 * @method static debug($message, array $context = array())
 * @method static info($message, array $context = array())
 * @method static notice($message, array $context = array())
 * @method static warn($message, array $context = array())
 * @method static warning($message, array $context = array())
 * @method static err($message, array $context = array())
 * @method static error($message, array $context = array())
 * @method static crit($message, array $context = array())
 * @method static critical($message, array $context = array())
 * @method static alert($message, array $context = array())
 *
 */
class Logger
{
    static $logger;
    static $methods=[];

    /**
     * @return \Monolog\Logger
     * @throws \Exception
     * @throws \ReflectionException
     */
    public static function getInstance(){
        if(!isset(self::$logger)){
            $logId = Utils::getLogId();
            self::$logger = new \Monolog\Logger($logId);
            $file = sprintf("%s/app.%s.log",LOG_PATH,date("Y-m-d"));
            $streamHandler = new StreamHandler($file,LOG_LEVEL);
            self::$logger->pushHandler($streamHandler);

            //反射读取logger的所有方法
            $ref = new \ReflectionClass(\Monolog\Logger::class);
            $methods = $ref->getMethods();
            foreach ($methods as $method){
                self::$methods[]=$method->name;
            }
        }
        return self::$logger;
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments) {

        $logger = self::getInstance();
        if(in_array($name,self::$methods)){
            call_user_func_array([$logger,$name],$arguments);
        }
    }

    /**
     * 获取log需要打印的header信息
     * @return array
     */
    public static function getLogHeaders(){
        $httpHeaders = getAllHeaders();
        //需要过滤的header信息
        $hideKeys = [
            'connection','content-length','origin','content-type','accept','accept-encoding','accept-language'
        ];
        foreach ($hideKeys as $val){
            unset($httpHeaders[$val]);
        }
        $usedHeaders = [
            'request_method'=>$_SERVER['REQUEST_METHOD'],
            'client_ip'=>getClientIP(),
            'request_time'=>$_SERVER['REQUEST_TIME'],
        ];
        return array_merge($usedHeaders,$httpHeaders);
    }


}