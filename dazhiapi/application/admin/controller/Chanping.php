<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
use think\Db;
class Chanping extends Common
{
    function _initialize()
    {
        parent::_initialize();
    }
    public function Clist(){
        $data['title'] = input('title')?array('like', "%".input('title')."%"):array('neq', "*");
        $type['type'] = input('type')?array('=',input('type')):array('neq','*');
        $info = db('chanping')
            ->alias('a')
            ->where($data)
            ->where($type)
            ->join('cfl b','a.type=b.id')
            ->field('a.*,b.name')
            ->paginate(5);
        $this->assign('info', $info);
        $this->assign('page', $info->render());//分页映射
        $this->assign('fenlei',db('cfl')->select());

        return $this->fetch('index');

    }
    public function Domes(){
//        只需要M()->startTrans();//开启事务,M()可以是M('xxx')
//        $m->rollback();//事务回滚
//        $m->commit();//提交事务
        return $this->fetch('domes');
    }

    public function dadd(){
//如果没有撤回就加1如果撤回了就回滚；
        $data['name'] = 1;
        $mod1 = M('domes');
        $mod1->startTrans();
        $mod1->add($data);
        if (input('ha')){
            $mod1->rollback();
            $result['status'] = fales;
        }
        $result['status'] = true;
        $mod1->commit();
        return $result;
    }

    public function dadds(){
//如果没有撤回就加1如果撤回了就回滚；
//        Db::transaction(function(){
//            $data['name'] = 1;
//            Db::table('zydz_domes')->insert($data);
//    });

        // 启动事务
        $goodName['name'] = input('val');
        Db::startTrans();
        try{
            Db::table('zydz_domes')->insert($goodName);
            // 提交事务
            Db::commit();
            } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->error('添加货物地区失败，请重试');
            }
        $this->success('添加货物地区成功','www.baidu.com'); //$this-success放在最外面，不然会认为有报错回滚

    }






    //    删除    companylist
    public function del(){
        $id = input('id');
        $info = db('chanping')->where(['id'=>$id])->delete();
        if ($info){
            return ['code'=>1];
        }else{
            return ['code'=>0];
        }
    }

    //    映射添加页面
    public function add(){
        $info = db('cfl')->select();
        $this->assign('info', $info);
        return $this->fetch('contact_add');
    }

    //    处理数据
    public function cadds(){
        $count = input('post.');
        $info = db('chanping')->insert($count);
        if ($info){
            return ['code'=>1];
        }else{
            return ['code'=>0];
        }
    }


    //    映射修改页面
    public function upd(){
        $id = input('id');
        $info = db('chanping')->where(['id'=>$id])->select();
        $this->assign('info',$info);//分页映射
        $this->assign('fenlei',db('cfl')->select());//分类映射
        return $this->fetch('contact_update');
    }

    public function upds(){
        $count = input('post.');
        $info = db('chanping')->update($count);
        if ($info){
            return ['code'=>1];
        }else{
            return ['code'=>0];
        }
    }

    // 产品详情页
    public function content(){
        $id = input('id');
        $this->assign('id',$id);//分页映射
        return $this->fetch('content');
    }

}