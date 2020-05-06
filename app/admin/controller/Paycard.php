<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\facade\Db;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Paycard extends Backend
{
    
    /**
     * Paycard模型对象
     * @var \app\admin\model\Paycard
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Paycard;
        $this->view->assign("statusList", $this->model->getStatusList());
        $AmountList = \app\common\model\Payplan::get_Planname();
        $this->view->assign('amountList', $AmountList);
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
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
                ->withJoin(['payplan'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                ->withJoin(['payplan'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                //$row->visible(['id','pid','uid','cardnum','cardamount','createtime','status']);
                $row->visible(['id','pid','cardnum','cardamount','createtime','status']);
                $row->getRelation('payplan')->visible(['title']);
            }
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 卡密生成
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post('row/a');
            if ($params) {
                $params = $this->preExcludeFields($params);
                $data = [];
                for ($i = 1; $i <= $params['num']; $i++) {
                    $card['pid'] = $params['pid'];
                    $card['uid'] = (int) '0'; //未分配
                    $card['cardnum'] = (int) Gen_CardNumber();
                    $card['cardamount'] = (int) $params['amount'];
                    $card['status'] = (int) $params['status'];
                    array_push($data,$card);
                }
                $result = Db::table('app_paycard')->insertAll($data);
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
        }
        return $this->view->fetch();
    }

}
