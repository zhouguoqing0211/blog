<?php
//定义命名空间‘
namespace app\controllers;
//use出机类控制器
use app\controllers\Common;
//use出服务层
use app\services\AdminService;

class Admin extends Common
{
    /*
      后台主界面
    */
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
    $res = (new AdminService)->select();
    $sum = (new AdminService)->count();
    //从数据库查询数据
    return  view('adminlist',[
      'res'=>$res,
      'sum'=>$sum
      ]);
   }
/*
  添加管理员
*/
  function add()
  {
    return view('addadmin');
  }
/*
  处理数据  
*/
  function addadmin()
  {
    $AdminService = new AdminService;
    //
    $res = $AdminService->addadmin();
    if($res)
    {
       echo  "<script> alert('添加成功');location.href='/Admin/adminlist'</script>";
    }else{
        echo '添加失败';
    }
  }
}
    
