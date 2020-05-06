<?php

namespace addons\address\controller;

use think\addons\Controller;
use think\facade\Config;
use think\facade\Lang;
use think\facade\View;

class Index extends Controller
{

    public function index()
    {
        // 语言检测
        $lang = strip_tags(Lang::getLangset());

        $site = Config::get("site");

        // 配置信息
        $config = [
            'site'           => array_intersect_key($site, array_flip(['name', 'cdnurl', 'version', 'timezone', 'languages'])),
            'upload'         => null,
            'modulename'     => 'addons',
            'controllername' => 'index',
            'actionname'     => 'index',
            'jsname'         => 'addons/address',
            'moduleurl'      => '',
            'language'       => $lang,
        ];
        $config = array_merge($config, Config::get("view_replace_str"));
        // 配置信息后
        hook("config_init", $config);
        // 加载当前控制器语言包
        $this->view->assign('site', $site);
        $this->view->assign('config', $config);
        return $this->view->fetch();
    }

    /**
     * 选择地址
     * @return string
     * @throws \think\Exception
     */
    public function select()
    {
        $config = get_addon_config('address');
        $lat = $this->request->get('lat', $config['lat']);
        $lng = $this->request->get('lng', $config['lng']);
        $this->view->assign('lat', $lat);
        $this->view->assign('lng', $lng);
        $this->view->assign('location', $config['location']);
        return $this->view->fetch('index/' . $config['maptype']);
    }

}
