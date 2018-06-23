<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class Person extends Base{



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        parent::__construct();
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

        if($this->login['id']!=1)
            $whereStr .= ' AND admin_id='.$this->login['id'];

        $data = $model->lists($whereStr,$page,$pagesize);
        return $data;
    }

    public function row($id){
        $where = $this->login['id']==1 ? array() : array('admin_id'=>$this->login['id']);
        return $this->serviceModel->row($id,$where);
    }

    public function add($data){
        $data['admin_id'] = $this->login['id'];
        $res = $this->serviceModel->add($data);
        return $res;
    }

    public function edit($id,$data){
        $where = $this->login['id']==1 ? array() : array('admin_id'=>$this->login['id']);
        return $this->serviceModel->edit($id,$data,$where);
    }

    public function delete($id){
        $where = $this->login['id']==1 ? array() : array('admin_id'=>$this->login['id']);
        return $res = $this->serviceModel->del($id,$where);
    }


}