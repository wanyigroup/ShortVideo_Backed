<?php

namespace app\admin\model;

use app\common\model\BaseModel;

class Area extends BaseModel
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
}
