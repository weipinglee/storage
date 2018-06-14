<?php
// +----------------------------------------------------------------------
// | @date: 2017/11/22 - 17:20
// +----------------------------------------------------------------------
// | @author：Ulex
// +----------------------------------------------------------------------
// | @desc：
// +----------------------------------------------------------------------
namespace app\admin\Controller;
use think\Request;

class Privilege extends Base
{
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->model = \think\Loader::model('Privilege','service');
        $this->view->engine->layout('layout/layout');
    }

    public function lst()
    {
        // 设置页面中的信息
        $this->assign(array(
            '_page_btn_name' => '添加权限',
            '_page_btn_link' => url('add'),
            '_page_btn_refresh' => TRUE,
        ));
        return $this->fetch();
    }


    /**
     * 权限列表接口
     */
    public function priv_list(){
        $model = $this->model;
        $data = $model->lists();
        die(json_encode($data));
    }


    public function add(Request $request)
    {
        if($request->isPost())
        {
            $model = $this->model;
            $insertData = array(
                'parent_id'=>$request->param('parent_id'),
                'module_name' =>$request->param('module_name'),
                'controller' => $request->param('controller'),
                'action_name' => $request->param('action_name'),
                'pri_name' => $request->param('pri_name')
            );

            $res = $model->add($insertData);
            die(json_encode($res));
        }else{

            return $this->fetch();
        }

    }
    public function edit(Request $request)
    {

        if($request->isPost())
        {
            $model = $this->model;
            $updateData = array(
                'parent_id'=>$request->param('parent_id'),
                'module_name' =>$request->param('module_name'),
                'controller' => $request->param('controller'),
                'action_name' => $request->param('action_name'),
                'pri_name' => $request->param('pri_name')
            );
            $id = $request->param('id');

            $res = $model->edit($id,$updateData);
            die(json_encode($res));
        }
        else{
            $id = $request->param('id');
            $this->assign('id',$id);
            return $this->fetch();
        }

    }

    //模型类并没有做删除操作
    public function delete(Request $request)
    {
        $id = $request->param('id');
        $res = $this->model->del($id);
        die(json_encode($res));
    }
}