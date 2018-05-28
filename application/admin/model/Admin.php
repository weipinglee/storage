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
class Admin extends Base{


    protected $validateObj = null;
    protected $rules = array(
        'adminname' => 'require|max:32',
        'password' => 'require'
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

    /**
     * 管理员登陆验证处理
     * @param $adminname
     * @param $pass
     * @return array
     */
    public function login($adminname,$pass){
        $validate = $this->validateObj;
        if($validate->check(array('adminname'=>$adminname,'password'=>$pass))){
            $model = new DbModel('admin');

            $row =$model->where(array('adminname'=>$adminname))->getObj();
            if(empty($row) || $row['password']!=md5($pass)){
                return self::getSuccInfo(0,'用户名或密码错误');
            }
            Session::set('id',$row['id']);
            Session::set('adminname',$row['adminname']);
            Session::set('name',$row);
            return self::getSuccInfo();

        }else{
            return self::getSuccInfo(0,$validate->getError());
        }
    }

    /**
     * 登出
     */
    public function logout(){
        Session::clear();
    }




}