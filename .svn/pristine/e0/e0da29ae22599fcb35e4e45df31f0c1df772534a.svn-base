<?php

namespace app\admin\controller;


class Vmember extends Common
{
    function _initialize()
    {
        parent::_initialize();
    }

    public function vipPeople()
    {
        $this->assign("uid",session('aid'));
        //姓名
        $val=input('v_name');//搜索框传值
//        $url['val'] = $val;
        $this->assign('testval',$val);
        //电话
        $v_phone=input('v_phone');//搜索框传值
//        $url['v_phone'] = $v_phone;
        $this->assign('v_phone',$v_phone);
        //籍贯
        $v_place=input('v_place');//搜索框传值
//        $url['v_place'] = $v_place;
        $this->assign('v_place',$v_place);
        //身份证
        $v_cardid=input('v_cardid');//搜索框传值
//        $url['v_cardid'] = $v_cardid;
        $this->assign('v_cardid',$v_cardid);

        $map=array();
        $map['l_type']=2;
        if($val){
            $map['v_name']= array('like',"%".$val."%");
        }
        if($v_phone){
            $map['v_phone']= array('like',"%".$v_phone."%");
        }
        if($v_place){
            $map['v_place']= array('like',"%".$v_place."%");
        }
        if($v_cardid){
            $map['v_cardid']= array('like',"%".$v_cardid."%");
        }

        $res= db("user_login")->alias('u')->join('vmember_vipbase v','u.l_id=v.v_loginid','left')->where($map)->order('v_id asc')->select();

        $adminList=db("user_login")->alias('u')->join('vmember_vipbase v','u.l_id=v.v_loginid','left')
            ->where($map)
            ->paginate(config('pageSize'));//分页查询
        $adminList->appends($map);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('res',$res);
        return $this->view->fetch();
    }
    //显示添加页面
    public function vipAdd()
    {
        $edu=db('data_education')->select(); //学历
        $this->assign('edu',$edu);
        return $this->view->fetch();
    }
    //执行添加高端会员
    public function dovipAdd(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                $vphotograph=$info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        $admin = db('vmember_vipbase');
        $data = array(
            'v_name' => input('post.v_name'),
            'v_sex' => input('post.v_sex'),
            'v_nation' => input('post.v_nation'),
            'v_political' => input('post.v_political'),
            'v_height' => input('post.v_height'),
            'v_cardid' => input('post.v_cardid'),
            'v_birthday' => input('post.v_birthday'),
            'v_place' => input('post.v_place'),
            'v_education' => input('post.v_education'),
            'v_marry' => input('post.v_marry'),
            'v_phone' => input('post.v_phone'),
            'v_email' => input('post.v_email'),
            'v_txplace' => input('post.v_txplace'),
            'v_zipcode' => input('post.v_zipcode'),
            'v_jmam' => input('post.v_jmam'),
            'v_jrelationship' => input('post.v_jrelationship'),
            'v_jphone' => input('post.v_jphone'),
            'v_time' => date('y-m-d H:i:s',time()),
            'v_salary' => input('post.v_salary'),
            'v_photograph' => $vphotograph,
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '高端会员添加成功!';
        $result['url'] = url('vipPeople');
        return $result;
    }
    //查询高端会员基本信息是否存在
    public function vipSearch(){
        $vid=input('v_id');
        if($vid){
            $res=db('vmember_vipbase')->where('v_id='.$vid)->find();
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
    //显示修改高端会员页面
    public function vipUpdate(){
        $edu=db('data_education')->select();//学历
        $this->assign('edu',$edu);
        $info = db('vmember_vipbase')->where('v_id='.input('v_id'))->find();
        $this->assign('info', $info);
        return $this->view->fetch();
    }
    //执行修改操作
    public function dovipUpdate(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                $vphotograph=$info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

        $admin=db('vmember_vipbase');
        $where['v_id'] = input('v_id');
        $data = array(
            'v_name' => input('post.v_name'),
            'v_sex' => input('post.v_sex'),
            'v_nation' => input('post.v_nation'),
            'v_political' => input('post.v_political'),
            'v_height' => input('post.v_height'),
            'v_cardid' => input('post.v_cardid'),
            'v_birthday' => input('post.v_birthday'),
            'v_place' => input('post.v_place'),
            'v_education' => input('post.v_education'),
            'v_marry' => input('post.v_marry'),
            'v_phone' => input('post.v_phone'),
            'v_email' => input('post.v_email'),
            'v_txplace' => input('post.v_txplace'),
            'v_zipcode' => input('post.v_zipcode'),
            'v_jmam' => input('post.v_jmam'),
            'v_jrelationship' => input('post.v_jrelationship'),
            'v_jphone' => input('post.v_jphone'),
//            'v_time' => input('post.v_time'),
            'v_salary' => input('post.v_salary'),
            'v_photograph' =>  $vphotograph,
        );
        $admin->where($where)->update($data);
        $result['status'] = 1;
        $result['info'] = '高端会员修改成功!';
        $result['url'] = url('vipPeople');
        return $result;
    }
    //删除数据
    public function vipDel()
    {
        $vid = input('v_id');//登录表id
//        $loginid=db('vmember_vipbase')->where('v_id='.$vid)->value('v_loginid');

        $e = db('vmember_vipbase')->where('v_loginid=' . $vid)->value('v_id');
        if ($e) {
            $r = db('user_login')->where('l_id', $vid)->delete();
            $s = db('vmember_vipbase')->where('v_id=' . $e)->delete();
            if ($r && $s) {
                $this->redirect('vipPeople');
            }
        }else{
            $r = db('user_login')->where('l_id', $vid)->delete();
            if ($r) {
                $this->redirect('vipPeople');
            }
        }
    }
    //显示教育经历添加页面
    public function vipeduAdd(){
        $vid=input('v_id');//高级会员id
        $this->assign('vid',$vid);
        $edu=db('data_education')->select();//学历
        $this->assign('edu',$edu);
        $res=db('vmember_vipbase')->where('v_id',$vid)->find();
        $this->assign('res',$res);
        return $this->view->fetch();
    }
    //执行添加操作
    public function dovipeduAdd(){
        $admin = db('vmember_vipedu');
        $data = array(
            'e_pid' => input('post.e_pid'),
            'e_schoolname' => input('post.e_schoolname'),
            'e_begintime' => input('post.e_begintime'),
            'e_endtime' => input('post.e_endtime'),
            'e_edu' => input('post.e_edu'),
            'e_major' => input('post.e_major'),
            'e_time' => time(),
            'e_sort' => input('post.e_sort'),
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '教育经历添加成功!';
        $result['url'] = url('vipPeople');
        return $result;
    }
    //显示工作经历添加页面
    public function vipworkAdd(){
        $vid=input('v_id');//高级会员id
        $this->assign('vid',$vid);
        return $this->view->fetch();
    }
    //执行工作经历添加操作
    public function dovipworkAdd(){
        $admin = db('vmember_vipwork');
        $data = array(
            'v_pid' => input('post.v_pid'),
            'v_companyname' => input('post.v_companyname'),
            'v_begintime' => input('post.v_begintime'),
            'v_endtime' => input('post.v_endtime'),
            'v_salary' => input('post.v_salary'),
            'v_witness' => input('post.v_witness'),
            'v_phone' => input('post.v_phone'),
            'v_reason' => input('post.v_reason'),
            'v_time' => time(),
            'v_sort' => input('post.v_sort'),
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '工作经历添加成功!';
        $result['url'] = url('vipPeople');
        return $result;
    }
    //显示添加家庭基本情况页面
    public function vipfamilyAdd(){
        $vid=input('v_id');//高级会员id
        $this->assign('vid',$vid);
        return $this->view->fetch();
    }
    //执行添加家庭基本情况操作
    public function dovipfamilyAdd(){
        $admin = db('vmember_vipfamily');
        $data = array(
            'f_pid' => input('post.f_pid'),
            'f_name' => input('post.f_name'),
            'f_relationship' => input('post.f_relationship'),
            'f_age' => input('post.f_age'),
            'f_phone' => input('post.f_phone'),
            'f_company' => input('post.f_company'),
            'f_work' => input('post.f_work'),
            'f_time' => date('Y-m-d H:i:s',time()),
            'f_sort' => input('post.f_sort'),
        );
        $admin->insert($data);
        $result['status'] = 1;
        $result['info'] = '家庭基本情况添加成功!';
        $result['url'] = url('vipPeople');
        return $result;
    }
    //显示简历
    public function resume(){
        $vid=input('v_id');//高级会员id
        $vipbase=db('vmember_vipbase')->where('v_id',$vid)->find();//高端会员基本信息
        $this->assign('vipbase',$vipbase);
        $vipedu=db('vmember_vipedu')->where('e_pid',$vid)->order('e_begintime asc')->select();//教育经历
        $this->assign('vipedu',$vipedu);
        $vipwork=db('vmember_vipwork')->where('v_pid',$vid)->order('v_begintime asc')->select();//工作经历
        $this->assign('vipwork',$vipwork);
        $vipfamily=db('vmember_vipfamily')->where('f_pid',$vid)->select();//家庭基本情况
        $this->assign('vipfamily',$vipfamily);
        return $this->view->fetch();
    }
}