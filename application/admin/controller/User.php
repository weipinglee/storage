<?php
/**
 * Created by PhpStorm.
 * User: weipinglee
 * Date: 2018/5/25
 * Time: 10:10
 */

namespace app\admin\Controller;


class User extends Base{

    public function _initialize()
    {
        parent::_initialize();

        $this->view->engine->layout('Layout/layout');

    }



}
