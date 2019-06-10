<?php
namespace app\admin\controller;


class Exper extends Common {
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
           $map['e_name']= array('like',"%".$val."%");
       }
        $res=db('data_exper')->where($map)->order('e_time desc')->select();
        $this->assign('res',$res);
        return $this->view->fetch();
   }
    //删除数据
    public function delexper(){
        $eid=input('get.e_id');
            db('data_exper')->where('e_id='.$eid)->delete();
            $this->redirect('index');
    }
    //显示添加工作经验
    public function addexper(){
        return $this->view->fetch();
    }
    //执行添加工作经验
    public function doaddexper(){
        $admin = db('data_exper');
        $username = input('post.e_name');
        $data = array(
            'e_name' => $username,
            'e_sort' => input('post.e_sort'),
            'e_time' => time(),
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '用户添加成功!';
        $result['url'] = url('index');
        return $result;
    }
    //显示修改页面
    public function showUpdate(){
        $auth_group = db('data_exper')->select();
        $info = db('data_exper')->where('e_id='.input('e_id'))->find();
        $this->assign('info', $info);
        $this->assign('auth_group', $auth_group);
        return $this->view->fetch();
    }
    //执行修改操作
    public function doUpdate(){
        $admin=db('data_exper');
        $where['e_id'] = input('e_id');
        if(input('post.e_name')){
            $admindata['e_name']=input('e_name');
        }
        $admindata['e_sort']=input('e_sort');
        $admin->where($where)->update($admindata);
        $result['status'] = 1;
        $result['info'] = '工作经验修改成功!';
        $result['url'] = url('index');
        return $result;
    }

}