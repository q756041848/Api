<?php
namespace app\Juenapi\controller;

use think\Controller;
use think\Db;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
class Index extends Controller
{
    //根据小程序appid获取微信唯一身份标示openid
    public function getopenid()
    {
        $code = input('code');
        $appid = 'wx020a5fb38c05c6ee';
        $appsecrte = '3972b19641f7604baa8e53418d46b044';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $appsecrte . '&js_code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();   //初始化
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (isset($output['openid']) && !empty($output['openid'])) {
            return json_encode($output);
        }
    }

    public function isregister(){
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $where['openid']=$openid;
        $userinfo=db('jobuser')->where($where)->find();
        $cominfo=db('company')->where($where)->find();
        if(!empty($userinfo))
        {
            if($userinfo['flag']==1)
            {
                $result=[
                    'code'=>1,
                    'msg'=>'已注册为普工用户'
                ];
            }else{
                $result=[
                    'code'=>2,
                    'msg'=>'已注册为高级用户'
                ];
            }

        }else{
            if(!empty($cominfo))
            {
                $result=[
                    'code'=>3,
                    'msg'=>'已注册为公司用户'
                ];
            }
            else{
                $result=[
                    'code'=>0,
                    'msg'=>'尚未注册'
                ];
            }
        }
        return json_encode($result);
    }
    

    //公司注册
    public function addcompany()
    {

        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }

        $data['companyname'] = input('companyname');
        $data['linkman'] = input('linkman');
        $data['phone'] = input('phone');
        $data['address'] = input('address');
        $data['types'] = input('types');
        $data['scale'] = input('scale');
        $data['openid'] = input('openid');
        $ainfo = db('company')->insert($data);


