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

use app\upload\model\Upload as UploadModel;

use think\exception\ValidateException;

class Upload
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

    public function process()
    {
        try {
            if ($this->request->isPost())
            {
                $files = $this->request->file('file');
                $data = UploadModel::UploadProcess($files);
                // var_dump($data);
                return json(['status' => 'success', 'code' => 200, 'data'=> $data, 'msg' => '上传成功!']);
            }
        } catch (\Exception $e) {
            return json(['code' => 0,'status' => 'fail','msg' => $e->getMessage()]);
        }
    }
}
