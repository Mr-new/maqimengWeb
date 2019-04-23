<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class IndexController extends BaseController {
    //显示首页
    public function index(){

        $result=array(
            'success'=>true,
            'msg'=>'请求成功',
            'data' => 111
        );
        $this->ajaxReturn($result);
    }
}