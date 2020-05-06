<?php
// 公共助手函数
use think\Model;
use think\facade\Config;
use think\facade\Db;
use think\facade\Event;
use think\facade\Lang;

use GeoIp2\Database\Reader;


// 清理标题的特殊字符串
function clean_strstr($valor) {
    $valor = str_ireplace("Chinese homemade video","",$valor);
    $valor = str_ireplace('PornOnion.com','Girls Do Porn Part',$valor);
    $valor = str_ireplace("【JPTG】","",$valor);
    $valor = str_ireplace("【","",$valor);
    $valor = str_ireplace("ed_","",$valor);
    $valor = str_ireplace("]","",$valor);
    $valor = str_ireplace("[","",$valor);
    $valor = str_ireplace("（","",$valor);
    $valor = str_ireplace("）","",$valor);
    $valor = str_ireplace("-","",$valor);
    $valor = str_ireplace("_","",$valor);
    $valor = str_ireplace("】","",$valor);
    $valor = str_ireplace("。","",$valor);
    $valor = str_ireplace(".","",$valor);
    $valor = str_ireplace("...","",$valor);
    $valor = str_ireplace("(","",$valor);
    $valor = str_ireplace(")","",$valor);
    $valor = str_ireplace("!","",$valor);
    $valor = str_ireplace("¡","",$valor);
    $valor = str_ireplace("?","",$valor);
    $valor = str_ireplace("=","",$valor);
    $valor = str_ireplace("&","",$valor);
    $valor = str_ireplace("xvid","",$valor);
    $valor = str_replace('h264','',$valor);
    $valor = str_ireplace("�"," ",$valor);
    $valor = str_ireplace("KH"," ",$valor);
    $valor = str_replace(PHP_EOL, '', $valor);
    return $valor;
}

/**
 * 将多个一维数组合拼成二维数组
 *
 * @param  Array $keys 定义新二维数组的键值，每个对应一个一维数组
 * @param  Array $args 多个一维数组集合
 * @return Array
 */
function array_merge_more($keys, ...$arrs){
    // 检查参数是否正确
    if(!$keys || !is_array($keys) || !$arrs || !is_array($arrs) || count($keys)!=count($arrs)){
        return array();
    }
    // 一维数组中最大长度
    $max_len = 0;
    // 整理数据，把所有一维数组转重新索引
    for($i=0,$len=count($arrs); $i<$len; $i++){
        $arrs[$i] = array_values($arrs[$i]);

        if(count($arrs[$i])>$max_len){
            $max_len = count($arrs[$i]);
        }
    }
    // 合拼数据
    $result = array();
    for($i=0; $i<$max_len; $i++){
        $tmp = array();
        foreach($keys as $k=>$v){
            if(isset($arrs[$k][$i])){
                $tmp[$v] = $arrs[$k][$i];
            }
        }
        $result[] = $tmp;
    }
    return $result;
}

// 获得总计评论
function get_comment_count($vid)
{
    $value = Db::table('app_video_comments')->where('vid',$vid)->count("id");
    //var_dump($value);
    return $value;
}

// 数字格式化
function float_number($number){
    $length = strlen($number);  //数字长度
    if($length > 8){ //亿单位
        $str = substr_replace(strstr($number,substr($number,-7),' '),'.',-1,0)."b";
    }elseif($length >4){ //万单位
        $str = substr_replace(strstr($number,substr($number,-3),' '),'.',-1,0)."w";
    //}elseif($length >3){ //千单位//
        //$str = substr_replace(strstr($number,substr($number,-2),' '),'.',-1,0)."k";
    }else{
        return $number;
    }
    return $str;
}

// 生成卡号
function Gen_CardNumber($start=0,$end=9,$length=10){
    //初始化变量为0
    $connt = 0;
    //建一个新数组
    $temp = array();
    while($connt < $length){
        //在一定范围内随机生成一个数放入数组中
        $temp[] = mt_rand($start, $end);
        //$data = array_unique($temp);
        //去除数组中的重复值用了“翻翻法”，就是用array_flip()把数组的key和value交换两次。这种做法比用 array_unique() 快得多。
        $data = array_flip(array_flip($temp));
        //将数组的数量存入变量count中
        $connt = count($data);
    }
    //为数组赋予新的键名
    shuffle($data);
    //数组转字符串
    $str=implode(",", $data);
    //替换掉逗号
    $number=str_replace(',', '', $str);
    return $number;
}

