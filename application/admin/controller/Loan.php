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
        $event  = \think\loader::controller('Loan','event');
        $page = isset($_GET['page']) ? $_GET['page'] : 1 ;

        $where = array();
        $where['del'] = 0;
        $param = $request->param();
        if(isset($param['end_date_l'])){
            $where['end_date_l'] = $param['end_date_l'];
        }
        if(isset($param['end_date_r'])){
            $where['end_date_r'] = $param['end_date_r'];
        }

        if(isset($param['amount_l'])){
            $where['loan_amount_l'] = $param['amount_l'];
        }

        if(isset($param['amount_r'])){
            $where['loan_amount_r'] = $param['amount_r'];
        }

        if(isset($param['rec_person']) ){
            $where['rec_person'] = $param['rec_person'];
        }
        if(isset($param['status'])){
            switch($param['status']){
                case 'over' :
                    $where['status'] = '已结束';
                    break;
                case 'unover' :
                    $where['status'] = array('in','已保存,已提交');
            }
        }

        $data = $event->lst($where,$page);//print_r($data);
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
            $data = $request->param();//print_r($data);
            if($data['exp_final_income']!=$data['real_final_income'] && $data['note']==''){
                die(json_encode(array('success'=>0,'info'=>'最后收益不等于预期分成后收益，请填写备注')));
            }
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
