<?php

// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'sms_send' => ['app\listener\sms_send'], //短信发送
    ],

    'subscribe' => [
    ],
];