        if ($ainfo) {
            $result = [
                'code' => 1,
                'msg' => '录入成功'
            ];
        } else {
            $result = [
                'code' => 0,
                'msg' => '录入失败'
            ];
        }
        return json_encode($result);
    }
    //投递历简
    public function postjobs()
    {
        $openid = input('openid');
        $data['uid']=db('jobuser')->where("openid='". $openid ."'")->value('id');
        $data['jid']=input('jid');
        $data['postime']=date('Y-m-d H:i:s',time());
        $info=db('jobpost')->insert($data);
        $result=[
            'msg'=>'投递成功',
            'code'=>1
        ];
        return json_encode($result);
    }


    //个人注册
    public function addpersonal()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['telphone'] = input('telphone');
        $checkcode=input('checkcode');
        $where['telcode']=$checkcode;
        $where['telphone']=$data['telphone'];
        $checkinfo=db('telcode')->where($where)->find();
        if(empty($checkinfo))
        {
            $result=[
                'code' => 2,
                'msg' => '验证码有误'
            ];
        }else{
            if(time()-$checkinfo['sendtime']>60)
            {
                $result=[
                    'code' => 3,
                    'msg' => '验证码已过期'
                ];
            }else{
                $data['name'] = input('name');
                $data['sex'] = input('sex');
                $data['birthday'] = date('Y-m-d', time());

                $data['address'] = input('address');
                $data['openid'] = input('openid');
                $data['flag']=input('flag');

                $info = db('jobuser')->insert($data);
                if ($info) {
                    $result = [
                        'code' => 1,
                        'msg' => '录入成功'
                    ];
                } else {
                    $result = [
                        'code' => 0,
                        'msg' => '录入失败'
                    ];
                }
            }

        }

        return json_encode($result);
    }

    //发布招聘
    public function addrecruitment()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['cid']=db('company')->where("openid='" . $openid . "'")->value('id');
        $data['title'] = input('title');
        $cwhere['cname']=input('hiring');
        $data['hiring'] = db('cate')->where($cwhere)->value('id');
        $data['salary_begin'] = input('salary_begin');
        $data['salary_end'] = input('salary_end');

        $data['numbers'] = input('numbers');
        $data['retime']=date('Y-m-d',time());
        $data['education']=db('educonfig')->where('title="'.input('education').'"')->value('id');
        $data['experience']=db('experconfig')->where('title="'.input('experience').'"')->value('id');
         
        $data['workplace'] = input('workplace');
        $data['city']=input('city');
        $data['area']=input('area');
        $data['category'] = input('category');
        $data['wid'] = input('wid');
        $data['description'] = input('description');
        $data['picture'] = input('picture');
        $info = db('job')->insert($data);
        $id=db('job')->getLastInsID();
        $result['id']=$id;
        return json_encode($result);
    }
    public function addmyexpect(){
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $attrs=input('attrs');
        $vals=input('vals');
        $data[$attrs]=$vals;
        $where['openid']=$openid;
        $info=db('jobuser')->where($where)->update($data);
        $result=[
            'code'=>1,
            'msg'=>'更新成功'
        ];
        return json_encode($result);
    }
    public function delworking(){
        $id=input('id');
        $info=db('working')->where('id='.$id)->delete();
        return json_encode(['code'=>1,'msg'=>'删除成功']);
    }
    //添加工作
    public function addexperience()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $data['compariy'] = input('compariy');
        $data['starttime'] = date('Y-m-d', time());
        $data['endtime'] = date('Y-m-d', time());
        $data['pay'] = input('pay');
        $data['certifier'] = input('certifier');
        $data['phone'] = input('phone');
        $data['leave'] = input('leave');
        $data['position'] = input('position');
        $info = db('working')->insert($data);
        $currdata = [
            'id' => db('working')->getLastInsID(),
            'compariy' => $data['compariy'],
            'starttime' => $data['starttime'],
            'endtime' => $data['endtime'],
            'pay' => $data['pay'],
            'certifier' => $data['certifier'],
            'phone' => $data['phone'],
            'leave' => $data['leave'],
        ];
        if ($info) {
            $result = [
                'msg' => '提交成功',
                'currdata' => $currdata

            ];
        } else {
            $result = [
                'msg' => '提交失败'
            ];
        }
        return json_encode($result);

    }
    //删除教育经历
    public function delschooling(){
        $id=input('id');
        $info=db('education')->where('id='.$id)->delete();
        return json_encode(['code'=>1,'msg'=>'删除成功']);
    }
    //添加教育经历
    public function addschooling()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $data['school'] = input('school');
        $data['stime'] = date('Y-m-d', time());
        $data['ttime'] = date('Y-m-d', time());
        $data['professional'] = input('professional');
        $data['schooling'] = input('schooling');
        $info = db('education')->insert($data);
        $currdata = [
            'id' => db('education')->getLastInsID(),
            'school' => $data['school'],
            'stime' => $data['stime'],
            'ttime' => $data['ttime'],
            'schooling' => $data['schooling'],
            'professional' => $data['professional']

        ];
        if ($info) {
            $result = [
                'msg' => '提交成功',
                'currdata' => $currdata
            ];
        } else {
            $result = [
                'msg' => '提交失败'
            ];
        }

        return json_encode($result);

    }
    public function delfamily(){
        $id=input('id');
         $info=db('family')->where('id='.$id)->delete();
        return json_encode(['code'=>1,'msg'=>'删除成功']);
    }
    //添加家庭基本情况
    public function addsituation()
    {

        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $data['name'] = input('name');
        $data['relation'] = input('relation');
        $data['contact'] = input('contact');
        $data['workunit'] = input('workunit');
        $data['ajob'] = input('ajob');
        //$data['uid'] = input('uid');
        $data['age'] = input('age');
        $info = db('family')->insert($data);
        $currdata = [
            'id' => db('family')->getLastInsID(),
            'name' => $data['name'],
            'relation' => $data['relation'],
            'contact' => $data['contact'],
            'workunit' => $data['workunit'],
            'ajob' => $data['ajob'],
            'uid' => $data['uid'],
            'age' => $data['age'],
        ];
        if ($info) {
            $result = [
                'msg' => '提交成功',
                'currdata' => $currdata

            ];
        } else {
            $result = [
                'msg' => '提交失败'
            ];
        }
        return json_encode($result);
    }

    //联系我们
    public function Contactus()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $where = db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $info = db('contact')->where($where)->select();
        return json_encode($info);

    }

    //我的简历详情
    public function infodetails()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }

        $userinfo=db('jobuser')->where("openid='" . $openid . "'")->find();

        $where['uid']=$userinfo['id'];
        $education = db('education')->where($where)->select();
        $working = db('working')->where($where)->select();
        $family = db('family')->where($where)->select();
        $friendw = db('friendw')->where($where)->select();
        $info = [
            'userinfo'=>$userinfo,
            'education'=>$education,
            'working'=>$working,
            'family'=>$family,
            'friendw'=>$friendw,

        ];
        return json_encode($info);
    }

    //朋友部门添加
    public function addfriend()
    {

        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');

        $data['incompany'] = input('incompany');
        $data['uname'] = input('uname');
        $data['between'] = input('between');
        $data['department'] = input('department');
        $info = db('friendw')->insert($data);
        if ($info) {
            $result = [
                'msg' => '提交成功'
            ];
        } else {
            $result = [
                'msg' => '提交失败'
            ];
        }

        return json_encode($result);

    }

    //我的推荐
    public function recommended()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $info = db('recommended')->select();
        return json_encode($info);
    }

    //添加推荐
    public function addrecommended()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $data['uname'] = input('uname');
        $data['company'] = input('company');
        $data['time'] = date('Y-m-d H:i:s', time());
        $data['tel'] = input('tel');
        $data['unit'] = input('unit');
        $info = db('recommended')->insert($data);
        if ($info) {
            $result = [
                'msg' => '提交成功'
            ];
        } else {
            $result = [
                'msg' => '提交失败'
            ];
        }

        return json_encode($result);
    }

