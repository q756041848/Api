<?php
/**
 * Created by PhpStorm.
 * User: xiaob
 * Date: 2019/4/29
 * Time: 16:45
 */
namespace app\admin\model;
use think\Model;
use think\Db;

class ApiModel extends  Model
{
    //======================================主页================================================//
    public function index(){
        $id = input('id');
        $date = \db('pm')->where('p_id',$id)->select();
        $Cshow = \db('code')->where('c_pid',$id)->select();
        $connector = \db('connector')->where('c_pm',$id)->select();
        $interModuleId = \db('pfolder')->where('pm_id',$id)->select();
        $pfolder = \db('pfolder')->where('pm_id',$id)->select();

        foreach($pfolder as $key=>&$v)
        {
            $v['con']=db('connector')->where('c_pidname='.$v['id'])->select();
        }
        $msg = [
            'id'=>$id,
            'date'=>$date,
            'Cshow'=>$Cshow,
            'connector'=>$connector,
            'pfolder'=>$pfolder,
            'interModuleId'=>$interModuleId

        ];
        return $msg;
    }
    //======================================主页================================================//



    //======================================文档、公共请求参数================================================//
    //index页文档修改
    public function WenDang(){
        return \db('pm')->update(input('post.'));
    }


    //公共参数修改
    public function CommonParamInfoTab(){
        return \db('pm')->update(input('post.'));
    }
    //======================================文档、公共请求参数================================================//



    //======================================文件夹================================================//
    //文件夹上面所有的局部刷新
    public function SuoYou(){
        return \db('connector')->where('c_pm',input('id'))->select();
    }


    //文件夹的局部刷新
    public function ApiMokuai(){
        $id = input('id');
        $pfolder = \db('pfolder')->where('pm_id',$id)->select();
        foreach($pfolder as $key=>&$v)
        {
            $v['con']=db('connector')->where('c_pidname='.$v['id'])->select();
        }
        $msg = ['id'=>$id, 'pfolder'=>$pfolder];
        return $msg;
    }


    //文件夹添加修改
    public function MoKuai(){
        $date = input('post.');
        if ($date['p_ids']=='0'){
            unset($date['p_ids'],$date['id']);
            $info = \db('pfolder')->insertGetId($date);
            if ($info) {// $msg['status'] = "添加成功";
                $msg['id'] = db('pfolder')->where(['id'=>$info])->field('pm_id')->find();
                $msg['sta'] = '0';
            }
        }else{
            unset($date['p_ids']);
            $info = \db('pfolder')->update($date);
            if ($info) {//$msg['status'] = "修改成功";
                $msg['id'] = db('pfolder')->where(['id'=>$date['id']])->field('pm_id')->find();
                $msg['sta'] = '1';
            }
        }
        return $msg;
    }


    //文件夹删除
    public function PfolderDel(){
        if (input('num')){
            //如果模块下有接口全部移到默认模块
            \db('connector')->where(['c_id'=>input('id')])->update(['c_pidname'=>0]);
            $info = \db('pfolder')->delete(['id'=>input('id')]);
        }else{
            $info = \db('pfolder')->delete(['id'=>input('id')]);
        }
        return $info;
    }

    //======================================文件夹================================================//



    //======================================错误码部分================================================//
    //错误码的局部刷新
    public function ApiCuowuma(){
        $Cshow = \db('code')->where(['c_pid'=>input('id')])->select();
        return $Cshow;
    }


    //错误码添加修改
    public function Code()
    {
        $date = input('post.');
        if ($date['c_ids'] == '0') {
            unset($date['c_ids'], $date['c_id']);
            $info = \db('code')->insertGetId($date);
            if ($info) {
                $msg['status'] = "保存成功";
                $msg['data'] = db('code')->where(['c_id' => $info])->find();
            }
        } else {
            unset($date['c_ids']);
            $info = \db('code')->update($date);
            if ($info) {
                $msg['status'] = "保存成功";//修改
                $msg['data'] = db('code')->where(['c_id' => $date['c_id']])->find();
            }
        }
        return $msg;
    }

    //======================================错误码部分================================================//




    //======================================接口部分=====================================================//
    //接口的局部刷新
    function ApiJiekou(){
        if (input('con')){
            $where2['c_title'] = array('like', "%".input('con')."%");
        }else{
            $where2 = "1=1";
        }
        if (input('pid')){
            $where1['c_pm'] = input('pid');
        }else{
            $where1['c_pidname'] = input('id');
        }
        $connector = \db('connector')
            ->where($where1)
            ->where($where2)
            ->select();
        if ($connector){
            $num = 1;
        }else{
            $num = 0;
        }
         $msg = ['connector'=>$connector];
         return $msg;
    }


    //接口的添加修改
    public function JieKou(){
        $date = input('post.');
        if ($date['con_ids']=='0'){
            unset($date['con_ids'],$date['c_id']);
            $info = \db('connector')->insertGetId($date);
            if ($info) {
                $msg['status'] = "保存成功";
                $msg['id'] = db('connector')->where(['c_id'=>$info])->field('c_pidname')->find();
                $msg['pid'] = $info;
                $msg['sta'] = 1;
            }
        }else{
            unset($date['con_ids']);
            $info = \db('connector')->update($date);
            if ($info) {
                $msg['status'] = "修改成功";
                $msg['id'] = db('connector')->where(['c_id'=>$date['c_id']])->field('c_pidname')->find();
                $msg['pid'] = $date['c_id'];
                $msg['sta'] = 2;
            }
        }
        return $msg;
    }


    //删除接口
    function JiekouDel(){
        $info = \db('connector')->delete(['c_id'=>input('id')]);
        return '1';
    }

    //点击修改接口 ->接口修改页面显示的选择文件夹->单独映射
    public function ApiMokuais(){
        $interModuleId = \db('pfolder')->where(['pm_id'=>input('id')])->select();
        return $interModuleId;
    }
    //======================================接口部分=====================================================//


    //======================================接口->请求参数================================================//

    public function QingQiu(){
        $dates = db('required')->where(['pid'=>input('id')])->select();
        return $dates;
    }
    //======================================接口->请求参数END================================================//
}