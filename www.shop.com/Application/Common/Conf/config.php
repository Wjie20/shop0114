<?php
return array(
    //'配置项'=>'配置值'
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'shop', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => '123456', // 密码
    'DB_PORT' => '', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_PARAMS' => array(), // 数据库连接参数
    'DB_DEBUG' => true, // 数据库调试模式 开启后可以记录SQL日志

    //阿里大鱼发送短信的配置
    'SMS_CONFIG' => array(
        'appkey' => '23302808',  //APP_KEY
        'secretKey' => '866a0f29b7c81e1c3c7eb4295dad8119'  //安全码
    ),

    //163网易邮箱服务器 发送邮件
    'MAIL_CONFIG' => array(
        'Host' => 'smtp.163.com',  //邮件服务器地址
        'Username' => '18758546284@163.com',  //安全码
        'Password' => 'wj759445818',
        'From' => '18758546284@163.com',
    )
);