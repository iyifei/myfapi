<?php
/**
 * 核心类
 * Author: 闵益飞
 * Date: 2018/5/23
 */
//定义日志
use Common\Libs\Page;
use Common\Libs\Response;
use Common\Libs\Utils;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Illuminate\Container\Container;
use Monolog\Logger;

//判断是否为ajax请求
define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax']) || isset($_SERVER['HTTP_AJAX'])) ? true : false);
define('LOG_PATH',sprintf('%s/_logs/%s',SYS_PATH,APP_NAME));
define('LOG_LEVEL',Logger::DEBUG);
//读取配置文件
$iniFiles = @dir_files(SYS_PATH . '/configs');
$iniOpFiles = @dir_files(OP_CONF_DIR);
$iniFiles = array_merge($iniFiles,$iniOpFiles);
global $_gblConfig;
global $_gblConfig;
$_gblConfig=load_configs($iniFiles);

//初始化容器
$container = Container::getInstance();

//设置路由
$dispatcher = simpleDispatcher(function(RouteCollector $r) {

    $routes = config('route');
    foreach ($routes as $route){
        $r->addRoute($route['method'], $route['route'], $route['handler']);
    }
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

try{
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    \Common\Libs\Logger::debug('core.php routeInfo',$routeInfo);
    \Common\Libs\Logger::debug('core.php request',$_REQUEST);
    switch ($routeInfo[0]) {
        case Dispatcher::NOT_FOUND:
            // ... 404 Not Found
            http_response_code(404);
            break;
        case Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            http_response_code(405);
            break;
        case Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            // ... call $handler with $vars
            $container->bind(Page::class,$handler);
            /**
             * @var Page $class
             */
            $class = $container->make(Page::class);
            //执行前置函数
            $class->beforeExecute();
            //执行运行函数
            $class->execute($vars);
            break;
    }
}catch (\Exception $e){
    $code = $e->getCode();
    $error = $e->getMessage();
    $logid = Utils::getLogId();
    if(IS_AJAX){
        $result = [
            'logid'=>$logid,
            'status' => $code,
            'errmsg' => $error,
        ];
        Response::echoJson($result);
    }else{
        echo sprintf("出错了！logid=【%s】,code=【%d】,error=【%s】",$logid,$code,$error);
    }
}