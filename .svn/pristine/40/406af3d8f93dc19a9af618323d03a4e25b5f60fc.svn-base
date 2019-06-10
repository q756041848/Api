<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Companyscale extends Common
{
    //显示公司规模
    public function index(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['c_scale_name']= array('like',"%".$val."%");
        }

        $adminList=Db::table('dzjn_scmember_company_scale')->where($map)->order("c_scale_sort desc")->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);

        return $this->fetch('companyscale');
    }

    //显示添加规模界面
    public function showAdd(){
        return $this->fetch('showadd');
    }

    //插入福利页面
    public function doAdd(){
        $admin = db('scmember_company_scale');

        $username = input('post.c_scale_name');
        $check_user = $admin->where(array('c_scale_name'=>$username))->find();
        if ($check_user) {
            $result['status'] = 0;
            $result['info'] = '此规模已经存在!';
            return $result;
        }

        $data['c_scale_name'] = $username;
        $data['c_scale_sort'] = input('post.c_scale_sort');
        $data['c_scale_time'] = date('Y-m-d H:i:s');

        $admin->insert($data);

        $result['status'] = 1;
        $result['info'] = '添加成功!';
        $result['url'] = url('index');


        return $result;
    }


    //删除
    public function companyscaleDel(){
        $admin_id=input('get.c_scale_id');

            if (empty($admin_id)) {
                $result['status'] = 0;
                $result['info'] = '福利ID不存在!';
                $result['url'] = url('index');
                return $result;
            }
            db('scmember_company_scale')->where('c_scale_id='.$admin_id)->delete();
            $this->redirect('index');

    }


    //显示修改
    public function showupdate(){
        $info = db('scmember_company_scale')->where('c_scale_id='.input('c_scale_id'))->find();
        $this->assign('info', $info);
        return $this->fetch('showupdate');
    }


    //修改
    public function doUpdate(){
        $admin=db('scmember_company_scale');
        $cid=input('post.c_scale_id');

        $map['c_scale_name'] =input("c_scale_name");
        $map['c_scale_sort'] =input("c_scale_sort");
        $map['c_scale_time'] =date("Y:m:s H:i:s");

        $admin->where("c_scale_id",$cid)->update($map);
        $result['status'] = 1;
        $result['info'] = '修改成功!';
        $result['url'] = url('index');
        return $result;

    }



}