//用户协议
    public function agreement()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $info = db('agreement')->select();
        return json_encode($info);

    }

//奖励政策
    public function rewardpolicy()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $info = db('rewardpolicy')->select();
        return json_encode($info);
    }

//一封信
    public function aletter()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $info = db('aletter')->select();
        return json_encode($info);
    }
    //用户协议奖励政策一封信帮助中心企业资料
    public function agreal()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $agreement = db('agreement')->select();
        $rewardpolicy = db('rewardpolicy')->select();
        $aletter = db('aletter')->select();
        $helpcenter = db('helpcenter')->select();
        $company = db('company')->select();
        $info = [
            $agreement,
            $rewardpolicy,
            $aletter,
            $helpcenter,
            $company,
        ];
        return json_encode($info);
    }

//修改教育经历
    public function upeducation()
    {

        $where['id']=input('id');
        $data['school'] = input('school');
        $data['stime'] = input('stime');
        $data['ttime'] = input('ttime');
        $data['professional'] = input('professional');
        $data['schooling'] = input('schooling');
        $info = db('education')->where($where)->update($data);
        $currdata = [
            'id' =>input('id'),
            'school' => $data['school'],
            'stime' => $data['stime'],
            'ttime' => $data['ttime'],
            'professional'=>$data['professional'],
            'schooling' => $data['schooling'],
        ];
        if ($info) {
            $result = [
                'msg' => '修改成功',
                'currdata' => $currdata
            ];
        } else {
            $result = [
                'msg' => '修改失败'
            ];
        }
        return json_encode($result);
    }

