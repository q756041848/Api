<?php
/**
 * Created by PhpStorm.
 * User: shuyuan
 * Date: 2019/4/18
 * Time: 11:15 AM
 */

namespace app\api\controller;
use think\Controller;
use common;


class Preview extends Controller
{

    public  function index(){
        $info=db('connector')->select();
        print($info);
        exit();
        $this->assign('info',$info);
        return $this->fetch();
    }
}