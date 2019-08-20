<?php
//定义命名空间‘
namespace app\controllers;
//use出机类控制器
use app\controllers\Common;
//use出服务层
use app\services\AdminService;

class Admin extends Common
{
    function  index()
    {
        return  view('index');
    }

/*
    欢迎界面
*/
   function welocome()
   {
       echo '欢迎管理员';
   }
/*
  管理员列表
*/
   function adminlist()
   {
      return  view('adminlist');
   }
/*
  添加管理员
*/
  function add()
  {
    return view('addadmin');
  }


}