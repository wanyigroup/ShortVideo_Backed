<?php

namespace addons\easysms;

use app\common\library\Sms;
use think\Addons;

/**
 * 在线命令插件
 */
class Easysms extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        return true;
    }
    /**
     * 短信发送行为
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsSend($params)
    {
        $config = require_once 'config/smsconfig.php';
        $addon_cfg = get_addon_config('easysms');
        $easySms = new library\EasySms($config);
        try {
            $result = $easySms->send($params->mobile, [
                'content'  => '您的验证码为: '.$params->code,
                'template' => $addon_cfg['template'][$params->event],
                'data' => [
                    'code' => $params->code
                ],
            ]);
        }catch (\Exception $exception){
            return false;
        }
        $is_suc = false;
        foreach ($result as $v){
            if($v['status']=='success'){
                $is_suc = true;
                break;
            }
        }
        return $is_suc;
    }

    /**
     * 短信发送通知
     * @param   array   $params
     * @return  boolean
     */
    public function smsNotice($params)
    {
        $config = require_once 'config/smsconfig.php';
        $easySms = new library\EasySms($config);
        try {
            $result = $easySms->send($params['mobile'], [
                'content' =>$params['msg'],
                'template' => $params['template'],
                'data' => isset($params['data']) ? $params['data'] : [],
            ]);
        }catch (\Exception $exception){
            return false;
        }
        $is_suc = false;
        foreach ($result as $v){
            if($v['status']=='success'){
                $is_suc = true;
                break;
            }
        }
        return $is_suc;
    }

    /**
     * 检测验证是否正确
     * @param   Sms     $params
     * @return  boolean
     */
    public function smsCheck($params)
    {
        return TRUE;
    }

}
