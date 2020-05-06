<?php
declare (strict_types = 1);

namespace app\bigdata\model;

use think\Model;

/**
 * @mixin think\Model
 */
class Index extends Model
{
    protected $table = 'market_data'; #数据表
    //protected $name = 'user'; #模型名称
    protected $pk = 'data_id'; #主键ID
    //protected $json = ['data_info']; #Json字段

    protected static function init()
    {

    }

    public static function into($data)
    {
        $uuid = $data['data_uuid'];
        // 判断UUID是否存在
        $check = self::where('data_uuid',$uuid)->field('data_uuid')->findOrEmpty();
        // UUID不存在 新增
        if ($check->isEmpty()) {
            $array = $data['data_info'];
            $array = array_combine(
                array_map(function($k){ return 'data_'.$k; }, array_keys($array)),
                $array
            );
            $index = new Index;
            $index->data_uuid = $uuid;
            $index->save($array);
            return true;
        } else {
            return false;
        }
    }
}
