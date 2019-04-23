<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class MenuController extends BaseController {
    //获取菜单列表
    public function getMenuList(){
        $userId=I('userId');
        $jurisdictionTable=M('admin_jurisdiction');
        $jurisdictionStr=$jurisdictionTable->where("userid=$userId")->getField("menuid");  //获取菜单id
        if($jurisdictionStr){
            $jurisdictionIdList = explode(',',$jurisdictionStr);
            $menuTable=M("admin_menu");
            $arr=array();
            foreach ($jurisdictionIdList as $k=>$v){
                $find=$menuTable->where("id=$v and pid=0")->find();
                if(!empty($find)){
                    array_push($arr,$find);
                }
            }
            foreach ($arr as $k=>$v){
                $subs=$menuTable->where("pid={$v['id']}")->select();
                if(!empty($subs)) {
                    $arr[$k]['subs']=$subs;
                }
                foreach ($arr[$k]['subs'] as $key=>$val){
                    $subsThree=$menuTable->where("pid={$val['id']}")->select();
                    if(!empty($subsThree)){
                        $arr[$k]['subs'][$key]['subs']=$subsThree;
                    }
                }
            }
            if($arr){
                $result=array(
                    'success'=>true,
                    'msg'=>'请求成功',
                    'data' => $arr
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'请求失败',
                    'data' => ''
                );
            }
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有查询到此用户的权限',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}