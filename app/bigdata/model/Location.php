<?php
declare (strict_types = 1);

namespace app\bigdata\model;

use think\Model;
use think\facade\Db;

/**
 * @mixin think\Model
 */

class Location extends Model
{
    protected $table = 'market_data_location'; #数据表
    //protected $name = 'user'; #模型名称
    protected $pk = 'location_id'; #主键ID
    //protected $json = ['data_info']; #Json字段

    protected static function init()
    {

    }

    public static function get_uuid($guid)
    {
        $uuid = Db::table('market_data')->where('data_uuid', $guid)->findOrEmpty();
        return (!empty($uuid) ? $uuid['data_id'] : 0 );
    }

    public static function into($data)
    {
        $guid = $data['data_uuid'];
        $relid = self::get_uuid($guid);
        unset($data["data_info"]['uuid']);
        $arrays = $data['data_info'];
        foreach ($arrays['assistInfo'] as $k=>$v)
        {
            $arrays[$k] = $v;
        }
        unset($arrays['assistInfo']);

        $arrays = array_combine(
            array_map(function($k){ return 'location_'.$k; }, array_keys($arrays)),
            $arrays
        );

        //var_dump($arrays);
        //die();
        $location = new Location();
        $location->location_relid = $relid;
        $location->save($arrays);
        return true;
    }
}