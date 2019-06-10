<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Input;

class Index extends Common
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
        if (!session('aid')){
            $this->redirect('login/login');
        }
        return $this->fetch('index');
    }
    
}
