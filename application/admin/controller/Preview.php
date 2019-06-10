<?php
/**
 * Created by PhpStorm.
 * User: shuyuan
 * Date: 2019/4/18
 * Time: 11:15 AM
 */

namespace app\admin\controller;
use think\Controller;
use common;
class Preview extends Controller
{
    public  function  mo(){
        $title['c_url'] = input('c_url')?array('like', "%".input('c_url')."%"):array('neq', "*");
        $contt = db('connector')
            ->where($title)
            ->select();
        $this->assign('contt',$contt);
        return $this->fetch();
    }
    //主页
    public  function index()
    {
        $scheme=array();
        $scheme=
        [
            '1'=>'application/json',
            '2'=>'application/x-www-form-urlencoded',
            '3'=>'multipart/form-data'
        ];
        $method=array();
        $method=
        [
                '0'=>'GET',
                '1'=>'POST',
                '2'=>'PUT',
                '3'=>'DELETE',
                '4'=>'PATCH',
                '5'=>'OPTIONS'
        ];
        //接口和文件夹
        $pfolder = \db('pfolder')->where('pm_id',input('id'))->select();
        foreach($pfolder as $key=>&$v)
        {
            $v['con']=db('connector')->where('c_pidname='.$v['id'])->select();
        }
        //返回码
        $code = db('code')->where('c_pid',input('id'))->select();
        //所有接口
        $con = db('connector')
            ->where('c_pm',input('id'))
            ->alias('a')
            ->join('pfolder b','a.c_pidname = b.id')
            ->field('a.*,b.name')
            ->select();
        $word = db('pm')->field('p_title,p_txt')->where('p_id',input('id'))->find();
        $this->assign('p_title',$word['p_title']);
        $this->assign('p_txt',$word['p_txt']);
        $this->assign('con',$con);
        $this->assign('code',$code);
        $this->assign('scheme',$scheme);
        $this->assign('method',$method);
        $this->assign('pfolder',$pfolder);
        $this->assign('contt',$con);
        return $this->fetch();
    }

    //响应内容
    public function curl()
    {
         $ids=input('ids');
         $id=input('id');
         $type=input('code');
        $info=db('connector')
        ->where('c_id',$ids)
        ->value('c_url');
        $curlpage = $type='' ? ("$info") : ("$info/?$type=$id");
        $curl= $type='' ? geturl("$info") : geturl("$info/?$type=$id");
        $toub=get_head("$info");
        $cu=getCurlCommand();
        $header_info=get_headers("$info");
        $arr=[$curl,$toub,$cu,$header_info,$curlpage];
        return $arr;
    }
      //请求参数请求响应
    public   function parameter()
    {
        return  db('connector')->where(input('post.'))->value('c_required');
    }
    //请求响应
    public function response(){
        return  db('connector')->where(input('post.'))->value('c_response');
  
    }
}
