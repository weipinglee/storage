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
class Person extends Base{


    protected $validateObj = null;

    protected $tableName = 'person';
    protected $rules = array(
        'name' => array('max'=>30),//姓名
        'mobile' => array('regex'=>'/^\d{6,15}$/i'),//手机号
        'sex'    => 'chsAlpha',//性别
        'zu'     => 'chsAlpha',//名族
        'birth'  => 'date',//生日
        'place'   => 'chsAlpha',//籍贯
        'edu'    => 'max:20',//学历
        'biye'   => 'max:100',//毕业院校
        //'work_years' => '',//工作年限
        'shenfenzheng' => array('number'),//身份证
        'email'  => 'email',//邮箱
        'is_marry' => 'chsAlpha',//婚姻状况
        'mate_name'  => 'max:30',//配偶姓名
        'mate_phone' => 'number',//配偶电话
        'emeg_cont_phone' => 'number',//紧急联系人电话
        'emeg_cont_name' => 'max:30',//紧急联系人姓名

    );


    protected $fieldDesc = array(
        'shenfenzheng'=>'身份证'
    );


    protected $insertRules = array(
        'name' => array('require'),
        'mobile' => array('require'),
        'shenfenzheng' => array('unique'=>'person')
    );

    protected $updateRules = array(
        'shenfenzheng' => array('unique'=>'person')
    );

    protected $message  =   [
        'shenfenzheng.unique' => '该身份证号已存在'
    ];
    protected $searchFields = array(
        'name' => '姓名',
        'mobile' => '手机号'
    );

    public function __construct()
    {
        parent::__construct();
    }








}