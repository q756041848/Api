<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Input;
use think\Session;
use app\admin\model\Indexs;
class Index extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        // 生成token，并映射到表单input里
        $token = $this->request->token('__token__', 'sha1');
        $this->assign('token', $token);
//        var_dump(request()->session());exit;
        $datas = new Indexs();
        $datalist = $datas->index();
//       新版本

        //映射input  如果input有值传自身，没值传3 代表全部
        $this->assign('codes',$datalist['codes']);
        $this->assign('p_title',$datalist['p_title']);
        $this->assign('p_etitle',$datalist['p_etitle']);

        //分页主页=
        $this->assign('info',$datalist['info']);
        $this->assign('showpage',$datalist['info']->render());
        $this->assign('total',$datalist['info']->total());
        return $this->fetch();
    }


    //修改管理员状态
    public function adminState(){
        $pid=input('post.id');
        //实例化类
        $datas = new Indexs();
        $result = $datas->adminState($pid);
//        if($status==1){
//            $data['p_status'] = 0;`
//            db('pm')->where('p_id='.$pid)->update($data);
//            $result['status'] = 1;
//            $result['info'] = '已禁止';
//        }else{
//            $data['p_status'] = 1;
//            db('pm')->where('p_id='.$pid)->update($data);
//            $result['status'] = 1;
//            $result['info'] = '已启用';
//        }
        $arr[]=$result['info'];
        $arr[]=$result['status'];
        return $arr;
    }


    //删除
    public function deletes()
    {

        $p_id=input('p_id');
        $datas = new Indexs();
        $info = $datas->deletes($p_id);
        if ($info) {
            $msg = ['code' => 1, 'msg' => '删除成功'];
        } else {
            $msg = ['code' => 0, 'msg' => '删除失败'];
        }
        $this->redirect('index');
    }


//添加
    public function add()
    {

        return $this->fetch();
    }

    public function do_add()
    {
        $token = request()->param("__token__"); //这是表单提交获取的token
        $stoken = request()->session("__token__"); //session中的token

        $data=input('post.');
        unset($data['__token__']);


        if ($token == $stoken) {
            //数据库插入数据
            // 提交一次清空token
            $datas = new Indexs();
            $info = $datas->do_add($data);
            session('__token__', null);

            return json($info);

        } else {
            // 手速快多次点击不做任何操作
            // echo '请勿重复提交';
        }


    }



//    //修改状态
//    public function newsState(){
//        $id=input('post.p_id');
//
//        $status=db('pm')->where('p_id='.$id)->value('p_status');//判断当前状态情况
//        if($status==1){
//            $data['p_status'] = 0;
//            db('pm')->where('p_id='.$id)->update($data);
//            $result['status'] = 1;
//            $result['info'] = '关闭';
//        }else{
//            $data['p_status'] = 1;
//            $data['publish_time'] = time();
//            db('pm')->where('p_id='.$id)->update($data);
//            $result['status'] = 1;
//            $result['info'] = '开启';
//        }
//        return $result;
//    }
//
//

}


?>