//生成密码
function Gen_CardPassword() {
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d').substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
        $d = '',
        $f = 0;
        $f < 8;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return  $d;
}

// 数组健值重命名
function Array_Keys_Rename($oldKey, $newKey, array $input){
    $return = array();
    foreach ($input as $key => $value) {
        if ($key===$oldKey)
            $key = $newKey;
        if (is_array($value))
            $value = replaceKeys( $oldKey, $newKey, $value);
        $return[$key] = $value;
    }
    return $return;
}

// 编码转换
function Str_UTF8($strText)
{
    $encode = mb_detect_encoding($strText, array('GB2312','GBK'));
    if($encode == "GB2312")
    {
        return @iconv('GB2321','UFT-8',$strText);
    }
    else
    {
        return @iconv('GBK','UFT-8',$strText);
    }
}

// 取国家电话号码头

function get_dialcode()
{
    //$IP = get_ip();
    //$CN = get_CC($IP);
    //$CC = Config::get('country');
    //return $CC[$CN]['code'];
}

/**
 * 随机字符
 * @param int $length 长度
 * @param string $type 类型
 * @param int $convert 转换大小写 1大写 0小写
 * @return string
 */
function random($length=10, $type='letter', $convert=0)
{
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'letter';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $a = mt_rand(0, $strlen);
        $code .= $string[$a];
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

// 创建邮件或短信验证码
function createCode($length = 4){
    $min = pow(10 , ($length - 1));
    $max = pow(10, $length) - 1;
    return rand($min, $max);
}

//验证手机号是否正确
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}

/*
 * 生成交易流水号
 * @param char(2) $type
 */
function doOrderSn($type){
    return date('YmdHis') .$type. substr(microtime(), 2, 3) .  sprintf('%02d', rand(0, 99));
}

