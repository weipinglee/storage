<?php
// +----------------------------------------------------------------------
// | @date: 2018/06/14
// +----------------------------------------------------------------------
// | @author：weipinglee
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;

use think\Model;
use \extDB\DbModel;
use \extDB\DbQuery;

class Role extends Base
{

    protected $tableName = 'role';
    //允许插入的字段
    protected $insertFields = array('role_name');
    //允许更新的字段
    protected $updateFields = array('id','role_name');

    protected $rules = array(
        'role_name'=> array('require','max'=>30)
    );



}