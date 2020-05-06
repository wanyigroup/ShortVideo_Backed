<?php

namespace app\admin\model;

use app\common\model\BaseModel;


class Videocomments extends BaseModel
{
    // 表名
    protected $name = 'video_comments';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['1' => __('Status 1'), '0' => __('Status 0')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function video()
    {
        return $this->belongsTo('Video', 'vid', 'id')->joinType('LEFT');
    }

    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id')->joinType('LEFT');
    }
}
