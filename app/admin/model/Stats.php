<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\facade\Db;

class Stats extends BaseModel
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    //自定义日志标题
    //protected static $title = '';
    //自定义日志内容
    //protected static $content = '';

    public static function get_TotalUsers()
    {
        $data = [];
        $data['total_users'] = Db::table('app_user')->count();
        $data['total_vipuser'] = Db::table('app_user')->where('vip',1)->count();
        $data['total_freeuser'] = $data['total_users'] - $data['total_vipuser'];
        $data['total_percentage'] = round($data['total_users'] / $data['total_vipuser'] *100)."％";
        return $data;
    }

    public static function get_TotalClip()
    {
        $data = [];
        $data['total_video'] = Db::table('app_video')->count('id');
        $data['total_comments'] = Db::table('app_video_comments')->count('id');
        $data['total_queue'] = Db::table('app_videoqueue')->where('status','0')->count('id');
        return $data;
    }

    public static function get_TotalOrder()
    {
        $data = [];
        $data['total_order'] = Db::table('app_paycardlog')->count('id');
        return $data['total_order'];
    }

    public static function get_TotalAmount()
    {
        $data = [];
        $data['total_amount'] = Db::table('app_paycardlog')->sum('amount');
        return $data['total_amount'];
    }

    public static function get_apptrack()
    {
        $res = Db::query("SELECT DATE_FORMAT(createtime, '%Y/%m/%d') AS Dates, count(*) as count
   FROM app_track
  WHERE createtime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
Group by
  Dates
  ORDER BY Dates ASC");

        return $res;
    }

    public static function get_UserStats()
    {
        $data = [];
        $data['today_signup'] = Db::table('app_user')->whereDay('jointime')->count('id');  //今天注册
        $data['today_signin'] = Db::table('app_user')->whereDay('logintime')->count('id');;  //今天登陆
        $data['today_ordered'] = Db::table('app_paytran')->whereDay('created_at')->count('id'); //今日订单
        $data['today_waitreview'] = Db::table('app_paytran')->where('status',0)->whereDay('created_at')->count('id');; //待处理的订单

        //$data['cur_today'] = Db::table('app_user')->whereDay('create_time')->select(); //当天 //昨天->whereDay('create_time', 'yesterday')
        //$data['cur_week'] = Db::table('app_user')->whereWeek('create_time')->select();
        return $data;
    }


}





