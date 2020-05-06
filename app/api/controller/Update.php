<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Config;
//use think\facade\Db;
//use think\Request;

/**
 * 更新接口.
 */
class Update extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function index()
    {
        return "APK Update Servers!";
    }

    public function check_version()
    {
        $platform = $this->request->request('platform', 'android', 'trim,strip_tags,htmlspecialchars');
        $data = \app\common\model\Versions::where('status',1)
            ->where('type',$platform)
            ->order('id','desc')
            ->find();
        return json($data);
    }

    public function latest_version()
    {
        // TODO 添加统计
        // $downloadurl = Config::get('app.app_host');
        $downloadurl = \think\facade\Request::domain();
        return redirect($downloadurl."/latestapk/Smallfish_build_v1.0.1.apk");
    }

}