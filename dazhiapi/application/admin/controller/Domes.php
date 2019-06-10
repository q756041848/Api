<?php
/**
 * Created by PhpStorm.
 * User: xiaob
 * Date: 2019/4/12
 * Time: 9:04
 */

namespace app\admin\controller;


class Domes extends Common
{
    public function index(){

        return $this->fetch('index');
    }
}