function deldir($dir) {
//先删除目录下的文件：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹：
    if(rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}


/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

//获得用户IP地址
function get_ip(){
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

//判断是否IP地址
function is_ip($str){
    $ip=explode('.',$str);
    for($i=0;$i<count($ip);$i++){
        if($ip[$i]>255){
            return false;
        }
    }
    return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str);
}

// 根据IP地址获得国家
function get_CC($IP) {
    //use GeoIp2\Database\Reader;
    $databaseFile = root_path().'extend/DB/GeoLite2-City.mmdb';
    $reader = new Reader($databaseFile);
    $CCtmp =  $reader->get($IP);
    $CC = $CCtmp['country']['iso_code'];
    $reader->close();
    return $CC;
}

// 获得昵称
function get_nickname($uid)
{
    //$uid=1;
    $username = Db::table('app_user')->where('user_id',$uid)->value('user_nickname');
    if(empty($username)) {
        $username = '无名氏'.$uid;
    }
    return $username;
}

// 去除数组KEY
function array_remove_key($array,$keys){
    if (!is_array($array) || !is_array($keys)){
        return false;
    }
    foreach($array as $t){
        foreach($keys as $k){
            unset($t[$k]);
        }
        $doc[]=$t;
    }
    return $doc;
}

//数组横向排序
function array_merge_2(){
    $arrays = func_get_args();
    $result = array();
    foreach($arrays as $array) {
        if(is_array($array)) {
            foreach($array as $key => $value){
                $result[$key][] = $value;
            }
        }
    }
    return $result;
}

// 时长转换
function duration( $duration)
{
    $duration_formated  = NULL;
    $duration           = round($duration);
    if ( $duration > 3600 ) {
        $hours              = floor($duration/3600);
        $duration_formated .= sprintf('%02d',$hours). ':';
        $duration           = round($duration-($hours*3600));
    }
    if ( $duration > 60 ) {
        $minutes            = floor($duration/60);
        $duration_formated .= sprintf('%02d', $minutes). ':';
        $duration           = round($duration-($minutes*60));
    } else {
        $duration_formated .= '00:';
    }
    return $duration_formated . sprintf('%02d', $duration);
}

// 转换数字为
function number_format_short( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}

// 图片存储目录结构算法
function put_dir($id) {
    $tmp = md5($id);
    $dir = substr($tmp,0,1).'/'.substr($tmp,0,4).'/'.substr($tmp,4,8);
    return $dir;
    //print_r($dir);
    //print_r($tmp);
}

// 建立文件夹
function mkdirs($dir, $mode = 0777) {
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}

// 获得视频时长
function get_duration($videofile) {
    //Get Duration
    $result = shell_exec('ffmpeg -i "' . $videofile . '" 2>&1');
    preg_match('/(?<=Duration: )(\d{2}:\d{2}:\d{2})\.\d{2}/', $result, $match);
    $dur = $match['0'];
    list($hours, $minutes, $seconds,$fractions) = sscanf($dur, "%d:%d:%d:%d");
    $totalDurationSeconds = $hours * 3600 + $minutes * 60 + $seconds + doubleval($fractions) / 100.0;
    $totalDuratioHours = doubleval($totalDurationSeconds) / 3600.0;
    $duration = $totalDurationSeconds;
    return $duration;
}



//通过字段值获取字段配置的名称
function getFieldVal($val,$fieldConfig){
    if($fieldConfig){
        foreach(explode(',',$fieldConfig) as $k=>$v){
            if(strpos($v,strval($val)) !== false){
                $tempstr = explode('|',$v);
                $fieldval = $tempstr[0];
            }
        }
        return $fieldval;
    }
}

//通过字段名称获取字段配置的值
function getFieldName($val,$fieldConfig){
    if($fieldConfig){
        foreach(explode(',',$fieldConfig) as $k=>$v){
            if(strpos($v,strval($val)) !== false){
                $tempstr = explode('|',$v);
                $fieldName = $tempstr[1];
            }
        }
        return $fieldName;
    }
}










//自带的

if (! function_exists('tp5ControllerToTp6Controller')) {
    /**
     * TP5二级目录转TP6二级目录.
     *
     * @param  string  $class
     *
     * @return string
     */
    function tp5ControllerToTp6Controller($class = '')
    {
        $_arr = explode('/', $class);
        $route = $class;
        if (count($_arr) >= 3) {
            $route = '';
            foreach ($_arr as $_k => $_v) {
                $route .= $_v;
                ($_k == 0) ? $route .= '.' : $route .= '/';
            }
            $route = rtrim($route, '/');
        } elseif (count($_arr) == 2) {
            $route = implode('.', $_arr).'/index';
        }

        return $route;
    }
}
if (! function_exists('model')) {
    /**
     * 实例化Model.
     *
     * @param  string  $name
     * @param  string  $layer
     * @param  bool  $appendSuffix
     *
     * @throws \think\Exception
     * @return Model
     */
    function model($name = '', $layer = 'model', $appendSuffix = false)
    {
        if (class_exists($name)) {
            return new $name();
        }
        $class = app()->getNamespace().'\\'.$layer.'\\'.$name;
        if (class_exists($class)) {
            return new $class();
        }
        $class = 'app\\common\\'.$layer.'\\'.$name;
        if (class_exists($class)) {
            return new $class();
        } else {
            throw new \think\Exception('model not found');
        }
    }
}

/**
 * 处理插件钩子.
 *
 * @param  string  $event  钩子名称
 * @param  array|null  $params  传入参数
 * @param  bool  $once
 *
 * @return mixed
 */
function hook($event, $params = null, bool $once = false)
{
    return Event::trigger($event, $params, $once);
}

/**
 * 获得插件列表.
 *
 * @return array
 */
function get_addon_list()
{
    $results = scandir(ADDON_PATH);
    $list = [];
    foreach ($results as $name) {
        if ($name === '.' or $name === '..') {
            continue;
        }
        if (is_file(ADDON_PATH.$name)) {
            continue;
        }
        $addonDir = ADDON_PATH.$name.DIRECTORY_SEPARATOR;
        if (! is_dir($addonDir)) {
            continue;
        }

        if (! is_file($addonDir.ucfirst($name).'.php')) {
            continue;
        }

        //这里不采用get_addon_info是因为会有缓存
        //$info = get_addon_info($name);
        $info_file = $addonDir.'info.ini';
        if (! is_file($info_file)) {
            continue;
        }
        $info = parse_ini_file($info_file, true, INI_SCANNER_TYPED) ?: [];
        //$info = Config::parse($info_file, '', "addon-info-{$name}");
        if (! isset($info['name'])) {
            continue;
        }
        $info['url'] = addon_url($name);
        $list[$name] = $info;
    }

    return $list;
}

/**
 * 获得插件内的服务类.
 *
 * @return array
 */
function get_addon_service()
{
    $results = scandir(ADDON_PATH);
    $list = [];
    foreach ($results as $name) {
        if ($name === '.' or $name === '..') {
            continue;
        }
        if (is_file(ADDON_PATH.$name)) {
            continue;
        }
        $addonDir = ADDON_PATH.$name.DIRECTORY_SEPARATOR;
        if (! is_dir($addonDir)) {
            continue;
        }

        if (! is_file($addonDir.ucfirst($name).'.php')) {
            continue;
        }
        $addonServiceDir = ADDON_PATH.$name.DIRECTORY_SEPARATOR.'service'.DIRECTORY_SEPARATOR;

        if (! is_dir($addonServiceDir)) {
            continue;
        }

        $service_files = is_dir($addonServiceDir) ? scandir($addonServiceDir) : [];
        $namespace = 'addons\\'.$name.'\\service\\';
        foreach ($service_files as $file) {
            if (strpos($file, '.php')) {
                $className = str_replace('.php', '', $file);
                $class = $namespace.$className;
                if (class_exists($class)) {
                    $list[] = $class;
                }

            }
        }
    }

    return $list;
}

/**
 * 获得插件自动加载的配置.
 *
 * @param  bool  $truncate  是否清除手动配置的钩子
 *
 * @return array
 */
function get_addon_autoload_config($truncate = false)
{
    // 读取addons的配置
    $config = (array) Config::get('addons');
    if ($truncate) {
        // 清空手动配置的钩子
        $config['hooks'] = [];
    }
    $route = [];
    // 读取插件目录及钩子列表
    $base = get_class_methods('\\think\\Addons');
    $base = array_merge($base, ['install', 'uninstall', 'enable', 'disable']);
    $url_domain_deploy = false;
    $addons = get_addon_list();
    $domain = [];
    foreach ($addons as $name => $addon) {
        if (! $addon['state']) {
            continue;
        }

        // 读取出所有公共方法
        $methods = (array) get_class_methods('\\addons\\'.$name.'\\'.ucfirst($name));
        // 跟插件基类方法做比对，得到差异结果
        $hooks = array_diff($methods, $base);
        // 循环将钩子方法写入配置中
        foreach ($hooks as $hook) {
            $hook = parseName($hook, 0, false);
            if (! isset($config['hooks'][$hook])) {
                $config['hooks'][$hook] = [];
            }
            // 兼容手动配置项
            if (is_string($config['hooks'][$hook])) {
                $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
            }
            if (! in_array($name, $config['hooks'][$hook])) {
                $config['hooks'][$hook][] = $name;
            }
        }
        $conf = get_addon_config($addon['name']);
        if ($conf) {
            $conf['rewrite'] = isset($conf['rewrite']) && is_array($conf['rewrite']) ? $conf['rewrite'] : [];
            $rule = array_map(function ($value) use ($addon) {
                return "{$addon['name']}/{$value}";
            }, array_flip($conf['rewrite']));
            if (isset($conf['domain']) && $conf['domain']) {
                $domain[] = [
                    'addon'  => $addon['name'],
                    'domain' => $conf['domain'],
                    'rule'   => $rule,
                ];
            } else {
                $route = array_merge($route, $rule);
            }
        }
    }
    $config['service'] = get_addon_service();
    $config['route'] = $route;
    $config['route'] = array_merge($config['route'], $domain);

    return $config;
}

/**
 * 获取插件类的类名.
 *
 * @param  string  $name  插件名
 * @param  string  $type  返回命名空间类型
 * @param  string  $class  当前类名
 *
 * @return string
 */
function get_addon_class($name, $type = 'hook', $class = null)
{
    $name = parseName($name);
    // 处理多级控制器情况
    if (! is_null($class) && strpos($class, '.')) {
        $class = explode('.', $class);

        $class[count($class) - 1] = parseName(end($class), 1);
        $class = implode('\\', $class);
    } else {
        $class = parseName(is_null($class) ? $name : $class, 1);
    }
    switch ($type) {
        case 'controller':
            $namespace = '\\addons\\'.$name.'\\controller\\'.$class;
            break;
        default:
            $namespace = '\\addons\\'.$name.'\\'.$class;
    }

    return class_exists($namespace) ? $namespace : '';
}

/**
 * 读取插件的基础信息.
 *
 * @param  string  $name  插件名
 *
 * @return array
 */
function get_addon_info($name)
{
    $addon = get_addon_instance($name);
    if (! $addon) {
        return [];
    }

    return $addon->getInfo($name);
}

/**
 * 获取插件类的配置数组.
 *
 * @param  string  $name  插件名
 *
 * @return array
 */
function get_addon_fullconfig($name)
{
    $addon = get_addon_instance($name);
    if (! $addon) {
        return [];
    }

    return $addon->getFullConfig($name);
}

/**
 * 获取插件类的配置值值
 *
 * @param  string  $name  插件名
 *
 * @return array
 */
function get_addon_config($name)
{
    $addon = get_addon_instance($name);
    if (! $addon) {
        return [];
    }

    return $addon->getConfig($name);
}

/**
 * 获取插件的单例.
 *
 * @param  string  $name  插件名
 *
 * @return mixed|null
 */
function get_addon_instance($name)
{
    static $_addons = [];
    if (isset($_addons[$name])) {
        return $_addons[$name];
    }
    $class = get_addon_class($name);
    if (class_exists($class)) {
        $_addons[$name] = new $class();

        return $_addons[$name];
    } else {
        return;
    }
}

/**
 * 插件显示内容里生成访问插件的url.
 *
 * @param  string  $url  地址 格式：插件名/控制器/方法
 * @param  array  $vars  变量参数
 * @param  bool|string  $suffix  生成的URL后缀
 * @param  bool|string  $domain  域名
 *
 * @return bool|string
 */
function addon_url($url, $vars = [], $suffix = true, $domain = false)
{
    $url = ltrim($url, '/');
    $addon = substr($url, 0, stripos($url, '/'));
    if (! is_array($vars)) {
        parse_str($vars, $params);
        $vars = $params;
    }
    $params = [];
    foreach ($vars as $k => $v) {
        if (substr($k, 0, 1) === ':') {
            $params[$k] = $v;
            unset($vars[$k]);
        }
    }
    $val = "@addons/{$url}";
    $config = get_addon_config($addon);

    $rewrite = $config && isset($config['rewrite']) && $config['rewrite'] ? $config['rewrite'] : [];

    if ($rewrite) {
        $path = substr($url, stripos($url, '/') + 1);
        if (isset($rewrite[$path]) && $rewrite[$path]) {
            $val = $rewrite[$path];
            array_walk($params, function ($value, $key) use (&$val) {
                $val = str_replace("[{$key}]", $value, $val);
            });
            $val = str_replace(['^', '$'], '', $val);
            if (substr($val, -1) === '/') {
                $suffix = false;
            }
        } else {
            // 如果采用了域名部署,则需要去掉前两段
            /*if ($indomain && $domainprefix) {
                $arr = explode("/", $val);
                $val = implode("/", array_slice($arr, 2));
            }*/
        }
    } else {
        // 如果采用了域名部署,则需要去掉前两段
        /*if ($indomain && $domainprefix) {
            $arr = explode("/", $val);
            $val = implode("/", array_slice($arr, 2));
        }*/
        foreach ($params as $k => $v) {
            $vars[substr($k, 1)] = $v;
        }
    }
    $url = url($val, [], $suffix, $domain).($vars ? '?'.http_build_query($vars) : '');
    $url = preg_replace("/\/((?!index)[\w]+)\.php\//i", '/', $url);

    return $url;
}

/**
 * 设置基础配置信息.
 *
 * @param  string  $name  插件名
 * @param  array  $array  配置数据
 *
 * @throws Exception
 * @return bool
 */
function set_addon_info($name, $array)
{
    $file = ADDON_PATH.$name.DIRECTORY_SEPARATOR.'info.ini';
    $addon = get_addon_instance($name);
    $array = $addon->setInfo($name, $array);
    if (! isset($array['name']) || ! isset($array['title']) || ! isset($array['version'])) {
        throw new Exception('插件配置写入失败');
    }
    $res = [];
    foreach ($array as $key => $val) {
        if (is_array($val)) {
            $res[] = "[$key]";
            foreach ($val as $skey => $sval) {
                $res[] = "$skey = ".(is_numeric($sval) ? $sval : $sval);
            }
        } else {
            $res[] = "$key = ".(is_numeric($val) ? $val : $val);
        }
    }
    if ($handle = fopen($file, 'w')) {
        fwrite($handle, implode("\n", $res)."\n");
        fclose($handle);
        //清空当前配置缓存
        Config::set([$name => null], 'addoninfo');
    } else {
        throw new Exception('文件没有写入权限');
    }

    return true;
}

/**
 * 写入配置文件.
 *
 * @param  string  $name  插件名
 * @param  array  $config  配置数据
 * @param  bool  $writefile  是否写入配置文件
 *
 * @throws Exception
 * @return bool
 */
function set_addon_config($name, $config, $writefile = true)
{
    $addon = get_addon_instance($name);
    $addon->setConfig($name, $config);
    $fullconfig = get_addon_fullconfig($name);
    foreach ($fullconfig as $k => &$v) {
        if (isset($config[$v['name']])) {
            $value = $v['type'] !== 'array' && is_array($config[$v['name']]) ? implode(',',
                $config[$v['name']]) : $config[$v['name']];
            $v['value'] = $value;
        }
    }
    if ($writefile) {
        // 写入配置文件
        set_addon_fullconfig($name, $fullconfig);
    }

    return true;
}

/**
 * 写入配置文件.
 *
 * @param  string  $name  插件名
 * @param  array  $array  配置数据
 *
 * @throws Exception
 * @return bool
 */
function set_addon_fullconfig($name, $array)
{
    $file = ADDON_PATH.$name.DIRECTORY_SEPARATOR.'config.php';
    if (! is_really_writable($file)) {
        throw new Exception('文件没有写入权限');
    }
    if ($handle = fopen($file, 'w')) {
        fwrite($handle, "<?php\n\n".'return '.varexport($array, true).";\n");
        fclose($handle);
    } else {
        throw new Exception('文件没有写入权限');
    }

    return true;
}

if (! function_exists('input_token')) {
    /**
     * 生成表单令牌.
     *
     * @param  string  $name  令牌名称
     *
     * @return string
     */
    function input_token($name = '__token__')
    {
        return '<input type="hidden" name="'.$name.'" value="'.token($name).'" />';
    }
}
if (! function_exists('parseName')) {
    function parseName($name, $type = 0, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);

            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }

        return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
    }
}
if (! function_exists('__')) {

    /**
     * 获取语言变量值
     *
     * @param  string  $name  语言变量名
     * @param  array  $vars  动态变量值
     * @param  string  $lang  语言
     *
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || ! $name) {
            return $name;
        }
        if (! is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }

        return Lang::get($name, $vars, $lang);
    }
}

if (! function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本.
     *
     * @param  int  $size  大小
     * @param  string  $delimiter  分隔符
     *
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }

        return round($size, 2).$delimiter.$units[$i];
    }
}

if (! function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间.
     *
     * @param  int  $time  时间戳
     * @param  string  $format  日期时间格式
     *
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);

        return date($format, $time);
    }
}

if (! function_exists('human_date')) {

    /**
     * 获取语义化时间.
     *
     * @param  int  $time  时间
     * @param  int  $local  本地时间
     *
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }
}

if (! function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     *
     * @param  string  $url  资源相对地址
     * @param  bool  $domain  是否显示域名 或者直接传入域名
     *
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $url = preg_match($regex, $url) ? $url : \think\facade\Config::get('upload.cdnurl').$url;
        if ($domain && ! preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain.$url;
        }

        return $url;
    }
}

if (! function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写.
     *
     * @param  string  $file  文件或目录
     *
     * @return bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        } elseif (! is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);

        return true;
    }
}

if (! function_exists('rmdirs')) {

    /**
     * 删除文件夹.
     *
     * @param  string  $dirname  目录
     * @param  bool  $withself  是否删除自身
     *
     * @return bool
     */
    function rmdirs($dirname, $withself = true)
    {
        if (! is_dir($dirname)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }

        return true;
    }
}

