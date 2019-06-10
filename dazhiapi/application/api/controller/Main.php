<?php
/**

 * SELECT *,(POWER(MOD(ABS(c_longitude - 118),360),2) + POWER(ABS(c_latitude - 35),2)) AS distance
FROM zydz_scmember_company ORDER BY distance LIMIT 1000
 */

namespace app\api\controller;

use think\Controller;
use think\Db;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
class Main extends Base
{
//    //获取招聘信息
//    public function getRecruitList(){
//        $pageindex = input("pageindex")?input("pageindex"):1;
//        $pagesize = input("pagesize")?input("pagesize"):config("pagesize");
//        $map=array();
//        if(input("region")){
//            $map['r_city'] = input("region");
//        }
//        if(input("work")){
//            $map['r_work'] = input("work");
//        }
//        if(input("scale")&&input("scale")!='0'){
//            $map['r_scale'] = input("scale");
//        }
//        if(input("title")){
//            $map['r_title']  = ['like','%'.input("title").'%'];
//        }
//        $map['r_check'] = '1';
//        $db = db("recruit");
//        $res=$db->alias('r')
//            ->join('zydz_data_region re','r.r_area=re.id')           //地区
//            ->join('zydz_data_work work','r.r_work = work.w_id')
//            ->join('zydz_data_exper exper','r.r_exper = exper.e_id') //经验
//            ->join('zydz_data_education edu','r.r_education = edu.e_id')  //学历
//            ->join('zydz_data_scale scale','r.r_scale = scale.s_id')      //薪资
//            ->join('zydz_scmember_company sc','r.r_companyid=sc.c_id')  //公司
//
//            ->field('r.r_id as rid,r.r_title as rtitle,re.name as regionname,exper.e_name as expname,edu.e_name as eduname, scale.s_name as scalename,
//            r.r_number as rnumber,sc.c_name as companyname, r.r_welfare as r_welfare')
//            ->where($map)
//            ->order("r_sort desc")
//            ->page($pageindex,$pagesize)->select();
//
//        //设置福利
//
//        foreach ($res as $k=>$v){
//            $res[$k]['r_welfare'] = explode(',',$v['r_welfare']);
//        }
//        //$res = db("recruit")->page($pageindex,$pagesize)->order("r_sort desc")->select();
//        if(input('t')=='1'){
//            echo "<pre>";
//            print_r($res);
//            exit();
//        }
//        $r['code']='1';
//        $r['msg'] = '查询成功';
//        $r['data']=$res;
//        return json($r);
//
//    }

