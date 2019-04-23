<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //显示首页
    public function index(){
        $main=M("main");
        $imgurl=C('imgurl');
        $sql=$main->select();
        for($i=0;$i<count($sql);$i++){
            $sql[$i]['m_minimg']=$imgurl.$sql[$i]['m_minimg'];
            $sql[$i]['m_maximg']=$imgurl.$sql[$i]['m_maximg'];
        }
        $this->assign('list',$sql);
        $m_type=M("m_type");
        $sel=$m_type->select();
        $this->assign('sel',$sel);
        $banner=M("banner");
        $b=$banner->order("b_datetime desc")->limit(3)->select();
        $bdata=$this->imgurlfor($b,"b_image");
        $this->assign('banner',$bdata);
        $this->display('index');
    }
    //返回ajax请求的项目详情数据
    public function details(){
        $id=I("post.id");
        $main=M("main");
        $sql=$main->where("m_id='$id'")->find();
        $sql['m_maximg']=C('imgurl').$sql['m_maximg'];
        echo json_encode($sql);
    }
    //返回ajax请求的项目缩略图数据
    public function probably(){
        $type=I("post.type");
        $main=M("main");
        $imgurl=C('imgurl');
        if(empty($type)){
            $sql=$main->field("m_id,m_title,m_minimg")->select();
        }else{
            $sql=$main->where("m_type='$type'")->field("m_id,m_title,m_minimg")->select();
        }
        for($i=0;$i<count($sql);$i++){
            $sql[$i]['m_minimg']=$imgurl.$sql[$i]['m_minimg'];
            $sql[$i]['m_maximg']=$imgurl.$sql[$i]['m_maximg'];
        }
        echo json_encode($sql);
    }
    //执行拼接图片路径函数   接收参数为：1.查询的返回值   2.存放图片的字段名
    public function imgurlfor($a,$name){
        $imgurl=C('imgurl');
        for($i=0;$i<count($a);$i++){
            $a[$i][$name]=$imgurl.$a[$i][$name];
        }
        return $a;
    }
    //跨域测试接口
    public function ajax(){
        header("Access-Control-Allow-Origin: *");
        $name=I("name");
        echo json_encode($name);
    }
}