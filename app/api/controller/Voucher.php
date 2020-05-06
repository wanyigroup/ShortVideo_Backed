<?php
namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Config;
use think\facade\Db;

/**
 * 分析接口.
 */
class Voucher extends Api
{
    protected $noNeedLogin = "*";
    protected $noNeedRight = "*";

    public function index()
    {
            $data = [];
            $_vtable = 'app_paycard';
            if ($this->request->isPost())
            {
                // Check Token
                $check = $this->request->checkToken('__token__');
                if(false === $check) {
                    throw new \think\Exception('令牌验证失败,请刷新本页面后重试invalid token',0);
                }
                $data = $this->request->param();

                // 查询卡号是不是存在
                $querycard = Db::table($_vtable)->where('cardnum','=', $data['cardnum'])->where('status','=','0')->findOrEmpty();
                //var_dump($querycard);
                if(empty($querycard))
                    throw new \think\Exception('卡密不存在或已被使用!,Card number does not exist or has used1',0);

                // 添加VIP的天数
                $user = \app\admin\model\User::find($data['uid']);
                $plan = $querycard['pid'];
                $info =  \app\common\model\Payplan::get_Planvalue($plan);
                $vipday = $info[$plan]['days'] + $info[$plan]['freedays'];
                $user->vip = 1;
                if(strtotime($user->vipdate) < strtotime(date("Y-m-d"))) {
                    $user->vipdate = date('Y-m-d', strtotime('+ '.$vipday.' days',strtotime(date('Y-m-d'))));
                } else {
                    $user->vipdate = date('Y-m-d', strtotime('+ '.$vipday.' days',strtotime($user->vipdate)));
                }
                $user->save();

                // 更改卡密状态为已使用
                Db::table($_vtable)
                    ->where('id',$querycard['id'])
                    ->update(['status' => '1', 'uid' => $data['uid'],'updatetime' => Db::raw('NOW()') ]);

                // 记录变动
                $cardlog = \app\common\model\Paycardlog::create([
                    'uid' => $data['uid'],
                    'tranid' =>  $data['cardnum'],
                    'amount' => $info[$plan]['amount'],
                    'usetime' => Db::raw('NOW()'),
                ]);
            }
            $response = ['code'=>200,'status'=>'success','data'=>$data,'msg'=>'成功!'];
            return json($response);
            /*
        try {
        } catch (\Exception $e) {
            return json(['code'=>0,'status'=>'fail','data'=>'','msg'=>$e->getMessage()]);
        }
            */
    }


}