<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Recruitrecommend extends Common
{
    //显示浏览记录页面和查询记录
    public function index(){
        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['l_name|r_title']= array('like',"%".$val."%");
        }

        $adminList=Db::table('dzjn_recruit_recommend')->alias('a')->join('dzjn_user_login l','a.r_uid = l.l_id')->where($map)->order("r_time desc")->paginate(config('pageSize'));
        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);
        return $this->fetch('recruitrecommend');
           
    }
    //根据判断条件以及获取到的手机号分别进行三个表查询并赋值
    public function broselect(){
        $where['l_phone']=input('get.l_phone');
        $_SESSION['l_phone'] = $where['l_phone'];
        $select = Db::table('dzjn_user_login')->where($where)->value("l_type");
        if($select=='1'){
          echo '<script>url="simSelect";window.location.href=url;</script>';
        }elseif($select=='2'){
          echo '<script>url="resume";window.location.href=url;</script>';
        }else{
          echo '<script>url="companyParticulars";window.location.href=url;</script>';
        }
    }

    public function simSelect(){
        $where['l_phone']=$_SESSION['l_phone']; 
        $simpeoplelist=Db::table('dzjn_user_login')->alias('a')->field("*,b.name as name,b2.name as name2,b3.name as name3")->join('dzjn_smember_simpeople l','a.l_phone =l.s_phone')->where($where)->join('dzjn_data_region b','l.s_privice = b.id')->join('dzjn_data_region b2','l.s_city = b2.id')->join('dzjn_data_region b3','l.s_area = b3.id')->find();
            $this->assign('simpeople',$simpeoplelist);
            return $this->fetch();
    }

    public function resume(){
        $where['l_phone']=$_SESSION['l_phone']; 
        $vipbase=Db::table('dzjn_user_login')->alias('a')->join('dzjn_vmember_vipbase l','a.l_phone =l.v_phone')->where($where)->find();//高端会员基本信息
        $select = Db::table('dzjn_user_login')->alias('a')->join('dzjn_vmember_vipbase l','a.l_phone =l.v_phone')->where($where)->value("v_id");
        $this->assign('vipbase',$vipbase);
        $vipedu=db('vmember_vipedu')->where('e_pid',$select)->order('e_begintime asc')->select();//教育经历
        $this->assign('vipedu',$vipedu);
        $vipwork=db('vmember_vipwork')->where('v_pid',$select)->order('v_begintime asc')->select();//工作经历
        $this->assign('vipwork',$vipwork);
        $vipfamily=db('vmember_vipfamily')->where('f_pid',$select)->select();//家庭基本情况
        $this->assign('vipfamily',$vipfamily);
        return $this->view->fetch();
    }

        public function companyParticulars(){
        $where['l_phone']=$_SESSION['l_phone'];   
        $company=Db::table('dzjn_scmember_company')->alias('a')->join('dzjn_user_login l','a.c_phone =l.l_phone')->where($where)->find();
            $this->assign('particulars',$company);
            return $this->fetch();
    }    
}