   //获取招聘信息列表-按照距离排序
    public function getRecruitList(){
        $pageindex1 = input("pageindex")?input("pageindex"):0;

        $pagesize = input("pagesize")?input("pagesize"):config("pagesize");
        $pageindex=$pageindex1* $pagesize;
        $map=array();
        $area = input("region");
        $local_lat = input("lat");
        $local_long = input("long");
        $work='';
        $scale='';
        $title=input("title");
        if(input("work")&&input("work")!='职位'){
            $work = input("work");
        }
        if(input("scale")&&input("scale")!="薪资"){
            $scale = input("scale");
        }

        $map['r_check'] = '1';
        // 小程序获取本地位置成功
        if($local_lat&&$local_long){
            $res = DB::query("SELECT `r_id`,`r_title`,`r_headwork`,`r_work`,`r_scale`,`r_exper`,`r_number`,`r_education`,`r_province`,`r_city`,
`r_area`,`r_detailaddress`,`r_type`,`r_welfare`,`r_desc`,`r_sort`,`r_time`,`sc`.`c_name`,sc.c_name as companyname,sc.c_longitude,sc.c_latitude,
(POWER(MOD(ABS(sc.c_longitude - $local_long),360),2) + POWER(ABS(sc.c_latitude - $local_lat),2)) AS distance
FROM `zydz_recruit` `r` INNER JOIN `zydz_scmember_company` `sc` ON `r`.`r_companyid`=`sc`.`c_id` 
WHERE `r_check` = 1 
AND ( `r_province` LIKE '%$area%' OR `r_city` LIKE '%$area%' OR `r_area` LIKE '%$area%' OR `r_detailaddress` LIKE '%$area%' ) 
and r_title like '%$title%' and r_work like '%$work%' and r_scale like '%$scale%' 
ORDER BY `r_sort` DESC,distance,`r_time` DESC LIMIT $pageindex,$pagesize");
        }else{
            $res = DB::query("SELECT `r_id`,`r_title`,`r_headwork`,`r_work`,`r_scale`,`r_exper`,`r_number`,`r_education`,`r_province`,`r_city`,
`r_area`,`r_detailaddress`,`r_type`,`r_welfare`,`r_desc`,`r_sort`,`r_time`,`sc`.`c_name`,sc.c_name as companyname,sc.c_longitude,sc.c_latitude,
'' AS distance
FROM `zydz_recruit` `r` INNER JOIN `zydz_scmember_company` `sc` ON `r`.`r_companyid`=`sc`.`c_id` 
WHERE `r_check` = 1 
AND ( `r_province` LIKE '%$area%' OR `r_city` LIKE '%$area%' OR `r_area` LIKE '%$area%' OR `r_detailaddress` LIKE '%$area%' ) 
and r_title like '%$title%' and r_work like '%$work%' and r_scale like '%$scale%' 
ORDER BY `r_sort` DESC,`r_time` DESC LIMIT $pageindex,$pagesize");
        }

        foreach ($res as $k=>$v){
            $res[$k]['r_welfare'] = explode(',',$v['r_welfare']);
            if($local_long&&$local_lat&&$res[$k]['c_longitude']&&$res[$k]['c_latitude']){
                $distance = $this->getdistance($local_long,$local_lat,$res[$k]['c_longitude'],$res[$k]['c_latitude']);
                $res[$k]['distance'] = $distance;
            }else{
                $res[$k]['distance'] = '--m';
            }

        }
        if(input('t')=='1'){
            echo "<pre>";
            print_r($res);
            exit();
        }
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);

    }
    //根据坐标计算距离
    function getdistance($lng1,$lat1,$lng2,$lat2){
        $radLat1=deg2rad($lat1);
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
        if($s<1000){
            $s = floor($s).'m';
        }else{
            $s = (floor($s/100))/10 .'km';
        }
        return $s;
    }

    //获取招聘信息详情
    public function getRecruitDetail(){
        $token = input("token");
        $rid = input("rid");
        $res=Db::table('zydz_recruit')->alias('r')
            ->join('zydz_scmember_company sc','r.r_companyid=sc.c_id')  //公司
            ->where('r.r_id',$rid)
            ->find();
        //设置福利
        $res['r_welfare'] = explode(',',$res['r_welfare']);
        //获取省
        $province = db('data_region')->where('id',$res['r_province'])->find();
        $city = db('data_region')->where('id',$res['r_city'])->find();
        $area = db('data_region')->where('id',$res['r_area'])->find();
        $res['address'] = $province['name'].$city['name'].$area['name'];

        if($token){
            //查询是否已经投递
            $userinfo = explode(',',$this->decrypt($token));
            $dmap['d_lid'] = $userinfo[0];
            $dmap['d_rid'] = $rid;
            $deliveryres = db('recruit_delivery')->where($dmap)->find();
            if($deliveryres){
                $res['tdelivery']=1;
                $res['tdeliveryMsg'] = "已投递";
            }else{
                $res['tdelivery']=0;
                $res['tdeliveryMsg'] = "投递";
                //将企业电话号码隐藏
                $res['c_phone'] = substr_replace($res['c_phone'], '****', 3, 4)."(投递简历后可查看)";
            }
            //查询是否收藏过
            $cmap['c_lid'] = $userinfo[0];
            $cmap['c_rid'] = $rid;
            $collection = db('recruit_collection')->where($cmap)->find();
            if($collection){
                $res['tcollection']=1;
                $res['tcollectionMsg'] = "已收藏";
            }else{
                $res['tcollection']=0;
                $res['tcollectionMsg'] = "收藏";
            }
        }else{
            //未注册肯定为投递过
            $res['tdelivery']=0;
            $res['tdeliveryMsg'] = "投递";
            $res['tcollection']=0;
            $res['tcollectionMsg'] = "收藏";
        }
        //获取公司环境图片
         $images = db("recruit_image")->field("i_id,i_rid,i_image")->where("i_rid",$res['c_id'])->select();
        //将图片路径转化为全路径
        foreach ($images as $k=>$v){
            $images[$k]['i_image'] = config('baseurl')."uploads/".$v['i_image'];
        }
        $res['images'] = $images;
//        $res['comphone']=substr_replace($res['comphone'],'****',3,4);//手机号处理
        if(input('t')=='1'){
            echo "<pre>";
            print_r($res);
            exit();
        }
        $r['code']='1';
        $r['msg'] = '查询成功';
        $r['data']=$res;
        return json($r);
    }

   //根据token和招聘信息id判断改用户是否已经收藏该信息
    public function getCollectionByToken(){
        $token = input("token");
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];
        $rid = input("rid");
        $map["c_lid"] = $userid;
        $map['c_rid'] = $rid;
        $res=db("recruit_collection")->where($map)->find();
        if($res){
            $r['code']='1';
            $r['msg'] = '已收藏';
            $r['data']=$r;
        }else{
            $r['code']='0';
            $r['msg'] = '收藏';
            $r['data']=$r;
        }
        return json($r);
    }

