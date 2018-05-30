<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;

use think\Validate;
use \extDB\DbModel;
use think\Session;
class Loan extends Base{


    protected $validateObj = null;

    protected $tableName = 'loan';
    protected $rules = array(
        'person_id'=>'number',
        'begin_date' => 'date',
        'end_date'   => 'date',
        'exp_income'   => 'float',
        'loan_amount'  => 'float',
        'period'       => 'chsAlpha',
        'rate'         => 'float',
        'rec_person'   => 'number',
        'rec_rate'     => 'float',
        'exp_final_income' => 'float',
        'real_end_date' => 'date',
        'real_final_income' => 'float',
        'real_income'   => 'float',
       // 'manual_over_time' => ''
        'status'       => 'chsAlpha'

    );

    protected $insertRules = array(
        'person_id' => 'require',

    );

    protected $uniqueFields = array(

    );

    public function __construct()
    {
        $this->validateObj = new Validate($this->rules);
    }

    public function check($data,&$error){
        if($this->validateObj->check($data,$this->rules)){
            return true;
        }
        $error = $this->validateObj->getError();
        return false;
    }

    public function getTable(){
        return $this->tableName;
    }


    public function searchFields(){
        return array(
            'begin_date' => array(
                'text'=>'开始日期',
                'type'=> 'greater'
            ),

            'end_date' => array(
                'text'=>'结束日期',
                'type'=> 'less'
            ),
            'loan_amount' => array(
                'text'=>'借贷资金',
                'type'=> 'between'
            ),
            'rec_person' => '推荐人'

        );
    }






}