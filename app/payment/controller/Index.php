<?php
declare (strict_types = 1);

namespace app\payment\controller;

use think\Request;

use think\facade\Config;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class Index
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $OID = $this->request->param('orderid');
        if(empty($OID))
            throw new \think\Exception('请求参数错误', 0);
        $orders = Db::table('app_shop_order')->where("orderid",$OID)->find();
        View::assign($orders);
        return View::fetch('mobile');
    }

    public function status()
    {
        $OID = $this->request->param('trade_no');
        $orders = Db::table('app_shop_order')->where("orderid",$OID)->find();
        if($orders['status'] === 1 ) {
            $response = ['status' => 2, 'code' => 200, 'msg' => '支付成功!'];
        } elseif ($orders['status'] === 4 ) {
            $response = ['status' => 1, 'code' => 0, 'msg' => '支付失败!'];
        } else {
            $response = ['status' => 0, 'code' => 0, 'msg' => '等待支付!'];
        }
        return json($response);
    }

    public function callback()
    {
        return 'success';
    }

    public function notify()
    {
        if ($this->request->isPost())
        {
            $data = $this->request->param();
            $orders = Db::table('app_shop_order')->where("orderid",$data['orderid'])->find();

            if($data['status'] == 1) {

            // 更新订单
            Db::table('app_shop_order')
                ->where('orderid', $data['trade_no'])
                ->update([
                        'status' => 1,
                        'tranid' => $data['tranid'],
                        'updated_at' => date('Y-m-d h:i:s', time()),
                ]);

            // 给用户充值
            Db::table('app_user')
                ->where('user_id', $orders['uid'])
                ->inc('user_balance', $orders['amount'] * 10)
                ->update();

                return 'success';
            }

        }
    }
}
