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

class Loan extends Base{

    protected $tableName = 'loan';
    protected $rules = array(
        'person_id'=>'number',
        'begin_date' => 'date',
        'end_date'   => 'date',
        'exp_income'   => 'float',
        'loan_amount'  => 'float',
        'period'       => 'chsAlpha',
        'rate'         => 'float',
        'rec_person'   => 'chsAlpha',
        'rec_person_id'   => 'number',
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

    protected $fieldType = array(
        'person_id'=>'int',
        'begin_date' => 'date',
        'end_date'   => 'date',
        'exp_income'   => 'float',
        'loan_amount'  => 'float',
        'period'       => 'varchar',
        'rate'         => 'float',
        'rec_person'   => 'int',
        'rec_rate'     => 'float',
        'exp_final_income' => 'float',
        'real_end_date' => 'date',
        'real_final_income' => 'float',
        'real_income'   => 'float',
        // 'manual_over_time' => ''
        'status'       => 'varchar'
    );










}