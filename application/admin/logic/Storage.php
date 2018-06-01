<?php
// +----------------------------------------------------------------------
// | @date: 2018/05/31 -
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：逻辑层的借贷管理模型
// +----------------------------------------------------------------------
namespace app\admin\logic;



class Storage extends Income{


   public function computeIncome($amount,$begin,$end,$rate,$period,$rec_rate=0){
       return $this->getIncome($amount,$begin,$end,$rate,$period,0);



   }

   public function computeFinalIncome($id,$real_end_date){
       $model = \think\loader::model('Storage','service');
       $row = $model->row($id);
       if(empty($row)){
           return array('exp_income'=>0,'exp_final_income'=>0,'info'=>'入仓记录不存在');
       }

       $data = $this->getIncome($row['loan_amount'],$row['begin_date'],$real_end_date,$row['rate'],$row['period'],$row['rec_rate']);
       return $data;
   }





}