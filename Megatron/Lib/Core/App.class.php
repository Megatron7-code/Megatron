<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午3:11
 */
namespace App;

final class App
{
    public static function run()
    {
        self::init();
        self::setUrl();
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
}