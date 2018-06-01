<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;


class Config extends Base{

    protected $tableName = 'config';
    protected $rules = array(
        'overdue_days'=>'number'

    );


    protected $fieldType = array(
        'overdue_days'=>'int'
    );








}