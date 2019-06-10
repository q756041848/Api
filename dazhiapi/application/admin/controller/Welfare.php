<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Welfare extends Common
{
    //显示福利页面和查询福利
    public function index(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['w_name']= array('like',"%".$val."%");
        }

        $adminList=Db::table('dzjn_data_welfare')->where($map)->order("w_sort desc")->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);

        return $this->fetch('welfare');
    }

    //显示插入福利页面
    public function welfareAdd(){
        return $this->fetch('welfare_add');
    }

    //插入福利页面
    public function welfare_Add(){
        $admin = db('data_welfare');

        $username = input('post.username');
        $check_user = $admin->where(array('w_name'=>$username))->find();
        if ($check_user) {
            $result['status'] = 0;
            $result['info'] = '福利已存在，请重新输入福利!';
            return $result;
        }

        $data['w_name'] = $username;
        $data['w_sort'] = input('post.pwd');
        $data['w_time'] = time();

        $admin->insert($data);

        $result['status'] = 1;
        $result['info'] = '福利添加成功!';
        $result['url'] = url('index');


        return $result;
    }


    //删除福利
    public function welfareDel(){
        $admin_id=input('get.w_id');
        if (session('aid')==1){
            if (empty($admin_id)){
                $result['status'] = 0;
                $result['info'] = '福利ID不存在!';
                $result['url'] = url('index');
                return $result;
            }
            db('data_welfare')->where('w_id='.$admin_id)->delete();
            $this->redirect('index');
        }
    }


    //显示修改福利信息
    public function welfareUpdate(){
        $info = db('data_welfare')->where('w_id='.input('w_id'))->find();
        $this->assign('info', $info);
        return $this->fetch('welfare_update');
    }


    //修改学历信息
    public function welfare_Update(){
        $admin=db('data_welfare');
        $pwd=input('post.pwd');

        $map['w_id'] = array('neq',input('post.w_id'));
        $where['w_id'] = input('post.w_id');

        //检查福利是否存在
        if(input('post.username')){
            $map['w_name'] = input('post.username');
            $check_user = $admin->where($map)->find();
            if ($check_user) {
                $result['status'] = 0;
                $result['info'] = '福利已存在，请重新输入福利!';
                $result['url'] = url('educationUpdate');
                return $result;
            }
        }

        if ($pwd){
            $admindata['w_sort']=input('post.pwd');
        }
        if(input('post.username')){
            $admindata['w_name']=input('post.username');
        }

        $admin->where($where)->update($admindata);
        $result['status'] = 1;
        $result['info'] = '福利修改成功!';
        $result['url'] = url('index');
        return $result;

    }



}