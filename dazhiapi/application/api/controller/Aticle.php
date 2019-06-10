<?php
namespace app\Juenapi\controller;
namespace app\api\controller;
use think\Controller;
use think\Db;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

class Aticle extends Controller
{
    //一封信、奖励政策..传值到关于我们页面
    public function aboutus(){
        $type=input('type')?input('type'):1;
        $artical=db('data_article')->where('a_type',$type)->find();

        if($artical){
            $data['code']='1';
            $data['msg']='返回成功';
            $data['data']=$artical;

            return json($data);
        }
    }

    //公司联系方式传值到关于我们页面
    public function Contactus(){
        $artical=db('data_aboutus')->select();

        if($artical){
            $data['code']='1';
            $data['msg']='返回成功';
            $data['data']=$artical;

            return json($data);
        }

    }

    //帮助中心信息传值到关于我们页面
    public function helpcenter(){
        $help=db('data_helpcenter')->order('h_titleid asc')->select();

        if($help){
            $data['code']='1';
            $data['msg']='返回成功';
            $data['data']=$help;

            return json($data);
        }

    }

}
