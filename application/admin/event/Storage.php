<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class Storage {



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        $this->serviceModel = \think\Loader::model('Storage','service');


    }

    public function lst($where=array(),$page=1,$pagesize=10){
        $model = $this->serviceModel;
        $whereParse = array();
        if(isset($where['del'])){
            $whereParse['del'] = $where['del']==0 ? 0 : 1;
        }

        if(isset($where['end_date_l']) ){
            $whereParse['l.end_date'][] = array('egt',$where['end_date_l']);
        }
        if(isset($where['end_date_r'])){
            $whereParse['l.end_date'][] = array('elt',$where['end_date_r']);
        }

        if(isset($where['loan_amount_l']) && floatval($where['loan_amount_l'])>0){
            $whereParse['l.loan_amount'][] = array('egt',floatval($where['loan_amount_l']));
        }

        if(isset($where['loan_amount_r']) && floatval($where['loan_amount_r'])>0){
            $whereParse['l.loan_amount'][] = array('elt',floatval($where['loan_amount_r']));
        }


        if(isset($where['status']) && $where['status']!==0){
            $whereParse['l.status'] = $where['status'];
        }

        $data = $model->lists($whereParse,$page,$pagesize);
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