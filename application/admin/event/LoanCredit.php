<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class LoanCredit extends Template{



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        parent::__construct();
        $this->serviceModel = \think\Loader::model('LoanCredit','service');


    }

    protected function listsWhere()
    {
        $where = array();
        if(isset($where['del'])){
            $whereParse['del'] = $where['del']==0 ? 0 : 1;
        }

        if(isset($where['status']) && $where['status']!==0){
            $whereParse['l.status'] = $where['status'];
        }

        if(isset($where['owner']) && $where['owner']>0){
            $whereParse['l.owner'] = $where['owner'];
        }

        if($this->login['id']!=1){
            $where['l.admin_id'] = $this->login['id'];

        }

        return $where;
    }

    protected function rowWhere(){
        return array('l.admin_id'=>$this->login['id']);
    }

    protected function admin_id(&$data){
        $data['admin_id'] = $this->login['id'];
    }




}