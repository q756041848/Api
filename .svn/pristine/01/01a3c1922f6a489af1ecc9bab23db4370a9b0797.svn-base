<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
class Image extends Controller
{
    //显示图片上传示例页面
    public function show(){
        $sid = input("sid");
        if(!$sid){
            echo "请将业务id以参数的形式传进来,参数名为sid";
            exit();
        }
        $this->assign("sid",$sid);
        //查询之前已经上传过的图片
        $images = db('recruit_image')->where('i_rid',$sid)->select();
        $this->assign('images',$images);
        return $this->view->fetch();
    }
    //上传图片至服务器
    public function uploadImage(){
        $sid = input("sid");
        if(!$sid){
            echo "图片上传异常！";
            exit();
        }
        $file = request()->file('fileList');
        if($file){
            $info = $file->move(ROOT_PATH . 'public'.DS.'static' . DS . 'uploads');
            $filename=$info->getSaveName();
            if($filename){
                //信息保存至数据库
                $d['i_rid']=$sid;
                $d['i_image']=$filename;
                $d['i_time']=time();
                $r=db('recruit_image')->insert($d);
                echo $filename;
            }else{
                echo "图片上传失败";
            }
        }else{
            echo "图片获取失败！";
        }
    }
    //删除图片
    public function deleteImg(){
        $id = input("id");
        $r = db("recruit_image")->where('i_id',$id)->find();
        if($r){
            db("recruit_image")->where('i_id',$id)->delete();
            //删除文件
            $src = ROOT_PATH . 'public'.DS.'static' . DS . 'uploads'.DS.$r['i_image'];
            unlink($src);
            echo 1;
        }else{
            echo 0;
        }
    }

}