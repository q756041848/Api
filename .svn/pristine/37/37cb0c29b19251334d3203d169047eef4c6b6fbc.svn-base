<?php

namespace app\api\controller;

use think\Controller;
use think\Db;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

class Member extends Base
{
    //高端会员添加修改
    public function addVipbaseInfo()
    {
        //基础表信息
        $token = input('token');
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = $this->decrypt($token);
        $user = explode(",", $userinfo);
//        print_r($user);
        if ($user) {
            $r = db('vmember_vipbase')->where('v_loginid', $user[0])->find();
            if ($r) {
                $data = array(
                    'v_name' => input('name'),
                    'v_sex' => input('sex'),
                    'v_nation' => input('nation'),//民族
                    'v_political' => input('politics'),//政治面貌
                    'v_height' => input('height'),
                    'v_cardid' => input('card'),//身份证
                    'v_birthday' => input('birthday'),
                    'v_place' => input('place'),//籍贯
                    'v_education' => input('education'),
                    'v_marry' => input('marry'),
                    'v_phone' => input('telphone'),
                    'v_email' => input('mail'),
                    'v_txplace' => input('maliting'),//通讯地址
                    'v_zipcode' => input('postcode'),//邮编
                    'v_jmam' => input('urgency'),//紧急联系人
                    'v_jrelationship' => input('relation'),//与本人关系
                    'v_jphone' => input('urgenoyphone'),
                    'v_openid' => input('openid'),
                );
                $base = db('vmember_vipbase')->where('v_id', $r['v_id'])->update($data);
            } else {
                $data = array(
                    'v_name' => input('name'),
                    'v_sex' => input('sex'),
                    'v_nation' => input('nation'),//民族
                    'v_political' => input('politics'),//政治面貌
                    'v_height' => input('height'),
                    'v_cardid' => input('card'),//身份证
                    'v_birthday' => input('birthday'),
                    'v_place' => input('place'),//籍贯
                    'v_education' => input('education'),
                    'v_marry' => input('marry'),
                    'v_phone' => input('telphone'),
                    'v_email' => input('mail'),
                    'v_txplace' => input('maliting'),//通讯地址
                    'v_zipcode' => input('postcode'),//邮编
                    'v_jmam' => input('urgency'),//紧急联系人
                    'v_jrelationship' => input('relation'),//与本人关系
                    'v_jphone' => input('urgenoyphone'),
                    'v_time' => date('y-m-d H:i:s', time()),
                    'v_openid' => input('openid'),
                    'v_loginid'=>$user[0],
                );
                $base = db('vmember_vipbase')->insert($data);
            }
            if ($base) {
                $result = [
                    'code' => 1,
                    'msg' => '保存成功'
                ];
            } else {
                $result = [
                    'code' => 0,
                    'msg' => '保存失败'
                ];
            }
        }
        return json($result);
    }

