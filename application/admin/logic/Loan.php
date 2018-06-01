<?php
// +----------------------------------------------------------------------
// | @date: 2018/05/31 -
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：逻辑层的借贷管理模型
// +----------------------------------------------------------------------
namespace app\admin\logic;



class Loan {


   public function computeIncome($amount,$begin,$end,$rate,$period,$rec_rate=0){
       $dateBeginObj = new \DateTime($begin);
       $dateEndObj   = new \DateTime($end);
       $interVal = $dateBeginObj->diff($dateEndObj);
       $interDays = $interVal->format('%R%a');
       $amount = floatval($amount);
       $rate = floatval($rate);
       $rec_rate = floatval($rec_rate);
       $income = 0;
       $final_income = 0;
       if($interDays<=0 || $amount<0 || $rate<0 ){
           return array('exp_income'=>$income,'exp_final_income'=>$final_income,'info'=>'');
       }

       if($period=='日'){
           $income = bcmul($amount * $interDays , $rate/1000,2);
           if($rec_rate>0){
               $final_income = round($income - bcmul($income,$rec_rate)/100,2);
           }else{
               $final_income = $income;
           }

       }elseif($period=='月'){
           $interMonths = $interVal->format('%m');

            $interVal2 = new \DateInterval('P'.$interMonths.'M');//months个月的间隔
            $dateBeginObj->add($interVal2);
            $interVal3 = $dateBeginObj->diff($dateEndObj);
            $interDays = $interVal3->format('%R%a');
            //echo $interMonths.'_'.$interDays;

           $monthDays = cal_days_in_month(CAL_GREGORIAN, $dateEndObj->format('m'), $dateEndObj->format('Y'));

           $income = $amount * $rate * $interMonths + bcdiv($interDays * $rate * $amount,30) ;
           $income = $final_income = round($income/1000,2);

           if($rec_rate>0){
               $final_income = round($income - bcmul($income,$rec_rate)/100,2);
           }



       }elseif($period=='年'){
           $interYears = $interVal->format('%y');
           $interVal2 = new \DateInterval('P'.$interYears.'Y');//$interYears年的间隔
           $dateBeginObj->add($interVal2);
           $interVal3 = $dateBeginObj->diff($dateEndObj);
           $interDays = $interVal3->format('%R%a');

           $income = $amount * $rate * $interYears + bcdiv($interDays * $rate * $amount,365) ;
           $income = $final_income = round($income/1000,2);
           if($rec_rate>0){
               $final_income = round($income - bcmul($income,$rec_rate)/100 , 2);
           }

       }
       $final_income = $final_income<0 ? 0 : $final_income;
       return array('income'=>$income,'final_income'=>$final_income,'info'=>'');



   }

   public function computeFinalIncome($id,$real_end_date){
       $model = \think\loader::model('Loan','service');
       $row = $model->row($id);
       if(empty($row)){
           return array('exp_income'=>0,'exp_final_income'=>0,'info'=>'贷款记录不存在');
       }

       $data = $this->computeIncome($row['loan_amount'],$row['begin_date'],$real_end_date,$row['rate'],$row['period'],$row['rec_rate']);
       return $data;
   }





}