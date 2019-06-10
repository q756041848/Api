<?php

namespace app\api\controller;

use think\Controller;
use think\Db;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

class Recruit extends Base
{
    //用户查看招聘信息详情-生成浏览记录
    public function addBrowse()
    {
        $token = input("token");
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));
        $d['b_lid'] = $userinfo[0];
        $d['b_rid'] = input("rid");  //招聘信息id

        //查询该用户之前是否浏览过此信息
        $browse = db("recruit_browse")->where($d)->find();
        if ($browse) {
            //之前已经浏览过-只需要修改
            $res = db("recruit_browse")->where($d)->update(["b_time" => time()]);
        } else {
            $d['b_time'] = time();
            $res = db("recruit_browse")->insert($d);
        }
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '浏览记录添加成功';
            $r['data'] = array();
        } else {
            $r['code'] = '0';
            $r['msg'] = '浏览记录添加失败';
            $r['data'] = array();
        }
    }

    //获取我的浏览记录列表
    public function getBrowseList()
    {
        $token = input('token');
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));
        $uid = $userinfo[0];
        $pageindex = input("pageindex") ? input("pageindex") : 1;
        $pagesize = input("pagesize") ? input("pagesize") : config("pagesize");
        $db = db("recruit_browse");
        $res = $db->alias('rb')//浏览表
        ->join('zydz_recruit r', 'rb.b_rid=r.r_id')//招聘信息主表
//            ->join('zydz_data_scale scale','r.r_scale = scale.s_id')      //薪资
        ->join('zydz_scmember_company sc', 'r.r_companyid=sc.c_id')//公司
        ->field('rb.b_id as bid,rb.b_time as btime ,r.r_id as rid,r.r_title as rtitle,r.r_scale as scalename,sc.c_name as companyname')
            ->where("rb.b_lid", $uid)
            ->order("b_time desc")
            ->page($pageindex, $pagesize)->select();
//        echo $res->getLastSql();
//
//        echo "<pre>";
//        print_r($res);
//        exit();

        //转换时间格式
        foreach ($res as $k => $v) {
            $res[$k]['btime'] = date("Y-m-d H:i", $v['btime']);
        }
        $r['code'] = '1';
        $r['msg'] = '查询成功';
        $r['data'] = $res;
        return json($r);
    }

    //我的收藏
    public function getCollectionList()
    {
        $token = input("token");
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));
        $uid = $userinfo[0];
        $pageindex = input("pageindex") ? input("pageindex") : 1;
        $pagesize = input("pagesize") ? input("pagesize") : config("pagesize");
        $db = db("recruit_collection");
        $res = $db->alias('rc')//收藏表
        ->join('zydz_recruit r', 'rc.c_rid=r.r_id')//招聘信息主表
//        ->join('zydz_data_scale scale','r.r_scale = scale.s_id')      //薪资
        ->join('zydz_scmember_company sc', 'r.r_companyid=sc.c_id')//公司
        ->field('rc.cc_id as cid,rc.cc_time as ctime ,r.r_id as rid,r.r_title as rtitle,r.r_scale as scalename,sc.c_name as companyname')
            ->where("rc.c_lid", $uid)
            ->order("cc_time desc")
            ->page($pageindex, $pagesize)->select();
        //转换时间格式
        foreach ($res as $k => $v) {
            $res[$k]['ctime'] = date("Y-m-d H:i", $v['ctime']);
        }
        $r['code'] = '1';
        $r['msg'] = '查询成功';
        $r['data'] = $res;
        return json($r);
    }

    //我的投递
    public function getDeliveryList()
    {
        $token = input("token");
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));
        $uid = $userinfo[0];
        $pageindex = input("pageindex") ? input("pageindex") : 1;
        $pagesize = input("pagesize") ? input("pagesize") : config("pagesize");
        $db = db("recruit_delivery");
        $res = $db->alias('rd')//收藏表
        ->join('zydz_recruit r', 'rd.d_rid=r.r_id')//招聘信息主表
//        ->join('zydz_data_scale scale','r.r_scale = scale.s_id')      //薪资
        ->join('zydz_scmember_company sc', 'r.r_companyid=sc.c_id')//公司
        ->field('rd.d_id as did,rd.d_time as dtime ,r.r_id as rid,r.r_title as rtitle,r.r_scale as scalename,sc.c_name as companyname')
            ->where("rd.d_lid", $uid)
            ->order("d_time desc")
            ->page($pageindex, $pagesize)->select();
        //转换时间格式
        foreach ($res as $k => $v) {
            $res[$k]['dtime'] = date("Y-m-d H:i", $v['dtime']);
        }
        $r['code'] = '1';
        $r['msg'] = '查询成功';
        $r['data'] = $res;
        return json($r);
    }

    //我的推荐
    public function getRecommendList()
    {
        $pageindex = input("pageindex") ? input("pageindex") : 1;
        $pagesize = input("pagesize") ? input("pagesize") : config("pagesize");
        $token = input('token');
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));
        $uid = $userinfo[0];
        $time = time();
        $data = db('user_login')->alias('a')
            ->join('recruit_recommend c', 'a.l_id = c.r_uid')
            ->join('recruit r', 'c.r_wid = r.r_id')
            ->join('scmember_company g', 'r.r_companyid = g.c_id')
            ->where('l_id', $uid)
            ->order('c.r_time desc')
            ->page($pageindex, $pagesize)->select();
