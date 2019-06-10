<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Session;
class Company extends Common
{
    function _initialize()
    {
        parent::_initialize();
    }
    //显示新闻列表
    public function companyList(){
        //如果有值就给，没有值就给奇怪字符
        $data['title'] = input('title')?array('like', "%".input('title')."%"):array('neq', "*");
        $type['type'] = input('type')?array('=',input('type')):array('neq','*');
        $time['time'] = input('time')?array('>','1553920061'):array('<',time());//区间范围写法$time['add_time'] = array('between', array($start,$end));
        $info = db('news')
            ->alias('a')
            ->where($data)
            ->where($time)
            ->where($type)
            ->join('cate b', 'a.type=b.id')
            ->order('id desc')
            ->field('a.*,b.typename')
            ->paginate(5);
        $this->assign('info', $info);
        $this->assign('fenlei',db('cate')->select());
        $this->assign('page', $info->render());//分页映射
        return $this->fetch('index');
    }

//    映射添加页面
    public function add(){
        $info = db('cate')->select();
        $this->assign('info', $info);
        return $this->fetch('contact_add');
    }
//    处理数据
    public function adds(){
        $count = input('post.');
        $count['time']=time();
        $info = db('news')->insert($count);

        if ($info){
            return ['code'=>1];
        }else{
            return ['code'=>0];
        }
    }

//    映射修改页面
    public function upd(){
        $id = input('id');
        $info = db('news')->where(['id'=>$id])->select();
        $this->assign('info',$info);//分页映射
        $this->assign('fenlei',db('cate')->select());//分类映射
        return $this->fetch('contact_update');
    }

    public function upds(){
        $count = input('post.');
        $info = db('news')->update($count);
        if ($info){
            return ['code'=>1];
        }else{
            return ['code'=>0];
        }
    }
//    删除
    public function del(){
        $id = input('id');
        $info = db('news')->where(['id'=>$id])->delete();
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
        return $this->fetch('company/content');
    }
}