<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class Video extends BaseModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'video';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'feature_text',
        'status_text'
    ];
    

    
    public function getFeatureList()
    {
        return ['0' => __('Feature 0'), '1' => __('Feature 1')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2'), '4' => __('Status 4')];
    }


    public function getFeatureTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['feature']) ? $data['feature'] : '');
        $list = $this->getFeatureList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
