<?php
/**
 * 公共函数
 * Author: 闵益飞
 * Date: 2018/5/23
 */

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