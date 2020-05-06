<?php

namespace app\common\model;

use PhpOffice\PhpSpreadsheet\Chart\PlotArea;

/**
 * 会员升级套餐
 */
class Payplan extends BaseModel
{
    // 表名
    protected $name = 'payplan';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    // 追加属性
    protected $append = [

    ];

    // 定义全局的查询范围
    protected $globalScope = ['status'];

    public function scopeStatus($query)
    {
        $query->where('status','1');
    }

    public static function get_Planname()
    {
        $list = Payplan::where("status",'1')->column('id,title','id');
        return $list;
    }

    public static function get_PlanAmount()
    {
        $list = Payplan::select()->column('amount');
        return $list;
    }

    public static function get_PlanInfo($value = false)
    {
        $list = Payplan::select();
        return $list;
    }

    public static function get_Planvalue($value)
    {
        $list = Payplan::where('id',$value)->column('id,amount,days,freedays','id');
        return $list;
    }

}
