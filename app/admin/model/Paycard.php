<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class Paycard extends BaseModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'paycard';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

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




    public function payplan()
    {
        return $this->belongsTo('Payplan', 'pid', 'id')->joinType('LEFT');
    }
}
