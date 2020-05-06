<?php
declare (strict_types = 1);

namespace app\bigdata\controller;

use think\facade\Db;
use think\Request;
use app\bigdata\model\Apkinstalled as ApkinstalledModel;

class Apkinstalled
{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

        /*
        $AID = 888;
        $RELID = 1 ;
        $res = Db::table('market_rel_appinstalled')
            ->where('appinstalled_id', $AID)
            ->where('data_id',$RELID)
            ->select()->count();
        //->findOrEmpty();
        var_dump($res);
        */




    }

    public function create()
    {
        $data = [];
        $data['data_uuid'] = $this->request->header('uuid');
        $data['data_appinstalled'] = $this->request->param();
        //$this->request->getInput();
        //var_dump($data);
        //die();
        $res = ApkinstalledModel::into($data);
        if($res) {
            return 'success';
        } else {
            return 'fail';
        }
    }

}
