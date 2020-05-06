<?php
declare (strict_types = 1);

namespace app\bigdata\controller;

use think\Request;
use think\facade\Db;

use app\bigdata\model\Index as IndexModel;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Index
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return 'success';
    }

    public function uuid()
    {
        try {
            $uuid = Uuid::uuid1();
            return json(['uuid'=>$uuid]);
        } catch (UnsatisfiedDependencyException $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }

    public function create()
    {
        $data = [];
        $data['data_uuid'] = $this->request->header('uuid');
        $data['data_info'] = $this->request->param();
        //var_dump($data,$this->request->header());
        //die();

        $res = \app\bigdata\model\Index::into($data);
        if($res) {
            return json(['code'=>200,'status'=>'success','msg'=>'Created']);
        } else {
            return json(['code'=>000,'status'=>'fail','msg'=>'Error']);
        }

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id = "")
    {
        $uuid = $this->request->param('uuid');
        $dataid = Db::table('market_data')->where('data_uuid', $uuid)->value('data_id');
        $data = [];
        $data['info'] = Db::view('market_data', '*')
            ->where('data_uuid', '=', $uuid)
            ->select()->toArray();

        // Contacts
        $data['contacts'] =
        $res = Db::view('market_data', 'data_id')
            ->view('market_rel_contacts', 'contacts_id', 'market_data.data_id=market_rel_contacts.data_id')
            ->view('market_data_contacts','contacts_name,contacts_phone','market_data_contacts.contacts_id=market_rel_contacts.contacts_id')
            ->where('data_uuid', '=', $uuid)
            ->select()->toArray();

        // APK Installed
        $data['appinstalled'] = Db::view('market_data', 'data_id')
            ->view('market_rel_appinstalled', 'appinstalled_id', 'market_data.data_id=market_rel_appinstalled.data_id')
            ->view('market_data_appinstalled','appinstalled_appName,appinstalled_pkgName,appinstalled_versionName','market_data_appinstalled.appinstalled_id=market_rel_appinstalled.appinstalled_id')
            ->where('data_uuid', '=', $uuid)
            ->select()->toArray();

        // Location Ponit
        $data['location'] = Db::table('market_data_location')->where('location_relid', $dataid)->select()->toArray();

        var_dump($data);

    }


}
