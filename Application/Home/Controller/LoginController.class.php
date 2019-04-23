<?php
namespace Home\Controller;
use Think\Cache\Driver\Redis;
use Think\Controller;
class LoginController extends Controller {
    //注册接口
    public function goregister(){
        $user=M("user");
        $data['username']=I("username");
        $data['password']=md5(I("password"));
        $find=$user->where("username='{$data['username']}'")->find();
        if($find){
            //此时说明该用户名已被注册
            $result=array(
                'code'=>1001,
                'msg'=>"该用户已被注册",
            );
        }else{
            $add=$user->add($data);
            if($add){
                $result=array(
                    'code'=>1999,
                    'msg'=>"注册成功",
                );
            }else{
                $result=array(
                    'code'=>1002,
                    'msg'=>"注册失败",
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //登陆接口
    public function gologin(){
        $user=M('user');
        $username=I('username');
        $password=md5(I('password'));
        $find=$user->where("username='$username'")->find();
        if($find){
            if($password==$find['password']){
                $result=array(
                    'code'=>'1999',
                    'msg'=>'登陆成功',
                    'username'=>$find['username']
                );
            }else{
                $result=array(
                    'code'=>'1002',
                    'msg'=>'密码错误'
                );
            }
        }else{
            $result=array(
                'code'=>'1001',
                'msg'=>'您还没有注册，请先注册'
            );
        }
        $this->ajaxReturn($result);
    }



}