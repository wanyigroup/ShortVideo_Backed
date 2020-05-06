<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Videoimport extends Backend
{

    /**
     * Video模型对象
     * @var \app\admin\model\Video
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        //$this->model = new \app\admin\model\Video;
        //$this->view->assign("featureList", $this->model->getFeatureList());
        //$this->view->assign("statusList", $this->model->getStatusList());
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
        // 设置过滤方法
        $this->request->filter(['strip_tags']);

        $data = [];
        if ($this->request->isPost()) {

            $params = $this->request->post('row/a');
            if ($params) {
                $params = $this->preExcludeFields($params);
                //var_dump($params);
                $tmp = glob($params['scandir'] . "*");
                $items = [];
                foreach ($tmp as $full) {
                    $item = [];
                    $item['path'] = $full;
                    $item['orgname'] = basename($full);
                    $item['newname'] = clean_strstr(preg_replace("/\.[^.]+$/", "", basename($full)));
                    $item['ext'] = strtolower(substr(basename($full), strrpos(basename($full), '.') + 1));
                    $items[] = $item;
                }
                $result = array("total" => count($items), "rows" => $items);
                return json($result);
            }
        }
        return $this->view->fetch();
    }

    public function import()
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isPost())
        {
            $params = $this->request->post("row");
            unset($params['scandir']);
            //var_dump($params);
            $orgname = array_map(function ($array_item){
                return basename($array_item);
            }, $params['path']);
            $md5 = array_map(function ($array1) {
                return md5_file($array1);
                //return shell_exec('md5sum -b ' . escapeshellarg($array1)); // 计算文件MD5 效率更高
            }, $params['path'] );
            $data = [];
            $keys = array('path','orgname','newname','md5');
            $result = array_merge_more($keys,$params['path'],$orgname,$params['title'],$md5);
            //print_r($result);
            foreach ($result  as $q )
            {
                // Todo 查询hash值 判断是不是重复 待添加

                $queue          = \app\common\model\Videoqueue::create(
                    [
                        'path' => $q['path'],
                        'orgname' => $q['orgname'],
                        'newname' => $q['newname'],
                        'hash' => $q['md5'],
                    ]
                );
                $queue->save();
                // 获取自增ID
                //echo $queue->id;
                if($queue->id) {
                    @rename($q['path'], app()->getRootPath().'original/'.$queue->id.'.tmp');
                }
            }
            $result = array("total" => count($result), "msg" => "导入完成,请在队列中查看!");
            return json($result);
        }
    }

    public function scandir()
    {
        // 设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isPost())
        {
            // $this->request->post('reqtype') == 'scan';
            $tmp = glob($this->request->post('scandir')."*");
            $items = [];
            foreach ($tmp as $full) {
                $item = [];
                $item['path'] = $full;
                $item['orgname'] = basename($full);
                $item['newname'] = clean(preg_replace("/\.[^.]+$/", "", basename($full)));
                $item['ext'] = strtolower(substr(basename($full), strrpos(basename($full), '.')+1));
                $items[] = $item;
            }
            $result = array("total" => count($items), "rows" => $items);
            return json($result);
        }
        return 'error!';
    }


}