<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use clt\Leftnav;
use app\admin\model\Smember as SmemberModel;
use app\admin\model\Param;
class Smember extends Common{
	//普通会员列表
    public  function simPeople(){

        $this->assign("uid",session('aid'));
        $job = db('data_work')->select();//工作模板条件搜索
        $this->assign('job',$job);//工作模板条件传值


        //搜索条件--姓名
        $s_name=input('s_name');
        $this->assign('s_name',$s_name);
        $map=array();
        $map['l_type']=1;
        if($s_name){
            $map['s_name']= array('like',"%".$s_name."%");
        }

        //电话
        $s_phone=input('s_phone');
        $this->assign('s_phone',$s_phone);
        if($s_phone){
            $map['s_phone']= array('like',"%".$s_phone."%");
        }

        //求职意向
        $s_job=input('s_job');
        $this->assign('s_job',$s_job);
        if($s_job&&$s_job!="求职意向"){
            $map['s_job']= array('like',"%".$s_job."%");
        }

         //地区
        $s_privice=input('r_privice');
        $this->assign('sr_privice',$s_privice);
        if($s_privice&&$s_privice!="全部"){
            $map['s_privice']= array('like',"%".$s_privice."%");
        }
        $s_city=input('r_city');
        $this->assign('sr_city',$s_city);
        if($s_city&&$s_city!="全部"){
            $map['s_city']= array('like',"%".$s_city."%");
        }
        $s_area=input('r_area');
        $this->assign('sr_area',$s_area);
        if($s_area&&$s_area!="全部"){
            $map['s_area']= array('like',"%".$s_area."%");
        }

        // if($val){
        //     $map['a.s_name|a.s_phone']= array('like',"%".$val."%");
        // }
        if(empty($map)){
            $simpeoplelist=db("user_login")->alias('u')->join('smember_simpeople lss',"u.l_id=lss.s_loginid",'left')
           // ->field("*,ldr.name as name,ldr2.name as name2,ldr3.name as name3")
//            ->join("dzjn_data_region ldr", "lss.s_privice = ldr.id")
//            ->join("dzjn_data_region ldr2", "lss.s_city = ldr2.id")
//            ->join("dzjn_data_region ldr3", "lss.s_area = ldr3.id")
            ->paginate(config('pageSize')); //分页
        }else{
            $simpeoplelist=db("user_login")->alias('u')->join('smember_simpeople lss',"u.l_id=lss.s_loginid",'left')
        //    ->field("*,ldr.name as name,ldr2.name as name2,ldr3.name as name3")
//            ->join("dzjn_data_region ldr", "lss.s_privice = ldr.id")
//            ->join("dzjn_data_region ldr2", "lss.s_city = ldr2.id")
//            ->join("dzjn_data_region ldr3", "lss.s_area = ldr3.id")
            ->where($map)
            ->paginate(config('pageSize')); //分页
        }     
        //echo db('smember_simpeople')->getlastsql();
        $simpeoplelist->appends($map);
        //获取分页显示
        $page = $simpeoplelist->render();

        // 模板变量赋值
        $this->assign('page', $page);
        $this->assign('simpeoplelist',$simpeoplelist);
        return $this->view->fetch();
    }

    //普通会员添加
    public function simAdd(){
        $data_work=db('data_work')->select();
        $this->assign('data_work',$data_work);
        return $this->fetch();
    }
    public function simInsert(){
        
        $smember_simpeople = db('smember_simpeople');
        $s_phone = input('post.s_phone');
        $check_user = $smember_simpeople->where(array('s_phone'=>$s_phone))->find();
        if ($check_user) {
            $result['status'] = 0;
            $result['info'] = '手机号已被注册，请不要重复注册!';
            return $result;
            exit;
        }
        $request = Request::instance();
        $data = array(
            's_name' => input('post.s_name'),
            's_sex' => input('post.s_sex'),
            's_birthday' => input('post.s_birthday'),
            's_phone' => input('post.s_phone'),
            's_privice' => input('post.r_privice'),
            's_city' => input('post.r_city'),
            's_area' => input('post.r_area'),
            's_job' => input('post.w_name'),
            's_time' => time(),
        );
        $rs = $smember_simpeople->insert($data);
        $result['status'] = 1;
        $result['info'] = '会员添加成功!';
        $result['url'] = url('simPeople');
        return $result;
    }

