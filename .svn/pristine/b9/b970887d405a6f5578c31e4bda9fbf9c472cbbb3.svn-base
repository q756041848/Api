<?php
namespace app\api\controller;
use think\Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
class Data extends Base
{
    //获取教育学历列表
    public function getEducation(){
        $res = db("data_education")->order('e_sort desc')->select();
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取工作经验列表
    public function getExper(){
        $res = db("data_exper")->order('e_sort desc')->select();
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取公司福利列表
    public function getWelfare(){
        $res = db("data_welfare")->order('w_sort desc')->select();
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取初始化地区数据-省市区
    public function getRegionIni(){
        $provincepid = input("province")?input("province"):1;
        $citypid = input("city")?input("city"):22;
        $areapid = input("area")?input("area"):292;
        //全部省
        $privince = db("data_region")->field("id,name")->where('pid',$provincepid)->order('sort desc')->select();
        //获取市-默认山东省内
        $city = db("data_region")->field("id,name")->where('pid',$citypid)->order('sort desc')->select();
        //获取区--默认为青岛市内
        $area = db("data_region")->field("id,name")->where('pid',$areapid)->order('sort desc')->select();

        $res = array($privince,$city,$area);
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获得对应省下面的市和区-市区
    public function getCityAndAreaByPid(){
        $privinceid = input("pid")?input("pid"):22;
        //获取市
        $city = db("data_region")->field("id,name")->where('pid',$privinceid)->order('sort desc')->select();
        //获取区
        $cityid = $city[0]['id'];
        $area = db("data_region")->field("id,name")->where('pid',$cityid)->order('sort desc')->select();
        $res = array($city,$area);
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取地区列表
    public function getRegionByPid(){
        $pid = input("pid")?input("pid"):1; //获取父级id，如果没有默认为0
        $res = db("data_region")->where('pid',$pid)->order('sort desc')->select();
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }
    //根据地区名称来获取地区的id---20180911优化新加
    public function getAreaByName(){
        $name = input("aname");
        if(!$name){
            $r['code']='0';
            $r['msg'] = '地区名称不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
       $res=db("data_region")->where("name",'like','%'.$name.'%')->find();
       $r['code']='1';
       $r['msg'] = '查询成功';
       $r['data']=$res;
       return json($r);
    }
    //获取市列表-招聘单个词的首字母分组
    public function getAreaGroup(){
        $list = array();
        $db = db("data_region");
        $letter = $db->field("letter")->where("type",'2') ->group('letter')->order("letter")->select();
        foreach ($letter as $k=>$v){
            $map['letter'] = $v['letter'];
            $map['type'] = '2';
            $area = db("data_region")->field("id,name")->order("letters")->where($map)->select();
            $aletter = array(
                'letter'=>$v['letter'],
                'data'=>$area
            );
            array_push($list,$aletter);
        }
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$list;
        return json($r);
    }
    //获取职位列表
    public function getWork(){
        $isall = input("isall");  //如果参数为1则显示表头
        $pid = input("pid")?input("pid"):0; //获取父级id，如果没有默认为0
        $res = db("data_work")->field("w_id,w_name,w_pid")->where('w_pid',$pid)->order("w_sort desc")->select();
        $twores=db("data_work")->field("w_id,w_name,w_pid")->where('w_pid',$res[0]['w_id'])->order("w_sort desc")->select();
        //构造标头
        if($isall=='1'){
            $arr = array(
                'w_id'=>'0',
                'w_name'=>'职位',
                'w_sort'=>'10000',
                'w_time'=>''
            );
            array_unshift($res,$arr);
            array_unshift($twores,$arr);
        }

        $data['one'] = $res;
        $data['two'] = $twores;
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$data;
        return json($r);
    }
    //获取职位列表 - 显示为page
    public function getWorkPage(){
        $isall = input("isall");  //如果参数为1则显示表头
        $pid = input("pid")?input("pid"):0; //获取父级id，如果没有默认为0
        $res = db("data_work")->field("w_id,w_name,w_pid")->where('w_pid',$pid)->order("w_sort desc")->select();
        foreach ($res as $k=>$v){
            $twores=db("data_work")->field("w_id,w_name,w_pid")->where('w_pid',$v['w_id'])->order("w_sort desc")->select();
            $res[$k]['two'] = $twores;
        }

        //构造标头
        if($isall=='1'){
            $arr = array(
                'w_id'=>'0',
                'w_name'=>'全部',
                'two'=>array(
                    array(
                    'w_id'=>'0',
                    'w_name'=>'职位',
                ))
            );
            array_unshift($res,$arr);
        }

        $data = $res;
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$data;
        return json($r);
    }
    //获取一级工种id 获取其下对应的二级职位列表
    public function getWorkByPid(){
        $pid = input("pid")?input("pid"):0; //获取父级id，如果没有默认为0
        $res = db("data_work")->field("w_id,w_name,w_pid")->where('w_pid',$pid)->order("w_sort desc")->select();

        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

   //获取工作类别
    public function getWrokType(){
        $res = db("data_worktype")->field("t_id,t_name")->order("t_sort desc")->select();

        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取薪资列表
    public function getScale(){
        $res = db("data_scale")->field("s_id,s_name")->order("s_sort desc")->select();
        //构造标头
        $arr = array(
            's_id'=>'0',
            's_name'=>'薪资',
            's_sort'=>'10000',
            's_time'=>''
        );
        array_unshift($res,$arr);
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

    //获取企业类型/规模列表
    public function getCompanyTS(){
        $res['type'] = db('scmember_company_type')->column('c_type_name');
        $res['scale'] = db('scmember_company_scale')->column('c_scale_name');
        if($res['type']&&$res['scale']){
            $r['code'] = 1;
            $r['msg'] = '企业类型、规模加载成功';
            $r['data'] = $res;
            return json($r);
        }
    }
    //获取意向企业列表
    public function datawork(){
        $rs = $work = db('data_work')->field('w_name')->select();
        foreach ($rs as $key => $value) {
            $rsa[] = $rs[$key]['w_name']; 
        }
        if ($rs) {
            $data = [''];
            $data = $rsa;
        }
        return json($data);
        
    }


    //根据openid获取企业成员信息
    public function getCompany(){
        // $openid = input('openid');
        $token = input('token');
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = $this->decrypt($token);
        $user = explode(",", $userinfo);

        if ($user) {
            $d  = db('scmember_company');
            $data['info'] = $d->where(array('c_loginid'=>$user[0]))->find();

            $db = db('recruit_image');
            $data['img'] = $db->where(array('i_rid' => $data['info']['c_id']))->select();

            $data['photo'] = array();
            foreach ($data['img'] as $key => $value) {
                $data['photo'][$key] = config('baseurl')."uploads/".$data['img'][$key]['i_image'];
            }
            $r['code'] = 1;
            $r['msg'] = '获取企业信息';
            $r['data'] = $data;
        }else{
            $r['code'] = 0;
            $r['msg'] = '登陆异常！';
            $r['data'] = array();
        }
        return json($r);
    }

    //根据openid获取企业环境图片
    public function getCompanyImg(){
        $openid = input('openid');
        $c_id = db('scmember_company')->where(array('c_openid' => $openid))->column('c_id');
        if ($openid) {
            $data['img'] = db('recruit_image')->where(array('i_rid' => $c_id))->select();
            $r['code'] = 1;
            $r['msg'] = '获取企业环境图片';
            $r['data'] = $data;
        }else{
            $r['code'] = 0;
            $r['msg'] = '请先登录！';
            $r['data'] = array();
        }
        return json($r);
    }
    //获取企业规模在页面显示
    public function getCompanyScale(){
        $res=db('scmember_company_scale')->field('c_scale_id,c_scale_name')->select();
//        echo "<pre>";
//        print_r($res);
        if($res){
            $r['code'] = 1;
            $r['msg'] = '查询成功';
            $r['data'] = $res;
        }
        return json($r);
    }
    //获取企业类型在页面显示
    public function getCompanyType(){
        $res=db('scmember_company_type')->field('c_type_id,c_type_name')->select();
        if($res){
            $r['code'] = 1;
            $r['msg'] = '查询成功';
            $r['data'] = $res;
        }
        return json($r);
    }
}