<?php
/**
2018/8/22*/
namespace app\admin\controller;
use think\Db;
use think\controller;

class Work extends Common{

        public function index(){

            $menu=db('data_work')->field("w_id,w_name")->where('w_pid=0')->select();//一级菜单
            $this->assign('menu', $menu);

            $val=input('r_title');
            $url['val'] = $val;
            $this->assign('testval',$val);

            $pid = input("pid");
            $url['pid'] = $pid;
            $this->assign('wid',$pid);

            $map=array();
            if($val){
                $map['w_name']= array('like',"%".$val."%");
            }
            if($pid){
                $map['w_pid'] = $pid;
            }else{
                $map['w_pid'] = 0;
            }

            $workList=db('data_work')
                ->where($map)
                ->order('w_sort desc')
                ->paginate(config('pageSize'));
            $workList->appends($url);


            // 模板变量赋值
            $page = $workList->render();

            $this->assign('page', $page);
            $this->assign('work_list',$workList);

            return $this->view->fetch('work');
        }

    //显示添加页面
    public function work_add(){
        $rel=db('data_work')->where('w_pid=0')->select();
        $this->assign('worklist', $rel);
        return $this->fetch();
    }

    //执行添加
    public function work_doAdd(){

        $data['w_pid']=input('w_pid');//所属一级菜单 不填默认为0
        $data['w_name']=input('w_name');
        $data['w_sort']=input('w_sort');//排序
        $data['w_time']=time();
        $rel=db('data_work')->insert($data);
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
    public function work_update(){
        $id=input('w_id');
        $worklist=db('data_work')->where('w_pid=0')->select();//一级菜单

        $rel=db('data_work')->where("w_id='$id'")->find();


        // 模板变量赋值
        $this->assign('work_list',$rel);
        $this->assign('worklist', $worklist);//降一级菜单映射

        return $this->fetch();
    }

    //执行修改页面
    public function work_doUpdate(){
        $id=input('w_id');
        $data['w_pid']=input('w_pid');//所属一级菜单 不填默认为0
        $data['w_name']=input('w_name');
        $data['w_sort']=input('w_sort');
        $data['w_time']=time();
        $rel=db('data_work')->where("w_id='$id'")->update($data);

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
    public function work_del(){
        $id=input('w_id');
        $rel=db('data_work')->where("w_id='$id'")->delete();

        if($rel>0){
            $this->redirect('index');
        }
    }




}