if (! function_exists('copydirs')) {

    /**
     * 复制文件夹.
     *
     * @param  string  $source  源文件夹
     * @param  string  $dest  目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (! is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName();
                if (! is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            }
        }
    }
}

if (! function_exists('mb_ucfirst')) {
    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)).mb_strtolower(mb_substr($string, 1));
    }
}

if (! function_exists('addtion')) {

    /**
     * 附加关联字段数据.
     *
     * @param  array  $items  数据列表
     * @param  mixed  $fields  渲染的来源字段
     *
     * @return array
     */
    function addtion($items, $fields)
    {
        if (! $items || ! $fields) {
            return $items;
        }
        $fieldsArr = [];
        if (! is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'],
                $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model']();
            } else {
                $model = $v['name'] ? \think\facade\Db::name($v['name']) : \think\facade\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in',
                $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }

        return $items;
    }
}

if (! function_exists('var_export_short')) {

    /**
     * 返回打印数组结构.
     *
     * @param  string  $var  数组
     * @param  string  $indent  缩进字符
     *
     * @return string
     */
    function var_export_short($var, $indent = '')
    {
        switch (gettype($var)) {
            case 'string':
                return '"'.addcslashes($var, "\\\$\"\r\n\t\v\f").'"';
            case 'array':
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        .($indexed ? '' : var_export_short($key).' => ')
                        .var_export_short($value, "$indent    ");
                }

                return "[\n".implode(",\n", $r)."\n".$indent.']';
            case 'boolean':
                return $var ? 'TRUE' : 'FALSE';
            default:
                return var_export($var, true);
        }
    }
}

