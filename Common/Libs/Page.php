<?php
namespace Common\Libs;
use Common\Exception\MyfErrorCode;

/**
 * 页面基类
 * Author: 闵益飞
 * Date: 2018/5/23
 */

abstract class Page
{

    /**
     * @var \Smarty $smarty
     */
    protected $smarty;

    /**
     * 执行前置方法，集成此类后，需要调用 parent::beforeExecute();
     */
    public function beforeExecute()
    {
        $this->initSmarty();
    }

    protected function initSmarty(){
        $this->smarty = new \Smarty();
        $this->smarty->left_delimiter = '{';
        $this->smarty->right_delimiter = '}';
        //smt缓存路径
        $smtDir = SYS_PATH.'/_runtime/smt/'.APP_NAME.'/tpl_c';
        //模板路径
        $tplDir = APP_PATH.'/Views';
        $this->smarty->setTemplateDir($tplDir)->setCompileDir($smtDir);
        $this->assign('myf_path',getBaseURL());
        $this->assign('myf_url',getFullURL());
    }

    /**
     * assigns a Smarty variable
     * @param $tpl_var
     * @param null $value
     * @param bool $nocache
     */
    public function assign($tpl_var, $value = null, $nocache = false){
        $this->smarty->assign($tpl_var,$value,$nocache);
    }


    /**
     * 渲染模板
     * @param null $template
     * @param bool $compress
     */
    public function display($template = null,$compress=false){
        $content = $this->fetch($template,$compress);
        echo $content;
    }

    /**
     * 获取渲染模板内容
     * @param string $template 模板
     * @param bool $compress
     * @return string
     */
    public function fetch($template=null,$compress=false){
        if(!isset($template)){
            $className = get_class($this);
            $names = explode('\\',$className);
            unset($names[0],$names[1]);
            $template = join('/',$names).'.html';
        }
        $content = $this->smarty->fetch($template);
        if($compress){
            $content = compress_html($content);
        }
        return $content;
    }

    /**
     * 执行入口
     * @param array $vars
     * @return mixed
     */
    abstract function execute($vars = []);


    /**
     * 直接输出json格式
     * @param array $data
     */
    public function echoJson($data){
       Response::echoJson($data);
    }

    /**
     * 输出成功记录
     * @param array|string|bool|int $data
     */
    public function successJson($data){
        $res = [
            'logid'=>Utils::getLogId(),
            'status'=>MyfErrorCode::SUCCESS,
            'data'=>$data,
        ];
        $this->echoJson($res);
    }

}