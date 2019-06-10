<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
//// 指定允许其他域名访问
//header('Access-Control-Allow-Origin:*');
//// 响应类型
//header('Access-Control-Allow-Methods:*');
//// 响应头设置
//header('Access-Control-Allow-Headers:x-requested-with,content-type');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');
class Sms extends Controller
{
    public function test(){
        echo '111';
    }
    //发送手机验证码 - 最大发送频率60秒
    public function sendSms(){
        $telphone = input('telphone');
        if(!$telphone){
            $result['code'] = '101';
            $result['msg'] = "手机号不能为空";
            $result['data']=array();
            return json($result);
            exit();
        }
        $cout=db('telcode')->where('t_telphone='.$telphone)->find();
        if($cout){
            $t = $cout['t_sendtime'];
            $c = time()-$t;
            if($c<60){
                $result['code'] = '102';
                $result['msg'] = "5分钟内请勿重复发送";
                $result['data']=array();
                return json($result);
                exit();
            }
        }
        $randcode=mt_rand(100000,999999);
        $data=[
            't_telphone'=>$telphone,
            't_telcode'=>$randcode,
            't_sendtime'=>time()
        ];
        if($cout)
        {
            $info=db('telcode')->where('t_telphone='.$telphone)->update(['t_telcode'=>$randcode,'t_sendtime'=>time()]);
        }else{
            $info=db('telcode')->insert($data);
        }

        $c = $this->sendToSms($telphone,$randcode);
        $result['code'] = '1';
        $result['msg'] = "发送成功！";
        $result['data']=$c;
        return json($result);
    }
    //检查验证码是否正确 - 有限期10分钟
    public function checkCode(){
        $phone = input("phone");
        $code = input("code");
        $map['t_telphone'] = $phone;
        $map['t_telcode'] = $code;

        $res = db("telcode")->where($map)->find();
        if($res) {
            $t = $res['t_sendtime'];
            $c = time()-$t;
            if($c>600){
                $result['code'] = '0';
                $result['msg'] = "验证码已过期";
                $result['data']=array();
            }
            else{
                $result['code'] = '1';
                $result['msg'] = "验证码正确";
                $result['data']=array();
            }
        }else{
            $result['code'] = '0';
            $result['msg'] = "验证码错误";
            $result['data']=array();
        }
        return $result;
    }



    //参数配置方法
    private function  sendToSms($telphone,$randcode) {
        $params = array ();
        $params["PhoneNumbers"] = $telphone;  //手机号
        $accessKeyId = "LTAITKgIl4sStOmU";
        $accessKeySecret = "rFddL9TzZvvKO2mde4lnXOoOvuf4vY";
        $params["SignName"] = "诚言国际";
        $params["TemplateCode"] = "SMS_142382228";

        $params['TemplateParam'] = Array (
            "code" => $randcode,
            "product" => "1111"
        );
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        $helper = new \SignatureHelper();
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
        return $content;
    }
}
