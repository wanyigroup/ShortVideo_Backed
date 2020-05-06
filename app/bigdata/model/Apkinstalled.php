<?php
declare (strict_types = 1);

namespace app\bigdata\model;

use think\Model;
use think\facade\Db;

/**
 * @mixin think\Model
 */
class Apkinstalled extends Model
{
    protected $table = 'market_data_appinstalled'; #数据表
    protected $pk = 'appinstalled_id'; #主键ID

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
        unset($data["data_appinstalled"]['uuid']);
        $lists = [];
        $arrays = $data['data_appinstalled'];
        foreach ($arrays as $array)
        {
            $array = array_combine(
                array_map(function($k){ return 'appinstalled_'.$k; }, array_keys($array)),
                $array
            );
            array_push($lists,$array);
        }

        //var_dump($lists);

        $Appinstalled = new Apkinstalled;
        $obj = $Appinstalled->saveAll($lists);
        foreach($obj->toArray() as $k => $v )
        {
            $AID = $v['appinstalled_id'];
            $res =
                Db::table('market_rel_appinstalled')
                    ->where('appinstalled_id', $AID)
                    ->where('data_id',$relid)
                    ->select()
                    ->count();

            if($res === 0) {
                $RELTMP = [
                    'data_id' => $relid,
                    'appinstalled_id' => $AID
                ];
            } else {
                $RELTMP = [];
            }
            $RELID[] = $RELTMP;
        }
        if(!empty($RELID)) {
            Db::table('market_rel_appinstalled')->replace()->insertAll($RELID);
        }
        return true;
    }

}
