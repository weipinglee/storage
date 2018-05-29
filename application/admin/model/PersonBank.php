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
class PersonBank extends Base{


    protected $validateObj = null;

    protected $tableName = 'person_bank';
    protected $rules = array(
        'bank_name' => 'require',
        'bank_acc'  => 'require',
        'person_id' => 'require'

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

        );
    }






}