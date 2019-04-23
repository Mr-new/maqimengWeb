<?php
namespace Common\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
        if(!session('?userInfo')){
            $result=array(
                'success'=>true,
                'msg'=>'登陆失效',
                'data' => false
            );
            $this->ajaxReturn($result);
        }
    }
}