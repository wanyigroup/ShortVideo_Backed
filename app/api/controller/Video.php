<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\facade\Db;
use think\facade\Request;
use app\common\model\Video as VideoModel;

use itbdw\Ip\IpLocation;


/**
 * 示例接口.
 */
class Video extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    function __construct() {
        $this->vodurl = 'https://vcdn.afuny.com'; //Db::table('app_sys_config')->where('name', 'app_vod_url')->value('value');
        $this->imgurl = 'https://vcdn.afuny.com'; //Db::table('app_sys_config')->where('name', 'app_img_url')->value('value');
        $this->pagin_num = 10;
    }

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Index
     */
    public function index()
    {
        return "API:version@v1.0";
    }

    // 推荐列表
    public function feature()
    {
        try {
            $page = input('?page') ? input('get.page') : 1;
            $_vtable = "app_video";
            $_vkey = "id";
            $_vpage = $this->pagin_num;

            $lists = VideoModel::where('status',1)->where('feature',1)->order($_vkey, 'desc')->paginate([
                'list_rows'=> $_vpage,
                'page' => $page,
                'var_page' => 'page',
            ])->toArray();

            foreach ($lists['data'] as &$item)
            {
                $images = glob('/www/wwwroot/app.afuny.com/public/author/*.jpg');
                $avatar = $images[rand(0, count($images) - 1)];
                $item['avatar'] = 'https://apiv1.afuny.com/author/'.basename($avatar);
                $item['image'] = $this->imgurl.'/'.$item['image'];
                $item['mp4url'] = $this->vodurl.'/'.$item['file'];
                $item['hlsurl'] = $this->vodurl.'/hls/'.$item['file'].'/index.m3u8';
                $item['views'] = float_number($item['views']);
                $item['likes'] = float_number($item['likes']);
                $item['commentnum'] = get_comment_count($item['id']);
                unset($item['file']);
            }
            $lists['status'] = 'success';
            $lists['code'] = (($lists['total'] > 0) ? 200 : 0);
            return json($lists);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 默认列表
    public function lists()
    {
        try {
            $page = input('?page') ? input('get.page') : 1;
            $_vtable = "app_video";
            $_vkey = "id";
            $_vpage = $this->pagin_num;

            $lists = VideoModel::where('status',1)->order($_vkey, 'desc')->paginate([
                'list_rows'=> $_vpage,
                'page' => $page,
                'var_page' => 'page',
            ])->toArray();
            //var_dump($lists);
            foreach ($lists['data'] as &$item)
            {
                $images = glob('/www/wwwroot/app.afuny.com/public/author/*.jpg');
                $avatar = $images[rand(0, count($images) - 1)];
                $item['avatar'] = 'https://apiv1.afuny.com/author/'.basename($avatar);
                $item['image'] = $this->imgurl.'/'.$item['image'];
                $item['mp4url'] = $this->vodurl.'/'.$item['file'];
                $item['hlsurl'] = $this->vodurl.'/hls/'.$item['file'].'/index.m3u8';
                $item['views'] = float_number($item['views']);
                $item['likes'] = float_number($item['likes']);
                $item['commentnum'] = get_comment_count($item['id']);
                unset($item['file']);
            }
            $lists['status'] = 'success';
            $lists['code'] = (($lists['total'] > 0) ? 200 : 0);
            return json($lists);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 关注列表
    public function follow()
    {
        return self::lists();
    }

    // 收藏列表
    public function favorite()
    {
        return self::lists();
    }

    // 同城列表
    public function location()
    {
        try {
            // Location Info
            $IP = get_ip();
            $qqwry_filepath = app()->getRootPath().'qqwry.dat';
            $jsondata = IpLocation::getLocation($IP, $qqwry_filepath);
            //var_dump($jsondata);

            $page = input('?page') ? input('get.page') : 1;
            $_vtable = "app_video";
            $_vkey = "id";
            $_vpage = $this->pagin_num;

            $lists = VideoModel::where('status',1)->order($_vkey, 'desc')->paginate([
                'list_rows'=> $_vpage,
                'page' => $page,
                'var_page' => 'page',
            ])->toArray();
            //var_dump($lists);
            foreach ($lists['data'] as &$item)
            {
                $images = glob('/www/wwwroot/app.afuny.com/public/author/*.jpg');
                $avatar = $images[rand(0, count($images) - 1)];
                $item['avatar'] = 'https://apiv1.afuny.com/author/'.basename($avatar);
                $item['image'] = $this->imgurl.'/'.$item['image'];
                $item['mp4url'] = $this->vodurl.'/'.$item['file'];
                $item['hlsurl'] = $this->vodurl.'/hls/'.$item['file'].'/index.m3u8';
                $item['views'] = float_number($item['views']);
                $item['likes'] = float_number($item['likes']);
                $item['commentnum'] = get_comment_count($item['id']);
                unset($item['file']);
            }
            $lists['status'] = 'success';

            $lists['location'] = $jsondata['country'] . $jsondata['province'] . $jsondata['city'];
            $lists['code'] = (($lists['total'] > 0) ? 200 : 0);
            return json($lists);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
        //return self::lists();
    }

    // 热门搜索
    public function hotkeyword()
    {
        try {
            $words = [];
            $keywordlist = Db::table('app_hotkeyword')
                ->order('count','Desc')
                ->limit(10)
                //->whereTime('dates','-24 hours') //24小时热点
                ->select();
            foreach ($keywordlist as $item) {
                $word = [];
                $word['keyword'] = $item['terms'];
                $word['count'] = $item['count'];
                $words[] = $word;
            }
            $resp = [
                "status" => 200,
                "data" => $words,
            ];
            return json($resp);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 关键词搜索
    public function search($keyword='')
    {
        try {
            $page = input('?page') ? input('get.page') : 1;
            $_vtable = "app_video";
            $_vkey = "id";
            $_vpage = $this->pagin_num;

            $keyword = Request::param('keyword','','strip_tags,htmlspecialchars');

            if(!empty($keyword))
            {
                //记录搜索的关键词
                $querymaps = ['terms',$keyword];
                $terms = Db::table('app_hotkeyword')->where('terms', $keyword)->findOrEmpty();
                if(!empty($terms)) {
                    Db::table('app_hotkeyword')->where('terms',$keyword)->inc('count')->update();
                } else {
                    Db::table('app_hotkeyword')->save(['count' => 1, 'terms' => $keyword]);
                }

                //查询 where('status',1)
                $lists = VideoModel::whereLike("title","%$keyword%")
                    ->order($_vkey, 'desc')->paginate([
                    'list_rows'=> $_vpage,
                    'page' => $page,
                    'var_page' => 'page',
                ])->toArray();

                foreach ($lists['data'] as &$item)
                {
                    $images = glob('/www/wwwroot/app.afuny.com/public/author/*.jpg');
                    $avatar = $images[rand(0, count($images) - 1)];
                    $item['avatar'] = 'https://apiv1.afuny.com/author/'.basename($avatar);
                    $item['image'] = $this->imgurl.'/'.$item['image'];
                    $item['mp4url'] = $this->vodurl.'/'.$item['file'];
                    $item['hlsurl'] = $this->vodurl.'/hls/'.$item['file'].'/index.m3u8';
                    $item['views'] = float_number($item['views']);
                    $item['likes'] = float_number($item['likes']);
                    $item['commentnum'] = get_comment_count($item['id']);
                    unset($item['file']);
                }
                $lists['status'] = 'success';
                $lists['code'] = (($lists['total'] > 0) ? 200 : 0);
                return json($lists);
            } else {

            }
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }


    }

    // 视频详情
    public function view($key)
    {
        try {
            $_vtable = "app_video";
            $key = Request::param('key','','strip_tags,htmlspecialchars');
            $item = Db::table($_vtable)->where('vkey', $key)->where('status',1)->findOrEmpty();
            if(!empty($item)) {
                $statuscode = 200;
                $images = glob('/www/wwwroot/app.afuny.com/public/author/*.jpg');
                $avatar = $images[rand(0, count($images) - 1)];
                $item['avatar'] = 'https://apiv1.afuny.com/author/'.basename($avatar);
                $item['image'] = $this->imgurl.'/'.$item['image'];
                $item['mp4url'] = $this->vodurl.'/'.$item['file'];
                $item['hlsurl'] = $this->vodurl.'/hls/'.$item['file'].'/index.m3u8';
                $item['views'] = float_number($item['views']);
                $item['rating'] = float_number($item['rating']);
                $item['commentnum'] = get_comment_count($item['id']);
                unset($item['file']);
            } else {
                $statuscode = 0;
            }

            $resp = [
                'status' => $statuscode,
                'data' => $item,
            ];
            return json($resp);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }

    }

    // 喜欢行为 +1
    public function like($key)
    {
        try {
            $_vtable = "app_video";
            $key = Request::param('key','','strip_tags,htmlspecialchars');
            $value = Db::table($_vtable)->where('vkey', $key)->value('likes');
            if(Db::table($_vtable)->where('vkey', $key)->inc('likes')->update()){
                $value = float_number(Db::table($_vtable)->where('vkey', $key)->value('likes'));
                $resp = ['status' => 200,'value' => $value,'msg' => '点赞成功'];
            } else {
                $resp = ['status' => 0,'value' => $value,'msg' => '点赞失败'];
            }
            return json($resp);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 访问行为 +1
    public function views($key)
    {
        try {
            $_vtable = "app_video";
            $key = Request::param('key','','strip_tags,htmlspecialchars');
            $value = Db::table($_vtable)->where('vkey', $key)->value('views');
            if(Db::table($_vtable)->where('vkey', $key)->inc('views')->update()){
                $value = float_number(Db::table($_vtable)->where('vkey', $key)->value('views'));
                $resp = ['status' => 200,'value' => $value,'msg' => '刷新成功'];
            } else {
                $resp = ['status' => 000,'value' => $value,'msg' => '刷新失败'];
            }
            return json($resp);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }

    }

    // 收藏行为 +1
    public function fav($key)
    {
        try {
            $user = $this->auth->getUserinfo();
            if (Request::isPost())
            {
                $_vtable = "app_video_fav";
                $key = Request::param('key','','strip_tags,htmlspecialchars');
                $data = [];
                $data['uid'] = $user['id'];
                $data['vid'] = Db::table('app_video')->where('vkey', $key)->value('id');

                if(!empty(Db::table($_vtable)
                    ->where("uid",$user['id'])
                    ->where("vid",$data['vid'])->findOrEmpty()))
                    throw new \think\Exception('视频收藏过啦', 0);

                if(Db::table($_vtable)->replace()->save($data))
                {
                    $resp = ['code' => 200,'msg' => '收藏成功'];
                } else {
                    $resp = ['code' => 0,'msg' => '收藏失败'];
                }
                return json($resp);
            } else {
                //Todo 返回收藏夹
            }
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 创建评论
    public function commentcreate($key)
    {
        try {
            if (Request::isPost())
            {
                $token = Request::instance()->header('token');
                //if(!empty($token)) {
                    $user = \app\common\library\Token::get($token);
                //} else {
                //    $user['user_id'] = 2;
                //}
                $_vtable = "app_video_comments";
                $key = Request::param('key','','strip_tags,htmlspecialchars');
                $data = [];
                $data['uid'] = $user['user_id'];
                $data['vid'] = Db::table('app_video')->where('vkey', $key)->value('id');
                $data['content'] = Request::param('content','','strip_tags,htmlspecialchars');
                if(empty($data['content']))
                    throw new \think\Exception('评论内容不可用为空!', 0);
                if(empty($data['uid']))
                    throw new \think\Exception('请先登陆在评论', 0);
                if(Db::table($_vtable)->save($data))
                {
                    $resp = ['code' => 200,'msg' => '评论成功'];
                } else {
                    $resp = ['code' => 0,'msg' => '评论失败'];
                }
                return json($resp);
            } else {

            }
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 评论列表
    public function commentlist($key)
    {
        try {
            $_vtable = "app_video_comments";
            $key = Request::param('key','','strip_tags,htmlspecialchars');
            $data = [];
            $VID = Db::table('app_video')->where('vkey', $key)->value('id');

            $list = Db::table('app_video_comments')
                ->where('status',1)
                ->where('vid', $VID)
                ->order('id', 'desc')
                ->paginate()
                ->each(function($item, $key){
                    //$item['vkey'] = $key;
                    $item['nickname'] = '大力水手'; //get_nickname($item['uid']);
                    return $item;
                });

            if(!empty($list)){
                $resp = ['code' => 200,'data'=> $list, 'msg' => '成功'];
            } else {
                $resp = ['code' => 0,'data'=> '暂时没评论!' , 'msg' => '失败'];
            }
            return json($resp);
        } catch (\Exception $e) {
            return json(['status' => 'fail', 'code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    // 用户ID
    public function uid()
    {
        $token = Request::instance()->header('token');
        if(!empty($token)) {
            $user = \app\common\library\Token::get($token);
        } else {
            $user['user_id'] = 2;
        }
    }

}