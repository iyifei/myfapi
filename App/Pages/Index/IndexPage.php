<?php
/**
 * 首页
 * Author: 闵益飞
 * Date: 2018/5/25
 */

namespace App\Pages\Index;


use App\Pages\BasePage;

class IndexPage extends BasePage
{

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    function execute($vars = [])
    {
        $this->display();
    }
}