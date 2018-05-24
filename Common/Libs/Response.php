<?php
/**
 * 输出
 * Author: 闵益飞
 * Date: 2018/5/24
 */

namespace Common\Libs;


class Response
{

    /**
     * 输出json
     * @param array $data
     */
    public static function echoJson($data){
        Logger::debug('Response.php response',$data);
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

}