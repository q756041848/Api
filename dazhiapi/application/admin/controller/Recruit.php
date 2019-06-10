<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Recruit extends Common
{
    //显示招聘信息页面和查询招聘信息
    public function index(){
        //工作岗位查询
        $work = db('data_work')->field('w_id,w_name')->select();
        $this->assign('work',$work);

        //工作经验查询
        $exper = db('data_exper')->field('e_id,e_name')->select();
        $this->assign('exper',$exper);

        //工作类型查询
        $type = db('data_worktype')->field('t_id,t_name')->select();
        $this->assign('type',$type);
        $map=array();
        $title = input("r_title");
        $url['title'] = $title;
        $this->assign('tl',$title);

        $wk = input("r_work");
        $url['wk'] = $wk;
        $this->assign('wok',$wk);

        $exp = input("r_exp");
        $url['exp'] = $exp;
        $this->assign('ep',$exp);

        $place = input("r_place");
        $url['place'] = $place;
        $this->assign('wp',$place);

        $tp = input("r_type");
        $url['type'] = $tp;
        $this->assign('typ',$tp);
        //工作岗位
        $work = input("work");
        $url['work'] = $work;
        $this->assign('work',$work);
        $work2 = input("work2");
        $url['work2'] = $work2;
        $this->assign('work2',$work2);
        //登陆者管理员id查询
        $admin_id=session('aid');
        $this->assign('admin_id',$admin_id);
        if($title){
            $map['r_title']= array('like',"%".$title."%");
        }

//        if($wk&&$wk != 0){
//            $map['r_work']= $wk;
//        }
        if($exp&&$exp != "0"){
            $map['r_exper']= $exp;
        }

        if($tp &&$tp != "0"){
            $map['r_type']= $tp;
        }

        if($work2&&$work2!= "0"){
            $map['r_work']= $work2;
        }

        $db = db("recruit");
        $adminList=$db->alias('r')
            ->join('dzjn_scmember_company sc','r.r_companyid=sc.c_id')
            ->join('dzjn_data_check ck','r.r_check = ck.ck_id')
            ->field('r_id,r_title,r_headwork,r_work,r_scale,r_exper,r_number,r_education,r_province,r_city,r_area,r_detailaddress,r_type,r_welfare,r_desc,ck.ck_name,r_sort,r_time,sc.c_name')
            ->where($map)
            ->where('r_province|r_city|r_area|r_detailaddress','like','%'.$place.'%')
            ->order("r_sort desc,r_time desc")
            ->paginate(config('pageSize'));
//        print_r($map);
//        echo $db->getLastSql();
//        exit();
        //地区显示

        $adminList->appends($url);
        // 模板变量赋值
        $page = $adminList->render();
        $this->assign('page', $page);
        $this->assign('admin_list',$adminList);
        return $this->fetch('recruit');
    }

    //显示插入招聘信息
    public function recruitAdd(){
        //工作岗位查询
        $work = db('data_work')->field('w_id,w_name')->select();
        $this->assign('wk',$work);

        //工作经验查询
        $exper = db('data_exper')->field('e_id,e_name')->select();
        $this->assign('exper',$exper);

        //个人学历查询
        $education = db('data_education')->field('e_id,e_name')->select();
        $this->assign('education',$education);

        //工作福利查询
        $welfare = db('data_welfare')->field('w_id,w_name')->select();
        $this->assign('welfare',$welfare);

        //公司薪资
        $scale = db('data_scale')->field('s_id,s_name')->select();
        $this->assign('scale',$scale);

        //工作类型查询
        $type = db('data_worktype')->field('t_id,t_name')->select();
        $this->assign('type',$type);

        //公司名字信息查询
        $company = db("scmember_company")->field("c_id,c_name")->select();
        $this->assign("company",$company);

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



        return $this->fetch('recruit_add');
    }

    //插入招聘信息
    public function recruit_Add(){
        $uid = session("aid");
        $recruit = db('recruit');
        //获取输入框内容
        $data['r_companyid']=input("company");   //公司id
        $data['r_cid'] = $uid;
        $data['r_title'] = input('title');
        $data['r_headwork'] = input('work');
        $data['r_work'] = input('work2');
        $data['r_scale'] = input('scale');
        $data['r_exper'] = input('exper');
        $data['r_number'] = input('number');
        $data['r_education'] = input('education');
        //$data['r_workplace'] = input('workplace');
        $data['r_province'] = input('r_privice');
        $data['r_city'] = input('r_city');
        $data['r_area'] = input('r_area');
        $data['r_detailaddress']=input("r_detailaddress");
        $data['r_type'] = input('type');
        $wel = input('welfare/a');
        if($wel){
            $wel=implode(",",$wel);
            $data['r_welfare'] = $wel;
        };
        $data['r_desc'] = input('desc');
        $data['r_check'] = input('check');
        $data['r_sort'] = input('sort');
       // $data['r_img'] = $r_img;
        $data['r_time'] = date('Y-m-d h:i:s');
        //插入数据
        $res = $recruit->insert($data);

        if($res>0){
            $result['status'] = 1;
            $result['info'] = '招聘信息添加成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '招聘信息添加失败!';
            $result['url'] = url('recruitAdd');
        }

        return $result;
    }


    //删除招聘信息
    public function recruitDel(){
            $admin_id=input('post.r_id');
            if (session('aid')==1){
                if (empty($admin_id)){
                    $result['status'] = 0;
                    $result['info'] = '招聘信息ID不存在!';
                    $result['url'] = url('index');
                    return $result;
                }
                $del=db('recruit')->where('r_id='.$admin_id)->delete();
//                $this->redirect('index');
                return json($del);
            }
    }

    //显示修改招聘信息
    public function recruitUpdate(){

        //公司名字信息查询
        $company = db("scmember_company")->field("c_id,c_name")->select();
        $this->assign("company",$company);


        //工作岗位查询
        $work = db('data_work')->field('w_id,w_name')->select();
        $this->assign('wk',$work);

        //工作经验查询
        $exper = db('data_exper')->field('e_id,e_name')->select();
        $this->assign('exper',$exper);

        //个人学历查询
        $education = db('data_education')->field('e_id,e_name')->select();
        $this->assign('education',$education);

        //公司规模查询
        $scale = db('data_scale')->field('s_id,s_name')->select();
        $this->assign('scale',$scale);

        //工作类型查询
        $type = db('data_worktype')->field('t_id,t_name')->select();
        $this->assign('type',$type);

        //招聘信息
        $info = db('recruit')->where('r_id='.input('r_id'))->find();
        $this->assign('info', $info);

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

        //工作福利查询
        $welfare = db('data_welfare')->field('w_id,w_name')->select();
        //添加是否之前选中过
        $ywelfare = explode(',',$info['r_welfare']);
        foreach ($welfare as $k=>$v){
            $b=0;
            foreach ($ywelfare as $k1=>$v1){
                if($v['w_name']==$v1){
                    $b=1;
                }
            }
            $welfare[$k]['ischeck'] = $b;
        }
        $this->assign('welfare',$welfare);

        return $this->fetch('recruit_update');
    }


    //修改招聘信息
    public function recruit_Update(){
        $recruit = db('recruit');

        //获取输入框内容
        $data['r_title'] = input('title');
        $data['r_headwork'] = input('work');
        $data['r_work'] = input('work2');
        $data['r_scale'] = input('scale');
        $data['r_exper'] = input('exper');
        $data['r_number'] = input('number');
        $data['r_education'] = input('education');
        //$data['r_workplace'] = input('workplace');
        $data['r_province'] = input('r_privice');
        $data['r_city'] = input('r_city');
        $data['r_area'] = input('r_area');
        $data['r_detailaddress'] = input("r_detailaddress");
        $data['r_type'] = input('type');
        $data['r_desc'] = input('desc');
        $data['r_check'] = input('check');
        $data['r_sort'] = input('sort');
        $data['r_companyid'] = input('company');
        $wel = input('welfare/a');
        if($wel){
            $wel=implode(",",$wel);
            $data['r_welfare'] = $wel;
        };
        $where['r_id'] = input('post.r_id');

        $recruit->where($where)->update($data);
        $result['status'] = 1;
        $result['info'] = '招聘信息修改成功!';
        $result['url'] = url('index');
        return $result;

    }

    //招聘详细信息
    public function recruitDetails(){

        $rid = input('r_id');

        //招聘信息查询
        $info = db('recruit')->alias('r')
            ->join('dzjn_scmember_company sc','r.r_companyid=sc.c_id')
//            ->join('dzjn_data_work work','r.r_work = work.w_id')
//            ->join('dzjn_data_exper exper','r.r_exper = exper.e_id')
//            ->join('dzjn_data_education edu','r.r_education = edu.e_id')
//            ->join('dzjn_data_scale scale','r.r_scale = scale.s_id')
//            ->join('dzjn_data_worktype type','r.r_type = type.t_id')
//            ->join('dzjn_data_check ck','r.r_check = ck.ck_id')

//            ->field('work.w_name as work_name,exper.e_name as exp_name,edu.e_name as edu_name,ck_name,s_name,t_name,r_id,r_title,r_scale,r_welfare,r_number,r_area,r_type,r_desc,r_check,r_sort,r_time,c_id,sc.c_name')
            ->where('r_id='.$rid)
            ->find();
//        echo "<pre>";
//        print_r($info);
//        exit();
        //工作福利查询
        $wel = Db::query('SELECT r.r_id,(SELECT GROUP_CONCAT(w.w_name) FROM dzjn_data_welfare w WHERE FIND_IN_SET(w.w_id,r.r_welfare)) AS wel FROM dzjn_recruit r WHERE r_id = ?',["$rid"]);
        $this->assign('wel',$wel);

        //工作环境
        $images = db("recruit_image")->where("i_rid",$rid)->select();
        $this->assign("images",$images);

        //工作地区
//        $area = db("data_region")->where("id",$info['r_area'])->find();
//        $this->assign("area",$area);


        $this->assign('info',$info);

        return $this->fetch('recruit_details');
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
    //获取工作岗位信息
    public function getWorkByWpid(){
        if(input("wpid")){
            $wpid=input("wpid");
        }else{
            $wpid=0;
        }
        $data = db("data_work")->where("w_pid='$wpid'")->order("w_sort desc")->select();
        return json($data);
    }

}