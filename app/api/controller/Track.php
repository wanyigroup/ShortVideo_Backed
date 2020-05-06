<?php
namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Config;
use think\facade\Db;

/**
 * 分析接口.
 */
class Track extends Api
{
    protected $noNeedLogin = "*";
    protected $noNeedRight = "*";

    public function index()
    {
        try {
            $data = [];
            $_vtable = 'app_track';
            if ($this->request->isPost())
            {
                $data = $this->request->param();
                $data['apkver'] = (is_numeric($data['apkver']) ? $data['apkver'] : 0);
                if(!isset($data['uuid']) && empty($data['uuid']))
                    throw new \think\Exception('UUID is Empty',0);
                if(empty(Db::table($_vtable)->where('uuid', $data['uuid'])->findOrEmpty()))
                {
                    Db::table($_vtable)->save($data);
                } else {
                    Db::table($_vtable)
                        ->where('uuid', $data['uuid'])
                        ->inc('usetime', 1)
                        ->exp('updatetime','now()')
                        ->update();
                }
            }
            $response = ['code'=>200,'status'=>'success','data'=>$data,'msg'=>'成功!'];
            return json($response);
        } catch (\Exception $e) {
            return json(['code'=>0,'status'=>'fail','data'=>'','msg'=>$e->getMessage()]);
        }
    }
}