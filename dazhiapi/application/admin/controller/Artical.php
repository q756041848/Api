<?php
namespace app\admin\controller;
use think\Db;
use think\controller;

class Artical extends Common{

    //文章列表页
    public function index(){

        $val=input('val');
        $url['val'] = $val;
        $this->assign('testval',$val);
        $map=array();
        if($val){
            $map['a_content']= array('like',"%".$val."%");
        }

        $articleList=db('data_article')->alias('a')
            ->join('dzjn_data_article_type art','a.a_type = art.t_id','left')
            ->where($map)
            ->order('a_id asc')
            ->paginate(config('pageSize'));
        $articleList->appends($url);

        $type=db('data_article_type')->select();//一级菜单
        // 模板变量赋值
        $page = $articleList->render();
        $this->assign('type', $type);
        $this->assign('page', $page);
        $this->assign('article_list',$articleList);

        return $this->view->fetch('index');
    }

    //显示添加页面
    public function artical_add(){
        $type=db('data_article_type')->select();//文章类型

        // 模板变量赋值
        $this->assign('type', $type);
        return $this->fetch();
    }

    //执行添加
    public function artical_doAdd()
    {
        $data['a_type']=input('a_type');
        //接收富文本编辑器内容
        $data['a_content'] = input('a_content');
        $data['a_time'] = time();//获取当前时间戳
        $rel=db('data_article')->insert($data);
//        print_r($data);
        if($rel>0){
            $result['status'] = 1;
            $result['info'] = '添加成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '添加失败!';
            $result['url'] = url('index');

        }
        return $result;

    }

    //显示修改页面
    public function artical_update(){
        $id=input('a_id');

        $type=db('data_article_type')->select();//文章类型表

        $rel=db('data_article')->where("a_id='$id'")->find();
        // 模板变量赋值
        $this->assign('artical',$rel);
        $this->assign('type_list',$type);//文章类型

        return $this->fetch('artical_update');
    }

    //执行修改
    public function artical_doUpdate(){
        $id=input('a_id');
        $data['a_type']=input('a_type');
        //接收富文本编辑器内容
        $data['a_content'] = input('a_content');
        $data['a_time'] = time();//获取当前时间戳
        $rel=db('data_article')->where("a_id=$id")->update($data);
//        print_r($data);
        if($rel>0){
            $result['status'] = 1;
            $result['info'] = '修改成功!';
            $result['url'] = url('index');
        }else{
            $result['status'] = 0;
            $result['info'] = '修改失败!';
            $result['url'] = url('index');

        }
        return $result;

    }

    //执行删除
    public function artical_del(){
        $id=input('a_id');
        $rel=db('data_article')->where("a_id='$id'")->delete();

        if($rel>0){
            $this->redirect('index');
        }
    }

    //执行修改
    public function artical_preview(){
        $id=input('a_id');
        $rel=db('data_article')->where("a_id='$id'")->find();
        // 模板变量赋值
        $this->assign('artical',$rel);
        return $this->fetch();
    }

}