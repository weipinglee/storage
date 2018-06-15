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
use \extDB\DbModel;
use \extDB\DbQuery;
use \think\Session;
class Privilege extends Base{

    protected $tableName = 'privilege';
    //允许插入的字段
    protected $insertFields = array('pri_name','module_name','controller','action_name','parent_id');
    //允许更新的字段
    protected $updateFields = array('id','pri_name','module_name','controller','action_name','parent_id');

    //自动验证项
    protected $_validate = array(
          'pri_name'=> 'chsAlphaNum|max:20',//字母数字中文且小于30位
          'module_name' => 'chsAlphaNum|max:30',
          'controller' => 'chsAlphaNum|max:30',
          'action_name' => 'chsAlphaNum|max:30',
          'parent_id' => 'number',
    );



    /************************************* 递归相关方法 *************************************/
    //取数据
    public function getTree()
    {
        $model = new DbModel($this->tableName);
        $data = $model->select();
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
        $model = new DbModel($this->tableName);
        $data = $model->select();
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
    public function chkPri($rountParam=array())
    {
        //获取当前管理员所拥有的权限
        $adminId =Session::get('id');
        //超级管理员有所有权限
        if($adminId ==1)
            return true;
        $arModel = new DbQuery('admin_role as a');
        $arModel->join = 'left join role_pri as b on a.role_id=b.role_id 
                           left join privilege as  c on b.pri_id =c.id';
        $where = array(
            'a.admin_id' => $adminId,
            'c.module_name'=> $rountParam['module'],
            'c.controller' => $rountParam['controller'],
            'c.action_name'=> $rountParam['action']
        );
        $arModel->where = $where;
        $arModel->fields = 'count(a.admin_id) as count';
        $has = $arModel->find();
        return ($has[0]['count'] >0);

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
            $priModel = new DbQuery('privilege');
            $priModel->where = 'status=1';
            $priData =$priModel->find();
//			dump($priData);exit();
        }
        else {

            $arModel = new DbQuery('admin_role as a');
            $arModel ->join = 'left join ROLE_PRI as b on b.role_id=a.role_id
			        left join PRIVILEGE as c on c.id = b.pri_id';
            $arModel->where = 'c.status=1 and a.admin_id='.$adminId;
            $priData = $arModel->find();
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
        $query = new DbQuery('role_pri as a');
        $query->fields = 'b.id';
        $query->join = 'left join privilege as b on b.id=a.pri_id';
        $query->wehre = 'a.role_id='.$id;
        $data =$query->find();
        return $data;
    }
}