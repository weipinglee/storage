<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 11:39
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Model;
use think\Model;
class Privilege extends Model{
    //允许插入的字段
    protected $insertFields = array('pri_name','module_name','controller','action_name','parent_id');
    //允许更新的字段
    protected $updateFields = array('id','pri_name','module_name','controller','action_name','parent_id');

    //自动验证项
    protected $_validate = array(
        array('pri_name', 'require', '权限名不能为空！', 1, 'regex', 3),
        array('pri_name', '1,30', '权限名最长不能超过 30 个字符！', 1, 'length', 3),
        array('module_name', '1,30', '权限名最长不能超过 30 个字符！', 2, 'length', 3),
        array('controller', '1,30', '控制器名称最长不能超过 30 个字符！', 2, 'length', 3),
        array('action_name', '1,30', '方法名称最长不能超过 30 个字符！', 2, 'length', 3),
        array('parent_id', 'number', '父类Id必须是一个整数！', 2, 'regex', 3),
    );
    /************************************* 递归相关方法 *************************************/
    //取数据
    public function getTree()
    {
        $data = $this->select();
        return $this->_reSort($data);
    }
    private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                $v['level'] = $level;
                $ret[] = $v;
                $this->_reSort($data, $v['id'], $level+1, FALSE);
            }
        }
        return $ret;
    }
    public function getChildren($id)
    {
        $data = $this->select();
        return $this->_children($data, $id);
    }
    private function _children($data, $parent_id=0, $isClear=TRUE)
    {
        static $ret = array();
        if($isClear)
            $ret = array();
        foreach ($data as $k => $v)
        {
            if($v['parent_id'] == $parent_id)
            {
                $ret[] = $v['id'];
                $this->_children($data, $v['id'], FALSE);
            }
        }
        return $ret;
    }
    /************************************ 其他方法 ********************************************/
    public function _before_delete($option)
    {
        // 先找出所有的子分类
        $children = $this->getChildren($option['where']['id']);
        // 如果有子分类都删除掉
        if($children)
        {
            $this->error = '有下级数据无法删除';
            return FALSE;
        }
    }

    /**
     * @name：chkPri
     * @author：Ulex
     * @desc：检查当前管理员是否有权限访问这个网页
     * @return bool
     */
    public function chkPri()
    {
        //获取当前管理员所拥有的权限
        $adminId =session('id');
        //超级管理员有所有权限
        if($adminId ==1)
            return true;
        $arModel = D('admin_role');
        $has = $arModel->alias('a')
            ->join('left join __ROLE_PRI__ b on a.role_id =b.role_id
			        left join __PRIVILEGE__ c on b.pri_id =c.id')
            ->where(array(
                'admin_id' =>array('eq',$adminId),
                'c.module_name' =>array('eq',MODULE_NAME),
                'c.controller' =>array('eq',CONTROLLER_NAME),
                'c.action_name' =>array('eq',ACTION_NAME),
            ))->count();
        return ($has >0);

    }

    /**
     * @name：getMenus
     * @author：Ulex
     * @desc：获取当前管理员所拥有的前两级的权限
     * @return array
     */
    public function getMenus()
    {
        /***********先取出这个管理员搜所有的所有权限**************/
        $adminId =session('id');
        if($adminId  ==1){
            $priModel = D('privilege');
            $priData =$priModel->select();
//			dump($priData);exit();
        }
        else {

            $arModel = D('admin_role');
            $priData = $arModel->alias('a')
                ->join('left join __ROLE_PRI__  b on b.role_id=a.role_id
			        left join __PRIVILEGE__ c on c.id = b.pri_id')
                ->where(array(
                    'admin_id' => array('eq', $adminId)
                ))->select();
            /*dump($priData);
            exit();*/
        }
        //qu取前2级权限
        $btns = array();
        foreach ($priData as $k => $v) {
            if ($v['parent_id'] == 0) {
                //再找这个分类的子分类
                foreach ($priData as $k1 => $v1) {
                    if ($v1['parent_id'] == $v['id']) {
                        $v['children'][] = $v1;
                    }
                }
                $btns[]= $v;
            }
        }
        /*	dump($btns);
            exit();*/
        return $btns;
    }

    /**
     * @name：getPriByUserId
     * @author：Ulex
     * @desc：通过角色ID取对应权限
     * @param $id
     * @return mixed
     */
    public function getPriByUserId($id)
    {
        $data = D('role_pri')->alias('a')
            ->field('b.id')
            ->join('left join __PRIVILEGE__ as b on b.id =a.pri_id ')
            ->where([
                'a.role_id' =>['eq',$id]
            ])->select();
        return $data;
    }
}