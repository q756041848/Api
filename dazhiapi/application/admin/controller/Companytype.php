<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Companytype extends Common
{
    //显示企业类型
    public function index(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['c_type_name']= array('like',"%".$val."%");
        }

        $adminList=Db::table('dzjn_scmember_company_type')->where($map)->order("c_type_sort desc")->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);

        return $this->fetch('companytype');
    }

    //显示添加企业类型页面
    public function companytypeAdd(){
        return $this->fetch('companytypeadd');
    }

    //添加企业类型
    public function doAdd(){
        $admin = db('scmember_company_type');

        $username = input('post.c_type_name');
        $check_user = $admin->where(array('c_type_name'=>$username))->find();
        if ($check_user) {
            $result['status'] = 0;
            $result['info'] = '此类型已存在!';
            return $result;
        }

        $data['c_type_name'] = $username;
        $data['c_type_sort'] = input('post.c_type_sort');
        $data['c_time'] = time();

        $admin->insert($data);

        $result['status'] = 1;
        $result['info'] = '类型添加成功!';
        $result['url'] = url('index');

        return $result;
    }


    //删除工作类型
    public function cpmpanytypeDel(){
        $admin_id=input('get.c_type_id');

        if (empty($admin_id)){
            $result['status'] = 0;
            $result['info'] = '福利ID不存在!';
            $result['url'] = url('index');
            return $result;
        }
        db('scmember_company_type')->where('c_type_id='.$admin_id)->delete();
        $this->redirect('index');
    }


    //显示修改类型信息
    public function showupdate(){
        $info = db('scmember_company_type')->where('c_type_id='.input('c_type_id'))->find();
        $this->assign('info', $info);
        return $this->fetch('showupdate');
    }


    //修改类型信息
    public function doupdate(){
        $admin=db('scmember_company_type');
        $cid = input('post.c_type_id');
        $d['c_type_name']=input('post.c_type_name');
        $d['c_type_sort'] = input('post.c_type_sort');
        $d['c_time'] = input('post.c_time');

        $admin->where("c_type_id",$cid)->update($d);
        $result['status'] = 1;
        $result['info'] = '工作类型修改成功!';
        $result['url'] = url('index');
        return $result;
    }
}