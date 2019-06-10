<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
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
        $admin = db('admin');
        $map['a_username']=input('username');
        $map['a_is_open']=1;
        $this->check(input('code'));
        $password=md5(input('password'));
        $admininfo=$admin->where($map)->find();

        if (!$admininfo){
            $this->error('用户名或者密码错误，重新输入');
            exit();
        }else{
            if($password == $admininfo['a_password']){
                //登录后更新数据库，登录IP，登录次数,登录时间
                $data['a_ip'] = Request::instance()->ip();
                $where['a_id'] = $admininfo['a_id'];
                $admin->where($where)->setInc('a_hits',1);
                $admin->where($where)->update($data);
                Session::set('aid',$admininfo['a_id']);
                Session::set('a_username',$admininfo['a_username']);
                $result['status'] = 1;
                $result['msg'] = '恭喜您，登陆成功!';
                return $result;
            }else{
                $this->error('用户名或者密码错误，重新输入');
                exit();
            }
        }
    }
    //退出登陆
    public function logout(){
        session(null);
        $this->redirect('login/login');
    }
}