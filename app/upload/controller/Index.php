<?php
declare (strict_types = 1);

namespace app\upload\controller;

use think\App;
use think\Request;
use think\facade\Config;
use think\facade\Db;
use think\facade\Session;
use think\helper\Str;
use think\facade\View;
use think\facade\Validate;
//Use Model
//use app\model\User as UserModel;

use think\exception\ValidateException;

class Index
{
    protected $request;
    protected $app;
    protected $view;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */

    public function __construct(App $app,View $view,Request $request)
    {
        $this->app     = $app;
        $this->view    = $view;
        $this->request = $request;
    }

    //初始化配置文件
    public function initConfig()
    {

    }

    public function index()
    {


        try {
            if ($this->request->isPost())
            {
                $data = $this->request->param();
                throw new \think\Exception('用户已存在', 0);
            }

            return View::fetch("index1");

        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }


}
