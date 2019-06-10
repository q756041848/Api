<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Education extends Common
{
    //显示学历页面和查询学历
    public function index(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['e_name']= array('like',"%".$val."%");
        }

        $adminList=Db::table('dzjn_data_education')->where($map)->order("e_sort desc")->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);

        return $this->fetch('education');
    }

    //显示插入学历页面
    public function educationAdd(){
        return $this->fetch('education_add');
    }

    //插入学历页面
    public function education_Add(){
        $admin = db('data_education');

        $username = input('post.username');
        $check_user = $admin->where(array('e_name'=>$username))->find();
        if ($check_user) {
            $result['status'] = 0;
            $result['info'] = '学历已存在，请重新输入用户名!';
            return $result;
        }

        $data['e_name'] = $username;
        $data['e_sort'] = input('post.pwd');
        $data['e_time'] = time();

        $admin->insert($data);

        $result['status'] = 1;
        $result['info'] = '用户添加成功!';
        $result['url'] = url('index');


        return $result;
    }


    //删除学历
    public function educationDel(){
        $admin_id=input('get.e_id');
        if (session('aid')==1){
            if (empty($admin_id)){
                $result['status'] = 0;
                $result['info'] = '学历ID不存在!';
                $result['url'] = url('index');
                return $result;
            }
            db('data_education')->where('e_id='.$admin_id)->delete();
            $this->redirect('index');
        }
    }


    //显示修改学历信息
    public function educationUpdate(){
        $info = db('data_education')->where('e_id='.input('e_id'))->find();
        $this->assign('info', $info);
        return $this->fetch('education_update');
    }


    //修改学历信息
    public function education_Update(){
        $admin=db('data_education');
        $pwd=input('post.pwd');

        $map['e_id'] = array('neq',input('post.e_id'));
        $where['e_id'] = input('post.e_id');

       //检查学历是否存在
        if(input('post.username')){
            $map['e_name'] = input('post.username');
            $check_user = $admin->where($map)->find();
            if ($check_user) {
                $result['status'] = 0;
                $result['info'] = '学历已存在，请重新输入学历!';
                $result['url'] = url('educationUpdate');
                return $result;
            }
        }

        if ($pwd){
            $admindata['e_sort']=input('post.pwd');
        }
        if(input('post.username')){
            $admindata['e_name']=input('post.username');
        }

        $admin->where($where)->update($admindata);
        $result['status'] = 1;
        $result['info'] = '学历修改成功!';
        $result['url'] = url('index');
        return $result;

    }



}