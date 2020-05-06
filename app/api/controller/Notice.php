<?php

namespace app\api\controller;
use app\common\controller\Api;

/**
 * 公告.
 */
class Notice extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 公告
     */
    public function index()
    {
        try {
            $data = \app\common\model\Notice::where('status',1)->where('type','system')->select();
            $response = ['code'=>200,'status'=>'success','data'=> $data,'msg'=>'返回成功!'];
            return json($response);
        } catch (\Exception $e) {
            return json(['code'=>0,'status'=>'fail','data'=>'','msg'=>$e->getMessage()]);
        }
    }

}