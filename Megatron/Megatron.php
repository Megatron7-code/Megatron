<?phpuse Core\App;/** * Created by PhpStorm. * User: megatron * Date: 2017/6/14 * Time: 下午1:49 */final class Megatron{    public static function run()    {        self::setConst();        defined('DEBUG') || define('DEBUG', false);        if(DEBUG){            self::createDir();            self::importFile();        }else{            error_reporting(0);            require TEMP_PATH.'/~boot.php';        }        App::run();    }    /**     * 设置常量     */    private static function setConst()    {        $path = str_replace('\\', '/', __FILE__);        define('MEGATRON_PATH', dirname($path));        define('CONFIG_PATH', MEGATRON_PATH . '/Config');        define('DATA_PATH', MEGATRON_PATH . '/Data');        define('LIB_PATH', MEGATRON_PATH . '/Lib');        define('CORE_PATH', LIB_PATH . '/Core');        define('FUNCTION_PATH', LIB_PATH . '/Function');        define('ROOT_PATH', dirname(MEGATRON_PATH));        //临时目录        define('TEMP_PATH', ROOT_PATH . '/Temp');        //日志目录        define('LOG_PATH', TEMP_PATH . '/Log');        //应用目录        define('APP_PATH', ROOT_PATH . '/' . APP_NAME);        define('APP_CONFIG_PATH', APP_PATH . '/Config');        define('APP_CONTROLLER_PATH', APP_PATH . '/Controller');        define('APP_TPL_PATH', APP_PATH . '/Tpl');        define('APP_PUBLIC_PATH', APP_TPL_PATH . '/Public');        //公共常量        define('COMMON_PATH', ROOT_PATH.'/Common');        define('COMMON_CONFIG_PATH', COMMON_PATH.'/Config');        define('COMMON_MODEL_PATH', COMMON_PATH.'/Model');        define('COMMON_LIB_PATH', COMMON_PATH.'/Lib');        //版本号        define('DEV_VERSION', '1.0');        define('IS_POST', ($_SERVER['REQUEST_METHOD'] == 'POST' ? true : false));        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){            define('IS_AJAX', true);        }else{            define('IS_AJAX', false);        }    }    /**     * 创建目录     */    private static function createDir()    {        $dirs = [            COMMON_PATH,            COMMON_CONFIG_PATH,            COMMON_MODEL_PATH,            COMMON_LIB_PATH,            APP_PATH,            APP_CONFIG_PATH,            APP_CONTROLLER_PATH,            APP_TPL_PATH,            APP_PUBLIC_PATH,            TEMP_PATH,            LOG_PATH        ];        foreach ($dirs as $v) {            is_dir($v) || mkdir($v, 0777, true);        }        is_file(APP_TPL_PATH . '/success.html') || copy(DATA_PATH . '/Tpl/success.html', APP_TPL_PATH . '/success.html');        is_file(APP_TPL_PATH . '/error.html') || copy(DATA_PATH . '/Tpl/error.html', APP_TPL_PATH . '/error.html');    }    /**     * 导入文件     */    private static function importFile()    {        $files = [            CORE_PATH.'/Log.class.php',            FUNCTION_PATH . '/function.php',            CORE_PATH . '/Controller.class.php',            CORE_PATH . '/App.class.php',        ];        $str = '';        foreach ($files as $v) {            if(strpos($v, CORE_PATH) !== false){                $temp = file_get_contents($v);                $flag = 'namespace Core;';                $len = strlen($flag);                $num = strpos($temp, $flag) + $len;                $str .= 'namespace Core{'.trim(substr($temp, $num, -2)).'}';            }else{                $str .= 'namespace {'.trim(substr(file_get_contents($v), 5, -2)).'}';            }            require_once $v;        }        $str = "<?php\r\n".$str;        file_put_contents(TEMP_PATH.'/~boot.php', $str) || die('access not allow');    }}Megatron::run();