//修改家庭信息
    public function upfamily()
    {
        $where['id']=input('id');
        $data['name'] = input('name');
        $data['relation'] = input('relation');
        $data['contact'] = input('contact');
        $data['workunit'] = input('workunit');
        $data['ajob'] = input('ajob');
        $data['age'] = input('age');
        $info = db('family')->where($where)->update($data);
        $currdata = [
            'id' =>input('id'),
            'name' => $data['name'],
            'relation' => $data['relation'],
            'contact' => $data['contact'],
            'workunit' => $data['workunit'],
            'ajob' => $data['ajob'],
            'age' => $data['age'],
        ];
        if ($info) {
            $result = [
                'msg' => '修改成功',
                'currdata' => $currdata
            ];
        } else {
            $result = [
                'msg' => '修改失败'
            ];
        }
        return json_encode($result);

    }

    //修改工作经历
    public function upwork()
    {
        $where['id']=input('id');
        $data['id']=input('id');
        $data['compariy'] = input('compariy');
        $data['starttime'] = input('starttime');
        $data['endtime'] = input('endtime');
        $data['pay'] = input('pay');
        $data['certifier'] = input('certifier');
        $data['phone'] = input('phone');
        $data['leave'] = input('leave');
        $data['position'] = input('position');

        $info = db('working')->where($where)->update($data);
        $currdata = [
            'id' =>input('id'),
            'compariy' => $data['compariy'],
            'starttime' => $data['starttime'],
            'endtime' => $data['endtime'],
            'pay' => $data['pay'],
            'certifier' => $data['certifier'],
            'phone' => $data['phone'],
            'leave' => $data['leave'],
            'position' => $data['position'],
        ];
        if ($info) {
            $result = [
                'msy' => '修改成功',
                'currdata' => $currdata
            ];
        } else {
            $result = [
                'msy' => '修改失败'
            ];
        }
        return json_encode($result);
    }

    //修改用户基本信息
    public function upuser()
    {
        $openid = input('openid');
        if ($openid == "") {
            $result = ['msg' => 'openid不可以为空'];
            return json_encode($result);
        }
        $uid = db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $where['id'] = $uid;
        $data['name'] = input('name');
        $data['sex'] = input('sex');
        $data['birthday'] = input('birthday');
        $data['telphone'] = input('telphone');
        $data['address'] = input('address');
        $data['nation'] = input('nation');
        $data['status'] = input('status');
        $data['height'] = input('height');
        $data['card'] = input('card');
        $data['education'] = input('education');
        $data['marital'] = input('marital');
        $data['mail'] = input('mail');
        $data['maliting'] = input('maliting');
        $data['postcode'] = input('postcode');
        $data['urgency'] = input('urgency');
        $data['relation'] = input('relation');
        $data['urgenoyphone'] = input('urgenoyphone');
        $data['place'] = input('place');

        $info = db('jobuser')->where($where)->update($data);
        if ($info) {
            $result = [
                'msy' => '修改成功'
            ];
        } else {
            $result = [
                'msy' => '修改失败'
            ];
        }
        return json_encode($result);
    }

    //首页
    public function joblist()
    {

        $list = db('job')
            ->alias('j')
            ->join('__COMPANY__ c', 'c.id=j.cid')
            ->field('j.*,c.companyname')
            ->order('sorts desc')->select();
        foreach ($list as &$v) {
            $v['education'] =db('educonfig')->where('id='.$v['education'])->value('title');
            $v['experience'] = db('experconfig')->where('id='.$v['experience'])->value('title');
            $fuliarr = explode(',', $v['wid']);
            $where['id'] = ['in', $fuliarr];
            $v['fuli'] = db('welfare')->where($where)->column('title');

        }
        return json_encode($list);

    }

    //我的投递
    public function myjobaction()
    {

        $openid = input('openid');
        $where['ju.openid'] = $openid;
        $list = db('jobpost')
            ->alias('jp')
            ->join('__JOBUSER__ ju', 'ju.id=jp.uid')
            ->join('__JOB__ jb', 'jb.id=jp.jid')
            ->join('__COMPANY__ c', 'c.id=jb.cid')
            ->join('__EDUCONFIG__ ec','ec.id=jb.education')
            ->join('__EXPERCONFIG__ ex','ex.id=jb.experience')
            ->where($where)
            ->field('jb.*,c.companyname,jp.postime,ec.title as edutitle,ex.title as expertitle')
            ->order('jp.postime desc')
            ->select();
        foreach ($list as &$item) {
            $item['experience'] = $item['expertitle'];
            $item['education'] = $item['edutitle'];

            $widarr = explode(',', $item['wid']);
            $delivery = floatval($item['ischeck']);
            $item['ischeck'] = $item['ischeck']==1?'已审核':'未审核';

            $nwhere['id'] = ['in', $widarr];
            $where2['id'] = ['in', $delivery];


            $item['fuli'] = db('welfare')->where($nwhere)->column('title');
            $currtime = date('Y-m-d', time());
            $yestoday = date('Y-m-d', strtotime('-1 day'));
            $posttotime = strtotime($item['postime']);

            $posttime = date('Y-m-d', $posttotime);
            if ($currtime == $posttime) {
                $item['postime'] = date('H:i', $posttotime);
            } elseif ($posttime == $yestoday) {
                $item['postime'] = '昨天';
            } else {
                $item['postime'] = date('m-d', $posttotime);
            }

        }

        return json_encode($list);
    }


//我的福利
    public function welfare()
    {
        $openid = input('openid');
        if ($openid == '') {
            $result = ['msg' => 'openid不可为空'];
            return json_encode($result);
        }
        db('jobuser')->where("openid='" . $openid . "'")->value('id');
        $welfare = db('welfare')->select();

        return json_encode($welfare);
    }
