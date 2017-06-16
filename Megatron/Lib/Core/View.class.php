<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/16
 * Time: 下午12:57
 */
namespace Core;

class View
{
    private static $view = NULL;

    public function __construct()
    {
        if (!is_null(self::$view)) return;
        $smarty = new \Smarty();
        //模板目录
        $smarty->template_dir = APP_PATH . '/' . CONTROLLER . '/';
        //编译目录
        $smarty->compile_dir = APP_COMPILE_PATH;
        //缓存目录
        $smarty->cache_dir = APP_CACHE_PATH;
        //定界符
        $smarty->left_delimiter  = C('LEFT_DELIMITER');
        $smarty->right_delimiter = C('RIGHT_DELIMITER');
        //开启缓存
        $smarty->caching = C('CACHE_ON');
        //缓存时间
        $smarty->cache_lifetime = C('CACHE_TIME');
        self::$view             = $smarty;
    }

    /**
     * 绑定模板
     * @param $tpl
     */
    protected function display($tpl)
    {
        self::$view->display($tpl, $_SERVER['REQUEST_URI']);
    }

    /**
     * 模板赋值
     * @param $var
     * @param $value
     */
    protected function assign($var, $value)
    {
        self::$view->assign($var, $value);
    }

    /**
     * 是否开启缓存
     * @param null $tpl
     * @return mixed
     */
    protected function isCache($tpl = NULL)
    {
        if (!C('VIEW_ON')) halt('请先开启view');
        $tpl = $this->get_tpl($tpl);
        return self::$view->is_cached($tpl, $_SERVER['REQUEST_URI']);
    }
}

?>