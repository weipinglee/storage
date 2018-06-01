<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/5/25
 * Time: 10:10
 */

namespace app\admin\Controller;
use think\Request;

class Loan extends Base{

    protected $serviceModel = null;
    protected $model = null;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->serviceModel = \think\Loader::model('Loan','service');
        $this->model = \think\Loader::model('Loan','model');
        $this->view->engine->layout('layout/layout');
    }

    public function lst()
    {
        $request = Request::instance();
        $model = $this->serviceModel;
        $page = isset($_GET['page']) ? $_GET['page'] : 1 ;

        $where = 'l.del=0';

        $param = $request->param();
        if(isset($param['end_date_l'])){
            $where .= ' AND l.end_date >= "'.$param['end_date_l'].'"';
        }
        if(isset($param['begin_date_r'])){
            $where .= ' AND l.end_date <= "'.$param['begin_date_r'].'"';
        }

        if(isset($param['amount_l']) && floatval($param['amount_l'])>0){
            $where .= ' AND l.loan_amount >= '.floatval($param['amount_l']);
        }

        if(isset($param['amount_r']) && floatval($param['amount_r'])>0){
            $where .= ' AND l.loan_amount <= '.floatval($param['amount_r']);
        }

        if(isset($param['rec_person']) && $param['rec_person']!=''){
            $where .= ' AND l.rec_person like "'.$param['rec_person'].'"';
        }

        if(isset($param['status']) && $param['status']!=0){
            $where .= ' AND l.status = "'.$param['status'].'"';
        }
        $data = $model->lists($where,$page);//print_r($data);
        $this->assign(
            'data',$data['data']
        );
        $this->assign('pageData',$data['page']);

        // 设置页面中的信息
        $this->assign(array(
            '_page_btn_name' => '添加借贷',
            '_page_btn_link' => url('add'),
        ));


        return $this->fetch();
    }

    public function personList(){
        $request = Request::instance();

        $name = $request->param('name');

        $personEvent = \think\Loader::controller('Person','event');
        $data = $personEvent->lst(1,10000,array('name'=>$name));
        die(json_encode($data['data']));

    }
    public function add()
    {
        $request = Request::instance();
        if($request->isPost()){
            //print_r($request->param());
            $data = $request->param();
            if(isset($data['status'])){
                switch($data['status']){
                    case 0 : $data['status'] = '已保存';
                    break;
                    case 1 : $data['status'] = '已提交';
                    break;
                    case 2 : $data['status'] = '已结束';
                    break;
                }
            }
            $res = $this->serviceModel->add($data);
            die(json_encode($res));
        }elseif($request->isGet()){
            return $this->fetch();
        }


    }
    public function edit()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param('id');
            $data = $request->param();
            if(isset($data['status'])){
                switch($data['status']){
                    case 0 : $data['status'] = '已保存';
                        break;
                    case 1 : $data['status'] = '已提交';
                        break;
                    case 2 : $data['status'] = '已结束';
                        break;
                }
            }
            unset($data['id']);
            $res = $this->serviceModel->edit($id,$data);
            die(json_encode($res));
        }elseif($request->isGet()){
            $id = $request->param('id');
            $data = $this->serviceModel->row($id);
            $this->assign('data',$data);
            return $this->fetch();
        }

    }

    public function over()
    {
        $request = Request::instance();
        if($request->isPost()){
            $id = $request->param('id');
            $data = $request->param();
            $data['manual_over_time'] = date('Y-m-d H:i:s');
            $data['status'] = '已结束';
            unset($data['id']);
            $res = $this->serviceModel->edit($id,$data);
            die(json_encode($res));
        }elseif($request->isGet()){
            $id = $request->param('id');
            $data = $this->serviceModel->row($id);
            $this->assign('data',$data);
            return $this->fetch();
        }

    }
    public function delete()
    {
        $request = Request::instance();
        $res = $this->serviceModel->del($request->param('id'));
        die(json_encode($res));
    }

    public function getExpIncome(){
         $model = \think\Loader::model('Loan','logic');
        $request = Request::instance();
         $begin = $request->param('begin_date');
         $end = $request->param('end_date');
         $amount = $request->param('amount');
         $rate = $request->param('rate');
         $period = $request->param('period');
         $rec_rate = $request->param('rec_rate');
         $res = $model->computeIncome($amount,$begin,$end,$rate,$period,$rec_rate);
         die(json_encode($res));
    }

    /**
     * 获取最终收益
     */
    public function getFinalIncome(){
        $model = \think\Loader::model('Loan','logic');
        $request = Request::instance();
        $end = $request->param('real_end_date');
        $id = $request->param('id');
        $res = $model->computeFinalIncome($id,$end);
        die(json_encode($res));
    }




}
