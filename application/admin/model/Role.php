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



    // 添加前
    protected function _before_insert(&$data, $option)
    {
    }
    // 修改前
    protected function _before_update(&$data, $option)
    {
    }
    // 删除前
    protected function _before_delete($option)
    {
        if(is_array($option['where']['id']))
        {
            $this->error = '不支持批量删除';
            return FALSE;
        }
    }
    //添加后
    protected function _after_insert($data, $option)
    {
        $priId = I('post.pri_id');
        $rpModel = D('role_pri');
        foreach($priId as $v){
            $rpModel->add(array(
                'pri_id' =>$v,
                'role_id' => $data['id']
            ));
        }
    }
    // 修改后
    protected function _after_update($data, $option)
    {
        $priId = I('post.pri_id');

        $rpModel = D('role_pri');
        //删除之前的

        $rpModel->where([
            'role_id' => $data['id']
        ])
            ->delete();
        //添加
        foreach($priId as $v){
            $rpModel
                ->add(array(
                    'pri_id' =>$v,
                    'role_id' => $data['id']
                ));
        }
    }
    /************************************ 其他方法 ********************************************/
}