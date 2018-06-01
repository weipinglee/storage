<?php
// +----------------------------------------------------------------------
// | @date: 2018/6/1 - 11:13
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：入仓资金模型
// +----------------------------------------------------------------------
namespace app\admin\Model;

use think\Validate;

class Storage extends Base{

    protected $tableName = 'storage';

    //与借贷管理的表字段一样
    protected $rules = array(
        'person_id'=>'number',
        'begin_date' => 'date',
        'end_date'   => 'date',
        'exp_income'   => 'float',
        'loan_amount'  => 'float',
        'period'       => 'chsAlpha',
        'rate'         => 'float',
      //  'rec_person'   => 'chsAlpha',//入仓不要这两个字段，数据库未删除
        //'rec_rate'     => 'float',
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
      //  'rec_person'   => 'int',
       // 'rec_rate'     => 'float',
        'exp_final_income' => 'float',
        'real_end_date' => 'date',
        'real_final_income' => 'float',
        'real_income'   => 'float',
        // 'manual_over_time' => ''
        'status'       => 'varchar'
    );


    protected $searchFields = array(
        'begin_date' => array(
            'text'=>'开始日期',
            'type'=> 'greater'
        ),

        'end_date' => array(
            'text'=>'结束日期',
            'type'=> 'less'
        ),
        'loan_amount' => array(
            'text'=>'入仓资金',
            'type'=> 'between'
        )
    );







}