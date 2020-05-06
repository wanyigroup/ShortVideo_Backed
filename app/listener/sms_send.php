<?php
declare (strict_types = 1);

namespace app\listener;
use think\facade\Config;
/**
 * Class sms_send 短信发送类
 * @package app\listener
 */

class sms_send
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($sms)
    {

        $appid = Config::get('sms.submail.appid');
        $appkey = Config::get('sms.submail.appkey');
        $signature = Config::get('sms.submail.signature');

        $phone = $sms->mobile;
        $code = $sms->code;
        $api = 'https://api.submail.cn/message/send.json';
        $data = "【玖恬科技】您的验证码是 $code , 有效期5分钟!";
        $cmd = "curl -d 'appid=000000&to=$phone&content=$data&signature=000000' https://api.submail.cn/message/send.json";
        $output = shell_exec($cmd);
        $data = json_decode($output,true);
        if($data['status'] == 'success')
        {
            return true;
        } else {
            return false;
        }

    }    
}
