<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;


use \extDB\DbModel;
use think\Session;
class Admin extends Base{


    protected $tableName = 'admin';
    protected $rules = array(
        'adminname' => array(
            'max'=>32
        )
    );

    protected $insertRules = array(
        'adminname'=>array('require','unique'=>'admin'),
        'password' => 'require'
    );

    protected $updateRules = array(
        'adminname'=>array('unique'=>'admin'),
    );


    protected $message  =   [
        'adminname.require' => '名称必须',
        'adminname.max'     => '名称最多不能超过32个字符',
        'adminname.unique'  => '管理员名称已存在'
    ];
    protected $fieldDesc = array(
        'adminname'=>'管理员'
    );

    public function __construct()
    {
       parent::__construct();
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

            $adminService = \think\Loader::model('Admin','service');
            $adminData = $adminService->row($row['id']);
            $role_id = $adminData['role_id'];
            Session::set('role_id',$role_id);
            $roleService = \think\Loader::model('Role','service');
            $roleData = $roleService->row($role_id);
            Session::set('role_name',isset($roleData['role_name']) ? $roleData['role_name'] : '');
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

    public function logData($f=''){
        $id = Session::get('id');
        if($id){
            $data = array(
                'id'=> Session::get('id'),
                'adminname' => Session::get('adminname'),
                'row_id' => Session::get('row_id'),
                'row_name' => Session::get('row_name')
            );
            if(isset($data[$f])){
                return $data[$f];
            }
            return $data;
        }else{
            return false;
        }


    }




}