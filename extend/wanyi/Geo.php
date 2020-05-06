<?php
namespace wanyi;

use think\App;
use think\facade\Config;
use think\Db;
use Jfcherng\IpLocation\IpLocation;
use GeoIp2\Database\Reader;

class Geo {

    //protected static $instance;
    //protected $rules = [];

    //获得用户IP地址
    public static function get_ipaddress(){
        $ipaddress = '';
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        elseif(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        elseif(isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        elseif(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '0.0.0.0';
        return $ipaddress;
    }

    // 根据IP地址获得国家
    public static function get_CC() {
        $IP = self::get_ipaddress();
        $databaseFile = root_path().'extend/DB/GeoLite2-City.mmdb';
        $reader = new Reader($databaseFile);
        $CCtmp =  $reader->get($IP);
        $CC = $CCtmp['country']['iso_code'];
        $reader->close();
        return $CC;
    }

    public static function countrylist()
    {
        $data = [];
        $IP = get_ip();
        $CC = get_CC($IP);
        $CC1 = Config::get('country');
        $CC2 = Config::get('countryzh');
        $newarr = array_merge_recursive($CC1, $CC2);
        array_unshift($newarr , $newarr[$CC]);
        $data['status'] = 'success';
        $data['data'] = $newarr;
        //var_dump($data);
        return json($data);
    }

    public function geoip()
    {
        $data = [];
        $IP = get_ip();
        $data['USER_IP'] = $IP;
        $data['USER_CC'] = get_CC($IP);

        $valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $IP);

        if($valid == 1) {
            $ipFinder = IpLocation::getInstance();
            $ipFinder->setup([
                'ipipDb' => root_path().'extend/DB/ipipfree.ipdb',
                //'ipipDb' => false,
                'cz88Db' => root_path().'extend/DB/qqwry.ipdb',
                //'cz88DbIsUtf8' => false,
            ]);
            $LOC = $ipFinder->find($IP);
            $UAINFO = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
            $logininfo = [
                "uid" => 1,
                "utime" => time(),
                "uip" => $IP,
                "uloc" => implode(',',$LOC),
                "ua" => $UAINFO
            ];
            //var_dump($logininfo);
            $data['login'] = $logininfo;
            //@$db->insert ('users_loginhistory', $logininfo);
        }
        return var_dump($data);
    }



}