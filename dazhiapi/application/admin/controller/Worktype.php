<?php
namespace app\admin\controller;


class Worktype extends Common {
    function _initialize(){
        parent::_initialize();
    }
   public function index(){
        //显示数据
       $val=input('val');
//       $url['val'] = $val;
       $this->assign('testval',$val);
       $map=array();
       if($val){
           $map['t_name']= array('like',"%".$val."%");
       }
        $res=db('data_worktype')->where($map)->order('t_sort desc')->select();
        $this->assign('res',$res);
        return $this->view->fetch();
   }
    //删除数据
    public function delworktype(){
        $tid=input('get.t_id');
            db('data_worktype')->where('t_id='.$tid)->delete();
            $this->redirect('index');
    }
    //显示添加工作经验
    public function addworktype(){
        return $this->view->fetch();
    }
    //执行添加工作经验
    public function doaddworktype(){
        $admin = db('data_worktype');
        $username = input('post.t_name');
        $data = array(
            't_name' => $username,
            't_sort' => input('post.t_sort'),
            't_time' => date('Y-m-d H:i:s'),
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '工作类型添加成功!';
        $result['url'] = url('index');
        return $result;
    }
    //显示修改页面
    public function showupdate(){
        $info = db('data_worktype')->where('t_id='.input('t_id'))->find();
        $this->assign('info', $info);
        return $this->view->fetch();
    }
    //执行修改操作
    public function doupdate(){
        $admin=db('data_worktype');
        $where['t_id'] = input('t_id');
        if(input('post.t_name')){
            $admindata['t_name']=input('t_name');
        }
        $admindata['t_sort']=input('t_sort');
        $r=$admin->where($where)->update($admindata);



        if($r){
            $result['status'] = 1;
            $result['info'] = '工作类型修改成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '工作类型修改失败!';
            $result['url'] = url('index');
        }

        return $result;
    }

}