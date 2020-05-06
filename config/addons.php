<?php

return [
    'autoload' => false,
    'hooks' => [
        'sms_send' => [
            0 => 'easysms',
        ],
        'sms_notice' => [
            0 => 'easysms',
        ],
        'sms_check' => [
            0 => 'easysms',
        ],
        'ems_send' => [
            0 => 'faems',
        ],
        'ems_notice' => [
            0 => 'faems',
        ],
        'admin_login_init' => [
            0 => 'loginbg',
        ],
    ],
    'route' => [
        '/example$' => 'example/index/index',
        '/example/d/[:name]' => 'example/demo/index',
        '/example/d1/[:name]' => 'example/demo/demo1',
        '/example/d2/[:name]' => 'example/demo/demo2',
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
    ],
    'service' => [
    ],
];