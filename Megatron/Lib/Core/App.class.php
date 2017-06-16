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
        //自定义错误处理
        set_error_handler(array(__CLASS__, 'error'));
        //致命错误捕获
        register_shutdown_function(array(__CLASS__, 'fatalError'));
        self::userImport();
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
        //加载公共配置项
        $commonConfPath = COMMON_CONFIG_PATH . '/config.php';
        $commonConfStr  = <<<str
<?php
return array(
    //'key'=>'value'
);
?>
str;
        is_file($commonConfPath) || file_put_contents($commonConfPath, $commonConfStr);
        C(include $commonConfPath);
        //加载用户配置项
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
        switch (true) {
            case strlen($className) > 10 && (substr($className, -10) == 'Controller'):
                $path = APP_CONTROLLER_PATH . '/' . $className . '.class.php';
                if (!is_file($path)) halt($path . '控制器未找到');
                include $path;
                break;
            case strlen($className) > 5 && (substr($className, -5) == 'Model'):
                $path = COMMON_MODEL_PATH . '/' . $className . '.class.php';
                if (!is_file($path)) halt($path . '模型未找到');
                include $path;
                break;
            default:
                //去除命名空间
                $temp = str_replace('Tool\\', '', $className);
                $path = TOOL_PATH . '/' . $temp . '.class.php';
                if (!is_file($path)) halt($path . '类未找到');
                include $path;
                break;
        }
    }

    /**
     * 创建用户默认文件
     */
    private static function createDemo()
    {
        $path = APP_CONTROLLER_PATH . '/IndexController.class.php';

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
        if (class_exists($c)) {
            $obj = new $c();
            if (method_exists($obj, $a)) {
                $obj->$a();
            } else {
                halt($c . ':' . $a . '方法不存在');
            }
        } else {
            halt($c . '控制器不存在');
        }
    }

    /**
     * 导入用户配置的公共文件
     */
    private static function userImport()
    {
        $fileArr = C('AUTO_LOAD_FILE');
        if (is_array($fileArr) && !empty($fileArr)) {
            foreach ($fileArr as $v) {
                $path = COMMON_LIB_PATH . '/' . $v;
                if (is_file($path)) {
                    require_once COMMON_LIB_PATH . '/' . $v;
                } else {
                    halt('错误的配置项:' . $path . '文件不存在');
                }
            }
        }
    }

    /**
     * 自定义错误处理
     * @param $error
     * @param $error
     * @param $file
     * @param $line
     */
    public static function error($errno, $error, $file, $line)
    {
        switch ($error) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            default:
                if (DEBUG) {
                    $e = [
                        'message' => $error,
                        'file' => $file,
                        'line' => $line,
                        'class' => CONTROLLER,
                        'function' => ACTION
                    ];
                } else {
                    if ($url = C('ERROR_URL')) {
                        go($url);
                    } else {
                        $e['message'] = C('ERROR_MSG');
                    }
                }
        }
        include DATA_PATH . '/Tpl/halt.html';
        die;
    }

    /**
     * 致命错误处理
     */
    public static function fatalError()
    {
        if ($e = error_get_last()) {
            switch ($e['type']) {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    halt($e);
                    break;
            }
        }
    }
}

?>