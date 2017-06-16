<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午4:17
 */
namespace Core;

class Controller extends View
{
    private $var = [];

    public function __construct()
    {
        parent::__construct();
        if (method_exists($this, '__init')) {
            $this->__init();
        }
    }

    /**
     * 成功跳转
     * @param $msg
     * @param null $url
     * @param int $time
     */
    protected function success($msg, $url = NULL, $time = 3)
    {
        if (strpos('http://', $url) === false) {
            $url = 'http://' . $url;
        }
        include APP_TPL_PATH . '/success.html';
    }

    /**
     * 错误跳转
     * @param $msg
     * @param null $url
     * @param int $time
     */
    protected function error($msg, $url = NULL, $time = 3)
    {
        if (strpos('http://', $url) === false) {
            $url = 'http://' . $url;
        }
        include APP_TPL_PATH . '/error.html';
    }

    /**
     * 获取模板
     * @return string
     */
    protected function getTpl()
    {
        if (empty($tpl)) {
            $path = APP_TPL_PATH . '/' . CONTROLLER . '/' . ACTION . '.html';
        } else {
            $suffix = strrchr($tpl, '.');
            $tpl    = empty($suffix) ? $tpl . '.html' : $tpl;
            $path   = APP_TPL_PATH . '/' . CONTROLLER . '/' . $tpl;
        }
        return $path;
    }

    /**
     * 绑定模板
     * @param null $tpl
     */
    protected function display($tpl = NULL)
    {
        $path = $this->getTpl();
        if (!is_file($path)) halt($path . '模板文件不存在');
        if (C('VIEW_ON')) {
            parent::display($path);
        } else {
            extract($this->var);
            include $path;
        }
    }

    /**
     * 绑定变量
     * @param $var
     * @param $value
     */
    protected function assign($var, $value)
    {
        if (C('VIEW_ON')) {
            parent::assign($var, $value);
        } else {
            $this->var[$var] = $value;
        }
    }
}

?>