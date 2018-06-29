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

class LoanCredit extends Base{

    protected $tableName = 'loan_credit';
    protected $rules = array(
        'owner'=>'number',//持卡人
        'exp_income'   => 'float',//预期收益
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
        'owner'=>'int',
        'exp_income'   => 'float',
        'period'       => 'varchar',
        'rate'         => 'float',
        'rec_person_id'   => 'int',
        'rec_rate'     => 'float',
        'exp_final_income' => 'float',
        'real_end_date' => 'date',
        'real_final_income' => 'float',
        'real_income'   => 'float',
        // 'manual_over_time' => ''
        'status'       => 'varchar'
    );










}