    //根据token查询高端会员基本信息
    public function searchVipInfo()
    {
        $token = input('token');
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = $this->decrypt($token);
        $user = explode(",", $userinfo);
//        print_r($user);
        if ($user) {
            $res = db('vmember_vipbase')->where('v_loginid', $user[0])->find();
            if ($res) {
                $r['code'] = '1';
                $r['msg'] = '查询成功';
                $r['data'] = $res;
            } else {
                $r['code'] = '0';
                $r['msg'] = '查询失败';
                $r['data'] = $res;
            }
        } else {
            $r['code'] = 0;
            $r['msg'] = '登陆异常！';
            $r['data'] = array();
        }
        return json($r);
    }
    //添加/修改简历基本信息操作
//    public function getBaseInfo(){
//
//        $vname=input('name');
//        $vopenid=input('openid');
//        $opid=db('vmember_vipbase')->where('v_openid',$vopenid)->field('v_openid')->find();
//        //如果基本信息传参为空，调用查询接口，不为空调用添加/修改基本信息接口
//        if($vname==''){
//            $res=db('vmember_vipbase')->where('v_openid',$vopenid)->find();
//        }else{
//            //判断数据库中是否有openid，有就执行修改操作，没有就执行添加操作
//            if($opid['v_openid']==$vopenid){
//                $data=array(
//                    'v_name'=>$vname,
//                    'v_sex'=>input('sex'),
//                    'v_nation'=>input('nation'),//民族
//                    'v_political'=>input('politics'),//政治面貌
//                    'v_height'=>input('height'),
//                    'v_cardid'=>input('card'),//身份证
//                    'v_birthday'=>input('birthday'),
//                    'v_place'=>input('place'),//籍贯
//                    'v_education'=>input('education'),
//                    'v_marry'=>input('marry'),
//                    'v_phone'=>input('telphone'),
//                    'v_email'=>input('mail'),
//                    'v_txplace'=>input('maliting'),//通讯地址
//                    'v_zipcode'=>input('postcode'),//邮编
//                    'v_jmam'=>input('urgency'),//紧急联系人
//                    'v_jrelationship'=>input('relation'),//与本人关系
//                    'v_jphone'=>input('urgenoyphone'),
//                    'v_openid'=>$vopenid,
//                );
//                $res=db('vmember_vipbase')->where('v_openid',$vopenid)->update($data);
//            }else{
//                $data=array(
//                    'v_name'=>$vname,
//                    'v_sex'=>input('sex'),
//                    'v_nation'=>input('nation'),//民族
//                    'v_political'=>input('politics'),//政治面貌
//                    'v_height'=>input('height'),
//                    'v_cardid'=>input('card'),//身份证
//                    'v_birthday'=>input('birthday'),
//                    'v_place'=>input('place'),//籍贯
//                    'v_education'=>input('education'),
//                    'v_marry'=>input('marry'),
//                    'v_phone'=>input('telphone'),
//                    'v_email'=>input('mail'),
//                    'v_txplace'=>input('maliting'),//通讯地址
//                    'v_zipcode'=>input('postcode'),//邮编
//                    'v_jmam'=>input('urgency'),//紧急联系人
//                    'v_jrelationship'=>input('relation'),//与本人关系
//                    'v_jphone'=>input('urgenoyphone'),
//                    'v_time'=>date('y-m-d H:i:s',time()),
//                    'v_openid'=>$vopenid,
//                );
//                $res=db('vmember_vipbase')->insert($data);
//            }
//        }
//        if($res){
//            $r['code']='1';
//            $r['msg'] = '添加成功';
//            $r['data']=$res;
//        }else{
//            $r['code']='0';
//            $r['msg'] = '添加失败';
//            $r['data']=$res;
//        }
//
//        return json($r);
//    }
    //高端会员薪资/职位添加信息
    public function addVipSalaryInfo()
    {
        $vopenid = input('openid');
        $data = array(
            'v_work' => input('v_work'),
            'v_salary' => input('v_salary'),
        );
        $res = db('vmember_vipbase')->where('v_openid', $vopenid)->update($data);
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '插入成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '插入失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //教育经历添加修改
    public function addVipeduInfo()
    {
        $eid = input('eid');//教育经历id
        $vid = input('vid');//高端会员id(教育经历外键)
        //学校名称传参为空就调用查询接口，不为空调用插入接口
        if (input('e_schoolname') == '') {
            $res = db('vmember_vipedu')->where('e_pid', $vid)->order('e_time asc')->select();
        } else {
            if ($eid == '') {
                $data = array(
                    'e_pid' => $vid,
                    'e_schoolname' => input('e_schoolname'),
                    'e_begintime' => input('e_begintime'),
                    'e_endtime' => input('e_endtime'),
                    'e_edu' => input('e_edu'),
                    'e_major' => input('e_major'),
                    'e_time' => time(),
                );
                $res = db('vmember_vipedu')->insert($data);
            } else {
                $data = array(
                    'e_schoolname' => input('e_schoolname'),
                    'e_begintime' => input('e_begintime'),
                    'e_endtime' => input('e_endtime'),
                    'e_edu' => input('e_edu'),
                    'e_major' => input('e_major'),
                );
                $res = db('vmember_vipedu')->where('e_id', $eid)->order('e_time asc')->update($data);
            }

        }
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '插入成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '插入失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //显示教育经历修改页面
    public function showVipeduInfo()
    {
        $eid = input('eid');
        $res = db('vmember_vipedu')->where('e_id', $eid)->find();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '修改成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '修改失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //教育经历删除
    public function delVipeduInfo()
    {
        $eid = input('eid');
        $res = db('vmember_vipedu')->where('e_id', $eid)->delete();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '删除成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '删除失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //工作经历添加修改
    public function addVipworkInfo()
    {
        $vid = input('vid') ? input('vid') : '';//工作经历id
        $vipid = input('vipid');//高端会员id
        $v_companyname = input('v_companyname') ? input('v_companyname') : '';
        if ($v_companyname == '') {
            $res = db('vmember_vipwork')->where('v_pid', $vipid)->order('v_time asc')->select();
        } else {
            if ($vid == '') {
                $data = array(
                    'v_pid' => $vipid,
                    'v_companyname' => $v_companyname,
                    'v_position' => input('v_position'),
                    'v_begintime' => input('v_begintime'),
                    'v_endtime' => input('v_endtime'),
                    'v_salary' => input('v_salary'),
                    'v_witness' => input('v_witness'),
                    'v_phone' => input('v_phone'),
                    'v_reason' => input('v_reason'),
                    'v_time' => time(),
                );
                $res = db('vmember_vipwork')->insert($data);
            } else {
                $data = array(
                    'v_companyname' => $v_companyname,
                    'v_position' => input('v_position'),
                    'v_begintime' => input('v_begintime'),
                    'v_endtime' => input('v_endtime'),
                    'v_salary' => input('v_salary'),
                    'v_witness' => input('v_witness'),
                    'v_phone' => input('v_phone'),
                    'v_reason' => input('v_reason'),
                );
                $res = db('vmember_vipwork')->where('v_id', $vid)->update($data);
            }

        }
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '插入成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '插入失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //显示工作经历修改页面
    public function showVipworkInfo()
    {
        $vid = input('vid');
        $res = db('vmember_vipwork')->where('v_id', $vid)->find();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '修改成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '修改失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //删除工作经历
    public function delVipworkInfo()
    {
        $vid = input('id');
        $res = db('vmember_vipwork')->where('v_id', $vid)->delete();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '删除成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '删除失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //家庭基本情况添加修改
    public function addVipfamilyInfo()
    {
//        $vbase=db('vmember_vipbase')->where('v_openid',input('openid'))->find();//获取高端会员id
        $vipid = input('vipid');
        $fname = input('f_name') ? input('f_name') : '';
        $fid = input('fid') ? input('fid') : '';//家庭成员id
        if ($fname == '') {
            $res = db('vmember_vipfamily')->where('f_pid', $vipid)->order('f_time asc')->select();
        } else {
            if ($fid == '') {
                $data = array(
                    'f_pid' => $vipid,
                    'f_name' => input('f_name'),
                    'f_relationship' => input('f_relationship'),
                    'f_age' => input('f_age'),
                    'f_phone' => input('f_phone'),
                    'f_company' => input('f_company'),
                    'f_work' => input('f_work'),
                    'f_time' => date('y-m-d H:i:s', time()),
                );
                $res = db('vmember_vipfamily')->insert($data);
            } else {
                $data = array(
                    'f_name' => input('f_name'),
                    'f_relationship' => input('f_relationship'),
                    'f_age' => input('f_age'),
                    'f_phone' => input('f_phone'),
                    'f_company' => input('f_company'),
                    'f_work' => input('f_work'),
                );
                $res = db('vmember_vipfamily')->where('f_id', $fid)->update($data);
            }
        }
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '插入成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '插入失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //显示家庭基本信息修改页面
    public function showVipfamilyInfo()
    {
        $fid = input('fid');//家庭成员id
        $res = db('vmember_vipfamily')->where('f_id', $fid)->find();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '修改成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '修改失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //删除家庭基本信息
    public function delVipfamilyInfo()
    {
        $fid = input('fid');
        $res = db('vmember_vipfamily')->where('f_id', $fid)->delete();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '删除成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '删除失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //高端会员头像上传处理
    public function imgVipbaseInfo()
    {
        $openid = input('openid');//接收openid传参
        $file = request()->file('photo');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $path = $info->getSaveName();
                $data = array(
                    'v_photograph' => $path
                );
                $res = db('vmember_vipbase')->field('v_photograph')->where('v_openid', $openid)->update($data);//插入头像
            } else {
                // 上传失败获取错误信息
                $file->getError();
            }
        }
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '上传成功';
            $r['data'] = $file;
        } else {
            $r['code'] = '0';
            $r['msg'] = '上传失败';
            $r['data'] = $res;
        }

        return json($r);
    }

    //加载时显示头像
    public function showVipimgInfo()
    {
        $token = input('token');
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = $this->decrypt($token);
        $user = explode(",", $userinfo);
        if($user){
            $res = db('vmember_vipbase')->field('v_photograph')->where('v_loginid', $user[0])->find();
            $res['v_photograph'] = config('baseurl') . "uploads/" . $res['v_photograph'];
            $r['code'] = '1';
            $r['msg'] = '插入成功';
            $r['data'] = $res;
        }
        return json($r);
    }


    //获取普通会员信息
    public function getSimInfo()
    {
        $uid = input("uid");
        $user = db("smember_simpeople")->field("s_id,s_loginid,s_name,s_sex,s_birthday,s_phone,s_area,s_job")->where("s_loginid", $uid)->find();
//        $sim_phone=db("user_login")->where("l_id",$uid)->value('l_phone');//从登录表获取登陆者手机号码
        if ($user) {
            $area = db("data_region")->field("id,name")->where("id", $user['s_area'])->find();
            $user['s_areaname'] = $area['name'];
            $job = db("data_region")->field("id,name")->where("id", $user['s_area'])->find();
            $r['code'] = '1';
            $r['msg'] = '查询成功';
            $r['data'] = $user;
        } else {
            $r['code'] = '0';
            $r['msg'] = '查询为空';
            $r['data'] = array();
        }
        return json($r);
    }

    //普通会员简历查询修改、添加
    public function updateSimInfo()
    {
        $lid = input('uid');//登录id
        $res = db('smember_simpeople')->where('s_loginid', $lid)->find();
        $lname = input('lname') ? input('lname') : '';
        $data = array(
            's_name' => $lname,
            's_sex' => input('sex'),
            's_birthday' => input('birthday'),
//            's_privice'=>input('privince'),
//            's_city'=>input('city'),
            's_phone' => input('phone'),
            's_area' => input('area'),
            's_job' => input('work'),
            's_time' => time()
        );

        if ($res) {
            //数据库中已经有数据做更新操作
            $kr = db("smember_simpeople")->where('s_loginid', $lid)->update($data);
        } else {
            $data['s_loginid'] = $lid;
            $kr = db('smember_simpeople')->insert($data);
        }
        if ($kr) {
            $r['code'] = '1';
            $r['msg'] = '保存成功';
            $r['data'] = $res;
        } else {
            $r['code'] = '0';
            $r['msg'] = '保存失败';
            $r['data'] = $res;
        }
        return json($r);
    }
    //将登录表中类型求职改为普通会员或高端会员
    public function updatePeopleType(){
        $type=input('typeid');//会员类型
        $data=array('l_type'=>$type);
        $lid=input('lid');//登录id
        $res=db('user_login')->where('l_id',$lid)->update($data);
        if($res){
            $r['code'] = '1';
            $r['msg'] = '保存成功';
            $r['data'] = $res;
        }else{
            $r['code'] = '0';
            $r['msg'] = '保存失败';
            $r['data'] = $res;
        }
        return json($r);
    }
    //根据loginid查询会员类型
    public function getLoginidInfo(){
        $loginid=input('loginid');
        $res=db('user_login')->where('l_id',$loginid)->field('l_type')->find();
        if($res){
            $r['code'] = '1';
            $r['msg'] = '查询成功';
            $r['data'] = $res;
        }else{
            $r['code'] = '0';
            $r['msg'] = '查询失败';
            $r['data'] = array();
        }
        return json($r);
    }
}
