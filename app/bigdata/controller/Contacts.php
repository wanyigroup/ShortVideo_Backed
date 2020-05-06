<?php
declare (strict_types = 1);
namespace app\bigdata\controller;

use think\Request;
use think\facade\Db;

use app\bigdata\model\Contacts as ContactsModel;

class Contacts
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

    public function create()
    {
        $data = [];
        $data['data_uuid'] = $this->request->header('uuid');
        $data['data_contacts'] = $this->request->param();
        //var_dump($data);
        //var_dump();
        //die();
        $res = ContactsModel::into($data);
        if($res) {
            return 'success';
        } else {
            return 'fail';
        }
    }

}
