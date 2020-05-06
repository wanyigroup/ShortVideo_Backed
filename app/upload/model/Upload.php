<?php
declare (strict_types = 1);

namespace app\upload\model;

//use think\App;
use think\Model;
//use think\Request;
use think\facade\Config;
use think\facade\Db;
use think\facade\Session;
use think\helper\Str;
//use think\facade\View;
use think\facade\Validate;
/**
 * @mixin think\Model
 */
class Upload extends Model
{
    protected $table = 'app_video_source'; #数据表
    protected $pk = 'id'; #主键ID
    protected $createTime = 'created_at'; #定义时间戳字段名
    protected $updateTime = 'updated_at'; #定义时间戳字段名
    protected $autoWriteTimestamp = true; #自动时间戳

    public static function UploadProcess($datas)
    {
        $files = $datas;
        $data = [];
        $data['origin'] = $files->getOriginalName();
        $data['ext'] = $files->getOriginalExtension();
        $data['mime'] = $files->getMime();
        $data['size'] = $files->getSize();
        $data['md5'] = $files->hash('md5');
        // 查找数据库 MD5是不是重复
        $data['path'] = \think\facade\Filesystem::disk('public')->putFile( 'video_source', $files,'md5');
        //var_dump($data);

        $md5 = $datas->hash('md5');
        $querys = Upload::where('md5', $md5)->findOrEmpty();

        //var_dump($querys);

        if (!$querys->isEmpty())
            throw new \think\Exception('该内容已经存在,请勿重复上传', 0);

        // 入库
        $Q = new Upload;
        $Q->save($data);
        return $Q->id;

        // 加队列

    }
}