//收藏
    public function collectjob()
    {
        $types=input('types');
        if($types==0)
        {
            $openid = input('openid');
            $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');
            $data['jid']=input('id');
            $data['postime']=date('Y-m-d H:i:s',time());
            $info=db('collect')->insert($data);
            $result=[
                'msg'=>'收藏成功',
                'code'=>1
            ];
        }
        else{
            $openid = input('openid');
            $data['uid']=db('jobuser')->where("openid='" . $openid . "'")->value('id');
            $data['jid']=input('id');
            $info=db('collect')->where($data)->delete();
            $result=[
                'msg'=>'取消收藏成功',
                'code'=>1
            ];
        }

        return json_encode($result);
    }
    //获取职位第一级列表
    public function getfirstcate()
    {

        $list = db('cate')->where('pid=0')->select();
        if (input('pname') != '' && input('pname')!='undefined') {
            $pid = db('cate')->where("cname='" . input('pname') . "'")->value('id');
            $list2 = db('cate')->where('pid=' . $pid)->select();
        } else {
            $list2 = db('cate')->where('pid=' . $list[0]['id'])->select();
        }
        if (!empty($list2)) {
            if (input('cname') != '' && input('cname')!='undefined') {
                $pid = db('cate')->where("cname='" . input('cname') . "'")->value('id');
                $list3 = db('cate')->where('pid=' . $pid)->select();
            } else {
                $list3 = db('cate')->where('pid=' . $list2[0]['id'])->select();
            }
        } else {
            $list3 = [];
        }
        foreach ($list as $v) {
            $vlist[] = $v['cname'];
        }
        foreach ($list2 as $t) {
            $slist[] = $t['cname'];
        }
        foreach ($list3 as $m) {
            $tlist[] = $m['cname'];
        }
        foreach ($list3 as $key => $n) {
            $pclass[$key]['name'] = $n['id'];
            $pclass[$key]['value'] = $n['cname'];
        }
        $experience=db('experconfig')->column('title');
        $education=db('educonfig')->column('title');
        $result = [
            'collage' => $vlist,
            'second' => $slist,
            'pclass' => $pclass,
            'third' => $tlist,
            'exper'=>$experience,
            'edu'=>$education

        ];

        return json_encode($result);
    }
    public function getcates()
    {
        $cname = input('cname');

        $cateid = db('cate')->where('cname="' . $cname . '"')->value('id');
        $list = db('cate')->where('pid=' . $cateid)->select();
        if (!empty($list)) {
            $thirds = db('cate')->where('pid=' . $list[0]['id'])->select();
            foreach ($thirds as $key => $v) {
                $cnames[] = $v['cname'];
                $pclass[$key]['name'] = $v['id'];
                $pclass[$key]['value'] = $v['cname'];
            }
        } else {
            $cnames = ['暂无班级'];
            $pclass = [];
        }
        foreach ($list as $t) {
            $second[] = $t['cname'];
        }
        $result = [
            'sec' => $second,
            'pclass' => $pclass,
            'third' => $cnames
        ];
        return json_encode($result);
    }

    public function getthirdcate()
    {
        $cname = input('cname');
        $cateid = db('cate')->where('cname="' . $cname . '"')->value('id');
        $list = db('cate')->where('pid=' . $cateid)->column('cname');
        return json_encode($list);
    }
    //职位详情
    public function zdetails()
    {
        $openid = input('openid');
        $uid = db('jobuser')->where('openid="' . $openid . '"')->value('id');
        $id = input('id');
        $where['j.id'] = $id;
        $jobinfo = db('job')
            ->alias('j')
            ->join('__CATE__ ct','ct.id=j.hiring')
            ->join('__COMPANY__ c', 'c.id=j.cid')
            ->field('j.*,c.companyname,ct.cname')
            ->where($where)
            ->find();
        $jobinfo['experience'] = db('experconfig')->where('id='.$jobinfo['experience'])->value('title');
        $jobinfo['education'] = db('educonfig')->where('id='.$jobinfo['education'])->value('title');
        $welarr = explode(',', $jobinfo['wid']);
        $jobinfo['fuli'] = db('welfare')->where(['id' => ['in', $welarr]])->column('title');
        $wheres = [
            'uid' => $uid,
            'jid' => $id
        ];
        $jobinfo['isview'] = db('browsinghistory')->where($wheres)->count();
        $jobinfo['collection'] = db('collect')->where($wheres)->count();
        $jobinfo['tdelivery'] = db('jobpost')->where($wheres)->count();
        $jobinfo['picture']=explode('|',$jobinfo['picture']);
        foreach($jobinfo['picture'] as &$tt)
        {
            $tt="https://qmp.linyidz.cn".$tt;
        }
        if(!$jobinfo['isview'])
        {
            $datas['uid']=$uid;
            $datas['jid']=$id;
            $datas['postime']=date('Y-m-d H:i:s',time());
            $info=db('browsinghistory')->insert($datas);
        }
        $companyinfo=db('company')
            ->alias('c')
            ->join('__JOB__ j','c.id=j.cid')
            ->field('c.*')
            ->where('j.id='.$id)->find();


        $companyid=db('job')->where('id='.$id)->value('cid');
        $nwhere['cid']=$companyid;
        $nwhere['id']=['not in',$id];
        $morejob=db('job')->where($nwhere)->select();
        $result=[
            'jobinfo'=>$jobinfo,
            'cominfo'=>$companyinfo,
            'morejob'=>$morejob
        ];

        return json_encode($result);
    }

    //我的收藏
    public function collection()
    {
        $openid = input('openid');
        $where['ju.openid'] = $openid;
        $list = db('collect')
            ->alias('jp')
            ->join('__JOBUSER__ ju', 'ju.id=jp.uid')
            ->join('__JOB__ jb', 'jb.id=jp.jid')
            ->join('__COMPANY__ c', 'c.id=jb.cid')
            ->join('__EDUCONFIG__ ec','ec.id=jb.education')
            ->join('__EXPERCONFIG__ eg','eg.id=jb.experience')
            ->where($where)
            ->field('jb.*,c.companyname,jp.postime,ec.title as edutitle,eg.title as expertitle')
            ->order('jp.postime desc')
            ->select();

        foreach ($list as &$item) {
            $item['experience'] = $item['expertitle'];
            $item['education'] = $item['edutitle'];
            $item['ischeck'] = config('ischeck')[$item['ischeck']];
            $widarr = explode(',', $item['wid']);
            $delivery = floatval($item['ischeck']);

            $nwhere['id'] = ['in', $widarr];
            $where2['id'] = ['in', $delivery];


            $item['fuli'] = db('welfare')->where($nwhere)->column('title');
            $currtime = date('Y-m-d', time());
            $yestoday = date('Y-m-d', strtotime('-1 day'));
            $posttotime = strtotime($item['postime']);

            $posttime = date('Y-m-d', $posttotime);
            if ($currtime == $posttime) {
                $item['postime'] = date('H:i', $posttotime);
            } elseif ($posttime == $yestoday) {
                $item['postime'] = '昨天';
            } else {
                $item['postime'] = date('m-d', $posttotime);
            }

        }

        return json_encode($list);
    }

