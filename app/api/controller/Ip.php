<?php

namespace app\api\controller;
use app\common\controller\Api;
use wanyi;
//use GeoIp2\Database\Reader;

/**
 * 示例接口.
 */
class Ip extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function test()
    {
        $data = \wanyi\Geo::countrylist();
        //$data['cc'] = \wanyi\Geo::get_CC();
        var_dump($data);
        //$this->success('返回成功', $this->request->param());
    }

}