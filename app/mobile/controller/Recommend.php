<?php
namespace app\mobile\controller;
use app\common\controller\Frontend;
use think\App;

class Recommend extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return 'app推荐';
        //return $this->view->fetch();
    }
}