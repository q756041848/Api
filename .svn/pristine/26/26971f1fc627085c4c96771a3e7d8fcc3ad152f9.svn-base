<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
use app\admin\model\Scmember as MemberModel;
class Scmember extends Common{

	//企业会员列表
	public function company(){
        $this->assign("uid",session('aid'));
        $c_name = input('c_name');
        $c_contacts = input('c_contacts');
        $c_phone = input('c_phone');
        $c_addr = input('c_addr');
        $type = input('c_type');
        $scale = input('c_scale');
        $this->assign('c_name',$c_name);
        $this->assign('c_contacts',$c_contacts);
        $this->assign('c_phone',$c_phone);
        $this->assign('c_addr',$c_addr);
        $this->assign('type',$type);
        $this->assign('scale',$scale);
        $map=array();
        //$map['l_type']=3;
        if($c_name||$scale||$type||$c_contacts||$c_phone||$c_addr){
            $map['c_name']= array('like',"%".$c_name."%");
            $map['c_contacts'] = array('like',"%".$c_contacts."%");
            $map['c_phone'] =array('like',"%".$c_phone."%");
            $map['c_addr'] = array('like',"%".$c_addr."%");
            $map['c_type'] = array('like',"%".$type."%");
            $map['c_scale'] = array('like',"%".$scale."%");
        }
//        $company = db("user_login")->alias('u')->join("scmember_company c","u.l_id=c.c_loginid",'left')->where($map)->order('c_id desc')->select();

        $company = db("scmember_company")->where($map)->order('c_id desc')->select();



        foreach ($company as $key => $value) {
            if(strlen($company[$key]['c_des'])>20){
                $company[$key]['c_desp'] = $company[$key]['c_des'];
                $company[$key]['c_des'] = mb_substr($company[$key]['c_des'],0,20).'...';
            }
        }
        $c_type = db('scmember_company_type')->select();
        $c_scale = db('scmember_company_scale')->select();
        $this->assign('c_type',$c_type);
        $this->assign('c_scale',$c_scale);
        $this->assign('company',$company);
        return $this->view->fetch();
	}

    //添加企业会员页面
	public function companyAdd(){
		$c_type = db('scmember_company_type')->select();
		$c_scale = db('scmember_company_scale')->select();
		$this->assign('c_type',$c_type);
		$this->assign('c_scale',$c_scale);
        return $this->fetch();
	}

	//添加企业会员
	public function companyInsert(){
        $company = db('scmember_company');
        $companyname= input('post.c_name');
        $check_name = $company->where(array('c_name'=>$companyname))->find();
        if ($check_name) {
            $result['status'] = 0;
            $result['info'] = '企业已存在，请重新输入企业名称!';
            return $result;
            exit;
        }
        $request = Request::instance();
        $data = array(
            'c_name' => $companyname,
            'c_contacts' => input('post.c_contacts'),
            'c_phone' => input('post.c_phone'),
            'c_type' => input('post.c_type'),
            'c_scale' => input('post.c_scale'),
            'c_addr' => input('post.c_addr'),
            'c_longitude' => input('post.c_longitude'),
            'c_latitude' => input('post.c_latitude'),
            'c_des' => input('post.c_des'),
            'c_time' => date("Y-m-d H:i:s"),
            'c_sort' => 1
        );
        $company->insert($data);
        $result['status'] = 1;
        $result['info'] = '企业成员添加成功!';
        $result['url'] = url('company');
        return $result;
    }
    //删除企业会员
    public function companyDel(){
        $company_id=input('get.c_id');//登录表id

        if (session('aid')==1){
            if($company_id){
                $res=db('scmember_company')->where('c_id',$company_id)->delete();//删除信息表
                if($res){
                    $this->redirect('company');
                }else{
                    $result['status'] = 0;
                    $result['info'] = '删除失败';
                }
                return $result;
               // $cid=db('scmember_company')->where('c_loginid='.$company_id)->value('c_id');//获取信息表主键
//                if($cid){
//                    $res=db('scmember_company')->where('c_id',$cid)->delete();//删除信息表
//                    $r=db('user_login')->where('l_id',$company_id)->delete();//删除登录表
//                    if($res&&$r){
//                        $this->redirect('company');
//                    }
//                }else{
//                    $s=db('user_login')->where('l_id',$company_id)->delete();
//                    if($s){
//                        $this->redirect('company');
//                    }
//                }
            }

        }
    }
    //查询企业会员信息是否存在
    public function companySearch(){
        $cid=input('post.c_id');
        if($cid){
            $res=db('scmember_company')->where('c_id='.$cid)->find();
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
    //更新企业会员信息页面
    public function companyEdit(){
        $info = db('scmember_company')->where('c_id='.input('c_id'))->find();
        $c_type = db('scmember_company_type')->select();
		$c_scale = db('scmember_company_scale')->select();
		$this->assign('c_type',$c_type);
		$this->assign('c_scale',$c_scale);
        $this->assign('info', $info);
        return $this->view->fetch();
    }

    //更新企业会员信息
    public function companyUpdate(){
        $company=db('scmember_company');
        $map['c_id'] = array('neq',input('post.c_id'));
        $where['c_id'] = input('post.c_id');
        if(input('post.c_name')){
            $map['c_name'] = input('post.c_name');
            $check_user = $company->where($map)->find();
            if ($check_user) {
                $result['status'] = 0;
                $result['info'] = '	企业名称已存在，请重新输入企业名称!';
                return $result;

                exit;
            }
        }
        $data = array(
            'c_name' => input('post.c_name'),
            'c_contacts' => input('post.c_contacts'),
            'c_phone' => input('post.c_phone'),
            'c_type' => input('post.c_type'),
            'c_scale' => input('post.c_scale'),
            'c_addr' => input('post.c_addr'),
            'c_longitude' => input('post.c_longitude'),
            'c_latitude' => input('post.c_latitude'),
            'c_des' => input('post.c_des'),
            'c_time' => date("Y-m-d H:i:s"),
        );
        $company->where($where)->update($data);
        $result['status'] = 1;
        $result['info'] = '企业成员修改成功!';
        $result['url'] = url('company');
        return $result;
    }

    //企业会员详细页
    public function companyParticulars(){
        $where['c_id']=input('get.c_id');     
        $company=db('scmember_company')->where($where)->find();
        $this->assign('particulars',$company);
        return $this->fetch();
    }
}
?>