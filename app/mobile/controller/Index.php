<?php

namespace app\mobile\controller;

use app\common\controller\Frontend;
use think\App;

class Index extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return '';
        //return $this->view->fetch();
    }

}

