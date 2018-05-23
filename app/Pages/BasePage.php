<?php
namespace App\Pages;
use Common\Libs\Page;

/**
 * 基础页面
 * Author: 闵益飞
 * Date: 2018/5/23
 */

abstract class BasePage extends Page
{

    //是否公开接口
    protected $declareOpenApi = false;
    //是否需要登陆,默认是需要登陆
    protected $declareNeedLogin = true;
    //是否需要签名,默认是需要签名
    protected $declareNeedSign = true;

    /**
     * 可继承重写登陆与签名状态
     */
    protected function declarations() {

    }

    //执行前处理
    public function beforeExecute() {
        parent::beforeExecute();

        //执行重置状态
        $this->declarations();

        //是否为公开接口
        if(!$this->declareOpenApi){

            //校验登录状态
            if($this->declareNeedLogin){

            }

            //校验签名
            if($this->declareNeedSign){

            }
        }
    }

}