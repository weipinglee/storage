<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class Template extends Base{



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        parent::__construct();


    }

    public function lst($where=array(),$page=1,$pagesize=10){
        $model = $this->serviceModel;

        $whereParse = $this->listsWhere();
        $data = $model->lists($whereParse,$page,$pagesize);
        return $data;
    }

    public function pageBar()
    {
        return $this->serviceModel->pageBar();
    }

    protected function listsWhere(){
        return '';
    }

    public function row($id){
        $where = $this->login['id']==1 ? array() :$this->rowWhere();
        return $this->serviceModel->row($id,$where);
    }

    protected function rowWhere(){
        return array();
    }

    public function add($data){
        $this->admin_id($data);
        $res = $this->serviceModel->add($data);
        return $res;
    }

    protected function admin_id(&$data){
        $data['admin_id'] = $this->login['id'];
    }

    public function edit($id,$data){
        $where = $this->login['id']==1 ? array() : $this->rowWhere();
        return $this->serviceModel->edit($id,$data,$where);
    }

    public function delete($id){
        $where = $this->login['id']==1 ? array() : $this->rowWhere();
        return $res = $this->serviceModel->del($id,$where);
    }


}