//我的推荐
    public function irecommended()
    {
        $openid = input('openid');
        $where['ju.openid'] = $openid;
        $list = db('recommended')
            ->alias('jp')
            ->join('__JOBUSER__ ju', 'ju.id=jp.uid')
            ->join('__JOB__ jb', 'jb.id=jp.jid')
            ->join('__COMPANY__ c', 'c.id=jb.cid')
            ->join('__EDUCONFIG__ ec','ec.id=jb.education')
            ->join('__EXPERCONFIG__ eg','eg.id=jb.experience')
            ->where($where)
            ->field('jb.*,c.companyname,jp.postime,ec.title as edutitle,eg.title as expertitle')
            ->order('jp.postime desc')
            ->select();

        foreach ($list as &$item) {
            $item['experience'] = $item['expertitle'];
            $item['education'] = $item['edutitle'];
            $item['ischeck'] = config('ischeck')[$item['ischeck']];
            $widarr = explode(',', $item['wid']);
            $delivery = floatval($item['ischeck']);

            $nwhere['id'] = ['in', $widarr];
            $where2['id'] = ['in', $delivery];


            $item['fuli'] = db('welfare')->where($nwhere)->column('title');
            $currtime = date('Y-m-d', time());
            $yestoday = date('Y-m-d', strtotime('-1 day'));
            $posttotime = strtotime($item['postime']);

            $posttime = date('Y-m-d', $posttotime);
            if ($currtime == $posttime) {
                $item['postime'] = date('H:i', $posttotime);
            } elseif ($posttime == $yestoday) {
                $item['postime'] = '昨天';
            } else {
                $item['postime'] = date('m-d', $posttotime);
            }

        }
        return json_encode($list);
    }

    //浏览记录
    public function browsinghistory()
    {
        $openid = input('openid');
        $where['ju.openid'] = $openid;
        $list = db('browsinghistory')
            ->alias('jp')
            ->join('__JOBUSER__ ju', 'ju.id=jp.uid')
            ->join('__JOB__ jb', 'jb.id=jp.jid')
            ->join('__COMPANY__ c', 'c.id=jb.cid')
            ->join('__EDUCONFIG__ ec','ec.id=jb.education')
            ->join('__EXPERCONFIG__ eg','eg.id=jb.experience')
            ->where($where)
            ->field('jb.*,c.companyname,jp.postime,ec.title as edutitle,eg.title as expertitle')
            ->order('jp.postime desc')
            ->select();
        foreach ($list as &$item) {
            $item['experience'] = $item['expertitle'];
            $item['education'] = $item['edutitle'];
            
            $item['ischeck'] = config('ischeck')[$item['ischeck']];
            $widarr = explode(',', $item['wid']);
            $delivery = floatval($item['ischeck']);
            $nwhere['id'] = ['in', $widarr];
            $where2['id'] = ['in', $delivery];
            $item['fuli'] = db('welfare')->where($nwhere)->column('title');
            $currtime = date('Y-m-d', time());
            $yestoday = date('Y-m-d', strtotime('-1 day'));
            $posttotime = strtotime($item['postime']);
            $posttime = date('Y-m-d', $posttotime);
            if ($currtime == $posttime) {
                $item['postime'] = date('H:i', $posttotime);
            } elseif ($posttime == $yestoday) {
                $item['postime'] = '昨天';
            } else {
                $item['postime'] = date('m-d', $posttotime);
            }

        }
        return json_encode($list);
    }


