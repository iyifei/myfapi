<?php
/**
 * 公共函数
 * Author: 闵益飞
 * Date: 2018/5/23
 */

use Common\Exception\MyfErrorCode;
use Common\Exception\MyfException;

/**
 * 获取目录下的文件名称
 * @param $dir
 * @return array
 */
function dir_files($dir) {
    $files = [];
    if(is_dir($dir)){
        $handler = opendir($dir);
        while( ($filename = readdir($handler)) !== false )
        {
            $file = $dir . '/' . $filename;
            //略过linux目录的名字为'.'和‘..'的文件
            if($filename != "." && $filename != ".." && is_file($file))
            {
                $item = [ 'file' => $file, 'name' => $filename ];
                $files[] = $item;
            }
        }
        closedir($handler);
    }
    return $files;
}

/**
 * 读取配置文件内容
 * @param string $name
 * @return null
 */
function config($name = null) {
    global $_gblConfig;
    $nameArr = explode('.', $name);
    $fName = current($nameArr);
    $res = null;
    if (isset($_gblConfig[$fName])) {
        unset($nameArr[0]);
        $res = $_gblConfig[$fName];
        foreach ($nameArr as $ne) {
            if (isset($res[$ne])) {
                $res = $res[$ne];
            } else {
                $res = null;
                break;
            }
        }
    }
    return $res;
}

/**
 * 加载配置文件
 * @param array $configFiles 配置文件数组,注意数组的先后顺序，后面的文件会覆盖前面
 * @return array
 */
function load_configs($configFiles){
    $_gblConfig=[];
    foreach ($configFiles as $iniFile) {
        if(!isset($_gblConfig)){
            $_gblConfig=[];
        }
        $file = $iniFile['file'];
        $fileArr = explode("/",$file);
        $fileName = end($fileArr);
        $fileNames = explode(".",$fileName);
        $c = count($fileNames);
        $cs = [];
        if($fileNames[$c-2]=='config'){
            unset($fileNames[$c-1]);
            unset($fileNames[$c-2]);
            $data = include $file;
            switch ($c){
                case 3:
                    $cs[$fileNames[0]]=$data;
                    break;
                case 4:
                    $cs[$fileNames[0]][$fileNames[1]]=$data;
                    break;
                case 5:
                    $cs[$fileNames[0]][$fileNames[1]][$fileNames[2]]=$data;
                    break;
            }
            $_gblConfig = array_merge_recursive($_gblConfig,$cs);
        }
    }
    return $_gblConfig;
}

/**
 * 压缩html代码
 * @param $html_source
 * @return string
 */
function compress_html($html_source) {
    return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</","//","'/\*[^*]*\*/'","/\r\n/","/\n/","/\t/",'/>[ ]+</'),
        array(">\\1<",'','','','','','><'),$html_source)));
}


/**
 * 字符串加密
 * @param string $original
 * @param string $secret 秘钥
 * @return string
 */
function encodePassword($original, $secret = 'ZqK2et5JM') {
    $encoder = md5($secret . md5(base64_encode($original . "_myf_api")));
    return $encoder;
}

/**
 * 获取纯字符串
 * @param $name
 * @return null
 */
function getUrlString($name) {
    $value = filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRIPPED);
    if ($value) {
        return trim($value);
    } else {
        return null;
    }
}


/**
 * 获取客户端IP
 * @return null
 */
function getClientIP() {
    static $ip = NULL;
    if ($ip !== NULL) {
        return $ip;
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

/**
 * GET请求
 * @param $name
 * @param null $default
 * @return string
 */
function get($name, $default = null) {
    if (isset($_GET[$name])) {
        $value = $_GET[$name];
    } else {
        $value = $default;
    }
    return trim($value);
}


/**
 * 读取POST值
 * @param $name
 * @param null $default
 * @return string
 */
function post($name, $default = null) {
    if (isset($_POST[$name])) {
        $value = $_POST[$name];
    } else {
        $value = $default;
    }
    return trim($value);
}


/**
 * 读取请求数据
 * @param $name
 * @param null $default
 * @return string
 */
function request($name, $default = null) {
    if (isset($_REQUEST[$name])) {
        $value = $_REQUEST[$name];
    } else {
        $value = $default;
    }
    return trim($value);
}


/**
 * 获取Integer变量
 * @param String $name
 * @param $default null
 * @return NULL|number
 */
function getInteger($name, $default = null) {
    if (isset($_REQUEST[$name]) && is_numeric($_REQUEST[$name])) {
        $value = intval($_REQUEST[$name]);
    } else {
        $value = $default;
    }
    return $value;
}

/**
 * 获取Double变量
 * @param String $name
 * @param $default null
 * @return NULL|number
 */
function getDouble($name, $default = null) {
    if (isset($_REQUEST[$name]) && is_numeric($_REQUEST[$name])) {
        $value = doubleval($_REQUEST[$name]);
    } else {
        $value = $default;
    }
    return $value;
}

/**
 * 接受一个email参数，并校验格式
 * @param string $name 请求参数
 * @return string
 */
function requestEmail($name){
    $email = request($name);
    if(checkEmail($email)){
        return $email;
    }else{
        MyfException::throwExp($name . " is not a email", MyfErrorCode::PARAM_ERROR);
    }
}

/**
 * 读取不为null的请求数据
 * @param string $name 请求参数
 * @return mixed
 */
function requestNotEmpty($name) {
    if (isset($_REQUEST[$name]) && !empty($_REQUEST[$name])) {
        $value = $_REQUEST[$name];
        return $value;
    } else {
        MyfException::throwExp($name . " is not empty", MyfErrorCode::PARAM_ERROR);
    }
}

/**
 * 检查手机号格式
 * @param string $mobile 搜集好
 * @return bool
 */
function checkMobile($mobile) {
    $result = filter_var($mobile, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^1[0-9]{10,10}$/')));
    if ($result) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检查邮箱格式
 * @param string $email 邮箱
 * @return bool
 */
function checkEmail($email){
    $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
    if(preg_match($pattern,$email)){
        return true;
    } else{
        return false;
    }
}

/**
 * 读取一个header的值
 * @param $name
 * @param null $headers
 * @return null
 */
function getHeader($name, $headers = null) {
    if (!isset($headers)) {
        $headers = getAllHeaders();
    }
    if (isset($headers[$name])) {
        return $headers[$name];
    } else {
        return null;
    }
}

/**
 * 读取所有的header信息
 * @return array
 */
function getAllHeaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
        }
    }
    return $headers;
}

/**
 * 获取项目基础绝对URL
 * @return string
 */
function getFullURL() {
    $pageURL = 'http://';
    $sitePath = getBasePath();
    $host = $_SERVER["HTTP_HOST"];
    $port = $_SERVER["SERVER_PORT"];
    if ($port != "80") {
        $pageURL .= $host . $sitePath;
    } else {
        $pageURL .= str_replace(":80", "", $host) . $sitePath;
    }
    return $pageURL;
}

/**
 * 获取项目基础相对URL
 * @return string
 */
function getBasePath() {
    $sitePath = dirname($_SERVER['SCRIPT_NAME']);
    if ($sitePath == "/" || $sitePath == "\\") {
        $sitePath = "";
    }
    return $sitePath;
}

/**
 * 获取项目相对url地址
 * @return string
 */
function getBaseURL() {
    return getBasePath();
}
