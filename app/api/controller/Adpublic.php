<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 广告接口.
 */
class Adpublic extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function index()
    {
        return 'Adv Servers';
    }

    public function adappboot(Request $request)
    {
        //$_vtable = 'app_sys_config';
        //$_vkey = 'id';
        $config = [
            'adv_id' => 1,
            'adv_type' => 'img',
            'adv_img' => 'http://apiv1.afuny.com/resource/advimg/appload1.jpg',
            'adv_jump' => 'http://www.baidu.com',
            'adv_skiptime' => 5,
            'adv_status' => 1,
        ];
        return json($config);
    }

    public function adimg(Request $request)
    {
        //$_vtable = 'app_sys_config';
        //$_vkey = 'id';
        $config = [
            'adv_id' => 1,
            'adv_type' => 'img',
            'adv_img' => 'http://apiv1.afuny.com/resource/advimg/300x250.png',
            'adv_jump' => 'http://www.baidu.com',
            'adv_status' => 1,
        ];
        return json($config);
    }

    public function adtxt(Request $request)
    {
        //$_vtable = 'app_sys_config';
        //$_vkey = 'id';
        $config = [
            'adv_id' => 1,
            'adv_type' => 'text',
            'adv_img' => 'http://apiv1.afuny.com/resource/advimg/appload1.jpg',
            'adv_txt' => '震惊,是谁在半夜听墙角......',
            'adv_jump' => 'http://www.baidu.com',
            'adv_status' => 1,
        ];
        return json($config);
    }

}