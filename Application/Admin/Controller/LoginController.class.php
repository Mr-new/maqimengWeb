<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    //注册接口
//    public function goregister(){
//        $user=M("user");
//        $data['username']=I("username");
//        $data['password']=md5(I("password"));
//        $find=$user->where("username='{$data['username']}'")->find();
//        if($find){
//            //此时说明该用户名已被注册
//            $result=array(
//                'code'=>1001,
//                'msg'=>"该用户已被注册",
//            );
//        }else{
//            $add=$user->add($data);
//            if($add){
//                $result=array(
//                    'code'=>1999,
//                    'msg'=>"注册成功",
//                );
//            }else{
//                $result=array(
//                    'code'=>1002,
//                    'msg'=>"注册失败",
//                );
//            }
//        }
//        $this->ajaxReturn($result);
//    }
    //登陆接口
    public function Login(){
        $user=M('admin_user');
        $username=I('username');
        $password=I('password');
        $find=$user->where("username='$username'")->find();
        if($find){
            if($password==$find['password']){
                $userInfo=array(
                    'id'=>$find['id'],
                    'username'=>$find['username'],
                );
                session('userInfo',$userInfo);  //记录用户登录状态
                $role=M('admin_role');
                $find['roleName']=$role->where("id={$find['roleid']}")->getField("title");
                $images=new ImagesController();
                $newFind=$images->getImagesList($find,'picid', 'pic');
                unset($newFind['password']);
                unset($newFind['roleid']);
                $result=array(
                    'success'=>true,
                    'msg'=>'登陆成功',
                    'data'=>$newFind,
                );

            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'密码错误'
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有查找到此用户哟'
            );
        }
        $this->ajaxReturn($result);
    }
    //退出登陆
    public function quit(){
        session('userInfo',null);
        if(!session('?userInfo')){
            $result=array(
                'success'=>true,
                'msg'=>'您已退出登陆',
                'data' => ''
            );
            $this->ajaxReturn($result);
        }
    }



}