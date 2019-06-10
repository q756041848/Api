<?php
namespace app\api\controller;
use think\Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-type: text/html; charset=utf-8");
class Login extends Base
{
    public function test(){

        $data = 'PHP加密解密算法';
        $encrypt = $this->encrypt($data);
        $decrypt = $this->decrypt($encrypt);
        echo $encrypt, "\n", $decrypt;
    }
    //使用手机号 密码----注册
    public function newReg(){
        $phone =  input("phone");
        if(!$phone){
            $r['code'] = '0';
            $r['msg'] = "手机号不能为空";
            $r['data'] = array();
            return json($r);
            exit();
        }
        //查看手机号是否注册
        $reg=db('user_login')->where('l_phone',$phone)->find();
        if($reg){
            $r['code'] = '0';
            $r['msg'] = "该手机号已注册，可直接登录";
            $r['data'] = array();
            return json($r);
            exit();
        }
        $map["t_telphone"] = $phone;
        //验证验证码
        $map["t_telcode"] = input("code");
        $rcode = db("telcode")->where($map)->find();
        if ($rcode) {
            $t = $rcode['t_sendtime'];
            $c = time() - $t;
            if ($c > 600) {
                $r['code'] = '0';
                $r['msg'] = "验证码已过期";
                $r['data'] = array();
                return json($r);
                exit();
            }
        } else {
            $r['code'] = '0';
            $r['msg'] = "验证码错误";
            $r['data'] = array();
            return json($r);
            exit();
        }
        $d["l_phone"] = $phone;
        $d["l_password"] = md5(input("pass"));
        $d["l_name"] = input("uname");
        $d["l_type"] = input("ltype");
        $d["l_time"] = time();
//        $s['s_name']=input("uname");
//        $s['s_phone']=$phone;
        $res=db("user_login")->insert($d);
//        $s['s_loginid']=$loginid;
//        $sim=db('smember_simpeople')->insert($s);
        if($res){
            $r['code']='1';
            $r['msg'] = '注册成功!';
            $r['data']=$res;
            return json($r);
        }else{
            $r['code']='0';
            $r['msg'] = '注册失败!';
            $r['data']=array();
            return json($r);
        }
    }
    //使用手机号-密码   登录
    public function newLogin(){
        $d["l_phone"] = input("phone");
        $d["l_password"] = md5(input("pass"));
        if(!$d["l_phone"]||!$d["l_password"]){
            $r['code']='0';
            $r['msg'] = '手机号或者密码不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $res = db("user_login")->where($d)->find();
        if($res){
            $r['code']='1';
            $r['msg'] = '登录成功';
            $token = $res['l_id'].','.$res['l_phone'].','.$res['l_type'].','.$res['l_openid'];
            $res['token'] =$this->encrypt($token);
            $r['data']=$res;
            return json($r);
        }else{
            $r['code']='0';
            $r['msg'] = '用户名或者密码错误!';
            $r['data']=array();
            return json($r);
        }
    }





    //根据小程序code获取微信唯一身份标示openid
    public function getOpenId()
    {
        $code = input('code');   //微信客户端登录后获得
        $output = $this->openId($code);
        if (isset($output['openid']) && !empty($output['openid'])) {
            $r['code']='1';
            $r['msg'] = '获取openid成功';
            $r['data']=$output;
        }else{
            $r['code']='0';
            $r['msg'] = '获取openid失败';
            $r['data']=array();
        }
        return json($r);
    }
    //通过小程序code获得微信openid和用户在系统内是否已经注册
    public function getOpenIdAndToken()
    {
        $code = input('code');   //微信客户端登录后获得
        $output = $this->openId($code);
        if (isset($output['openid']) && !empty($output['openid'])) {
            //通过openid判断是否注册过
            $res = db('user_login')->where('l_openid',$output['openid'])->find();
            if($res){
                $r['code']='1';
                $r['msg'] = '用户已注册';
                $token = $res['l_id'].','.$res['l_phone'].','.$res['l_type'].','.$res['l_openid'];
                $res['token'] =$this->encrypt($token);
                $r['data']=$res;
            }else{
                $r['code']='2';
                $r['msg'] = '用户还未注册';
                $r['data']=array();
            }
        }else{
            $r['code']='0';
            $r['msg'] = '获取openid失败';
            $r['data']=array();
        }
        return json($r);
    }

    //通过openid判断用户是否注册过
    public function getUserByOpenId()
    {
        $openid = input('openid');
        $res = db('user_login')->where('l_openid',$openid)->find();
        if($res){
            $r['code']='1';
            $r['msg'] = '用户已注册';
            $token = $res['l_id'].','.$res['l_phone'].','.$res['l_type'].','.$res['l_openid'];
            $res['token'] =$this->encrypt($token);
            $r['data']=$res;
        }else{
            $r['code']='2';
            $r['msg'] = '用户还未注册';
            $r['data']=array();
        }
        return json($r);
    }


    //私有方法-调用微信api换取openid
    private function openId($code){
        $appid = 'wx020a5fb38c05c6ee';
        $appsecrte = '3972b19641f7604baa8e53418d46b044';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecrte . '&js_code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();   //初始化
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $output;
    }

    //注册企业会员
    public function addCompany(){
        $phone=input('phone');
        $code = input("code");
        $map['t_telphone'] =$phone;
        $map['t_telcode'] = $code;
        //检测验证码是否正确
        $rcode = db("telcode")->where($map)->find();
        if ($rcode) {
            $t = $rcode['t_sendtime'];
            $c = time() - $t;
            if ($c > 600) {
                $r['code'] = '0';
                $r['msg'] = "验证码已过期";
                $r['data'] = array();
                return json($r);
                exit();
            }
        } else {
            $r['code'] = '0';
            $r['msg'] = "验证码错误";
            $r['data'] = array();
            return json($r);
            exit();
        }

        $verify['l_phone'] = input('phone');
        $verify['l_openid'] = input('openid');
        if(!empty($verify)){
            $login = db('user_login');
            $res['l_phone'] = $login->where('l_phone',$verify['l_phone'])->select();
            $res['l_openid'] = $login->where('l_openid',$verify['l_openid'])->select();
            if ($res['l_phone']||$res['l_openid']) {
                $r['code'] = 0;
                $r['msg'] = '该账号已经注册';
                $r['data'] =array();
                return json($r);
                exit;
            }else{
                $company = db('scmember_company');
                $user['l_openid'] = input('openid');
                $user['l_time'] = time();
                $user['l_name'] = input('linkman');
                $user['l_phone'] = input('phone');
                $user['l_type'] = input('type');

                $data['c_name'] = input('companyname');
                $data['c_contacts'] = input('linkman');
                $data['c_phone'] = input('phone');
                $data['c_type'] = input('types');
                $data['c_scale'] = input('scale');
                $data['c_addr'] = input('address');
                $data['c_time'] = date('Y-m-d H:i:s');
                $data['c_latitude']= input('latitude');
                $data['c_longitude'] = input('longitude');
//                $data['c_sort'] = 1;
                $check_name = $company->where(array('c_name'=>$data['c_name']))->find();
                $check_phone = $company->where(array('c_phone'=>$data['c_phone']))->find();
                if ($check_name||$check_phone){
                    if (!empty($check_phone)) {
                        $r['code'] = 0;
                        $r['msg'] = '手机号已存在，请重新输入!';
                        $r['data'] =array();
                    }else{
                        $r['code'] = 0;
                        $r['msg'] = '该企业已存在，请重新输入!';
                        $r['data'] =array();
                    }
                    return json($r);
                    exit;
                }else{
                    $lid = db('user_login')->insertGetId($user);
                    $data['c_loginid']=$lid;
                    $c = $company->insert($data);
                    if ($c and $lid) {
                        $r['code'] = 1;
                        $r['msg'] = '注册成功!';
                        $r['data'] = array();
                    }else{
                        $r['code'] = 0;
                        $r['msg'] = '企业会员注册失败，请重新注册！';
                        $r['data'] = array();
                    }
                    return json($r);
                    exit;
                }
            }
        }
    }



        //企业会员上传环境图片
        public function uploadCompany(){
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
            $c_id = db('scmember_company')->where(array('c_loginid'=>$user[0]))->find();

            $file = request()->file('content');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');       //当前地址根目录下
            if ($info) {
                $data['i_rid'] = $c_id['c_id'];
                $data['i_image'] = $info->getSaveName();
                $data['i_time'] = time();
                $newid = db('recruit_image')->insertGetId($data);
                if ($newid) {
                    $nd['i_id'] = $newid;
                    $nd['i_rid'] = $data['i_rid'];
                    $nd['i_image'] =  config('baseurl')."uploads/".$data['i_image'];
                    $r['code'] = 1;
                    $r['msg'] = "图片上传成功！";
                    $r['data'] = $nd;
                }else{
                    $r['code'] = 0;
                    $r['msg'] = "图片上传失败！";
                    $r['data'] = array();
                }
                return json($r);
                exit();
            }else{
                $file->getError();
            }
        // }
    }



    // 普通会员注册
    
    public function addmessage()
    {
        $openid = input('openid');
        if (!$openid) {
            $r['code'] = '0';
            $r['msg'] = 'openid不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        } else {
            //检测微信号是否已经注册
            $weixin = db("user_login")->where("l_openid",$openid)->find();
            if($weixin){
                $result['code'] = '0';
                $result['msg'] = "微信号已经注册";
                $result['data'] = array();
                return json($result);
                exit();
            }
            //检测手机号是否已经注册
            $phone = input("phone");
            $lphone = db("user_login")->where("l_phone",$phone)->find();
            if($lphone){
                $result['code'] = '0';
                $result['msg'] = "此电话已经注册";
                $result['data'] = array();
                return json($result);
                exit();
            }
            $code = input("code");
            $map['t_telphone'] =$phone;
            $map['t_telcode'] = $code;
            //检测验证码是否正确
            $res = db("telcode")->where($map)->find();
            if ($res) {
                $t = $res['t_sendtime'];
                $c = time() - $t;
                if ($c > 600) {
                    $result['code'] = '0';
                    $result['msg'] = "验证码已过期";
                    $result['data'] = array();
                    return json($result);
                    exit();
                }
            } else {
                $result['code'] = '0';
                $result['msg'] = "验证码错误";
                $result['data'] = array();
                return json($result);
                exit();
            }
            //执行插入
            $login['l_phone'] = $phone;
            $login['l_name'] = input("lname");
            $login['l_time'] = time();
            $login['l_type'] = '1';
            $login['l_openid'] = $openid;
            $lid = db("user_login")->insertGetId($login);
            if ($lid) {
                //插入信息表
                $info['s_loginid'] = $lid;
                $info['s_name'] = input("lname");
                $info['s_sex'] = input("sex");
                $info['s_birthday'] = input("birthday");
                $info['s_phone'] = $phone;

                $info['s_privice'] = input("privice");
                $info['s_city'] = input("city");
                $info['s_area'] = input("area");

                $info['s_job'] = input("job");
                $info['s_time'] = time();
                $info['s_openid'] = $openid;
                $re=db('smember_simpeople')->insert($info);
            }
            if($lid&&$re){
                $result['code'] = '1';
                $result['msg'] = "注册成功";
                $result['data'] = array();
            }else{
                $result['code'] = '0';
                $result['msg'] = "注册失败";
                $result['data'] = array();
            }

            return json($result);
        }
    }
    //根据loginid获取用户登录手机号码
    public function getLoginPhoneInfo(){
        $loginid=input('lid');
        $res=db('user_login')->field('l_phone')->where('l_id='.$loginid)->find();
//        print_r($res);
//        exit();
        if($res){
            $result['code'] = '1';
            $result['msg'] = "查询成功";
            $result['data'] = $res;
        }else{
            $result['code'] = '0';
            $result['msg'] = "查询失败";
            $result['data'] = array();
        }
        return json($result);
    }
    //修改密码
    public function updatePassword(){
        $phone=input('phone');
        $code = input("code");
        $map['t_telphone'] =$phone;
        $map['t_telcode'] = $code;
        //检测验证码是否正确
        $rcode = db("telcode")->where($map)->find();
        if ($rcode) {
            $t = $rcode['t_sendtime'];
            $c = time() - $t;
            if ($c > 600) {
                $r['code'] = '0';
                $r['msg'] = "验证码已过期";
                $r['data'] = array();
                return json($r);
                exit();
            }
        } else {
            $r['code'] = '0';
            $r['msg'] = "验证码错误";
            $r['data'] = array();
            return json($r);
            exit();
        }

        $loginid=input('lid');
        $password=md5(input('pass'));
        $up['l_password']=$password;
        $res=db('user_login')->where('l_id='.$loginid)->update($up);
        if($res){
            $r['code'] = '1';
            $r['msg'] = "保存成功";
            $r['data'] = array();
            return json($r);
        }else{
            $r['code'] = '0';
            $r['msg'] = "保存失败";
            $r['data'] = array();
            return json($r);
        }
    }
    //找回密码
    public function forgetPassword(){
        $phone=input('phone');
        $code = input("code");
        $map['t_telphone'] =$phone;
        $map['t_telcode'] = $code;
        //检测验证码是否正确
        $rcode = db("telcode")->where($map)->find();
        if ($rcode) {
            $t = $rcode['t_sendtime'];
            $c = time() - $t;
            if ($c > 600) {
                $r['code'] = '0';
                $r['msg'] = "验证码已过期";
                $r['data'] = array();
                return json($r);
                exit();
            }
        } else {
            $r['code'] = '0';
            $r['msg'] = "验证码错误";
            $r['data'] = array();
            return json($r);
            exit();
        }

        $password=md5(input('pass'));
        $up['l_password']=$password;
        $res=db('user_login')->where('l_phone',$phone)->update($up);
        if($res){
            $r['code'] = '1';
            $r['msg'] = "保存成功";
            $r['data'] = array();
            return json($r);
        }else{
            $r['code'] = '0';
            $r['msg'] = "保存失败";
            $r['data'] = array();
            return json($r);
        }
    }
}


