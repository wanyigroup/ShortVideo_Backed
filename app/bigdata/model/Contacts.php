<?php
declare (strict_types = 1);

namespace app\bigdata\model;

use think\Model;
use think\facade\Db;


/**
 * @mixin think\Model
 */
class Contacts extends Model
{
    protected $table = 'market_data_contacts'; #数据表
    protected $pk = 'contacts_id'; #主键ID

    public static function get_uuid($guid)
    {
        $uuid = Db::table('market_data')->where('data_uuid', $guid)->findOrEmpty();
        return (!empty($uuid) ? $uuid['data_id'] : 0 );
    }

    public static function into($data)
    {
        $guid = $data['data_uuid'];
        $relid = self::get_uuid($guid);
        $RELID = [];

        unset($data["data_contacts"]['uuid']);

        $lists = [];
        $arrays = $data['data_contacts'];
        foreach ($arrays as $array)
        {
            $array = array_combine(
                array_map(function($k){ return 'contacts_'.$k; }, array_keys($array)),
                $array
            );
            array_push($lists,$array);
        }
        //var_dump($lists);

        $Contacts = new Contacts;
        $obj = $Contacts->saveAll($lists);
        //var_dump($obj);
        //die();
        //$data['objnum'] = count($obj);
        foreach($obj->toArray() as $k => $v )
        {
            $ID = $v['contacts_id'];
            $RELID[] = [
                'data_id' => $relid,
                'contacts_id' => $ID
            ];
        }
        //var_dump($RELID);
        Db::table('market_rel_contacts')->replace()->insertAll($RELID);
        return true;
    }
}