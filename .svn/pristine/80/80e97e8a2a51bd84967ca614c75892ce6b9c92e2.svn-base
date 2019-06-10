<?php



namespace app\admin\Model;
use think\Request;
use think\Controller;
use think\Session;
use think\Model;


class Logins extends  Model
{
    public function check($code = '')
    {
        if (!captcha_check($code)) {
            return ('验证码错误');
        } else {
            return true;
        }
    }
  public function regist($data){

      $data['a_addtime']=time();
      $data['a_is_open']=1;
      $data['a_password']=md5(input('a_password'));
      $info=db('admin')->insert($data);
       return $info;

  }
    public function action(){

        $map['a_username']=input('a_username');
        $map['a_is_open']=1;
        $this->check(input('code'));
        $password=md5(input('a_password'));
        $admininfo=db('admin')->where($map)->find();


        if (!$admininfo){
            //$this->error('用户名或者密码错误，重新输入');
            $msg=0;
        }else{
            if($password == $admininfo['a_password']){
                //登录后更新数据库，登录IP，登录次数,登录时间
                $data['a_ip'] = Request::instance()->ip();
                $where['a_id'] = $admininfo['a_id'];
                db('admin')->where($where)->setInc('a_hits',1);
                db('admin')->where($where)->update($data);
                Session::set('aid',$admininfo['a_id']);
                Session::set('a_username',$admininfo['a_username']);
                $result['status'] = 1;
                $result['msg'] = '恭喜您，登陆成功! ';
//                $this->redirect('/admin/login/');
                $msg=1;
            }else{
//                $this->error('用户名或者密码错误，重新输入');
//                exit();
                $msg=2;
            }
        }
        return $msg;
    }
}