//        echo "<pre>";
//        print_r($data);
//        exit();
// 			foreach ($data as $key => $value) {
//                $data[$key]['time'] = $time - $data[$key]['r_time'];
//                if($data[$key]['time']<='86400'){
//                	$data[$key]['r_time'] = '今天';
//                }else{
//                    $data[$key]['r_time'] = date("Y/m/d H:i",$data[$key]['r_time']);
//                }
//            }
        return json($data);
    }

    //企业账号-我的发布-招聘信息
    public function getRecruitByToken()
    {
        $token = input("token");
//        $token = "amVeZJdpZ5ufY5aUZZhhl40=";
        $pageindex = input("pageindex") ? input("pageindex") : 1;
        $pagesize = input("pagesize") ? input("pagesize") : config("pagesize");
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }

        $userinfo = explode(',', $this->decrypt($token));
//        echo "<pre>";
//        print_r($userinfo);
//        exit();
        $b = db("recruit");
        $res = $b->alias('r')
            ->join('zydz_scmember_company sc', 'r.r_companyid=sc.c_id')//公司
//            ->field('r.r_id as rid,r.r_title as rtitle,re.name as regionname,exper.e_name as expname,edu.e_name as eduname, scale.s_name as scalename,
//            r.r_number as rnumber,r.r_check as rcheck, sc.c_name as companyname, r.r_welfare as r_welfare, r.r_time as rtime')
            ->where("r.r_cid", $userinfo[0])//登录id
            ->order("r_time desc")
            ->page($pageindex, $pagesize)->select();


        foreach ($res as $k => $v) {
            //设置福利
            $res[$k]['r_welfare'] = explode(',', $v['r_welfare']);
            //浏览人数
            $res[$k]['r_browseNumber'] = db("recruit_browse")->where("b_rid", $v['r_id'])->count();
            //投递人数
            $res[$k]['r_deliveryNumber'] = db("recruit_delivery")->where("d_rid", $v['r_id'])->count();
            //审核状态
            $check = $v['r_check'];
            if ($check == '0') {
                $res[$k]['r_check'] = '审核中';
            } else if ($check == '1') {
                $res[$k]['r_check'] = '审核通过';
            } else {
                $res[$k]['r_check'] = '审核失败';
            }
        }

        if (input('t') == '1') {
            echo "<pre>";
            print_r($res);
            exit();
        }
        $r['code'] = '1';
        $r['msg'] = '查询成功';
        $r['data'] = $res;
        return json($r);
    }

    //企业账号-发布招聘信息
    public function releaseRecruit()
    {
        $token = input("token");
        if (!$token) {
            $r['code'] = '404';
            $r['msg'] = 'token不能为空';
            $r['data'] = array();
            return json($r);
            exit();
        }
        $userinfo = explode(',', $this->decrypt($token));

        $areaid = input("rareaid"); //区
        $cityinfo = Db::query("select * from zydz_data_region  where  id=(select pid from zydz_data_region where id='$areaid')");
        if ($cityinfo) {
            $cityid = $cityinfo[0]['id'];
            $provinceinfo = Db::query("select * from zydz_data_region  where  id=(select pid from zydz_data_region where id='$cityid')");
        }


        $d['r_cid'] = $userinfo[0];   //发布者id
        $d['r_title'] = input("rtitle"); //招聘标题
        $d['r_headwork'] = input("rheadwork"); // 一级岗位
        $d['r_work'] = input("rwork");   //二级岗位
        $d['r_scale'] = input("rscale");  //薪资
        $d['r_exper'] = input("rexper");   //经验
        $d['r_number'] = input("rnumber");  //人数
        $d['r_education'] = input("reducation"); //学历
//        if ($provinceinfo) {
//            $d['r_province'] = $provinceinfo[0]['name'];  //省
//        }
        if ($cityinfo) {
//            $d['r_city'] = $cityinfo[0]['name'];       //市
            $d['r_province'] = $cityinfo[0]['name'];       //市
        }


        $d['r_city'] = input("rareaname");     //区
        $d['r_detailaddress'] = input('detailaddress');//详细地址
        $d['r_type'] = input("rtype");    //全职or兼职
        $d['r_welfare'] = input("rwelfare");  //福利多个福利可使用逗号隔开', 房补,车补
        $d['r_desc'] = input("rdesc");       //描述
        $d['r_check'] = '0';     //审核 0待审核  1 通过  2 未通过
        $d['r_sort'] = '100';     //排序
        $d['r_time'] = date('Y-m-d H:i:s');  //发布时间

        //取得企业的id
        $getcid = db("scmember_company")->where("c_loginid", $userinfo[0])->find();
        $d['r_companyid'] = $getcid['c_id'];   //小程序发布-发布者就是企业

        $res = db("recruit")->insert($d);
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '发布成功';
            $r['data'] = array();
        } else {
            $r['code'] = '0';
            $r['msg'] = '发布失败';
            $r['data'] = array();
        }
        return json($r);
    }

    //根据招聘信息id删除招聘信息
    public function delRecuit()
    {
        $rid = input("rid");
        $res = db("recruit")->where("r_id", $rid)->delete();
        if ($res) {
            $r['code'] = '1';
            $r['msg'] = '删除成功';
            $r['data'] = array();
        } else {
            $r['code'] = '0';
            $r['msg'] = '删除失败';
            $r['data'] = array();
        }
        return json($r);
    }


}