<?php
declare (strict_types = 1);

namespace app\bigdata\controller;

use think\Request;
use think\facade\Db;

use app\bigdata\model\Location as LocationModel;

class Location
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function create()
    {
        $data = [];
        $data['data_uuid'] = $this->request->header('uuid');
        $data['data_info'] = $this->request->param();

        $res = LocationModel::into($data);
        if($res) {
            return 'success';
        } else {
            return 'fail';
        }


    }

}