    //根据token和招聘信息id判断改用户是否已经投递该信息
    public function getDeliveryByToken(){
        $token = input("token");
        $userinfo = explode(',',$this->decrypt($token));
        $userid = $userinfo[0];
        $did = input("did");
        $map["d_lid"] = $userid;
        $map['d_rid'] = $did;
        $res=db("recruit_delivery")->where($map)->find();
        if($res){
            $r['code']='1';
            $r['msg'] = '已投递';
            $r['data']=$r;
        }else{
            $r['code']='0';
            $r['msg'] = '投递';
            $r['data']=$r;
        }
        return json($r);
    }


    //收藏简历
    public function addCollection(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $d['c_lid'] = $userinfo[0];
        $d['c_rid'] = input("rid");
        if(!$d['c_rid']){
            $r['code']='404';
            $r['msg'] = '简历id不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $re=db("recruit_collection")->where($d)->find();
        if($re){
            $r['code']='2';
            $r['msg'] = '您已收藏过';
            $r['data']=array();
        }else{
            $d['cc_time'] = time();
            $res = db('recruit_collection')->insert($d);
            if($res){
                $r['code']='1';
                $r['msg'] = '收藏成功';
                $r['data']=array();
            }else{
                $r['code']='0';
                $r['msg'] = '收藏异常';
                $r['data']=array();
            }
        }
        return json($r);
    }

    //投递简历
    public function addDelivery(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $d['d_lid'] = $userinfo[0];
        $d['d_rid'] = input("rid");
        $re=db("recruit_delivery")->where($d)->find();
        if($re){
            $r['code']='2';
            $r['msg'] = '您已投递过';
            $r['data']=array();
        }else{
            $d['d_time'] = time();
            $res = db('recruit_delivery')->insert($d);
            if($res){
                $r['code']='1';
                $r['msg'] = '投递成功';
                $r['data']=array();
            }else{
                $r['code']='0';
                $r['msg'] = '投递异常';
                $r['data']=array();
            }
        }
        return json($r);
    }
    //推荐好友-上班
    public function recommend(){
        $token = input("token");
        if(!$token){
            $r['code']='404';
            $r['msg'] = 'token不能为空';
            $r['data']=array();
            return json($r);
            exit();
        }
        $userinfo = explode(',',$this->decrypt($token));
        $d['r_uid'] = $userinfo[0];       //推荐人id
        $d['r_udname'] = input("fname"); //好友姓名
        $d['r_udphone'] = input("fphone"); //好友电话
        $d['r_wid'] = input("rid");        //被推荐的位置id
        $res = db("recruit_recommend")->where($d)->find();
        if($res){
            $r['code']='2';
            $r['msg'] = '您已经推荐过';
            $r['data']=array();
            return json($r);
            exit();
        }
        $d['r_time'] = time();             //推荐时间
        $re = db("recruit_recommend")->insert($d);
        if($re){
            $r['code']='1';
            $r['msg'] = '推荐成功！';
            $r['data']=array();
        } else{
            $r['code']='1';
            $r['msg'] = '数据异常,稍后再试';
            $r['data']=array();
        }
        return json($r);
    }

}
