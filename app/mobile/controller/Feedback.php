<?php
namespace app\mobile\controller;
use app\common\controller\Frontend;
use think\App;

class Feedback extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return '意见收集';
        //return $this->view->fetch();
    }
}