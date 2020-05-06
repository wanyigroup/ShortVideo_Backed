<?php

namespace app\mobile\controller;

use app\common\controller\Frontend;
use think\App;
use think\facade\Request;

class User extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
        return json(['status' => 'fail', 'code' => 0, 'msg' => 'pong']);
    }

    public function voucher_shop()
    {
        try {
            $plan = \app\common\model\Payplan::get_PlanInfo();
            $this->assign(['list' => $plan]);
            return $this->view->fetch('user/voucher_shop');
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function voucher_exchange()
    {
        try {
            $token = Request::param('token','');
            if(empty($token))
                throw new \think\Exception('Please Login First!',0);
            $user = \app\common\library\Token::get($token);
            $uid = $user['user_id'];
            $this->assign(['uid' => $uid]);
            return $this->view->fetch('user/voucher_exchange');
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    public function charge()
    {
        var_dump($this->auth->isLogin());
        return $this->view->fetch();
    }

    public function voucher()
    {
        var_dump($this->auth->isLogin());
        return $this->view->fetch();
    }
}

