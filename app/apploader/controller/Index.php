<?php
declare (strict_types = 1);
namespace app\apploader\controller;

use think\Request;
use think\facade\Config;
use think\facade\Db;


class Index
{

    public function index()
    {
        return 'API';
    }

    public function config()
    {
        $_vtable = 'app_sys_config';
        $_vkey = 'id';

        $config = [];
        $config['api_name'] = "AppLoader";
        $config['api_status'] = true;

        $config['api_urls'] = [];
        $config['api_urls']['default'] = 'apiv1.afuny.com';
        $appgw = array_flip(Config::get('app.domain_bind'));

        /*
        load balancer
        $appgw[] = Db::table($_vtable)->where('name', 'app_gslb_gw1')->value('value');
        $appgw[] = Db::table($_vtable)->where('name', 'app_gslb_gw2')->value('value');
        $appgw[] = Db::table($_vtable)->where('name', 'app_gslb_gw3')->value('value');
        $appgw[] = Db::table($_vtable)->where('name', 'app_gslb_gw4')->value('value');
        $appgw[] = Db::table($_vtable)->where('name', 'app_gslb_gw5')->value('value');
        */

        $config['app_cachettl'] = 86400;  //Db::table($_vtable)->where('name', 'app_cachettl')->value('value');
        $config['app_gw_cachettl'] = 86400; // Db::table($_vtable)->where('name', 'app_gw_cachettl')->value('value');

        foreach ($appgw as $k => $v)
        {
            if(!empty($v)){
                $config['api_urls'][$k] = $v;
            }
        }

        return json($config);
    }
}
