<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Logins;
class Login extends Controller
{
    private $sysConfig ,$cache_model,$siteConfig,$menudata ;
    function _initialize()
    {

    }
    public function check($code = '')
    {
        if (!captcha_check($code)) {
            $this->error('验证码错误');
        } else {
            return true;
        }
    }
    public function login()
    {
        //判断管理员是否登录
        $aid = session('aid');
        if($aid){
            $this->redirect('index/index');
        }

        return $this->fetch();
    }
    public function action(){
        $datas = new Logins();
        $info = $datas->action();

            $this->redirect('index/index');


    }
    public  function  register(){
        return $this->fetch();
    }
    public  function  regist(){
        $data=input('post.');
        //实例化类
        $datas = new Logins();
        $info = $datas->regist($data);
        if(empty($info)){
            $result['status'] = 0;
            $result['msg'] = '注册失败 ';
            exit();
        }else{
            $result['status'] = 1;
            $result['msg'] = '恭喜您，注册成功! ';
            return $result;
        }
    }
    //退出登陆
    public function logout(){
        session(null);
        $this->redirect('login/login');
    }
}