<?php
//======================================公共头BEI================================================//
namespace app\admin\controller;
use app\admin\model\ApiModel;
//======================================公共头END================================================//


class Apiadd extends Common{
    //======================================主页================================================//
    public function index(){
        $ApiModel=new ApiModel();
        $msg=$ApiModel->index();
        $this->assign('interModuleId',$msg['interModuleId']);  //模块读取
        $this->assign('date',$msg['date']);
        $this->assign('Cshow',$msg['Cshow']);                //返回码映射
        $this->assign('connector',$msg['connector']);       //接口映射
        $this->assign('pfolder',$msg['pfolder']);           //文件夹映射
        $this->assign('id',$msg['id']);
        return $this->fetch();
    }
    //======================================主页END================================================//



    //======================================文档、公共请求参数================================================//
    //index页文档修改
    public function wendang(){
        $ApiModel=new ApiModel();
        return $ApiModel->WenDang();
    }

    //公共参数修改
    public function commonParamInfoTab(){
        $ApiModel=new ApiModel();
        return $ApiModel->CommonParamInfoTab();
    }

    //公共参数调用接口
    public function commonParamInfo(){
        return db('pm')->field('p_commonParamInfoTab')->where(input('post.'))->find();
    }
    //======================================文档、公共请求参数END================================================//




    //======================================文件夹================================================//
    //文件夹上面所有的局部刷新
    public function api_suoyou(){
        $ApiModel=new ApiModel();
        $this->assign('connector', $ApiModel->SuoYou());                  //文件夹映射
        return $this->fetch();
    }


    //文件夹的局部刷新
    public function api_mokuai(){
        $ApiModel=new ApiModel();
        $msg=$ApiModel->ApiMokuai();
        $this->assign('pfolder',$msg['pfolder']);                          //文件夹映射
        $this->assign('id',$msg['id']);
        return $this->fetch();
    }


    //文件夹添加修改
    public function mokuai(){
        $ApiModel=new ApiModel();
        return $ApiModel->Mokuai();
    }

    //文件夹删除
    public function pfolder_del(){
        $ApiModel=new ApiModel();
        return $ApiModel->PfolderDel();
    }


    //点击修改调用的文件夹数据显示
    public function dpfolder(){
        return db('pfolder')->where('id',input('id'))->find();
    }
    //======================================文件夹部分END================================================//






    //======================================错误码部分================================================//
    //错误码的局部刷新
    public function api_cuowuma(){
        $ApiModel=new ApiModel();
        $this->assign('Cshow',$ApiModel->ApiCuowuma());
        return $this->fetch();
    }

    //错误码添加修改
    public function code()
    {
        $ApiModel=new ApiModel();
        return $ApiModel->Code();
    }

    //点击修改调用的错误码数据显示
    public function cdate(){
        return db('code')->where(input('post.'))->find();
    }

    //错误码删除
    public function code_del(){
        return db('code')->delete(input('post.'));
    }
    //======================================错误码部分END================================================//




    //======================================接口部分=====================================================//
    //接口的局部刷新
    function api_jiekou(){
        $ApiModel=new ApiModel();
        $msg = $ApiModel->ApiJiekou();
        $this->assign('connector',$msg['connector']);
//        $this->assign('num',$num);
        return $this->fetch();
    }

    //接口的添加修改
    public function jiekou(){
        $ApiModel=new ApiModel();
        return $ApiModel->JieKou();
    }

    //删除接口
    function jiekou_del(){
        $ApiModel=new ApiModel();
        return $ApiModel->JiekouDel();
    }


    //修改接口点击调用的数据
    public function djiekou(){
         return db('connector')->where(input('post.'))->find();
    }


    //点击修改接口 ->接口修改页面显示的选择文件夹->单独映射
    public function api_mokuais(){
        $ApiModel=new ApiModel();
        $this->assign('interModuleId',$ApiModel->ApiMokuais());
        return $this->fetch();
    }
    //======================================接口部分END================================================//





    //======================================接口->请求参数================================================//
    //调用数据
        public function api_qingqiu(){
            return \db('required')->where('pid',input('id'))->find();
        }

        public function qingqiu(){
            $ApiModel=new ApiModel();
            $this->assign('date',$ApiModel->QingQiu());
            return $this->fetch();
        }
    //======================================接口->请求参数END================================================//

    //======================================接口->请求响应================================================//


}
?>