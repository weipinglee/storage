<?php
namespace app\admin\event;

use think\Controller;
use think\Request;

class Loan extends Template{



    protected $serviceModel = null;

    public function __construct(Request $request = null)
    {
        parent::__construct();
        $this->serviceModel = \think\Loader::model('Loan','service');


    }

    protected function listsWhere()
    {
        $where = array();
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

        if(isset($where['rec_person']) && $where['rec_person']!=''){
            $whereParse['p2.name'] = array('like',$where['rec_person']);
        }

        if(isset($where['status']) && $where['status']!==0){
            $whereParse['l.status'] = $where['status'];
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