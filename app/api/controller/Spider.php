<?php
//ignore_user_abort(true);
//set_time_limit(0); // disable the time limit for this script

namespace app\api\controller;
use app\common\controller\Api;

use think\facade\Db;

/**
 * 示例接口.
 */

function download($url,$path)
{
    $ch = curl_init($url);
    $fp = fopen($path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

class Spider extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function grab()
    {
        $user = 'secondclassvines';
        $offset =  $this->request->request('offset', 0, 'trim,strip_tags,htmlspecialchars');
        $baseurl='https://api.tumblr.com/v2/blog/'.$user.'/posts/video?api_key=bYlWHlsbt5buEJh7Kg6cRKI5cMOe3pfBr9s3r607sUgoTfnqgx&limit=20&offset=';
        $apiurl = $baseurl.$offset;
        $datas = json_decode(file_get_contents($apiurl),true);
        $total  = $datas['response']['total_posts'];
        $data = [];
        foreach ($datas['response']['posts'] as $c)
        {
            if($c['type'] == 'video' && !empty($c["video_url"]) )
            {
                $tmp = [];
                $id = uniqid();
                $tmp['title'] = empty($c["summary"]) ? $c["id"] : $c["summary"];
                $tmp['file'] = isset($c["video_url"]) ? $c["video_url"] : "";
                $tmp['image'] = addslashes($c["thumbnail_url"]);
                $tmp['height'] = $c['thumbnail_height'];
                $tmp['width'] = $c['thumbnail_width'];
                $tmp['duration'] = $c["duration"];

                $i = app()->getRootPath() . 'public/tmp/'.$id.'.jpg';
                $f = app()->getRootPath() . 'public/tmp/'.$id.'.mp4';
                download($tmp['image'],$i);
                download($tmp['file'],$f);

                unset($tmp['file'],$tmp['image']);

                $ROOTPATH = app()->getRootPath().'public/';
                $tmp['image'] = 'topic/img/'.$id.'.jpg';
                $tmp['file'] = 'topic/mp4/'.$id.'.mp4';
                $tmp['status'] = (int) 1;

                rename($i, $ROOTPATH.$tmp['image']);
                rename($f, $ROOTPATH.$tmp['file']);

                $id = Db::name('video')->strict(false)->insertGetId($tmp);

                Db::name('video')->where('id', $id)->update(['vkey' => md5($id)]);

                array_push($data,$tmp);
            }

        }
        var_dump($data);
    }

    public function test()
    {
        $this->success('返回成功', $this->request->param());
    }

}