if (! function_exists('letter_avatar')) {
    /**
     * 首字母头像.
     *
     * @param $text
     *
     * @return string
     */
    function letter_avatar($text)
    {
        $total = unpack('L', hash('adler32', $text, true))[1];
        $hue = $total % 360;
        [$r, $g, $b] = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg = "rgb({$r},{$g},{$b})";
        $color = '#ffffff';
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="'.$bg.'" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="'.$color.'" text-anchor="middle" text-rights="admin" alignment-baseline="central">'.$first.'</text></svg>');
        $value = 'data:image/svg+xml;base64,'.$src;

        return $value;
    }
}

if (! function_exists('hsv2rgb')) {
    function hsv2rgb($h, $s, $v)
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255),
        ];
    }
}

if (! function_exists('list_to_tree')) {
    /**
     * 把返回的数据集转换成Tree.
     *
     * @param  array  $list  要转换的数据集
     * @param  string  $pid  parent标记字段
     * @param  string  $level  level标记字段
     *
     * @return array
     */
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }

        return $tree;
    }
}

if (! function_exists('tree_to_list')) {
    /**
     * 将list_to_tree的树还原成列表.
     *
     * @param  array  $tree  原来的树
     * @param  string  $child  孩子节点的键
     * @param  string  $order  排序显示的键，一般是主键 升序排列
     * @param  array  $list  过渡用的中间数组，
     *
     * @return array        返回排过序的列表数组
     */
    function tree_to_list($tree, $child = '_child', $order = 'id', &$list = [])
    {
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = list_sort_by($list, $order, $sortby = 'asc');
        }

        return $list;
    }
}

