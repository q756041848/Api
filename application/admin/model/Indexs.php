<?php


namespace app\admin\model;

use think\Request;
use think\Controller;
use think\Session;
use think\Model;

class Indexs extends  Model
{
  public function  index(){
      if (!session('aid')) {
          $this->redirect('login/login');
      }

      $title['p_title'] = input('p_title')?array('like', "%".input('p_title')."%"):array('neq', "*");
      $etitle['p_etitle'] = input('p_etitle')?array('like', "%".input('p_etitle')."%"):array('neq', "*");
      switch (input('code')) {
          case "0":
              $code['p_status'] = array('eq', '0');
              $codes = 0;
              break;
          case "1":
              $code['p_status'] = array('eq', '1');
              $codes = 1;
              break;
          default:
              $code['p_status'] = array('between', '0,1');
              $codes = 2;
      }

      $info=db('pm')
          ->where($title)
          ->where($etitle)
          ->where($code)
          ->order('p_id desc')
          ->paginate(10);

            $datalist=[
                'info'=>$info,
                'p_title'=>input('p_title'),
                'p_etitle'=>input('p_etitle'),
                'codes'=>$code
            ];
            return $datalist;

  }



    public function adminState($pid){
        if (empty($pid)){
            $result['status'] = 0;
            $result['info'] = '�û�ID������!';
            $result['url'] = url('index');
            exit;
        }
        $status=db('pm')->where('p_id='.$pid)->value('p_status');//�жϵ�ǰ״̬���
        if($status==1){
            $data['p_status'] = 0;
            db('pm')->where('p_id='.$pid)->update($data);
            $result['status'] = 1;
            $result['info'] = '0';
        }else{
            $data['p_status'] = 1;
            db('pm')->where('p_id='.$pid)->update($data);
            $result['status'] = 1;
            $result['info'] = '1';
        }
        return $result;
    }

    public function deletes($p_id)
    {

        $info=db('pm')->where('p_id='.$p_id)->delete();
        $infos=db('pfolder')->where('pm_id='.$p_id)->delete();
        $infoss=db('connector')->where('c_pm='.$p_id)->delete();
        $infosss=db('code')->where('c_pid='.$p_id)->delete();

      return $info;
    }
    public function do_add($data)
    {


        $data['p_time']=time();

        $info=db('pm')->insert($data);


        return $info;
    }

}