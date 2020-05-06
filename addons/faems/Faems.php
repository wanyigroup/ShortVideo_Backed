<?php

namespace addons\faems;

use app\common\library\Email;
use think\Addons;

/**
 * 邮件发送插件
 */
class Faems extends Addons
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
     * 当发送邮件时
     * @return mixed
     */
    public function emsSend($params)
    {
        $email = new Email();
        $result = $email->to($params['email'])
            ->subject('请查收你的验证码')
            ->message('你的验证码是:' . $params['code'])
            ->send();
        return $result;
    }

    /**
     * 发送通知
     * @param $params
     * @return bool
     */
    public function emsNotice($params)
    {
        $subject = '你收到一封新的邮件！';
        $content = $params['msg'];
        $email = new Email();
        $result = $email->to($params['email'])
            ->subject($subject)
            ->message($content)
            ->send();
        return $result;

    }

}
