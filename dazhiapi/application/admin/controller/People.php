<?php
/**
2018/8/22*/
namespace app\admin\controller;
use think\Db;
use think\controller;

class People extends Common{

        public function index(){
            $this->assign("uid",session('aid'));
            $val=input('l_phone');
            $url['val'] = $val;
            $this->assign('l_phone',$val);

            $ltype = input("l_type");
            $url['ltype'] = $val;
            $this->assign('ltype',$ltype);
            $map=array();
            if($val){
                $map['l_phone']= array('like',"%".$val."%");
            }
            if($ltype){
                $map['l_type']= $ltype;
            }
            $userList=db('user_login')
                ->where($map)
                ->order('l_time desc')
                ->paginate(config('pageSize'));
            $userList->appends($url);
            // 模板变量赋值
            $page = $userList->render();
            $this->assign('page', $page);
            $this->assign('user_list',$userList);

            return $this->view->fetch('user');
        }

    //执行删除
    public function user_del(){
        $id=input('l_id');
        $rel=db('user_login')->where("l_id='$id'")->delete();
        if($rel>0){
            $this->redirect('index');
        }
    }


}