<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 开机广告.
 */
class Appbootadv extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function index()
    {
        //$_vtable = 'os_app_config';
        //$_vkey = 'id';
        $config = [
            'adv_id' => 1,
            'adv_type' => 'img',
            'adv_img' => 'https://apiv1.afuny.com/ads/appboot.jpg',
            'adv_jump' => 'https://www.baidu.com',
            'adv_status' => 1,
        ];
        return json($config);
    }

}