//我的发布
        public  function  showrelease()
        {
            $openid = input('openid');
            $where['c.openid'] = $openid;
            $list=db('job')
                ->alias('jb')
                ->join('__COMPANY__ c','c.id=jb.cid')
                ->join('__EDUCONFIG__ ec','ec.id=jb.education')
                ->join('__EXPERCONFIG__ eg','eg.id=jb.experience')
                ->where($where)
                ->order('jb.retime desc')
                ->field('jb.*,c.companyname,ec.title as edutitle,eg.title as expertitle')
                ->select();

            foreach ($list as &$item) {
                $item['experience'] = $item['expertitle'];
                $item['education'] = $item['edutitle'];
            
                $item['ischeck'] = config('ischeck')[$item['ischeck']];
                $widarr = explode(',', $item['wid']);
                $delivery = floatval($item['ischeck']);
                $nwhere['id'] = ['in', $widarr];
                $where2['id'] = ['in', $delivery];
                $item['fuli'] = db('welfare')->where($nwhere)->column('title');


                //统计投递人数
                $item['postnums']=db('jobpost')->where('jid='.$item['id'])->count();
                //统计浏览人数
                $item['viewnums']=db('browsinghistory')->where('jid='.$item['id'])->count();


                $currtime = date('Y-m-d', time());
                $yestoday = date('Y-m-d', strtotime('-1 day'));
                $posttotime = strtotime($item['retime']);
                $posttime = date('Y-m-d', $posttotime);
                if ($currtime == $posttime) {
                    $item['retime'] = date('H:i', $posttotime);
                } elseif ($posttime == $yestoday) {
                    $item['retime'] = '昨天';
                } else {
                    $item['retime'] = date('m-d', $posttotime);
                }
            }

            return json_encode($list);
        }
        //
//上传头像

      public function upimg()
      {
          $openid = input('openid');
          $file = request()->file('photo');
//        $cname=db('cate')->alias('c')->join('__STUDENT__ s','s.iclass=c.id')->where('s.openid="'.$openid.'"')->value('cname');
//        if($cname=='')
//        {
//            $cname='userphoto';
//        }
          $info = $file->move(ROOT_PATH . DS . 'Uploads');
          $data['photo'] = 'https://qmp.linyidz.cn/Uploads/' . $info->getSaveName();
          $infos = db('jobuser')->where("openid='" . $openid . "'")->update($data);
          return json_encode($infos);
      }
    //上传图片
    public function uploadimg()
    {
        $id = input('id');
        $file = request()->file('uploadimg');

        $info = $file->move(ROOT_PATH . DS . 'Uploads');
        $img = 'https://qmp.linyidz.cn/Uploads/' . $info->getSaveName();
        //$data['id']=$id;
        $thumb = db('job')->where('id=' . $id)->value('picture');
        if ($thumb != '') {
            $data['picture'] = $thumb . ',' . $img;
        } else {
            $data['picture'] = $img;
        }
        db('job')->where('id=' . $id)->update($data);
        return json_encode($data);
    }

}
