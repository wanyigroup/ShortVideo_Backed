<?php

namespace app\common\model;

/**
 * 卡密使用日志模型.
 */
class Paycardlog extends BaseModel
{
    // 表名
    protected $name = 'paycardlog';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    // 追加属性
    protected $append = [
    ];
}