if (! function_exists('list_sort_by')) {
    /**
     * 对查询结果集进行排序.
     *
     * @param  array  $list  查询结果
     * @param  string  $field  排序的字段名
     * @param  string  $sortby  排序类型 asc正向排序 desc逆向排序 nat自然排序
     *
     * @return array|bool
     */
    function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = [];
            foreach ($list as $i => $data) {
                $refer[$i] = &$data[$field];
            }
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }

            return $resultSet;
        }

        return false;
    }
}

if (! function_exists('upload_file')) {
    /**
     * 上传文件.
     *
     * @param  string  $file  上传的文件
     * @param  string  $name  上传的位置
     * @param  string  $path  上传的文件夹
     * @param  string  $validate  规则验证
     *
     * @return string|bool
     * @author niu
     */
    function upload_file($file = null, $name = 'local', $path = '', $validate = '')
    {
        //文件
        if (! $file) {
            return false;
        }
        //上传配置
        $config_name = 'filesystem.disks.'.$name;
        $filesystem = config($config_name);
        if (! $filesystem) {
            return false;
        }
        //上传文件
        if ($validate) {
            validate(['file' => $validate])->check(['file' => $file]);
        }
        $savename = \think\facade\Filesystem::disk($name)->putFile($path, $file, function ($file) {
            //重命名
            return date('Ymd').'/'.md5((string) microtime(true));
        });
        if (isset($filesystem['url'])) {
            $savename = $filesystem['url'].$savename;
        }

        return $savename;
    }
}

if (! function_exists('varexport')) {
    /**
     * var_export方法array转[]
     *
     * @param $expression
     * @param  bool  $return
     *
     * @return mixed|string|string[]|null
     */
    function varexport($expression, $return = false)
    {
        $export = var_export($expression, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [null, ']$1', ' => ['], $array);
        $export = join(PHP_EOL, array_filter(["["] + $array));
        if ((bool) $return) {

            return $export;
        } else {
            echo $export;
        }
    }
}