    //获取省信息
    public function getAddressByPid(){
        if(input("pid")){
            $pid=input("pid");
        }else{
            $pid=1;
        }
        $data = db("data_region")->where("pid='$pid'")->order("sort desc")->select();
        return json($data);
    }

    //删除会员
    public function simDel(){
        $smember=db('smember_simpeople');//普通会员表
        $userlogin=db('user_login');//登录表
        $s_id=input('get.s_id');//----登录表主键id
//        $s_loginid=$smember->where('s_id',$s_id)->value('s_loginid');//登录外键
        $re=$smember->where('s_loginid',$s_id)->value('s_id');
        if($re){
            $res=$userlogin->where('l_id',$s_id)->delete();//删除登录表
            $rs = $smember->where('s_id='.$re)->delete();//删除信息表
            $this->redirect('simPeople');
            if ($rs&&$res) {
                $result['status'] = 1;
                $result['info'] = '删除成功';
                $result['url'] = url('simPeople');
                return $result;
            }
        }else{
            $res=$userlogin->where('l_id',$s_id)->delete();//删除登录表
            $this->redirect('simPeople');
            if ($res) {
                $result['status'] = 1;
                $result['info'] = '删除成功';
                $result['url'] = url('simPeople');
                return $result;
            }
        }
    }
    //查询普通会员信息是否存在
    public function simSearch(){
        $sid=input('s_id');
        if($sid){
            $res=db('smember_simpeople')->where('s_id='.$sid)->find();
            if($res){
                $result['status'] = 1;
                $result['info'] = '查询成功';
            }
        }else{
            $result['status'] = 0;
            $result['info'] = '查询失败';
        }
        return json($result);
    }

    //更新会员信息
    public function simEdit(){
        $data_work = db('data_work')->select();
        $info = db('smember_simpeople')->where('s_id='.input('s_id'))->find();
        $this->assign('data_work', $data_work);
        $this->assign('info', $info);
        return $this->fetch();
    }
    public function simUpdate(){
        $where['s_id'] = input('post.s_id');
        $data = array(
            's_name' => input('post.s_name'),
            's_sex' => input('post.s_sex'),
            's_birthday' => input('post.s_birthday'),
            's_phone' => input('post.s_phone'),
            's_privice' => input('post.r_privice'),
            's_city' => input('post.r_city'),
            's_area' => input('post.r_area'),
            's_job' => input('post.w_name'),
        );

        $rs = db('smember_simpeople')->where($where)->update($data);
       
        if ($rs) {
            $result['status'] = 1;
            $result['info'] = '修改成功!';
            $result['url'] = url('simPeople');
            return $result;
        }else{
            $result['status'] = 0;
            $result['info'] = '修改失败!';
            $result['url'] = url('simPeople');
            return $result;
        }
    }
    public function simSelect(){
        $where['s_id']=input('get.s_id');     
        $simpeoplelist=db('smember_simpeople')->alias('lss')
            ->field("*,ldr.name as name,ldr2.name as name2,ldr3.name as name3")
            //->join('ls_auth_group ag','a.group_id = ag.group_id','left')
            //->field('a.*,ag.title')  //获取某个字段的值
            ->join("dzjn_data_region ldr", "lss.s_privice = ldr.id")
            ->join("dzjn_data_region ldr2", "lss.s_city = ldr2.id")
            ->join("dzjn_data_region ldr3", "lss.s_area = ldr3.id")
            ->where($where)
            ->find();
            
            $this->assign('simpeople',$simpeoplelist);
            return $this->fetch();
    }
}