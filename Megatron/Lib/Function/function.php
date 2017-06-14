<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午3:09
 */
function p($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

//1.加载系统配置项
//2.读取配置项
//3.临时动态改变配置项
//4.读取所有配置项
function C($var = NUll, $value = NUll)
{
    static $config = [];
    //加载配置项
    if (is_array($var)) {
        $config = array_merge($config, array_change_key_case($var, CASE_UPPER));
        return;
    }

    //读取配置项
    if (is_string($var)) {
        $var = strtoupper($var);
        if (!is_null($value)) {
            $config[$var] = $value;
            return;
        }

        return isset($config[$var]) ? $config[$var] : NULL;
    }
}
