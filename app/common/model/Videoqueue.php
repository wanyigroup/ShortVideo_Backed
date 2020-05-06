<?php

namespace app\common\model;

/**
 * 视频数据模型.
 */
class Videoqueue extends BaseModel
{
    // 表名
    protected $name = 'videoqueue';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [

    ];

    // 定义全局的查询范围
    /*
        * 如果需要动态关闭所有的全局查询范围，可以使用：
        * 关闭全局查询范围
        User::withoutGlobalScope()->select();
        可以使用withoutGlobalScope方法动态关闭部分全局查询范围。
        User::withoutGlobalScope(['status'])->select();
    */
    protected $globalScope = ['status'];
    public function scopeStatus($query)
    {
        //$query->where('status',1);
    }

}