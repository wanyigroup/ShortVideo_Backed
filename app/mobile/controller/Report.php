<?php
namespace app\mobile\controller;
use app\common\controller\Frontend;
use think\App;

class Report extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return '故障上报';
        //return $this->view->fetch();
    }
}