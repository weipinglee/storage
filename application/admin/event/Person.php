<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class Person {



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        $this->serviceModel = \think\Loader::model('Person','service');


    }

    public function lst($page=1,$pagesize=10,$where=array()){
        $model = $this->serviceModel;
        $whereStr = 'del=0 ';
        if(isset($where['name'])){

            $whereStr .=  ' AND (pinyin like "'.$where['name'].'%" || name like "'.$where['name'].'%")';
        }
        if(isset($where['mobile'])){
            $whereStr .= ' AND mobile like "'.$where['mobile'].'%"';
        }

        $data = $model->lists($whereStr,$page,$pagesize);
        return $data;
    }

    public function row($id){
        return $this->serviceModel->row($id);
    }

    public function add($data){
        $res = $this->serviceModel->add($data);
        return $res;
    }

    public function edit($id,$data){
        return $this->serviceModel->edit($id,$data);
    }

    public function delete($id){
        return $res = $this->serviceModel->del($id);
    }


}