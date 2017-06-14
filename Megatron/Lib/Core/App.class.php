<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午3:11
 */
namespace Core;

final class App
{
    public static function run()
    {
        self::init();
        self::setUrl();
        spl_autoload_register(array(__CLASS__, 'autoLoad'));
        self::createDemo();
        self::appRun();
    }

    /**
     * 初始化框架
     */
    private static function init()
    {
        //加载配置项
        C(include CONFIG_PATH . '/config.php');
        $userConfPath = APP_CONFIG_PATH . '/config.php';
        $userConfStr  = <<<str
<?php
return array(
    //'key'=>'value'
);
?>
str;
        is_file($userConfPath) || file_put_contents($userConfPath, $userConfStr);
        C(include $userConfPath);
        //设置默认时区
        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));
        //开启session
        C('SESSION_AUTO_START') || session_start();
    }

    /**
     * 设置路径
     */
    private static function setUrl()
    {
        $path = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        $path = str_replace('\\', '/', $path);

        define('__APP__', $path);
        define('__ROOT__', dirname(__APP__));
        define('__TPL__', __ROOT__ . '/' . APP_NAME . '/Tpl');
        define('__PUBLIC__', __TPL__ . '/Public');
    }

    /**
     * 自动加载
     * @param $className
     */
    private static function autoLoad($className)
    {
        include APP_CONTROLLER_PATH.'/'.$className.'.class.php';
    }

    /**
     * 创建用户默认文件
     */
    private static function createDemo()
    {
        $path = APP_CONTROLLER_PATH.'/IndexController.class.php';

        $str = <<<str
<?php
use Core\Controller;

class IndexController extends Controller{
    public function index(){
        header('Content-type:text/html;charset=utf-8');
        echo '<h1> :) 欢迎使用Megatron框架!</h1>';
    }
}
?>
str;
        is_file($path) || file_put_contents($path, $str);
    }

    /**
     * 加载默认控制器
     */
    private static function appRun()
    {
        $c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';
        $a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';

        define('CONTROLLER', $c);
        define('ACTION', $a);

        $c .= 'Controller';
        $obj = new $c();
        $obj->$a();
    }
}
?>