<?php

namespace app\api\controller;
use app\common\controller\Api;

/**
 * 示例接口.
 */
class Demo extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function test()
    {
        $this->success('返回成功', $this->request->param());
    }

}
