<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\facade\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Video extends Backend
{
    
    /**
     * Video模型对象
     * @var \app\admin\model\Video
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Video;
        $this->view->assign("featureList", $this->model->getFeatureList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','vkey','feature','title','image','duration','file','likes','views','createtime','updatetime','status']);
                
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 上传
     */

    public function upload()
    {
        if ($this->request->isPost())
        {
            try {
                $file = request()->file('file');
                //$savename = \think\facade\Filesystem::putFile( 'tmp', $file, 'md5');

                // Todo 查询hash值 判断是不是重复 待添加
                $queue = \app\common\model\Videoqueue::create(
                    [
                        'path' => '',
                        'orgname' => $file->getOriginalName(),
                        'newname' => clean_strstr(preg_replace("/\.[^.]+$/", "", basename($file->getOriginalName()))),
                        'hash' => $file->hash('md5')
                    ]
                );
                $queue->save();
                if($queue->id) {
                    //$f = \think\facade\Filesystem::putFile( app()->getRootPath().'original/'.$queue->id.'.tmp', $file,);
                    //var_dump($f);
                    //@rename($savename, app()->getRootPath().'original/'.$queue->id.'.tmp');
                    @move_uploaded_file($file,app()->getRootPath().'original/'.$queue->id.'.tmp');
                    Db::table('app_videoqueue')->save(['id' => $queue->id, 'path' => app()->getRootPath().'original/'.$queue->id.'.tmp']);
                }
                $response = ['code'=>200,'status'=>'success','data'=>$queue->id,'msg'=>'返回成功!'];
                return json($response);
            } catch (\Exception $e) {
                return json(['code'=>0,'status'=>'fail','data'=>'','msg'=>$e->getMessage()]);
            }
        }
    }
}
