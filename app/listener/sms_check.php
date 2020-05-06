<?php
declare (strict_types = 1);

namespace app\listener;

use think\facade\Config;
/**
 * Class sms_send 短信发送类
 * @package app\listener
 */

class sms_check
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($sms)
    {
        return true;
    }
}