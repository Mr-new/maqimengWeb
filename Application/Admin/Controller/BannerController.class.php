<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\BaseController;
class BannerController extends BaseController {
    //获取banner轮播图列表
    public function getBannerList(){
        $select_word=I('select_word'); //搜索关键词
        $pageIndex=I('pageIndex');  //当前第几页
        $number=I('number');  //每页显示数量
        $banner=M('banner');
        $map['b_title']=array('like',"%$select_word%");
        $list=$banner->where($map)->order('b_datetime desc')->page($pageIndex.",$number")->select();
        $count=$banner->where($map)->count();// 查询满足要求的总记录数
        $sumPage=ceil($count/$number);  //总页数=总条数/每页显示的条数
        if($list){
            $images=new ImagesController();
            $slist=$images->getImagesList($list,'b_imgid', 'b_image');
            $result=array(
                'success'=>true,
                'msg'=>'请求成功',
                'data' => array(
                    'sumPage' => $sumPage,
                    'currentPage' => $pageIndex,
                    'list' => $slist
                )
            );
        }else{
            $result=array(
                'success'=>false,
                'msg'=>'没有找到您需要的数据哟',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
    //修改banner轮播图信息
    public function saveBanner(){
        $banner=M('banner');
        $id=I('b_id')?I('b_id'):null;  //主键id
        $imgId=I('b_imgid')?I('b_imgid'):null;  //图片id
        $data['b_title']=I('b_title');
        $data['b_datetime']=date('Y-m-d H:i:s',time());
        if(!empty($imgId)){
            $data['b_imgid']=$imgId;
            $add=$banner->add($data);
            if($add){
                $result=array(
                    'success'=>true,
                    'msg'=>'添加成功',
                    'data' => $add
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'添加失败',
                    'data' => ''
                );
            }
        }else{
            $save=$banner->where("b_id=$id")->save($data);
            if($save){
                $result=array(
                    'success'=>true,
                    'msg'=>'修改成功',
                    'data' => $id
                );
            }else{
                $result=array(
                    'success'=>false,
                    'msg'=>'修改失败',
                    'data' => ''
                );
            }
        }
        $this->ajaxReturn($result);
    }
    //删除banner轮播图
    public function deleteBanner(){
        $id=I('b_id');
        $banner=M('banner');
        $imgId=$banner->where("b_id=$id")->getField('b_imgid');
        $images=new ImagesController();
        $images->deleteImage($imgId);
        $delSql=$banner->where("b_id=$id")->delete();
        if($delSql){
            $result=array(
                'success'=>true,
                'msg'=>'删除成功',
                'data' => $delSql
            );
        }else{
            $result=array(
                'success'=>true,
                'msg'=>'删除失败',
                'data' => ''
            );
        }
        $this->ajaxReturn($result);
    }
}