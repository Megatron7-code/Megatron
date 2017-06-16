<?php
/**
 * Created by PhpStorm.
 * User: megatron
 * Date: 2017/6/14
 * Time: 下午3:22
 */
return array(
    // 'key' => 'value'
    'CODE_LEN'=>4,
    //默认时区
    'DEFAULT_TIME_ZONE'=>'PRC',
    //开启session
    'SESSION_AUTO_START'=>true,
    'VAR_ACTION'=>'a',
    'VAR_CONTROLLER'=>'c',
    //开启日志
    'SAVE_LOG'=>true,
    //错误跳转的地址
    'ERROR_URL'=>'',
    //错误提示信息
    'ERROR_MSG'=>'出错了啦',
    //自动加载Common/Lib目录下的文件
    'AUTO_LOAD_FILE'=>array(),
    //数据库配置
    'DB_CHARSET'=>'utf8',
    'DB_HOST'=>'127.0.0.1',
    'DB_PORT'=>3306,
    'DB_USER'=>'root',
    'DB_PASSWORD'=>'',
    'DB_DATABASE'=>'',
    'DB_PREFIX'=>'',
    //view配置
    'VIEW_ON'=>false,
    'LEFT_DELIMITER'=>'{',
    'RIGHT_DELIMITER'=>'}',
    'CACHE_ON'=>false,
    'CACHE_TIME'=>60
);