<?php
namespace app\api\controller;
use think\Controller;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
class Company extends Base
{
    //读取企业简介
    public function getCompanyBrief(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];
        $con = db("scmember_company")->field("c_id,c_des")->where("c_loginid",$userid)->find();
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$con;
        return json($r);

    }
    //修改企业简介
    public function updateCompanyBrief(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];

        $d['c_des'] = input("cdes");
        $db = db('scmember_company');
        $res=$db->where('c_loginid',$userid)->update($d);
        if ($res) {
            $r['code'] = 1;
            $r['msg'] = "保存成功！";
            $r['data'] = $res;
        }else{
            $r['code'] = 0;
            $r['msg'] = "保存失败！";
            $r['data'] = $db->getLastSql();
        }
        return json($r);
    }
    //获取企业的工作环境图片
    public function getComImageByToken(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];

        $db =db("scmember_company");
        $res = $db->alias("sc")
               ->join("zydz_recruit_image ri","sc.c_id=ri.i_rid")//公司id
                ->field("ri.i_id,ri.i_rid,i_image")
               ->where("sc.c_loginid",$userid)->select();

//        echo $db->getLastSql();
//        exit();
        foreach ($res as $key => $value) {
            $res[$key]["i_image"] = config('baseurl')."uploads/".$value['i_image'];
        }
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }
    //删除工作环境图片
    public function delComImageById(){
        $i_id = input("imageid");
        $db = db("recruit_image");
        $image = $db->where("i_id",$i_id)->find();
        if($image){
            $re = $db->where("i_id",$i_id)->delete();
            if($re){
                //删除文件
                $filepath = $_SERVER['DOCUMENT_ROOT']."/uploads/".$image['i_image'];
                try{
                    unlink($filepath);
                }catch (Exception $e){

                }
                $r['code']='1';
                $r['msg'] = '删除成功';
                $r['data']=array();
            }else{
                $r['code']='0';
                $r['msg'] = '删除异常';
                $r['data']=array();
            }
        }else{
            $r['code']='404';
            $r['msg'] = 'id输入有误';
            $r['data']=array();
        }
        return json($r);
    }
    //修改企业资料
    public function updateCompanyInfo(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];

        $res = db("scmember_company")->where("c_loginid",$userid)->find();
        $data=array(
            'c_name'=>input('c_name'),
            'c_contacts'=>input('c_contacts'),
            'c_phone'=>input('c_phone'),
            'c_type'=>input('c_type'),
            'c_scale'=>input('c_scale'),
            'c_addr'=>input('c_addr'),
            'c_longitude'=>input('c_longitude'),
            'c_latitude'=>input('c_latitude'),
        );
        if($res){
            //已经有记录
            $kr = db("scmember_company")->where("c_loginid",$userid)->update($data);
            if($kr){
                $r['code'] = 1;
                $r['msg'] = "保存成功！";
                $r['data'] = $kr;
            }else{
                $r['code'] = 0;
                $r['msg'] = "保存失败！";
                $r['data'] = $kr;
            }
        }else{
            //还没有记录
            $data['c_loginid'] = $userid;
            $kr = db("scmember_company")->insert($data);
            if($kr){
                $r['code'] = 1;
                $r['msg'] = "保存成功！";
                $r['data'] = $kr;
            }else{
                $r['code'] = 0;
                $r['msg'] = "保存失败！";
                $r['data'] = $kr;
            }
        }

        return json($r);
    }
}