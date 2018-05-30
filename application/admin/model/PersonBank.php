<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;


class PersonBank extends Base{


    protected $validateObj = null;

    protected $tableName = 'person_bank';
    protected $rules = array(
        'person_id' => array('number')

    );

    protected $insertRules = array(
        'person_id' => array('require'),
        'bank_name' => array('require'),
        'bank_acc'  => array('require'),
    );

    public function __construct()
    {
        parent::__construct();
    }









}