<?php
namespace Common\Model;

use Myf\Database\Mysql\MysqlModel;

/**
 * Model基类
 * Author: 闵益飞
 * Date: 2018/5/23
 */

class Model extends MysqlModel
{

    /**
     * 获取数据库配置文件
     * @return mixed
     */
    public function getDbConfig()
    {
        $databaseName = $this->getDatabaseName();
        $mysqlConfig = config("mysql");
        return $mysqlConfig['database'][$databaseName];
    }

    /**
     * 获取数据库名称
     * @return mixed|null
     */
    public function getDatabaseName()
    {
        return config('mysql.default');
    }
}