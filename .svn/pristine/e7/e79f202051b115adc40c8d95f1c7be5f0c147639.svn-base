<?php
namespace app\admin\controller;
use think\Db;
use think\controller;

class Helpcenter extends Common{

    public function index(){

        $rel=db('data_helpcenter')->order('h_titleid','asc')->select();
        // 模板变量赋值
        $this->assign('info',$rel);

        return $this->fetch();
    }

    //显示添加页面
    public function helpcenter_add(){

        return $this->fetch();
    }

    //执行添加
    public function helpcenter_doAdd(){

        $data['h_titleid']=input('h_titleid');
        $data['h_title']=input('h_title');
        $data['h_content']=input('h_content');
        $data['h_time']=time();

        $rel=db('data_helpcenter')->insert($data);
        if($rel>0){
            $result['status'] = 1;
            $result['info'] = '添加成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '添加失败!';
            $result['url'] = url('index');

        }
        return $result;
    }

    //显示修改页面
    public function helpcenter_update(){
        $id=input('h_id');
        $rel=db('data_helpcenter')->where("h_id='$id'")->find();


        // 模板变量赋值
        $this->assign('info',$rel);

        return $this->fetch();
    }

    //执行修改页面
    public function helpcenter_doUpdate(){
        $id=input('h_id');
        $data['h_titleid']=input('h_titleid');
        $data['h_title']=input('h_title');
        $data['h_content']=input('h_content');
        $data['h_time']=time();
        $rel=db('data_helpcenter')->where("h_id='$id'")->update($data);

        if($rel>0){
            $result['status'] = 1;
            $result['info'] = '修改成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '修改失败!';
            $result['url'] = url('index');

        }
        return $result;

    }


    //执行删除
    public function contact_del(){
        $id=input('h_id');
        $rel=db('data_helpcenter')->where("h_id='$id'")->delete();

        if($rel>0){
            $this->redirect('index');
        }
    }




}