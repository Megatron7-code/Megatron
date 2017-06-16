<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午5:50
 */
namespace Core;

class Log
{
    /**
     * 日志记录
     * @param $msg
     * @param string $level
     * @param int $type
     * @param null $dest
     */
    public static function write($msg, $level = 'ERROR', $type = 3, $dest = NULL)
    {
        if (!C('SAVE_LOG')) return;
        if (is_null($dest)) {
            $dest = LOG_PATH . '/' . date('Y-m-d') . ".log";
        }
        if (is_dir(LOG_PATH)) error_log("[TIME]: " . date('Y-m-d H:i:s') . " {$level} : {$msg}\r\n", $type, $dest);
    }
}

?>