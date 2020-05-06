<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class Versions extends BaseModel
{

    use SoftDelete;

    

    // 表名
    protected $name = 'versions';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'type_text',
        'update_mode_text',
        'status_text'
    ];
    

    
    public function getTypeList()
    {
        return ['android' => __('Android'), 'ios' => __('Ios')];
    }

    public function getUpdateModeList()
    {
        return ['0' => __('Update_mode 0'), '1' => __('Update_mode 1')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getUpdateModeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['update_mode']) ? $data['update_mode'] : '');
        $list = $this->getUpdateModeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
